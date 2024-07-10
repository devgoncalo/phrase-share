<?php
include '../database/connection.php';
include '../translations.php';

session_start();

$errors = [];

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit();
}

if (isset($_SESSION['status']) && $_SESSION['status'] == 1) {
    header('Location: ../dashboard.php');
    exit();
}

$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
$trans = $translations[$language] ?? $translations['en'];

$title = '';
$content = '';

function generateUniqueId($length = 8) {
    return substr(bin2hex(random_bytes($length)), 0, $length);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        header("Location: auth/login.php");
        exit();
    }

    $title = $_POST['title'];
    $content = $_POST['content'];
    $visibility = 0;
    $visibility_type = $_POST['visibility_type'];
    $show_time = ($_POST['visibility_type'] == 'automatic') ? date('Y-m-d H:i:s', strtotime($_POST['show_time'])) : null;

    if ($visibility_type == 'automatic' && strtotime($show_time) < time()) {
        $errors[] = $trans['create_show_time_past_error'];
    } elseif (strlen($content) > 56) {
        $errors[] = $trans['create_content_length_error'];
    } else {
        try {
            $phrase_id = generateUniqueId();
            $stmt = $pdo->prepare("INSERT INTO phrases (id, user_id, title, content, visibility_type, visibility, show_time) VALUES (:id, :user_id, :title, :content, :visibility_type, :visibility, :show_time)");
            $stmt->execute(['id' => $phrase_id, 'user_id' => $user_id, 'title' => $title, 'content' => $content, 'visibility_type' => $visibility_type, 'visibility' => $visibility, 'show_time' => $show_time]);
            header('Location: ../dashboard.php');
            exit();
        } catch (PDOException $e) {
            error_log('Error: ' . $e->getMessage(), 0);
            $errors[] = "An error occurred. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($language); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhraseShare · <?php echo htmlspecialchars($trans['create_page_title']) ?></title>
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="min-h-screen bg-black bg-gradient-to-tr from-neutral-900/50 to-neutral-700/30 overflow-hidden text-neutral-100">
    <div class="container mx-auto py-8">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-8">
            <h1 class="text-[28px] font-bold leading-[34px] tracking-[-0.416px] text-neutral-100">
                <?php echo htmlspecialchars($trans['create_page_title']); ?>
            </h1>
            <a href="../dashboard.php" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 bg-white pl-3 pr-3 text-sm font-semibold text-black transition duration-200 ease-in-out hover:bg-white/90 focus-visible:bg-white/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400">
                <span class="inline-flex flex-row items-center gap-2">
                    <i data-lucide="arrow-left" class="size-4"></i>
                    <?php echo htmlspecialchars($trans['general_go_back']); ?>
                </span>
            </a>
        </div>
        <div class="mx-auto max-w-5xl px-6">
            <form class="flex flex-col gap-6" method="post">
                <div class="space-y-2">
                    <label for="title" class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-normal text-neutral-400"><?php echo htmlspecialchars($trans['create_title_label']); ?></label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" class="flex w-full rounded-md py-2 text-sm outline-none ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:border-black focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-50 h-8 border border-neutral-700 bg-neutral-900 px-2 text-neutral-100 transition duration-200 ease-in-out placeholder:text-neutral-500">
                </div>
                <div class="space-y-2">
                    <label for="content" class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-normal text-neutral-400"><?php echo htmlspecialchars($trans['create_content_label']); ?></label>
                    <textarea id="content" name="content" class="flex w-full rounded-md py-2 text-sm outline-none ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:border-black focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-50 h-32 border border-neutral-700 bg-neutral-900 px-2 text-neutral-100 transition duration-200 ease-in-out placeholder:text-neutral-500 resize-none"><?php echo htmlspecialchars($content); ?></textarea>
                </div>
                <div class="space-y-2">
                    <label for="visibility_type" class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-normal text-neutral-400"><?php echo htmlspecialchars($trans['create_visibility_label']); ?></label>
                    <select id="visibility_type" name="visibility_type" class="flex w-full rounded-md text-sm outline-none ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:border-black focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-50 h-8 border border-neutral-700 bg-neutral-900 px-2 text-neutral-100 transition duration-200 ease-in-out placeholder:text-neutral-500">
                        <option value="" selected disabled></option>
                        <option value="automatic"><?php echo htmlspecialchars($trans['create_visibility_option_auto']); ?></option>
                        <option value="manual"><?php echo htmlspecialchars($trans['create_visibility_option_manual']); ?></option>
                    </select>
                </div>
                <div id="show-time-input" class="space-y-2 hidden">
                    <label for="show_time" class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-normal text-neutral-400"><?php echo htmlspecialchars($trans['create_show_time_label']); ?></label>
                    <input type="datetime-local" id="show_time" name="show_time" class="flex w-full rounded-md py-2 text-sm outline-none ring-offset-background text-neutral-100 focus-visible:border-black focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-50 h-8 border border-neutral-700 bg-neutral-900 px-2 transition duration-200 ease-in-out">
                </div>
                <?php if (!empty($errors)) : ?>
                    <div class="flex w-full flex-row gap-1 items-center text-red-500 text-xs" role="alert">
                        <?php foreach ($errors as $error) : ?>
                            <i data-lucide="ban" class="size-3"></i>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="flex flex-row gap-2">
                    <button type="submit" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 p-2 text-sm font-semibold text-white transition duration-200 ease-in-out hover:bg-neutral-800 focus-visible:border-black focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-800">
                        <?php echo htmlspecialchars($trans['create_create_button']); ?>
                    </button>
                    <a href="../dashboard.php" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md p-2 text-sm font-semibold text-white transition duration-200 ease-in-out hover:bg-neutral-800 focus-visible:border-black focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-800">
                        <?php echo htmlspecialchars($trans['create_cancel_button']); ?>
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script>
        lucide.createIcons();

        document.getElementById('visibility_type').addEventListener('change', function() {
            var visibilityType = this.value;
            var showTimeInput = document.getElementById('show-time-input');
            if (visibilityType === 'automatic') {
                showTimeInput.classList.remove('hidden');
            } else {
                showTimeInput.classList.add('hidden');
            }
        });
    </script>
</body>

</html>