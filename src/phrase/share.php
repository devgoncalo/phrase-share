<?php
include '../database/connection.php';

if (!isset($_GET['id'])) {
    header('Location: ../dashboard.php');
    exit();
}

$phrase_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM phrases WHERE id = :id");
$stmt->execute(['id' => $phrase_id]);
$phrase = $stmt->fetch();

if (!$phrase) {
    header('Location: ../dashboard.php');
    exit();
}

$public_url = "http://localhost:8000/src/phrase/view.php?id=$phrase_id";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhraseShare · Share</title>
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="min-h-screen bg-black bg-gradient-to-tr from-neutral-900/50 to-neutral-700/30 overflow-hidden text-neutral-100">
    <div class="container mx-auto py-8">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-8">
            <h1 class="text-[28px] font-bold leading-[34px] tracking-[-0.416px] text-neutral-100">
                Share Phrase
            </h1>
            <a href="../dashboard.php" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 bg-white pl-3 pr-3 text-sm font-semibold text-black transition duration-200 ease-in-out hover:bg-white/90 focus-visible:bg-white/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400 dark:bg-white dark:text-black dark:hover:bg-white/90 dark:focus-visible:bg-white/90 dark:focus-visible:ring-white/40 dark:disabled:hover:bg-white">
                <span class="inline-flex flex-row items-center gap-2">
                    <i data-lucide="arrow-left" class="size-4"></i>
                    Go Back
                </span>
            </a>
        </div>

        <div class="mx-auto max-w-5xl px-6">
            <div class="flex flex-col gap-6">
                <div class="flex flex-col gap-2">
                    <label for="title" class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-normal text-neutral-400">Use the following URL to share the phrase:</label>
                    <input type="text" id="title" name="title" readonly class="flex w-full rounded-md py-2 text-sm outline-none ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:border-black focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-50 h-8 border border-neutral-700 bg-neutral-900 px-2 text-neutral-400 transition duration-200 ease-in-out placeholder:text-neutral-500">
                    <?php if ($phrase['visibility_type'] == 'manual' && $phrase['visibility'] == '0') : ?>
                        <div class="flex items-center gap-1 text-blue-300 mb-1">
                            <i data-lucide="info" class="size-3"></i>
                            <span class="text-xs">You need to make the visibility public for people to see your phrase.</span>
                        </div>
                    <?php endif; ?>
                        <div class="flex flex-row gap-2">
                            <button id="generateLinkBtn" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 p-2 text-sm font-semibold text-white transition duration-200 ease-in-out hover:bg-neutral-800 focus-visible:border-black focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-800 dark:bg-neutral-300 dark:text-neutral-100">
                                <span id="generateText">Generate Link</span>
                                <span id="loadingSpinner" class="hidden ">Loading...</span>
                            </button>
                            <a id="seePhraseLink" class="hidden inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md p-2 text-sm font-semibold text-white transition duration-200 ease-in-out hover:bg-neutral-800 focus-visible:border-black focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-800 dark:bg-neutral-300 dark:text-neutral-100">
                                See Your Phrase
                            </a>
                        </div>
                </div>
            </div>
        </div>
        <script>
            lucide.createIcons();

            const generateLinkBtn = document.getElementById('generateLinkBtn');
            const generateText = document.getElementById('generateText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const titleInput = document.getElementById('title');
            const seePhraseLink = document.getElementById('seePhraseLink');

            generateLinkBtn.addEventListener('click', () => {
                generateText.classList.add('hidden');
                loadingSpinner.classList.remove('hidden');

                setTimeout(() => {
                    titleInput.value = '<?php echo $public_url; ?>';
                    loadingSpinner.classList.add('hidden');
                    generateText.classList.remove('hidden');
                    seePhraseLink.classList.remove('hidden');
                    seePhraseLink.href = '<?php echo $public_url; ?>';
                }, 2000);
            });
        </script>
</body>

</html>