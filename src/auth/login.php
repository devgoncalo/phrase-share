<?php
include '../database/connection.php';

$errors = [];

session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ../dashboard.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['email'])) {
        $errors[] = "The email is required.";
    }
    if (empty($_POST['password'])) {
        $errors[] = "The Password is required.";
    }

    if (empty($errors)) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['email'] = $email;
                $_SESSION['user_id'] = $user['id'];
                header('Location: ../dashboard.php');
                exit();
            } else {
                $errors[] = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            error_log('Database Error: ' . $e->getMessage(), 0);
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
    <title>PhraseShare · Login</title>
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
                    <h1 class="text-lg font-[500] tracking-tight antialiased">Welcome back!</h1>
                    <p class="text-xs font-[400] tracking-tight text-neutral-400 antialiased">
                        By signing in, you agree to our <a class="text-blue-400 focus-within:underline focus-within:outline-none hover:underline" target="_blank" href="/legal/terms-of-service">terms</a>, and <a class="text-blue-400 focus-within:underline focus-within:outline-none hover:underline" target="_blank" href="/legal/privacy-policy">privacy policy</a>.
                    </p>
                </div>
                <form method="post" action="login.php" class="flex w-full flex-col items-center justify-center gap-y-6">
                    <div class="flex w-full flex-col justify-center gap-y-4">
                        <div class="w-full space-y-2"><label class="font-medium text-sm leading-none shadow-sm peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for=":r6:-form-item">Email</label>
                            <div class="w-full">
                                <div class="w-full h-[42px] px-3 flex items-center rounded-xl border border-neutral-800 focus-within:border-2 focus-within:border-[#1e1e20] focus-within:ring-2 focus-within:ring-neutral-700 bg-neutral-900 transition-all duration-200 relative data-[filled=true]:border-neutral-200">
                                    <input placeholder="example@email.com" type="email" id="email" name="email" class="flex-1 h-full py-2 outline-none text-sm text-neutral-300 bg-transparent relative z-[9999] disabled:cursor-not-allowed shadow-inner" />
                                </div>
                            </div>
                        </div>
                        <div class="w-full space-y-2"><label class="font-medium inline-flex w-full justify-between text-sm leading-none shadow-sm peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for=":r7:-form-item">Password</label>
                            <div class="w-full">
                                <div class="w-full h-[42px] px-3 flex items-center rounded-xl border border-neutral-800 focus-within:border-2 focus-within:border-[#1e1e20] focus-within:ring-2 focus-within:ring-neutral-700 bg-neutral-900 transition-all duration-200 relative data-[filled=true]:border-neutral-200">
                                    <input placeholder="********" type="password" id="password" name="password" class="flex-1 h-full py-2 outline-none text-sm text-neutral-300 bg-transparent relative z-[9999] disabled:cursor-not-allowed shadow-inner" />
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
                            Log In
                        </button>
                    </div>
                </form>
                <div class="flex w-full flex-row items-center justify-start gap-x-2">
                    <span class="text-xs font-[400] leading-tight text-neutral-400 antialiased">
                        Don't have an account?
                    </span>
                    <a href="register.php" class="inline-flex h-7 items-center justify-center gap-x-1 whitespace-nowrap rounded-md border-[0.5px] border-black bg-[#262628] px-2 py-0.5 text-xs font-medium text-neutral-100 shadow-sm transition-colors focus-within:ring-2 focus-within:ring-neutral-700 hover:bg-[#2c2c2e] focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50">
                        Sign Up
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>