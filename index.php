<?php 
session_start();
include 'src/translations.php';

if (isset($_GET['lang'])) {
    $_SESSION['language'] = $_GET['lang'];
}

$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
$trans = $translations[$language] ?? $translations['en'];

?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($language); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhraseShare</title>
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-black bg-gradient-to-tr from-neutral-900/50 to-neutral-700/30 overflow-hidden">
    <main class="min-h-[100vh] mt-[10rem] md:mt-0">
        <form id="languageForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET" class="absolute left-9 top-9">
            <select name="lang" id="language" onchange="this.form.submit()" class='inline-flex select-none items-center justify-center rounded-full bg-neutral-900 py-0.5 px-2 text-sm font-semibold text-neutral-100 transition duration-200 ease-in-out hover:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-100 appearance-none'>
                <option value="en" <?php echo ($_GET['lang'] ?? 'en') === 'en' ? 'selected' : ''; ?>>🇺🇸</option>
                <option value="it" <?php echo ($_GET['lang'] ?? 'en') === 'it' ? 'selected' : ''; ?>>🇮🇹</option>
                <option value="pt" <?php echo ($_GET['lang'] ?? 'en') === 'pt' ? 'selected' : ''; ?>>🇵🇹</option>
            </select>
        </form>
        <div class="flex flex-col gap-8 pb-8 md:gap-16 md:pb-16 xl:pb-24">
            <div class="flex flex-col items-center justify-center max-w-3xl px-8 mx-auto mt-8 sm:min-h-screen sm:mt-0 sm:px-0">
                <!-- <div class="hidden sm:mb-8 sm:flex sm:justify-center">
                    <a href="https://github.com/devgoncalo/phrase-share" class="text-neutral-400 relative overflow-hidden rounded-full py-1.5 px-4 text-sm leading-6 ring-1 ring-neutral-100/10 hover:ring-neutral-100/30 duration-150 outline-none focus-visible:ring-2 focus-visible:ring-neutral-100/30">
                        PhraseShare is Open Source on
                        <span class="font-semibold text-neutral-200">GitHub<span aria-hidden="true">→</span></span>
                    </a>
                </div> -->
                <div>
                    <h1 class="py-4 text-5xl font-bold tracking-tight text-center text-transparent bg-gradient-to-t bg-clip-text from-neutral-100/50 to-white sm:text-7xl">
                        <?php echo htmlspecialchars($trans['home_title']); ?></br><?php echo htmlspecialchars($trans['home_sub_title']); ?>
                    </h1>
                    <p class="mt-6 leading-5 text-neutral-600 sm:text-center">
                        <?php echo htmlspecialchars($trans['home_description']); ?>
                    </p>
                    <div class="flex flex-col justify-center gap-4 mx-auto mt-8 sm:flex-row sm:max-w-lg ">
                        <a href="src/auth/login.php" class="sm:w-1/2 sm:text-center inline-block space-x-2 rounded px-4 py-1.5 md:py-2 text-base font-semibold leading-7 text-white ring-1 ring-neutral-600 hover:bg-white hover:text-neutral-900 duration-150 hover:ring-white hover:drop-shadow-cta outline-none focus-visible:ring-2 focus-visible:ring-neutral-100/30">
                            Login
                        </a>
                        <a href="src/auth/register.php" class="sm:w-1/2 sm:text-center inline-block transition-all space-x-2 rounded px-4 py-1.5 md:py-2 text-base font-semibold leading-7 text-neutral-800 bg-neutral-50 ring-1 ring-transparent hover:text-neutral-100 hover:ring-neutral-600/80 hover:bg-neutral-900/20 duration-150 hover:drop-shadow-cta outline-none focus-visible:ring-2 focus-visible:ring-neutral-200">
                            <span>Register</span><span aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>