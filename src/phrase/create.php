<?php
include '../database/connection.php';

$errors = [];

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit();
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
    $visibility_type = $_POST['visibility_type'];
    $visibility = ($_POST['visibility_type'] == 'automatic') ? 1 : 0;
    $show_time = ($_POST['visibility_type'] == 'automatic') ? date('Y-m-d H:i:s', strtotime($_POST['show_time'])) : null;

    if (strlen($content) > 56) {
        $errors[] = "Phrase content must not exceed 56 characters.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO phrases (user_id, title, content, visibility_type, visibility, show_time) VALUES (:user_id, :title, :content, :visibility_type, :visibility, :show_time)");
            $stmt->execute(['user_id' => $user_id, 'title' => $title, 'content' => $content, 'visibility_type' => $visibility_type, 'visibility' => $visibility, 'show_time' => $show_time]);
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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhraseShare · Create</title>
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="min-h-screen bg-black bg-gradient-to-tr from-neutral-900/50 to-neutral-700/30 overflow-hidden text-neutral-100">
    <div class="container mx-auto py-8">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-8">
            <h1 class="text-[28px] font-bold leading-[34px] tracking-[-0.416px] text-neutral-100">
                Create Phrase
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
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <label for="title" class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-normal text-neutral-400">Phrase Title:</label>

                        <button type="button" id="generate-ai" class="inline-flex cursor-pointer select-none text-sm text-white transition duration-200 ease-in-out focus-visible:bg-neutral-800 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-800 dark:bg-neutral-300 dark:text-neutral-100">
                            <div class="flex items-center gap-1">
                                <i data-lucide="sparkles" class="size-4"></i>
                                <span id="generate-text">Write with Artificial Intelligence</span>
                                <span id="loading-text" class="hidden">Generating...</span>
                            </div>
                        </button>
                    </div>
                    <input type="text" id="title" name="title" class="flex w-full rounded-md py-2 text-sm outline-none ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:border-black focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-50 h-8 border border-neutral-700 bg-neutral-900 px-2 text-neutral-100 transition duration-200 ease-in-out placeholder:text-neutral-500">
                </div>
                <div class="space-y-2">
                    <label for="content" class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-normal text-neutral-400">Phrase Content:</label>
                    <textarea id="content" name="content" class="flex w-full rounded-md py-2 text-sm outline-none ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:border-black focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-50 h-32 border border-neutral-700 bg-neutral-900 px-2 text-neutral-100 transition duration-200 ease-in-out placeholder:text-neutral-500 resize-none"></textarea>
                </div>
                <div class="space-y-2">
                    <label for="visibility_type" class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-normal text-neutral-400">Visibility:</label>
                    <select id="visibility_type" name="visibility_type" class="flex w-full rounded-md text-sm outline-none ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:border-black focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-50 h-8 border border-neutral-700 bg-neutral-900 px-2 text-neutral-100 transition duration-200 ease-in-out placeholder:text-neutral-500">
                        <option value="" selected disabled></option>
                        <option value="automatic">Show automatically</option>
                        <option value="manual">Decide manually</option>
                    </select>
                </div>
                <div id="show-time-input" class="space-y-2 hidden">
                    <label for="show_time" class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-normal text-neutral-400">What time should the phrase be shown?</label>
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
                    <button type="submit" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 p-2 text-sm font-semibold text-white transition duration-200 ease-in-out hover:bg-neutral-800 focus-visible:border-black focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-800 dark:bg-neutral-300 dark:text-neutral-100">
                        Create
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

        document.getElementById('visibility_type').addEventListener('change', function() {
            var visibilityType = this.value;
            var showTimeInput = document.getElementById('show-time-input');
            if (visibilityType === 'automatic') {
                showTimeInput.classList.remove('hidden');
            } else {
                showTimeInput.classList.add('hidden');
            }
        });

        document.getElementById('generate-ai').addEventListener('click', async function() {
            const prompt = "Generate a title and a phrase. WITH NO OTHER TEXT BEFORE THE TITLE OR AFTER THE PHRASE. The response should be formatted as followed 'title; phrase' and with the following rules:\n\n1. The response must be a string with the format 'title; phrase'.\n2. There should be no newline characters or backticks in the response.\n3. The response should be returned without any additional formatting or characters.\n4. Ensure that the title and phrase are meaningful and coherent.\n5. The phrase SHOULD NOT EXCEED 56 CHARACTERS.\n\nReturn the response with the title and phrase.";
            const model = "llama3";

            document.getElementById('generate-text').classList.add('hidden');
            document.getElementById('loading-text').classList.remove('hidden');

            try {
                const response = await fetch('http://localhost:11434/api/generate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        model: model,
                        prompt: prompt,
                        stream: false,
                    })
                });

                const responseData = await response.text();
                const data = JSON.parse(responseData);

                const [title, phrase] = data.response.split(';').map(item => item.trim());

                document.getElementById('title').value = title.replace(/^"|"$/g, '');
                document.getElementById('content').value = phrase.replace(/^"|"$/g, '');

                document.getElementById('generate-text').classList.remove('hidden');
                document.getElementById('loading-text').classList.add('hidden');
            } catch (error) {
                console.error('Error generating title and phrase: ' + error.message);
            }
        });
    </script>
</body>

</html>