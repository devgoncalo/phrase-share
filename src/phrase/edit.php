<?php
include '../database/connection.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $phrase_id = $_POST['phrase_id'];

    try {
        $stmt = $pdo->prepare("UPDATE phrases SET title = :title, content = :content WHERE id = :phrase_id");
        $stmt->execute(['title' => $title, 'content' => $content, 'phrase_id' => $phrase_id]);
        header('Location: ../dashboard.php');
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

        $creation_time = strtotime($phrase["creation_time"]);
        $current_time = strtotime("+2 hours");

        if ($current_time <= strtotime($phrase['creation_time']) + (5 * 60)) {
            header('Location: ../dashboard.php');
            exit();
        }
    } catch (PDOException $e) {
        echo "Error fetching phrase: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhraseShare · Edit</title>
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="min-h-screen bg-black bg-gradient-to-tr from-neutral-900/50 to-neutral-700/30 overflow-hidden text-neutral-100">
    <div class="container mx-auto py-8">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-8">
            <h1 class="text-[28px] font-bold leading-[34px] tracking-[-0.416px] text-neutral-100">
                Edit Phrase
            </h1>
            <a href="../dashboard.php" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 bg-white pl-3 pr-3 text-sm font-semibold text-black transition duration-200 ease-in-out hover:bg-white/90 focus-visible:bg-white/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400 dark:bg-white dark:text-black dark:hover:bg-white/90 dark:focus-visible:bg-white/90 dark:focus-visible:ring-white/40 dark:disabled:hover:bg-white">
                <span class="inline-flex flex-row items-center gap-2">
                    <i data-lucide="arrow-left" class="size-4"></i>
                    Go Back
                </span>
            </a>
        </div>

        <div class="mx-auto max-w-5xl px-6">
            <form class="flex flex-col gap-6" method="post" action="">
                <input type="hidden" name="phrase_id" value="<?php echo $phrase_id; ?>">
                <div class="space-y-2">
                    <label for="title" class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-normal text-neutral-400">Phrase Title:</label>
                    <input type="text" id="title" name="title" value="<?php echo $phrase['title']; ?>" class="flex w-full rounded-md py-2 text-sm outline-none ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:border-black focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-50 h-8 border border-neutral-700 bg-neutral-900 px-2 text-neutral-100 transition duration-200 ease-in-out placeholder:text-neutral-500">
                </div>
                <div class="space-y-2">
                    <label for="content" class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-normal text-neutral-400">Phrase Content:</label>
                    <textarea id="content" name="content" class="flex w-full rounded-md py-2 text-sm outline-none ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:border-black focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-50 h-32 border border-neutral-700 bg-neutral-900 px-2 text-neutral-100 transition duration-200 ease-in-out placeholder:text-neutral-500 resize-none"><?php echo $phrase['content']; ?></textarea>
                </div>
                <div class="flex flex-row gap-2">
                    <button type="submit" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 p-2 text-sm font-semibold text-white transition duration-200 ease-in-out hover:bg-neutral-800 focus-visible:border-black focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-800 dark:bg-neutral-300 dark:text-neutral-100">
                        Save
                    </button>
                    <a href="../dashboard.php" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md p-2 text-sm font-semibold text-white transition duration-200 ease-in-out hover:bg-neutral-800 focus-visible:border-black focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-800 dark:bg-neutral-300 dark:text-neutral-100">
                        Cancel
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