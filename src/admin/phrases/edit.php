<?php
include '../../database/connection.php';
include '../../translations.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT admin FROM users WHERE id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch();

if (!$user || $user['admin'] != 1) {
    header('Location: ../../dashboard.php');
    exit();
}

$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
$trans = $translations[$language] ?? $translations['en'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $phrase_id = $_POST['phrase_id'];

    try {
        $stmt = $pdo->prepare("UPDATE phrases SET title = :title, content = :content WHERE id = :phrase_id");
        $stmt->execute(['title' => $title, 'content' => $content, 'phrase_id' => $phrase_id]);
        header('Location: ./overview.php');
        exit();
    } catch (PDOException $e) {
        echo "Error updating phrase: " . $e->getMessage();
    }
}

if (isset($_GET['id'])) {
    $phrase_id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM phrases WHERE id = :phrase_id");
        $stmt->execute(['phrase_id' => $phrase_id]);
        $phrase = $stmt->fetch();
    } catch (PDOException $e) {
        echo "Error fetching phrase: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($language); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhraseShare · <?php echo htmlspecialchars($trans['edit_page_title']) ?></title>
    <link rel="shortcut icon" href="../../assets/favicon.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="min-h-screen bg-black bg-gradient-to-tr from-neutral-900/50 to-neutral-700/30 overflow-hidden text-neutral-100">
    <div class="container mx-auto py-8">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-8">
            <h1 class="text-[28px] font-bold leading-[34px] tracking-[-0.416px] text-neutral-100">
                <?php echo htmlspecialchars($trans['edit_page_title']) ?>
            </h1>
            <a href="./overview.php" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 bg-white pl-3 pr-3 text-sm font-semibold text-black transition duration-200 ease-in-out hover:bg-white/90 focus-visible:bg-white/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400">
                <span class="inline-flex flex-row items-center gap-2">
                    <i data-lucide="arrow-left" class="size-4"></i>
                    <?php echo $trans['general_go_back']; ?>
                </span>
            </a>
        </div>
        <div class="mx-auto max-w-5xl px-6">
            <form class="flex flex-col gap-6" method="post" action="">
                <input type="hidden" name="phrase_id" value="<?php echo $phrase_id; ?>">
                <div class="space-y-2">
                    <label for="title" class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-normal text-neutral-400"><?php echo htmlspecialchars($trans['edit_title_label']) ?></label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($phrase['title']); ?>" class="flex w-full rounded-md py-2 text-sm outline-none ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:border-black focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-50 h-8 border border-neutral-700 bg-neutral-900 px-2 text-neutral-100 transition duration-200 ease-in-out placeholder:text-neutral-500">
                </div>
                <div class="space-y-2">
                    <label for="content" class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-normal text-neutral-400"><?php echo htmlspecialchars($trans['edit_content_label']) ?></label>
                    <textarea id="content" name="content" class="flex w-full rounded-md py-2 text-sm outline-none ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:border-black focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-50 h-32 border border-neutral-700 bg-neutral-900 px-2 text-neutral-100 transition duration-200 ease-in-out placeholder:text-neutral-500 resize-none"><?php echo htmlspecialchars($phrase['content']); ?></textarea>
                </div>
                <div class="flex flex-row gap-2">
                    <button type="submit" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 p-2 text-sm font-semibold text-white transition duration-200 ease-in-out hover:bg-neutral-800 focus-visible:border-black focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-800">
                        <?php echo htmlspecialchars($trans['edit_save_button']) ?>
                    </button>
                    <a href="../dashboard.php" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md p-2 text-sm font-semibold text-white transition duration-200 ease-in-out hover:bg-neutral-800 focus-visible:border-black focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-800">
                        <?php echo htmlspecialchars($trans['edit_cancel_button']) ?>
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>