<?php
include '../database/connection.php';
include '../translations.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../lib/php-mailer/src/Exception.php';
require '../lib/php-mailer/src/PHPMailer.php';
require '../lib/php-mailer/src/SMTP.php';

$errors = [];

session_start();

if (isset($_GET['lang'])) {
  $_SESSION['language'] = $_GET['lang'];
}

$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
$trans = $translations[$language] ?? $translations['en'];

if (isset($_SESSION['user_id'])) {
  header('Location: ../dashboard.php');
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
  $email_sent = false;

  if (!$email) {
    $errors[] = $trans['reset_invalid_email'];
  } else {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
      $token = bin2hex(random_bytes(16));

      $expire_time = date('Y-m-d H:i:s', strtotime('+1 hour'));
      $stmt = $pdo->prepare("UPDATE users SET reset_token = :token, reset_token_expire = :expire_time WHERE id = :id");
      $stmt->execute(['token' => $token, 'expire_time' => $expire_time, 'id' => $user['id']]);

      $reset_link = "http://localhost:8000/src/auth/reset.php?token=" . $token;

      if (isset($_SESSION['language'])) {
        $reset_link .= "&lang=" . $_SESSION['language'];
      }

      $mail = new PHPMailer(true);

      try {
        $mail->isSMTP();
        $mail->Host = 'ssl0.ovh.net';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply@i-told-u.com';
        $mail->Password = 'NoQ!34343Reply!';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('no-reply@i-told-u.com', 'PhraseShare');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Reset Your PhraseShare Password";
        $mail->Body = "Reset link: " . $reset_link;

        $mail->send();
        $email_sent = true;
      } catch (Exception $e) {
        $errors[] = $trans['reset_email_error'] . ': ' . $mail->ErrorInfo;
      }
    } else {
      $errors[] = $trans['reset_email_not_found'];
    }
  }
}

?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($language); ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PhraseShare · <?php echo htmlspecialchars($trans['forgot_page_title']); ?></title>
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
          <h1 class="text-lg font-[500] tracking-tight antialiased"><?php echo htmlspecialchars($trans['forgot_welcome']); ?></h1>
          <p class="text-xs font-[400] tracking-tight text-neutral-400 antialiased"><?php echo htmlspecialchars($trans['forgot_information']); ?></p>
        </div>
        <form method="post" action="forgot-password.php" class="flex w-full flex-col items-center justify-center gap-y-6">
          <div class="flex w-full flex-col items-center justify-center gap-y-3">
            <div class="w-full space-y-2">
              <label class="text-sm leading-none shadow-sm peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                Email
              </label>
              <div class="w-full">
                <div class="w-full h-[42px] px-3 flex items-center rounded-xl border border-neutral-800 focus-within:border-2 focus-within:border-[#1e1e20] focus-within:ring-2 focus-within:ring-neutral-700 bg-neutral-900 transition-all duration-200 relative data-[filled=true]:border-neutral-200">
                  <input placeholder="example@email.com" type="email" id="email" name="email" class="flex-1 h-full py-2 outline-none text-sm text-neutral-300 bg-transparent relative z-[9999] disabled:cursor-not-allowed shadow-inner" />
                </div>
              </div>
            </div>
            <?php if ($email_sent) : ?>
              <div id="success-message" class="flex w-full flex-row items-center gap-2 text-xs text-green-500">
                <i data-lucide="check" class="size-4"></i>
                <span><?php echo htmlspecialchars($trans['forgot_success_message']); ?></span>
              </div>
            <?php endif; ?>
          </div>
          <div class="flex w-full flex-col items-center justify-center gap-y-2">
            <button type="submit" class="ring-offset-background text-neutral-900 inline-flex h-10 w-full items-center justify-center gap-x-1 whitespace-nowrap rounded-lg border border-neutral-700 bg-neutral-100 px-4 py-2 text-sm font-medium antialiased shadow-sm transition-colors focus-within:border-2 focus-within:border-[#1e1e20] focus-within:ring-2 focus-within:ring-neutral-700 hover:bg-neutral-300 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50">
              <?php echo htmlspecialchars($trans['forgot_submit']); ?>
            </button>
          </div>
        </form>
        <div class="flex w-full flex-row items-center justify-start gap-x-2">
          <span class="text-sm font-[400] leading-tight text-neutral-400 antialiased"><?php echo htmlspecialchars($trans['forgot_remember_action']); ?></span>
          <a href="./login.php" class="inline-flex h-7 items-center justify-center gap-x-1 whitespace-nowrap rounded-md border-[0.5px] border-black bg-[#262628] px-2 py-0.5 text-xs font-medium text-neutral-100 shadow-sm transition-colors focus-within:ring-2 focus-within:ring-neutral-700 hover:bg-[#2c2c2e] focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50">
            <?php echo htmlspecialchars($trans['general_go_back']); ?>
          </a>
        </div>
      </div>
    </div>
  </div>
  <script>
    lucide.createIcons();

    const form = document.querySelector('form');

    form.addEventListener('submit', function() {
      const submitButton = form.querySelector('button[type="submit"]');
      submitButton.textContent = '<?php echo htmlspecialchars($trans["forgot_loading_text"]); ?>';
    });
  </script>
</body>

</html>