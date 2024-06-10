<?php
include "../database/connection.php";

if (!isset($_GET["id"])) {
    header("Location: ../dashboard.php");
    exit();
}

$phrase_id = $_GET["id"];

$stmt = $pdo->prepare("SELECT * FROM phrases WHERE id = :id");
$stmt->execute(["id" => $phrase_id]);
$phrase = $stmt->fetch();

if (!$phrase) {
    header("Location: ../dashboard.php");
    exit();
}

$title = $phrase["title"];
$visibility_type = $phrase["visibility_type"];
$visibility = $phrase["visibility"];

$user_id = $phrase["user_id"];
$user_stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
$user_stmt->execute(["user_id" => $user_id]);
$user = $user_stmt->fetch();

if ($visibility_type == "manual") {
    if ($visibility == "1") {
        $content = $phrase["content"];
    } else {
        $content = "The writer didn't publish the phrase yet.";
    }
}

if ($visibility_type == "automatic") {
    $show_time = strtotime($phrase["show_time"]);
    $current_time = strtotime("+2 hours");
    $remaining_time = $show_time - $current_time;

    if ($current_time < $show_time) {
        $content =
            "The phrase is not yet published. Time remaining: " .
            gmdate("H:i:s", $remaining_time) .
            ".";
    } else {
        $content = $phrase["content"];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhraseShare · View</title>
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="min-h-screen bg-black bg-gradient-to-tr from-neutral-900/50 to-neutral-700/30 overflow-hidden text-neutral-100">
    <div class="container mx-auto py-8">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-8">
            <h1 class="text-[28px] font-bold leading-[34px] tracking-[-0.416px] text-neutral-100">
                View Phrase
            </h1>
            <a href="../dashboard.php" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 bg-white pl-3 pr-3 text-sm font-semibold text-black transition duration-200 ease-in-out hover:bg-white/90 focus-visible:bg-white/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400 dark:bg-white dark:text-black dark:hover:bg-white/90 dark:focus-visible:bg-white/90 dark:focus-visible:ring-white/40 dark:disabled:hover:bg-white">
                <span class="inline-flex flex-row items-center gap-2">
                    <i data-lucide="arrow-left" class="size-4"></i>
                    Go Back
                </span>
            </a>
        </div>

        <div class="mx-auto max-w-5xl px-6">
            <div class="flex justify-center">
                <div class="flex w-full flex-col px-6 py-8 border border-neutral-700 rounded-md">
                    <div class="flex flex-col items-center gap-6 md:flex-row">
                        <i data-lucide="text" class="size-16"></i>
                        <div class="w-full overflow-hidden text-center md:text-left">
                            <span class="text-sm font-semibold text-neutral-400"><?php echo $title; ?></span>
                            <?php if ($visibility_type == "manual" && $visibility == "0") : ?>
                                <p class="text-red-500"><?php echo $content; ?></p>
                            <?php elseif ($visibility_type == "manual" && $visibility == "1") : ?>
                                <h1 class="w-full truncate text-[28px] font-bold leading-[34px] tracking-[-0.416px] text-neutral-100 md:max-w-[800px]"><?php echo $content; ?></h1>
                            <?php elseif ($visibility_type == "automatic" && $current_time < $show_time) : ?>
                                <p class="text-red-500">The phrase is not yet published. Time remaining: <span id="time-remaining"><?php echo gmdate("H:i:s", $remaining_time); ?></span>.</p>
                            <?php else : ?>
                                <h1 class="w-full truncate text-[28px] font-bold leading-[34px] tracking-[-0.416px] text-neutral-100 md:max-w-[800px]"><?php echo $content; ?></h1>
                            <?php endif; ?>
                        </div>
                        <div class="flex shrink-0 items-center gap-4">
                            <nav class="mx-auto w-full max-w-[200px] space-y-2">
                                <button class="flex w-full max-w-[300px] items-center justify-between rounded-md border border-neutral-800 bg-neutral-900 p-2.5 outline-none focus-visible:ring-2 focus-visible:ring-neutral-700" tabindex="0">
                                    <i data-lucide="external-link" class="size-4"></i>
                                </button>
                            </nav>
                        </div>
                    </div>
                    <div class="mt-8 flex w-full flex-wrap">
                        <div class="flex basis-1/4 flex-col gap-1">
                            <label class="text-xs uppercase text-neutral-400">Created</label>
                            <p class="group text-start text-sm font-normal focus-visible:outline-none text-current">
                                <time class="group-focus-visible:border-b group-focus-visible:border-neutral-700">
                                    <?php echo $phrase["creation_time"]; ?>
                                </time>
                            </p>
                        </div>
                        <div class="flex basis-1/4 flex-col gap-1"><label class="text-xs uppercase text-neutral-400">Visibility</label>
                            <div class="flex items-center gap-2">
                                <?php if (
                                    $visibility == "1" &&
                                    $visibility_type == "automatic" &&
                                    $remaining_time > 0
                                ) : ?>
                                    <span class="inline-flex h-6 select-none items-center whitespace-nowrap rounded px-2 text-xs font-medium capitalize bg-blue-700/20 text-blue-500">
                                        Waiting publishing
                                    </span>
                                <?php elseif ($visibility == "1") : ?>
                                    <span class="inline-flex h-6 select-none items-center whitespace-nowrap rounded px-2 text-xs font-medium capitalize bg-green-700/20 text-green-500">
                                        Published
                                    </span>
                                <?php elseif ($visibility == "0") : ?>
                                    <span class="inline-flex h-6 select-none items-center whitespace-nowrap rounded px-2 text-xs font-medium capitalize bg-red-700/20 text-red-500">
                                        Private
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex basis-1/4 flex-col gap-1">
                            <label class="text-xs uppercase text-neutral-400">Visibility type</label>
                            <button class="group text-start text-sm font-normal focus-visible:outline-none text-current">
                                <span class="inline-flex h-6 select-none items-center whitespace-nowrap rounded bg-neutral-900 px-2 text-xs font-medium text-neutral-400 group-focus-visible:ring-2 group-focus-visible:ring-neutral-700">
                                    <?php if (
                                        $phrase["visibility_type"] == "manual"
                                    ) : ?>
                                        Manual
                                    <?php else : ?>
                                        Automatic
                                    <?php endif; ?>
                                </span>
                            </button>
                        </div>
                        <div class="flex basis-1/4 flex-col gap-1"><label class="text-xs uppercase text-neutral-400">Written by</label>
                            <p class="group text-start text-sm font-normal focus-visible:outline-none text-current">
                                <?php echo $user["username"]; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        lucide.createIcons();

        function startCountdown(duration) {
            let timer = duration,
                hours, minutes, seconds;
            const display = document.getElementById('time-remaining');

            const interval = setInterval(function() {
                hours = Math.floor(timer / 3600);
                minutes = Math.floor((timer % 3600) / 60);
                seconds = Math.floor(timer % 60);

                hours = hours < 10 ? "0" + hours : hours;
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = hours + ":" + minutes + ":" + seconds;

                if (--timer < 0) {
                    clearInterval(interval);
                    window.location.reload();
                }
            }, 1000);
        }

        const remainingTime = <?php echo $remaining_time; ?>;

        startCountdown(remainingTime);
    </script>
</body>

</html>