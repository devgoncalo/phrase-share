<?php
include '../database/connection.php';
include '../translations.php';

$errors = [];

session_start();

if (isset($_GET['lang'])) {
  $_SESSION['language'] = $_GET['lang'];
}

$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
$trans = $translations[$language] ?? $translations['en'];

if (!isset($_GET['token'])) {
  header('Location: ./login.php');
  exit();
}

$token = $_GET['token'];

$stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = :token AND reset_token_expire >= NOW()");
$stmt->execute(['token' => $token]);
$user = $stmt->fetch();

if (!$user) {
  header('Location: ./login.php');
  exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  if (empty($password) || empty($confirm_password)) {
    $errors[] = $trans['reset_password_empty'];
  } elseif ($password !== $confirm_password) {
    $errors[] = $trans['reset_password_mismatch'];
  } elseif (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long";
  } elseif (!preg_match('/[A-Z]/', $password)) {
    $errors[] = "Password must include at least one uppercase letter";
  } elseif (!preg_match('/[a-z]/', $password)) {
    $errors[] = "Password must include at least one lowercase letter";
  } elseif (!preg_match('/[0-9]/', $password)) {
    $errors[] = "Password must include at least one number";
  } elseif (!preg_match('/[\W]/', $password)) {
    $errors[] = "Password must include at least one special character";
  } else {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_token_expire = NULL WHERE id = :id");
    $stmt->execute(['password' => $hashed_password, 'id' => $user['id']]);

    header('Location: ./login.php?reset_success=true');
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($language); ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PhraseShare · <?php echo htmlspecialchars($trans['reset_password_page_title']); ?></title>
  <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="min-h-screen bg-black bg-gradient-to-tr from-neutral-900/50 to-neutral-700/30 overflow-hidden text-neutral-100">
  <div>
    <a href="./login.php" class="absolute left-9 top-9 inline-flex select-none items-center justify-center rounded-full bg-neutral-900 p-0.5 text-sm font-semibold text-neutral-100 transition duration-200 ease-in-out hover:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-100">
      <i data-lucide="x" class="size-4"></i>
    </a>
    <div class="flex min-h-screen w-full flex-col items-center justify-center">
      <div class="flex w-full max-w-[488px] flex-col items-center justify-center gap-y-6 rounded-xl p-12 text-neutral-100 md:justify-start">
        <div class="flex w-full items-start">
          <div class="rounded-xl shadow-sm border-2 border-neutral-700 p-2">
            <i data-lucide="link" class="size-5"></i>
          </div>
        </div>

        <div class="flex w-full flex-col justify-start gap-y-1">
          <h1 class="text-lg font-[500] tracking-tight antialiased"><?php echo htmlspecialchars($trans['reset_password_welcome']); ?></h1>
          <p class="text-xs font-[400] tracking-tight text-neutral-400 antialiased"><?php echo htmlspecialchars($trans['reset_password_information']); ?></p>
        </div>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?token=' . htmlspecialchars($token); ?>" class="flex w-full flex-col items-center justify-center gap-y-6">
          <div class="flex w-full flex-col items-center justify-center gap-y-4">
            <div class="w-full space-y-2">
              <label class="text-sm leading-none shadow-sm peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                Password
              </label>
              <div class="w-full">
                <div class="w-full h-[42px] px-3 flex items-center text-neutral-300 rounded-xl border border-neutral-800 focus-within:border-2 focus-within:border-[#1e1e20] focus-within:ring-2 focus-within:ring-neutral-700 bg-neutral-900 transition-all duration-200 relative data-[filled=true]:border-neutral-200">
                  <input placeholder="********" type="password" id="password" name="password" class="flex-1 h-full py-2 outline-none text-sm bg-transparent relative z-[1] disabled:cursor-not-allowed shadow-inner" />
                  <button type="button" onclick="togglePassword()" class="cursor-pointer z-[10] absolute right-3 text-neutral-400 transition duration-200 ease-in-out hover:text-neutral-100 focus-visible:outline-none">
                    <i id="showEye" class="size-3.5" data-lucide="eye"></i>
                    <i id="hideEye" class="hidden size-3.5" data-lucide="eye-off"></i>
                  </button>
                </div>
              </div>
            </div>
            <div class="w-full space-y-2">
              <label class="text-sm leading-none shadow-sm peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                <?php echo htmlspecialchars($trans['reset_password_confirm_label']); ?> Password
              </label>
              <div class="w-full">
                <div class="w-full h-[42px] px-3 flex items-center rounded-xl border border-neutral-800 focus-within:border-2 focus-within:border-[#1e1e20] focus-within:ring-2 focus-within:ring-neutral-700 bg-neutral-900 transition-all duration-200 relative data-[filled=true]:border-neutral-200">
                  <input placeholder="********" type="password" id="confirm_password" name="confirm_password" class="flex-1 h-full py-2 outline-none text-sm text-neutral-300 bg-transparent relative z-[9999] disabled:cursor-not-allowed shadow-inner" />
                </div>
              </div>
            </div>
          </div>
          <?php if (!empty($errors)) : ?>
            <div class="flex w-full flex-row gap-1 items-center text-red-500 text-xs" role="alert">
              <?php foreach ($errors as $error) : ?>
                <i data-lucide="ban" class="size-3"></i>
                <p><?php echo $error; ?></p>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <div class="flex w-full flex-col items-center justify-center gap-y-2">
            <button type="submit" class="ring-offset-background text-neutral-900 inline-flex h-10 w-full items-center justify-center gap-x-1 whitespace-nowrap rounded-lg border border-neutral-700 bg-neutral-100 px-4 py-2 text-sm font-medium antialiased shadow-sm transition-colors focus-within:border-2 focus-within:border-[#1e1e20] focus-within:ring-2 focus-within:ring-neutral-700 hover:bg-neutral-300 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50">
              <?php echo htmlspecialchars($trans['reset_password_submit']); ?>
            </button>
          </div>
        </form>
        <div class="flex w-full flex-row items-center justify-start gap-x-2">
          <span class="text-sm font-[400] leading-tight text-neutral-400 antialiased"><?php echo htmlspecialchars($trans['reset_password_action']); ?></span>
          <a href="./login.php" class="inline-flex h-7 items-center justify-center gap-x-1 whitespace-nowrap rounded-md border-[0.5px] border-black bg-[#262628] px-2 py-0.5 text-xs font-medium text-neutral-100 shadow-sm transition-colors focus-within:ring-2 focus-within:ring-neutral-700 hover:bg-[#2c2c2e] focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50">
            <?php echo htmlspecialchars($trans['general_go_back']); ?>
          </a>
        </div>
      </div>
    </div>
  </div>
  <script>
    lucide.createIcons();

    function togglePassword() {
      const passwordField = document.getElementById('password');
      const togglePasswordIcon = document.getElementById('togglePasswordIcon');

      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        document.getElementById('showEye').classList.add('hidden');
        document.getElementById('hideEye').classList.remove('hidden');
      } else {
        passwordField.type = 'password';
        document.getElementById('showEye').classList.remove('hidden');
        document.getElementById('hideEye').classList.add('hidden');
      }
    }
  </script>
</body>

</html>