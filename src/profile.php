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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $language = $_POST['language'];

  $sql = "UPDATE users SET username = :name, language = :language WHERE id = :user_id";
  $params = [
      ':name' => $name,
      ':language' => $language,
      ':user_id' => $user_id
  ];

  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);

  $_SESSION['language'] = $language;
  header('Location: profile.php');
  exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user === false) {
  echo "User not found!";
  exit();
}

?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($language); ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PhraseShare · <?php echo htmlspecialchars($trans['profile_page_title']); ?></title>
  <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="min-h-screen bg-black bg-gradient-to-tr from-neutral-900/50 to-neutral-700/30 overflow-hidden text-neutral-100">
  <div class="container mx-auto py-8">
    <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-8">
      <h1 class="text-[28px] font-bold leading-[34px] tracking-[-0.416px] text-neutral-100"><?php echo htmlspecialchars($trans['profile_page_title']); ?></h1>
      <a href="dashboard.php" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 bg-white pl-3 pr-3 text-sm font-semibold text-black transition duration-200 ease-in-out hover:bg-white/90 focus-visible focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400 bg-white">
        <span class="inline-flex flex-row items-center gap-2">
          <i data-lucide="arrow-left" class="size-4"></i>
          <?php echo htmlspecialchars($trans['general_go_back']); ?>
        </span>
      </a>
    </div>

    <div class="mx-auto max-w-5xl px-6">
      <section class="mb-6 rounded-lg border border-neutral-700">
        <div class="border-b border-neutral-700 p-4">
          <h1 class="text-base font-bold text-neutral-100"><?php echo htmlspecialchars($trans['profile_your_information']); ?></h1>
        </div>
        <form method="post" action="profile.php">
          <div class="mb-2 flex max-w-md flex-col gap-2 p-4">
            <div class="w-full space-y-1">
              <label class="text-sm leading-none shadow-sm peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                <?php echo htmlspecialchars($trans['profile_name']); ?>
              </label>
              <div class="mt-2">
                <input value="<?php echo htmlspecialchars($user['profile_username']); ?>" class='relative h-8 w-full select-none appearance-none rounded-md border border-neutral-700 bg-neutral-900 px-2 pl-2 pr-[var(--text-field-right-slot-width)] text-sm text-neutral-100 outline-none transition duration-200 ease-in-out placeholder:text-neutral-100 focus-visible:border focus-visible:border-black focus-visible:ring-2 focus-visible:ring-neutral-700 data-[state="read-only"]:cursor-default data-[state="read-only"]:border-neutral-400 data-[state="read-only"]:bg-neutral-500 data-[state="read-only"]:text-neutral-100' type="text" id="name" name="name" />
              </div>
            </div>
            <div class="w-full space-y-1">
              <label class="text-sm leading-none shadow-sm peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                <?php echo htmlspecialchars($trans['profile_email_address']); ?>
              </label>
              <div class="mt-2">
                <input value="<?php echo htmlspecialchars($user['profile_email']); ?>" readOnly class='relative h-8 w-full select-none appearance-none rounded-md border border-neutral-700 bg-neutral-900 px-2 pl-2 pr-[var(--text-field-right-slot-width)] text-sm text-neutral-100 outline-none transition duration-200 ease-in-out placeholder:text-neutral-100 focus-visible:border focus-visible:border-black focus-visible:ring-2 focus-visible:ring-neutral-700 data-[state="read-only"]:cursor-default data-[state="read-only"]:border-neutral-400 data-[state="read-only"]:bg-neutral-500 data-[state="read-only"]:text-neutral-100' type="email" id="email" name="email" />
              </div>
            </div>
            <div class="w-full space-y-1">
              <label class="text-sm leading-none shadow-sm peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                <?php echo htmlspecialchars($trans['profile_language']); ?>
              </label>
              <div class="mt-2">
                <select name="language" id="language" class='relative h-8 w-full select-none appearance-none rounded-md border border-neutral-700 bg-neutral-900 px-2 pl-2 pr-[var(--text-field-right-slot-width)] text-sm text-neutral-100 outline-none transition duration-200 ease-in-out placeholder:text-neutral-100 focus-visible:border focus-visible:border-black focus-visible:ring-2 focus-visible:ring-neutral-700 data-[state="read-only"]:cursor-default data-[state="read-only"]:border-neutral-400 data-[state="read-only"]:bg-neutral-500 data-[state="read-only"]:text-neutral-100'>
                  <option value="en" <?php echo ($language === 'en') ? 'selected' : ''; ?>>🇺🇸 English</option>
                  <option value="it" <?php echo ($language === 'it') ? 'selected' : ''; ?>>🇮🇹 Italiano</option>
                  <option value="pt" <?php echo ($language === 'it') ? 'selected' : ''; ?>>🇵🇹 Português</option>
                </select>
              </div>
            </div>
            <div>
              <button class="mt-2 inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 pl-3 pr-3 text-sm font-semibold text-white transition duration-200 ease-in-out hover:bg-black/90 focus-visible:border-black focus-visible:bg-black/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-black" type="submit">
                <?php echo htmlspecialchars($trans['profile_save_changes']); ?>
              </button>
            </div>
          </div>
        </form>

        <div class="flex border-t border-neutral-700 px-4 py-3">
          <span class="text-xs font-normal text-neutral-100">
            <?php echo htmlspecialchars($trans['profile_security_note']); ?>
          </span>
        </div>
      </section>
    </div>
  </div>
  <script>
    lucide.createIcons();

    document.getElementById('name').value = "<?php echo htmlspecialchars($user['username']); ?>";
    document.getElementById('email').value = "<?php echo htmlspecialchars($user['email']); ?>";
    document.getElementById('language').value = "<?php echo htmlspecialchars($user['language']); ?>";
  </script>
</body>

</html>
