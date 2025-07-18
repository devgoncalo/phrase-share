<?php

include 'database/connection.php';
include 'translations.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit();
}

$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
$trans = $translations[$language] ?? $translations['en'];

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT admin FROM users WHERE id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch();

$isAdmin = $user && $user['admin'] == 1;

$stmt = $pdo->prepare("SELECT status FROM users WHERE id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch();
$isBlocked = $user && $user['status'] == 1;

$limit = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page - 1) * $limit;

$stmt = $pdo->prepare("SELECT * FROM phrases WHERE user_id = :user_id LIMIT :start, :limit");
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':start', $start, PDO::PARAM_INT);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$phrases = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM phrases WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$row = $stmt->fetch();
$total_phrases = $row['total'];

$total_pages = ceil($total_phrases / $limit);

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

$current_time = time();
if ($visibility_type == "automatic") {
    foreach ($phrases as $phrase) {
        $show_time = strtotime($phrase["show_time"]);
        if ($current_time > $show_time && $phrase['visibility'] == "0") {
            $stmt = $pdo->prepare("UPDATE phrases SET visibility = 1 WHERE id = :id");
            $stmt->execute(["id" => $phrase['id']]);

            echo "<script>window.location.reload();</script>";
            exit();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($language); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhraseShare · <?php echo htmlspecialchars($trans['dashboard_page_title']); ?></title>
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body
    class="min-h-screen bg-black bg-gradient-to-tr from-neutral-900/50 to-neutral-700/30 overflow-hidden text-neutral-100">
    <div class="container mx-auto py-8">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-8">
            <h1 class="text-[28px] font-bold leading-[34px] tracking-[-0.416px] text-neutral-100">
                <?php echo htmlspecialchars($trans['dashboard_page_title']); ?>
            </h1>
            <div>
                <?php if (!$isBlocked): ?>
                    <a href="phrase/create.php"
                        class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 bg-white px-2 text-sm font-semibold text-black transition duration-200 ease-in-out hover:bg-white/90 focus-visible:bg-white/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400">
                        <span class="inline-flex flex-row items-center gap-2">
                            <i data-lucide="plus" class="size-4"></i>
                            <span
                                class="hidden md:flex"><?php echo htmlspecialchars($trans['dashboard_add_phrase']); ?></span>
                        </span>
                    </a>
                <?php endif; ?>
                <?php if ($isAdmin): ?>
                    <a href="./admin/dashboard.php"
                        class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md px-2 text-sm font-semibold text-white transition duration-200 ease-in-out bg-neutral-800 hover:bg-neutral-700 focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400    ">
                        <span class="inline-flex flex-row items-center gap-2">
                            <i data-lucide="shield" class="size-4"></i>
                        </span>
                    </a>
                <?php endif; ?>
                <a href="profile.php"
                    class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md px-2 text-sm font-semibold text-white transition duration-200 ease-in-out bg-neutral-800 hover:bg-neutral-700 focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400">
                    <span class="inline-flex flex-row items-center gap-2">
                        <i data-lucide="user" class="size-4"></i>
                    </span>
                </a>
            </div>
        </div>
        <div class="mx-auto max-w-3xl overflow-x md:max-w-5xl px-6 overflow-auto max-h-[calc(100vh-152px)]">
            <?php if ($isBlocked): ?>
                <div>
                    <div
                        class="flex h-80 flex-col items-center justify-center rounded-lg border border-red-600 bg-red-600/10 p-6">
                        <div class="mb-8 flex max-w-md flex-col gap-2 text-center">
                            <i data-lucide="ban" class="mx-auto size-10 text-red-600"></i>
                            <h2 class="text-xl font-bold tracking-[-0.16px] text-neutral-100">You have been blocked.</h2>
                            <span class="text-sm font-normal text-neutral-400">Your account has been blocked due to a
                                violation of our community guidelines. We take these matters seriously to ensure a safe and
                                positive experience for all our users.</span>
                        </div>
                    </div>
                </div>
            <?php elseif (empty($phrases)): ?>
                <div>
                    <div class="flex h-80 flex-col items-center justify-center rounded-lg border border-neutral-700 p-6">
                        <div class="mb-8 flex max-w-md flex-col gap-2 text-center">
                            <h2 class="text-xl font-bold tracking-[-0.16px] text-neutral-100">
                                <?php echo htmlspecialchars($trans['dashboard_you_have_not_created_phrases']); ?>
                            </h2>
                            <span
                                class="text-sm font-normal text-neutral-400"><?php echo htmlspecialchars($trans['dashboard_create_phrase_explanation']); ?></span>
                        </div>
                        <a href="phrase/create.php"
                            class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 pl-2 pr-3 text-sm font-semibold text-white transition duration-200 ease-in-out hover:bg-neutral-800 focus-visible:border-black focus-visible:bg-neutral-800 focus-visible:outline-none">
                            <span class="inline-flex flex-row items-center gap-2">
                                <i data-lucide="plus" class="size-4"></i>
                                <?php echo htmlspecialchars($trans['dashboard_add_phrase']); ?>
                            </span>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <table class="min-w-full border-separate border-spacing-0 border-none text-left">
                    <thead class="h-8 rounded-md bg-neutral-800">
                        <tr>
                            <th
                                class="h-8 w-[250px] border-b border-t border-neutral-700 px-3 text-xs font-semibold text-neutral-400 first:rounded-l-md first:border-l last:rounded-r-md last:border-r">
                                <?php echo htmlspecialchars($trans['dashboard_phrase_title']); ?>
                            </th>
                            <th
                                class="h-8 w-[250px] border-b border-t border-neutral-700 px-3 text-left text-xs font-semibold text-neutral-400 first:rounded-l-md first:border-l last:rounded-r-md last:border-r">
                                <?php echo htmlspecialchars($trans['dashboard_phrase_content']); ?>
                            </th>
                            <th
                                class="h-8 w-[210px] border-b border-t border-neutral-700 px-3 text-right text-xs font-semibold text-neutral-400 first:rounded-l-md first:border-l last:rounded-r-md last:border-r">
                                <?php echo htmlspecialchars($trans['dashboard_phrase_creation_date']); ?>
                            </th>
                            <th
                                class="h-8 w-[100px] border-b border-t border-neutral-700 px-3 text-right text-xs font-semibold text-neutral-400 first:rounded-l-md first:border-l last:rounded-r-md last:border-r">
                                <?php echo htmlspecialchars($trans['dashboard_phrase_status']); ?>
                            </th>
                            <th
                                class="h-8 w-[100px] border-b border-t border-neutral-700 px-3 text-right text-xs font-semibold text-neutral-400 first:rounded-l-md first:border-l last:rounded-r-md last:border-r">
                                <?php echo htmlspecialchars($trans['dashboard_phrase_visibility']); ?>
                            </th>
                            <th
                                class="h-8 border-b border-t border-neutral-700 px-3 text-xs font-semibold text-neutral-400 first:rounded-l-md first:border-l last:rounded-r-md last:border-r">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($phrases as $phrase): ?>
                            <tr>
                                <td class="h-10 truncate border-b border-neutral-700 px-3 py-3 text-sm">
                                    <?php echo $phrase['title']; ?>
                                </td>
                                <td class="h-10 truncate border-b border-neutral-700 px-3 text-sm">
                                    <?php echo $phrase['content']; ?>
                                </td>
                                <td class="h-10 truncate border-b border-neutral-700 px-3 text-right text-sm">
                                    <?php echo date('Y-m-d H:i:s', strtotime($phrase['creation_time'] . ' +2 hours')); ?>
                                </td>
                                <td
                                    class="h-10 truncate border-b border-neutral-700 px-3 text-right text-xs text-center text-sm">
                                    <?php if ($phrase['visibility'] == '0' && $phrase['visibility_type'] == "automatic"): ?>
                                        <?php echo $trans['view_status_waiting']; ?>
                                    <?php elseif ($phrase['visibility'] == "1"): ?>
                                        <?php echo $trans['view_status_public']; ?>
                                    <?php elseif ($phrase['visibility'] == "0"): ?>
                                        <?php echo $trans['view_status_private']; ?>
                                    <?php endif; ?>
                                </td>
                                <td
                                    class="h-10 truncate border-b border-neutral-700 px-3 text-right text-xs text-center text-sm">
                                    <?php echo $phrase['visibility_type'] == "automatic" ? 'Automatic' : 'Manual'; ?>
                                </td>
                                <td class="h-10 truncate border-b border-neutral-700 px-3 text-center text-sm">
                                    <button id="dropdownButton_<?php echo $phrase['id']; ?>" type="button"
                                        class="inline-flex h-6 w-6 cursor-pointer items-center justify-center rounded border border-none bg-transparent align-middle text-neutral-400 transition duration-200 ease-in-out hover:bg-neutral-700 focus-visible:bg-neutral-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-600 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400"
                                        aria-label="More actions">
                                        <i data-lucide="ellipsis" class="size-4"></i>
                                    </button>
                                    <div id="dropdownMenu_<?php echo $phrase['id']; ?>"
                                        class="hidden origin-top-right mt-1 absolute right-[0.75rem] md:right-auto z-50 min-w-[8rem] overflow-hidden rounded-md border text-popover-foreground shadow-md data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2 border-neutral-700 bg-neutral-900 p-1">
                                        <div class="flex flex-col gap-1 my-1">
                                            <?php if ($current_time <= strtotime($phrase['creation_time']) + (5 * 60)): ?>
                                                <a href="phrase/edit.php?id=<?php echo $phrase['id']; ?>" role="menuitem"
                                                    class="w-full flex items-center gap-2 rounded-sm border border-transparent px-1 text-neutral-400 hover:text-neutral-100 hover:bg-neutral-800 focus-visible:border-neutral-600 focus-visible:bg-neutral-700 focus-visible:text-neutral-100 focus-visible:outline-none">
                                                    <i data-lucide="pencil" class="size-4"></i>
                                                    <span
                                                        class="flex items-center gap-1 text-sm font-medium"><?php echo htmlspecialchars($trans['dashboard_edit_phrase']); ?></span>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($phrase['visibility'] == '0'): ?>
                                                <form method="POST">
                                                    <input type="hidden" name="phrase_id" value="<?php echo $phrase['id']; ?>">
                                                    <button type="submit" name="make_public"
                                                        class="w-full flex items-center gap-2 rounded-sm border border-transparent px-1 text-neutral-400 hover:text-neutral-100 hover:bg-neutral-800 focus-visible:border-neutral-600 focus-visible:bg-neutral-700 focus-visible:text-neutral-100 focus-visible:outline-none">
                                                        <i data-lucide="eye" class="size-4"></i>
                                                        <span
                                                            class="flex items-center gap-1 text-sm font-medium"><?php echo htmlspecialchars($trans['dashboard_show_phrase']); ?></span>
                                                    </button>
                                                </form>
                                            <?php elseif ($phrase['visibility'] == '1'): ?>
                                                <form method="POST">
                                                    <input type="hidden" name="phrase_id" value="<?php echo $phrase['id']; ?>">
                                                    <button type="submit" name="make_private"
                                                        class="flex items-center gap-2 rounded-sm border border-transparent px-1 pl-auto text-neutral-400 hover:text-neutral-100 hover:bg-neutral-800 focus-visible:border-neutral-600 focus-visible:bg-neutral-700 focus-visible:text-neutral-100 focus-visible:outline-none">
                                                        <i data-lucide="eye-off" class="size-4"></i>
                                                        <span
                                                            class="flex items-center gap-1 text-sm font-medium mr-3"><?php echo htmlspecialchars($trans['dashboard_hide_phrase']); ?></span>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            <a href="phrase/share.php?id=<?php echo $phrase['id']; ?>" role="menuitem"
                                                class="w-full flex items-center gap-2 rounded-sm border border-transparent px-1 text-neutral-400 hover:text-neutral-100 hover:bg-neutral-800 focus-visible:border-neutral-600 focus-visible:bg-neutral-700 focus-visible:text-neutral-100 focus-visible:outline-none">
                                                <i data-lucide="link" class="size-4"></i>
                                                <span
                                                    class="flex items-center gap-1 text-sm font-medium"><?php echo htmlspecialchars($trans['dashboard_share_phrase']); ?></span>
                                            </a>
                                            <form method="POST"
                                                onsubmit="return confirm('<?php echo htmlspecialchars($trans['dashboard_delete_confirmation']); ?>')">
                                                <input type="hidden" name="phrase_id" value="<?php echo $phrase['id']; ?>">
                                                <button type="submit" name="delete_phrase" role="menuitem"
                                                    class="w-full flex gap-2 px-1 items-center rounded-sm border border-transparent text-red-500 hover:bg-red-500/10 focus-visible:border-red-500/10 focus-visible:bg-red-500/10 focus-visible:text-red-500 focus-visible:outline-none">
                                                    <i data-lucide="trash" class="size-4"></i>
                                                    <?php echo htmlspecialchars($trans['dashboard_delete_phrase']); ?>
                                                </button>
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
        <?php if ($total_phrases > 10): ?>
            <div class="mx-auto max-w-3xl overflow-x md:max-w-5xl px-4 flex justify-between items-center mt-4">
                <div class="ml-2">
                    <span class="text-xs md:text-sm text-neutral-400">
                        <span class="hidden md:inline-flex""><?php echo htmlspecialchars($trans['admin_pagination_showing']); ?></span>
                        <span
                            class=" text-neutral-100
                            font-semibold"><?php echo min(count($phrases), $total_phrases); ?></span>
                        <?php echo htmlspecialchars($trans['admin_pagination_phrases_of']); ?>
                        <span class="text-neutral-100 font-semibold"><?php echo $total_phrases; ?></span>
                        <?php echo htmlspecialchars($trans['admin_pagination_total']); ?>
                    </span>
                </div>
                <div class="flex items-center md:mr-2">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?page=1"
                            class="inline-flex items-center px-1 py-0.5 md:px-2 md:py-1 rounded-l-md border border-neutral-700 bg-neutral-800 text-sm font-medium text-neutral-300 hover:bg-neutral-700">
                            <span class="sr-only">First</span>
                            <i data-lucide="chevrons-left" class="size-4"></i>
                        </a>
                        <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?page=<?php echo max(1, $page - 1); ?>"
                            class="inline-flex items-center px-1 py-0.5 md:px-2 md:py-1 border border-neutral-700 bg-neutral-800 text-sm font-medium text-neutral-300 hover:bg-neutral-700">
                            <span class="sr-only">Previous</span>
                            <i data-lucide="chevron-left" class="size-4"></i>
                        </a>
                        <span
                            class="inline-flex items-center gap-1 px-1 py-0.5 md:px-2 md:py-1 border border-neutral-700 bg-neutral-800 text-sm font-medium text-neutral-300">
                            <span class="hidden md:inline-flex">
                                <?php echo htmlspecialchars($trans['admin_pagination_page']); ?>
                            </span>
                            <?php echo $page . $trans['admin_pagination_page_of'] . $total_pages; ?>
                        </span>
                        <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?page=<?php echo min($total_pages, $page + 1); ?>"
                            class="inline-flex items-center px-1 py-0.5 md:px-2 md:py-1.5  border border-neutral-700 bg-neutral-800 text-sm font-medium text-neutral-300 hover:bg-neutral-700">
                            <span class="sr-only">Next</span>
                            <i data-lucide="chevron-right" class="size-4"></i>
                        </a>
                        <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?page=<?php echo $total_pages; ?>"
                            class="inline-flex items-center px-1 py-0.5 md:px-2 md:py-1 rounded-r-md border border-neutral-700 bg-neutral-800 text-sm font-medium text-neutral-300 hover:bg-neutral-700">
                            <span class="sr-only">Last</span>
                            <i data-lucide="chevrons-right" class="size-4"></i>
                        </a>
                    </nav>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <script>
        lucide.createIcons();

        <?php foreach ($phrases as $phrase): ?>
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