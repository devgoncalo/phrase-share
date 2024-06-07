<?php

include 'database/connection.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM phrases WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$phrases = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['delete_phrase'])) {
    $phrase_id = $_POST['phrase_id'];
    $stmt = $pdo->prepare("DELETE FROM phrases WHERE id = :phrase_id");
    $stmt->bindParam(':phrase_id', $phrase_id);
    $stmt->execute();

    header('Location: dashboard.php');
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['make_public'])) {
        $phrase_id = $_POST['phrase_id'];

        try {
            $stmt = $pdo->prepare("UPDATE phrases SET visibility = true WHERE id = :phrase_id");
            $stmt->bindParam(':phrase_id', $phrase_id);
            $stmt->execute();
            header('Location: dashboard.php');
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    if (isset($_POST['make_private'])) {
        $phrase_id = $_POST['phrase_id'];

        try {
            $stmt = $pdo->prepare("UPDATE phrases SET visibility = false WHERE id = :phrase_id");
            $stmt->bindParam(':phrase_id', $phrase_id);
            $stmt->execute();
            header('Location: dashboard.php');
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

$creation_time = strtotime($phrase["creation_time"]);
$current_time = time();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhraseShare · Dashboard</title>
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="min-h-screen bg-black bg-gradient-to-tr from-neutral-900/50 to-neutral-700/30 overflow-hidden text-neutral-100">
    <div class="container mx-auto py-8">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-8">
            <h1 class="text-[28px] font-bold leading-[34px] tracking-[-0.416px] text-neutral-100">
                Dashboard
            </h1>
            <div>
                <a href="phrase/create.php" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 bg-white pl-3 pr-3 text-sm font-semibold text-black transition duration-200 ease-in-out hover:bg-white/90 focus-visible:bg-white/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400 dark:bg-white dark:text-black dark:hover:bg-white/90 dark:focus-visible:bg-white/90 dark:focus-visible:ring-white/40 dark:disabled:hover:bg-white">
                    <span class="inline-flex flex-row items-center gap-2">
                        <i data-lucide="plus" class="size-4"></i>
                        Add Phrase
                    </span>
                </a>
                <a href="auth/logout.php" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md pl-3 pr-3 text-sm font-semibold text-white transition duration-200 ease-in-out hover:bg-neutral-800 focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400 dark:bg-white dark:text-black dark:hover:bg-white/90 dark:focus-visible:bg-white/90 dark:focus-visible:ring-white/40 dark:disabled:hover:bg-white">
                    <span class="inline-flex flex-row items-center gap-2">
                        <i data-lucide="log-out" class="size-4"></i>
                        Log Out
                    </span>
                </a>
            </div>
        </div>
        <div class="mx-auto max-w-5xl px-6">
            <?php if (empty($phrases)) : ?>
                <div>
                    <div class="flex h-80 flex-col items-center justify-center rounded-lg border border-neutral-700 p-6">
                        <div class="mb-8 flex max-w-md flex-col gap-2 text-center">
                            <h2 class="text-xl font-bold tracking-[-0.16px] text-neutral-100">You haven't created any phrases.</h2>
                            <span class="text-sm font-normal text-neutral-400">Once you create a phrase, you'll be able to share it with everyone.</span>
                        </div>
                        <a href="phrase/create.php" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 pl-2 pr-3 text-sm font-semibold text-white transition duration-200 ease-in-out hover:bg-neutral-800 focus-visible:border-black focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-800 dark:bg-neutral-300 dark:text-neutral-100">
                            <span class="inline-flex flex-row items-center gap-2">
                                <i data-lucide="plus" class="size-4"></i>
                                Add Phrase
                            </span>
                        </a>
                    </div>
                </div>
            <?php else : ?>
                <table class="min-w-full border-separate border-spacing-0 border-none text-left">
                    <thead class="h-8 rounded-md bg-neutral-800">
                        <tr>
                            <th class="h-8 w-[250px] border-b border-t border-neutral-700 px-3 text-xs font-semibold text-neutral-400 first:rounded-l-md first:border-l last:rounded-r-md last:border-r">Title</th>
                            <th class="h-8 w-[250px] border-b border-t border-neutral-700 px-3 text-left text-xs font-semibold text-neutral-400 first:rounded-l-md first:border-l last:rounded-r-md last:border-r">Content</th>
                            <th class="h-8 w-[206px] border-b border-t border-neutral-700 px-3 text-right text-xs font-semibold text-neutral-400 first:rounded-l-md first:border-l last:rounded-r-md last:border-r">Creation Date</th>
                            <th class="h-8 w-[100px] border-b border-t border-neutral-700 px-3 text-right text-xs font-semibold text-neutral-400 first:rounded-l-md first:border-l last:rounded-r-md last:border-r">Visibility</th>
                            <th class="h-8 w-[70px] border-b border-t border-neutral-700 px-3 text-xs font-semibold text-neutral-400 first:rounded-l-md first:border-l last:rounded-r-md last:border-r"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($phrases as $phrase) : ?>
                            <tr>
                                <td class="h-10 truncate border-b border-neutral-700 px-3 py-3 text-sm"><?php echo $phrase['title']; ?></td>
                                <td class="h-10 truncate border-b border-neutral-700 px-3 text-sm"><?php echo $phrase['content']; ?></td>
                                <td class="h-10 truncate border-b border-neutral-700 px-3 text-right text-sm"><?php echo $phrase['creation_time']; ?></td>
                                <td class="h-10 truncate border-b border-neutral-700 px-3 text-right text-xs text-center text-sm"><?php if ($phrase['visibility'] == '1') echo 'Public';
                                                                                                                                    else echo 'Private'; ?></td>
                                <td class="h-10 truncate border-b border-neutral-700 px-3 text-center text-sm">
                                    <button id="dropdownButton_<?php echo $phrase['id']; ?>" type="button" class="inline-flex h-6 w-6 cursor-pointer items-center justify-center rounded border border-none bg-transparent align-middle text-neutral-400 transition duration-200 ease-in-out hover:bg-neutral-700 focus-visible:bg-neutral-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-600 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400" aria-label="More actions">
                                        <i data-lucide="ellipsis" class="size-4"></i>
                                    </button>

                                    <div id="dropdownMenu_<?php echo $phrase['id']; ?>" class="hidden origin-top-right mt-1 absolute right-[9.75rem] z-50 min-w-[8rem] overflow-hidden rounded-md border text-popover-foreground shadow-md data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2 border-neutral-700 bg-neutral-900 p-1">
                                        <div class="flex flex-col gap-1 my-1">
                                            <?php if ($current_time <= strtotime($phrase['creation_time']) + (5 * 60)) : ?>
                                                <a href="phrase/edit.php?id=<?php echo $phrase['id']; ?>" role="menuitem" class="flex items-center gap-2 rounded-sm border border-transparent px-1 text-neutral-400 hover:text-neutral-100 hover:bg-neutral-800 focus-visible:bg-neutral-700 focus-visible:text-neutral-100 focus-visible:outline-none">
                                                    <i data-lucide="pencil" class="size-4"></i>
                                                    <span class="flex items-center gap-1 text-sm font-medium">Edit Phrase</span>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($phrase['visibility'] == '0') : ?>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="phrase_id" value="<?php echo $phrase['id']; ?>">
                                                    <button type="submit" name="make_public" class="flex items-center gap-2 rounded-sm border border-transparent px-1 text-neutral-400 hover:text-neutral-100 hover:bg-neutral-800 focus-visible:bg-neutral-700 focus-visible:text-neutral-100 focus-visible:outline-none">
                                                        <i data-lucide="globe" class="size-4"></i>
                                                        <span class="flex items-center gap-1 text-sm font-medium">Make Public</span>
                                                    </button>
                                                </form>
                                            <?php elseif ($phrase['visibility'] == '1') : ?>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="phrase_id" value="<?php echo $phrase['id']; ?>">
                                                    <button type="submit" name="make_private" class="flex items-center gap-2 rounded-sm border border-transparent px-1 text-neutral-400 hover:text-neutral-100 hover:bg-neutral-800 focus-visible:bg-neutral-700 focus-visible:text-neutral-100 focus-visible:outline-none">
                                                        <i data-lucide="globe" class="size-4"></i>
                                                        <span class="flex items-center gap-1 text-sm font-medium">Make Private</span>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            <a href="phrase/share.php?id=<?php echo $phrase['id']; ?>" role="menuitem" class="flex items-center gap-2 rounded-sm border border-transparent px-1 text-neutral-400 hover:text-neutral-100 hover:bg-neutral-800 focus-visible:bg-neutral-700 focus-visible:text-neutral-100 focus-visible:outline-none">
                                                <i data-lucide="link" class="size-4"></i>
                                                <span class="flex items-center gap-1 text-sm font-medium">Share Phrase</span>
                                            </a>
                                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this phrase?')">
                                                <div class="flex items-center gap-2 rounded-sm border border-transparent px-1 text-red-500 hover:bg-red-500/10 focus-visible:border-red-500/10 focus-visible:bg-red-500/10 focus-visible:text-red-500 focus-visible:outline-none">
                                                    <i data-lucide="trash" class="size-4"></i>
                                                    <input type="hidden" name="phrase_id" value="<?php echo $phrase['id']; ?>">
                                                    <button type="submit" name="delete_phrase" role=" menuitem">Delete Phrase</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    <script>
        lucide.createIcons();

        <?php foreach ($phrases as $phrase) : ?>
            const button_<?php echo $phrase['id']; ?> = document.getElementById('dropdownButton_<?php echo $phrase['id']; ?>');
            const dropdown_<?php echo $phrase['id']; ?> = document.getElementById('dropdownMenu_<?php echo $phrase['id']; ?>');

            button_<?php echo $phrase['id']; ?>.addEventListener('click', () => {
                dropdown_<?php echo $phrase['id']; ?>.classList.toggle('hidden');
            });

            document.addEventListener('click', (event) => {
                if (!dropdown_<?php echo $phrase['id']; ?>.contains(event.target) && !button_<?php echo $phrase['id']; ?>.contains(event.target)) {
                    dropdown_<?php echo $phrase['id']; ?>.classList.add('hidden');
                }
            });
        <?php endforeach; ?>
    </script>
</body>

</html>