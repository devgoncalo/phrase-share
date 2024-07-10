<?php
include '../database/connection.php';
include '../translations.php';

session_start();

$errors = [];

if (isset($_GET['lang'])) {
    $_SESSION['language'] = $_GET['lang'];
}

$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
$trans = $translations[$language] ?? $translations['en'];

if (isset($_SESSION['user_id'])) {
    header('Location: ../dashboard.php');
    exit();
}

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'invalid_token':
            $errors[] = $trans['login_invalid_token'];
            break;
        case 'db_error':
            $errors[] = $trans['login_db_error'];
            break;
        case 'token_not_provided':
            $errors[] = $trans['login_token_not_provided'];
            break;
        default:
            $errors[] = $trans['login_unknown_error'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email)) {
        $errors[] = $trans['login_email_required'];
    } elseif (empty($password)) {
        $errors[] = $trans['login_password_required'];
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                if ($user['confirmed'] == 1) {
                    $_SESSION['email'] = $email;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['status'] = $user['status'];

                    header('Location: ../dashboard.php');
                    exit();
                } else {
                    $errors[] = $trans['login_account_not_verified'];
                }
            } else {
                $errors[] = $trans['login_invalid_email_password'];
            }
        } catch (PDOException $e) {
            error_log('Database Error: ' . $e->getMessage(), 0);
            $errors[] = $trans['db_error'];
        }
    }
} else {
    $email = '';
}

?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($language); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhraseShare · <?php echo htmlspecialchars($trans['login_page_title']); ?></title>
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
                <div class="flex w-full items-start">
                    <div class="rounded-xl shadow-sm border-2 border-neutral-700 p-2">
                        <i data-lucide="link" class="size-5"></i>
                    </div>
                </div>
                <div class="flex w-full flex-col justify-start gap-y-1">
                    <h1 class="text-lg font-[500] tracking-tight antialiased"><?php echo htmlspecialchars($trans['login_welcome']); ?></h1>
                    <p class="text-xs font-[400] tracking-tight text-neutral-400 antialiased"><?php echo htmlspecialchars($trans['login_information']); ?> <a class="text-blue-400 focus-within:underline focus-within:outline-none hover:underline" target="_blank" href="/legal/terms-of-service"><?php echo htmlspecialchars($trans['login_terms']); ?></a>, & <a class="text-blue-400 focus-within:underline focus-within:outline-none hover:underline" target="_blank" href="/legal/privacy-policy"><?php echo htmlspecialchars($trans['login_privacy']); ?></a>.</p>
                </div>
                <form method="post" action="login.php" class="flex w-full flex-col items-center justify-center gap-y-6">
                    <div class="flex w-full flex-col justify-center gap-y-4">
                        <div class="w-full space-y-2"><label class="font-medium text-sm leading-none shadow-sm peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for=":r6:-form-item">Email</label>
                            <div class="w-full">
                                <div class="w-full h-[42px] px-3 flex items-center rounded-xl border border-neutral-800 focus-within:border-2 focus-within:border-[#1e1e20] focus-within:ring-2 focus-within:ring-neutral-700 bg-neutral-900 transition-all duration-200 relative data-[filled=true]:border-neutral-200">
                                    <input placeholder="example@email.com" type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="flex-1 h-full py-2 outline-none text-sm text-neutral-300 bg-transparent relative z-[9999] disabled:cursor-not-allowed shadow-inner" />
                                </div>
                            </div>
                        </div>
                        <div class="w-full space-y-2">
                            <div class="font-normal inline-flex w-full justify-between text-sm leading-none shadow-sm peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for=":r7:-form-item">
                                <label for="password" class="font-medium">Password</label>
                                <a class="text-blue-400 focus-within:underline focus-within:outline-none hover:underline" href="./forgot-password.php">
                                    <?php echo htmlspecialchars($trans['login_forgot_password']); ?>
                                </a>
                            </div>
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
                    <?php if (isset($_GET['verified']) && $_GET['verified'] === 'true') : ?>
                        <div id="success-message" class="flex w-full flex-row items-center gap-2 text-xs text-green-500">
                            <i data-lucide="check" class="size-4"></i>
                            <span><?php echo htmlspecialchars($trans['login_success_message']); ?></span>
                        </div>
                    <?php elseif (isset($_GET['confirmation_sent']) && $_GET['confirmation_sent'] === 'true') : ?>
                        <div id="success-message" class="flex w-full flex-row items-center gap-2 text-xs text-green-500">
                            <i data-lucide="check" class="size-4"></i>
                            <span><?php echo htmlspecialchars($trans['register_confirmation_sent_success']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_GET['reset_success']) && $_GET['reset_success'] === 'true') : ?>
                        <div id="success-message" class="flex w-full flex-row items-center gap-2 text-xs text-green-500">
                            <i data-lucide="check" class="size-4"></i>
                            <span><?php echo htmlspecialchars($trans['reset_password_success_message']); ?></span>
                        </div>
                    <?php endif; ?>
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
                            <?php echo htmlspecialchars($trans['login_submit']); ?>
                        </button>
                    </div>
                </form>
                <div class="flex w-full flex-row items-center justify-start gap-x-2">
                    <span class="text-xs font-[400] leading-tight text-neutral-400 antialiased">
                        <?php echo htmlspecialchars($trans['login_no_account']); ?>
                    </span>
                    <a href="register.php" class="inline-flex h-7 items-center justify-center gap-x-1 whitespace-nowrap rounded-md border-[0.5px] border-black bg-[#262628] px-2 py-0.5 text-xs font-medium text-neutral-100 shadow-sm transition-colors focus-within:ring-2 focus-within:ring-neutral-700 hover:bg-[#2c2c2e] focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50">
                        <?php echo htmlspecialchars($trans['login_register']); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        lucide.createIcons();

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const showSuccess = (param) => {
                const successMessage = document.getElementById('success-message');
                if (successMessage && urlParams.get(param) === 'true') {
                    successMessage.style.display = 'flex';
                    setTimeout(() => {
                        successMessage.style.display = 'none';
                    }, 5000);
                }
            };

            showSuccess('verified');
            showSuccess('confirmation_sent');
            showSuccess('reset_success');
        });

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