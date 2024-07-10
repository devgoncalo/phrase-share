<?php
include '../database/connection.php';
include '../translations.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

if (isset($_SESSION['status']) && $_SESSION['status'] == 1) {
    header('Location: ../dashboard.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: ../dashboard.php');
    exit();
}

$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
$trans = $translations[$language] ?? $translations['en'];

$phrase_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM phrases WHERE id = :id");
$stmt->execute(['id' => $phrase_id]);
$phrase = $stmt->fetch();

if (!$phrase) {
    header('Location: ../dashboard.php');
    exit();
}

$public_url = "http://localhost:8000/src/phrase/view.php?id=$phrase_id&lang=$language";
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($language); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhraseShare · <?php echo $trans['share_page_title']; ?></title>
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="min-h-screen bg-black bg-gradient-to-tr from-neutral-900/50 to-neutral-700/30 overflow-hidden text-neutral-100">
    <div class="container mx-auto py-8">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-8">
            <h1 class="text-[28px] font-bold leading-[34px] tracking-[-0.416px] text-neutral-100">
                <?php echo $trans['share_page_title']; ?>
            </h1>
            <a href="../dashboard.php" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 bg-white pl-3 pr-3 text-sm font-semibold text-black transition duration-200 ease-in-out hover:bg-white/90 focus-visible:bg-white/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400">
                <span class="inline-flex flex-row items-center gap-2">
                    <i data-lucide="arrow-left" class="size-4"></i>
                    <?php echo $trans['general_go_back']; ?>
                </span>
            </a>
        </div>

        <div class="mx-auto max-w-5xl px-6">
            <div class="flex flex-col gap-6">
                <div class="flex flex-col gap-2">
                    <label for="title" class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-normal text-neutral-400">
                        <?php echo $trans['share_use_url_label']; ?>
                    </label>
                    <input type="text" id="title" name="title" readonly class="flex w-full rounded-md py-2 text-sm outline-none ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:border-black focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-50 h-8 border border-neutral-700 bg-neutral-900 px-2 text-neutral-400 transition duration-200 ease-in-out placeholder:text-neutral-500" value="<?php echo $public_url; ?>">
                    <?php if ($phrase['visibility_type'] == 'manual' && $phrase['visibility'] == '0') : ?>
                        <div class="flex items-center gap-1 text-blue-300 mb-1">
                            <i data-lucide="info" class="size-3"></i>
                            <span class="text-xs"><?php echo $trans['share_visibility_note']; ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="flex flex-row gap-2">
                        <button id="generateLoading" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 p-2 text-sm font-semibold text-white transition duration-200 ease-in-out hover:bg-neutral-800 focus-visible:border-black focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-800">
                            <span><?php echo $trans['share_loading_text']; ?></span>
                        </button>
                        <a id="seePhraseLink" class="hidden inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 p-2 text-sm font-semibold text-white transition duration-200 ease-in-out hover:bg-neutral-800 focus-visible:border-black focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-800">
                            <?php echo $trans['share_see_phrase_button']; ?>
                        </a>
                        <button id="copyLinkBtn" class="hidden inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 bg-white px-2 text-sm font-semibold text-black transition duration-200 ease-in-out hover:bg-white/90 focus-visible:bg-white/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400">
                            <i id="copyIcon" data-lucide="copy" class="size-4"></i>
                            <i id="checkIcon" data-lucide="check" class="hidden size-4 text-green-500"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        lucide.createIcons();

        window.addEventListener('load', () => {
            const generateLoading = document.getElementById('generateLoading');
            const titleInput = document.getElementById('title');
            const copyLinkBtn = document.getElementById('copyLinkBtn');
            const seePhraseLink = document.getElementById('seePhraseLink');
            const copyIcon = document.getElementById('copyIcon');
            const checkIcon = document.getElementById('checkIcon');

            copyLinkBtn.addEventListener('click', () => {
                titleInput.select();
                document.execCommand('copy');

                copyIcon.classList.add('hidden');
                checkIcon.classList.remove('hidden');

                setTimeout(() => {
                    checkIcon.classList.add('hidden');
                    copyIcon.classList.remove('hidden');
                }, 2000);
            });

            generateLoading.classList.remove('hidden');

            setTimeout(() => {
                generateLoading.classList.add('hidden');
                seePhraseLink.href = '<?php echo $public_url; ?>';
                seePhraseLink.classList.remove('hidden');
                copyLinkBtn.classList.remove('hidden');
            }, 2000);
        });
    </script>
</body>

</html>