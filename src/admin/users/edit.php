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
  $user_id = $_POST['user_id'];
  $username = $_POST['username'];
  $email = $_POST['email'];
  $status = $_POST['status'];

  try {
    $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email, status = :status WHERE id = :user_id");
    $stmt->execute(['username' => $username, 'email' => $email, 'status' => $status, 'user_id' => $user_id]);
    header('Location: ./overview.php');
    exit();
  } catch (PDOException $e) {
    echo "Error updating user: " . $e->getMessage();
  }
}

if (isset($_GET['id'])) {
  $user_id = $_GET['id'];
  try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $user = $stmt->fetch();
  } catch (PDOException $e) {
    echo "Error fetching user: " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($language); ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PhraseShare · <?php echo htmlspecialchars($trans['admin_user_edit_page_title']) ?></title>
  <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="min-h-screen bg-black bg-gradient-to-tr from-neutral-900/50 to-neutral-700/30 overflow-hidden text-neutral-100">
  <div class="container mx-auto py-8">
    <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-8">
      <h1 class="text-[28px] font-bold leading-[34px] tracking-[-0.416px] text-neutral-100">
        <?php echo htmlspecialchars($trans['admin_user_edit_page_title']) ?>
      </h1>
      <a href="<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : './overview.php'; ?>" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 bg-white pl-3 pr-3 text-sm font-semibold text-black transition duration-200 ease-in-out hover:bg-white/90 focus-visible:bg-white/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400">
        <span class="inline-flex flex-row items-center gap-2">
          <i data-lucide="arrow-left" class="size-4"></i>
          <?php echo $trans['general_go_back']; ?>
        </span>
      </a>
    </div>

    <div class="mx-auto max-w-5xl px-6">
      <form class="flex flex-col gap-6" method="post" action="">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <div class="space-y-2">
          <label for="username" class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-normal text-neutral-400"><?php echo htmlspecialchars($trans['admin_user_edit_username_label']) ?></label>
          <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="flex w-full rounded-md py-2 text-sm outline-none ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:border-black focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-50 h-8 border border-neutral-700 bg-neutral-900 px-2 text-neutral-100 transition duration-200 ease-in-out placeholder:text-neutral-500">
        </div>
        <div class="space-y-2">
          <label for="email" class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-normal text-neutral-400"><?php echo htmlspecialchars($trans['admin_user_edit_email_label']) ?></label>
          <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="flex w-full rounded-md py-2 text-sm outline-none ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:border-black focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-50 h-8 border border-neutral-700 bg-neutral-900 px-2 text-neutral-100 transition duration-200 ease-in-out placeholder:text-neutral-500">
        </div>
        <div class="space-y-2">
          <label for="status" class="peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm font-normal text-neutral-400">
            <?php echo htmlspecialchars($trans['admin_user_edit_status_label']) ?>
          </label>
          <select id="status" name="status" class="flex w-full rounded-md py-1 text-sm outline-none ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:border-black focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-50 h-8 border border-neutral-700 bg-neutral-900 px-2 text-neutral-100 transition duration-200 ease-in-out placeholder:text-neutral-500">
            <option value="1" <?php echo $user['status'] == 1 ? 'selected' : ''; ?>><?php echo htmlspecialchars($trans['admin_user_edit_status_blocked']); ?></option>
            <option value="0" <?php echo $user['status'] == 0 ? 'selected' : ''; ?>><?php echo htmlspecialchars($trans['admin_user_edit_status_active']); ?></option>
          </select>
        </div>
        <div class="flex flex-row gap-2">
          <button type="submit" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 p-2 text-sm font-semibold text-white transition duration-200 ease-in-out hover:bg-neutral-800 focus-visible:border-black focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-800">
            <?php echo htmlspecialchars($trans['edit_save_button']) ?>
          </button>
          <a href="./dashboard.php" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md p-2 text-sm font-semibold text-white transition duration-200 ease-in-out hover:bg-neutral-800 focus-visible:border-black focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-800">
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