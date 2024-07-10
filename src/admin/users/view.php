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

$language = isset($_GET['lang']) ? $_GET['lang'] : (isset($_SESSION['language']) ? $_SESSION['language'] : 'en');
$trans = $translations[$language] ?? $translations['en'];

$user_id = $_GET["id"];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
$stmt->execute(["user_id" => $user_id]);
$user = $stmt->fetch();

if (!$user) {
  header("Location: ../../dashboard.php");
  exit();
}

$username = $user["username"];
$email = $user["email"];
$email_confirmed = $user["confirmed"];
$signup_time = $user["signup_time"];
$status = $user["status"];

$phrases_stmt = $pdo->prepare("SELECT COUNT(*) as phrases_created FROM phrases WHERE user_id = :user_id");
$phrases_stmt->execute(["user_id" => $user_id]);
$phrases_created = $phrases_stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($language); ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PhraseShare · <?php echo htmlspecialchars($trans['admin_user_view_page_title']) ?></title>
  <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="min-h-screen bg-black bg-gradient-to-tr from-neutral-900/50 to-neutral-700/30 overflow-hidden text-neutral-100">
  <div class="container mx-auto py-8">
    <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-8">
      <h1 class="text-[28px] font-bold leading-[34px] tracking-[-0.416px] text-neutral-100">
        <?php echo $trans['admin_user_view_page_title']; ?>
      </h1>
      <?php if (isset($_SERVER['HTTP_REFERER'])) : ?>
        <a href="./overview.php" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 bg-white pl-3 pr-3 text-sm font-semibold text-black transition duration-200 ease-in-out hover:bg-white/90 focus-visible:bg-white/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400">
          <span class="inline-flex flex-row items-center gap-2">
            <i data-lucide="arrow-left" class="size-4"></i>
            <?php echo $trans['general_go_back']; ?>
          </span>
        </a>
      <?php endif; ?>
    </div>

    <div class="mx-auto max-w-5xl px-6">
      <div class="flex justify-center">
        <div class="flex w-full flex-col px-6 py-8 border border-neutral-700 rounded-md">
          <div class="flex flex-col items-center gap-2 md:flex-row">
            <i data-lucide="user" class="size-14"></i>
            <div class="w-full overflow-hidden text-center md:text-left">
              <span class="text-md font-bold text-neutral-400"><?php echo htmlspecialchars($username); ?></span>
              <h1 class="w-full font-semibold truncate text-lg leading-[34px] tracking-[-0.416px] text-neutral-100 md:max-w-[800px]"><?php echo $email; ?></h1>
            </div>
          </div>
          <div class="w-full mt-8 flex flex-row gap-4 md:justify-between">
            <div class="flex flex-col gap-1">
              <label class="text-xs uppercase text-neutral-400"><?php echo $trans['admin_user_view_id_label']; ?></label>
              <p class="group text-start text-sm font-normal focus-visible:outline-none text-current">
                <?php echo $user_id; ?>
              </p>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs uppercase text-neutral-400"><?php echo $trans['admin_user_view_email_confirmed_label']; ?></label>
              <p class="group text-start text-sm font-normal focus-visible:outline-none text-current">
                <?php echo $email_confirmed == 1 ? $trans['admin_user_view_confirmed'] : $trans['admin_user_view_not_confirmed']; ?>
              </p>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs uppercase text-neutral-400"><?php echo $trans['admin_user_view_signup_time_label']; ?></label>
              <p class="group text-start text-sm font-normal focus-visible:outline-none text-current">
                <time class="group-focus-visible:border-b group-focus-visible:border-neutral-700">
                  <?php echo date('Y-m-d H:i:s', strtotime($signup_time)); ?>
                </time>
              </p>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs uppercase text-neutral-400"><?php echo $trans['admin_user_view_status_label']; ?></label>
              <p class="group text-start text-sm font-normal focus-visible:outline-none text-current">
              <?php echo $status == 1 ? $trans['admin_user_view_status_blocked'] : $trans['admin_user_view_status_active']; ?>
              </p>
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs uppercase text-neutral-400"><?php echo $trans['admin_user_view_phrases_creaeted_label']; ?></label>
              <p class="group text-start text-sm font-normal focus-visible:outline-none text-current">
                <?php echo $phrases_created; ?>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    lucide.createIcons();
  </script>
</body>

</html>