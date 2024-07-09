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
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username)) {
        $errors[] = $trans['register_username_required'];
    } elseif (empty($email)) {
        $errors[] = $trans['register_email_required'];
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = $trans['register_invalid_email_format'];
    } elseif (empty($password)) {
        $errors[] = $trans['register_password_required'];
    } else {
        $password = $_POST['password'];
        if (strlen($password) < 8) {
            $errors[] = $trans['register_password_length'];
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $errors[] = $trans['register_password_uppercase'];
        } elseif (!preg_match('/[a-z]/', $password)) {
            $errors[] = $trans['register_password_lowercase'];
        } elseif (!preg_match('/[0-9]/', $password)) {
            $errors[] = $trans['register_password_number'];
        } elseif (!preg_match('/[\W]/', $password)) {
            $errors[] = $trans['register_password_special'];
        }
    }

    if (empty($errors)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $language = $_SESSION['language'];

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $emailExists = $stmt->fetchColumn();

        if ($emailExists) {
            $errors[] = $trans['register_email_exists'];
        } else {
            $token = bin2hex(random_bytes(16));

            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, language, confirmation_token) VALUES (:username, :email, :password, :language, :token)");
            $stmt->execute(['username' => $username, 'email' => $email, 'password' => $passwordHash, 'language' => $language, 'token' => $token]);

            $confirmation_link = "http://localhost:8000/src/auth/confirm.php?token=" . $token;
            if (isset($_SESSION['language'])) {
                $confirmation_link .= "&lang=" . $_SESSION['language'];
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
                $mail->Subject = $trans['register_email_subject'];
                $mail->Body = $trans['register_email_body'] . $confirmation_link;

                $mail->send();
                header('Location: login.php?confirmation_sent=true');
                exit();
            } catch (Exception $e) {
                $errors[] = $trans['register_email_error'] . ': ' . $mail->ErrorInfo;
            }
        }
    }
} else {
    $username = '';
    $email = '';
}

?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($language); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhraseShare · <?php echo htmlspecialchars($trans['register_page_title']); ?></title>
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="min-h-screen bg-black bg-gradient-to-tr from-neutral-900/50 to-neutral-700/30 overflow-hidden text-neutral-100">
    <div>
        <a class="absolute left-9 top-9 inline-flex select-none items-center justify-center rounded-full bg-neutral-900 p-0.5 text-sm font-semibold text-neutral-100 transition duration-200 ease-in-out hover:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-100" href="/">
            <i data-lucide="x" class="size-4"></i>
        </a>
        <div class="flex min-h-screen w-full flex-col items-center justify-center">
            <div class="flex w-full max-w-[488px] flex-col items-center justify-center gap-y-6 rounded-xl p-12 text-neutral-100 md:justify-start">
                <div class="flex w-full items-start ">
                    <div class="rounded-xl shadow-sm border-2 border-neutral-700 p-2">
                        <i data-lucide="link" class="size-5"></i>
                    </div>
                </div>
                <div class="flex w-full flex-col justify-start gap-y-1">
                    <h1 class="text-lg font-[500] tracking-tight antialiased"><?php echo htmlspecialchars($trans['register_welcome']); ?></h1>
                    <p class="text-xs font-[400] tracking-tight text-neutral-400 antialiased"><?php echo htmlspecialchars($trans['register_information']); ?> <a class="text-blue-400 focus-within:underline focus-within:outline-none hover:underline" target="_blank" href="/legal/terms-of-service"><?php echo htmlspecialchars($trans['register_terms']); ?></a>, & <a class="text-blue-400 focus-within:underline focus-within:outline-none hover:underline" target="_blank" href="/legal/privacy-policy"><?php echo htmlspecialchars($trans['register_privacy']); ?></a>.</p>
                </div>
                <form method="post" action="register.php" class="flex w-full flex-col items-center justify-center gap-y-6">
                    <div class="flex w-full flex-col justify-center gap-y-4">
                        <div class="w-full space-y-2"><label class="font-medium text-sm leading-none shadow-sm peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for=":r6:-form-item">Username</label>
                            <div class="w-full">
                                <div class="w-full h-[42px] px-3 flex items-center rounded-xl border border-neutral-800 focus-within:border-2 focus-within:border-[#1e1e20] focus-within:ring-2 focus-within:ring-neutral-700 bg-neutral-900 transition-all duration-200 relative data-[filled=true]:border-neutral-200">
                                    <input placeholder="Jhon Doe" type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" class="flex-1 h-full py-2 outline-none text-sm text-neutral-300 bg-transparent relative z-[9999] disabled:cursor-not-allowed shadow-inner" />
                                </div>
                            </div>
                        </div>
                        <div class="w-full space-y-2"><label class="font-medium text-sm leading-none shadow-sm peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for=":r6:-form-item">Email</label>
                            <div class="w-full">
                                <div class="w-full h-[42px] px-3 flex items-center rounded-xl border border-neutral-800 focus-within:border-2 focus-within:border-[#1e1e20] focus-within:ring-2 focus-within:ring-neutral-700 bg-neutral-900 transition-all duration-200 relative data-[filled=true]:border-neutral-200">
                                    <input placeholder="jhondoe@email.com" type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="flex-1 h-full py-2 outline-none text-sm text-neutral-300 bg-transparent relative z-[9999] disabled:cursor-not-allowed shadow-inner" />
                                </div>
                            </div>
                        </div>
                        <div class="w-full space-y-2"><label class="font-medium inline-flex w-full justify-between text-sm leading-none shadow-sm peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for=":r7:-form-item">Password</label>
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
                            <?php echo htmlspecialchars($trans['register_submit']); ?>
                        </button>
                    </div>
                </form>
                <div class="flex w-full flex-row items-center justify-start gap-x-2">
                    <span class="text-xs font-[400] leading-tight text-neutral-400 antialiased">
                        <?php echo htmlspecialchars($trans['register_no_account']); ?>
                    </span>
                    <a href="login.php" class="inline-flex h-7 items-center justify-center gap-x-1 whitespace-nowrap rounded-md border-[0.5px] border-black bg-[#262628] px-2 py-0.5 text-xs font-medium text-neutral-100 shadow-sm transition-colors focus-within:ring-2 focus-within:ring-neutral-700 hover:bg-[#2c2c2e] focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50">
                        <?php echo htmlspecialchars($trans['register_login']); ?>
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