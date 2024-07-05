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
  if (isset($_POST['delete_confirmation']) && $_POST['delete_confirmation'] === 'DELETE') {
    $sql = "DELETE FROM users WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);

    if ($stmt->execute()) {
      session_destroy();
      header('Location: auth/login.php');
      exit();
    } else {
      echo "Error deleting account.";
    }
  } else {
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
}


$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user === false) {
  header('Location: ./auth/login.php');
  session_destroy();
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
      <div>
        <a href="dashboard.php" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 bg-white pl-3 pr-3 text-sm font-semibold text-black transition duration-200 ease-in-out hover:bg-white/90 focus-visible focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400 bg-white">
          <span class="inline-flex flex-row items-center gap-2">
            <i data-lucide="arrow-left" class="size-4"></i>
            <?php echo htmlspecialchars($trans['general_go_back']); ?>
          </span>
        </a>
        <a href="auth/logout.php" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md px-2 text-sm font-semibold text-white transition duration-200 ease-in-out bg-neutral-800 hover:bg-neutral-700 focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400">
          <span class="inline-flex flex-row items-center gap-2">
            <i data-lucide="log-out" class="size-4"></i>
          </span>
        </a>
      </div>
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
                <input value="<?php echo htmlspecialchars($user['username']); ?>" class='relative h-8 w-full select-none appearance-none rounded-md border border-neutral-700 bg-neutral-900 px-2 pl-2 pr-[var(--text-field-right-slot-width)] text-sm text-neutral-100 outline-none transition duration-200 ease-in-out placeholder:text-neutral-100 focus-visible:border focus-visible:border-black focus-visible:ring-2 focus-visible:ring-neutral-700' type="text" id="name" name="name" />
              </div>
            </div>
            <div class="w-full space-y-1">
              <label class="text-sm leading-none shadow-sm peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                <?php echo htmlspecialchars($trans['profile_language']); ?>
              </label>
              <div class="mt-2">
                <select name="language" class='relative h-8 w-full select-none appearance-none rounded-md border border-neutral-700 bg-neutral-900 px-2 pl-2 pr-[var(--text-field-right-slot-width)] text-sm text-neutral-100 outline-none transition duration-200 ease-in-out placeholder:text-neutral-100 focus-visible:border focus-visible:border-black focus-visible:ring-2 focus-visible:ring-neutral-700'>
                  <option value="en" <?php echo ($language === 'en') ? 'selected' : ''; ?>>🇺🇸 English</option>
                  <option value="it" <?php echo ($language === 'it') ? 'selected' : ''; ?>>🇮🇹 Italiano</option>
                  <option value="pt" <?php echo ($language === 'pt') ? 'selected' : ''; ?>>🇵🇹 Português</option>
                </select>
              </div>
            </div>
            <div class="w-full space-y-1">
              <label class="text-sm leading-none shadow-sm peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                <?php echo htmlspecialchars($trans['profile_email_address']); ?>
              </label>
              <div class="mt-2">
                <input value="<?php echo htmlspecialchars($user['email']); ?>" readOnly class='relative h-8 w-full opacity-50 select-none appearance-none rounded-md border border-neutral-700 bg-neutral-900 px-2 pl-2 pr-[var(--text-field-right-slot-width)] text-sm text-neutral-100 outline-none transition duration-200 ease-in-out placeholder:text-neutral-100 focus-visible:border focus-visible:border-black focus-visible:ring-2 focus-visible:ring-neutral-700' type="email" id="email" name="email" />
              </div>
            </div>
            <div class="flex flex-row items-center gap-2 mt-2">
              <button class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 px-3 text-sm font-semibold text-white transition duration-200 ease-in-out hover:bg-black/90 focus-visible:border-black focus-visible:bg-black/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-black" type="submit">
                <?php echo htmlspecialchars($trans['profile_save_changes']); ?>
              </button>
              <button id="delete-account-btn" class="group flex items-center gap-2 rounded-md border border-transparent px-2 py-1 text-red-500 hover:bg-red-500/10 focus-visible:border-red-500/10 focus-visible:bg-red-500/10 focus-visible:text-red-500 focus-visible:outline-none">
                <i data-lucide="trash" class="size-4"></i>
                <span class="flex items-center gap-1 text-sm font-medium"><?php echo htmlspecialchars($trans['profile_delete']); ?></span>
              </button>
            </div>
          </div>
          <div class="flex border-t border-neutral-700 px-4 py-3">
            <span class="text-xs font-normal text-neutral-100">
              <?php echo htmlspecialchars($trans['profile_security_note']); ?>
            </span>
          </div>
        </form>

        <div id="delete-modal-background" class="fixed inset-0 bg-black bg-opacity-50 transition-opacity hidden" aria-hidden="true"></div>
        <div role="dialog" id="delete-modal" aria-modal="true" class="fixed left-[50%] top-[50%] z-50 grid w-full max-w-lg translate-x-[-50%] translate-y-[-50%] gap-4 border p-6 shadow-lg duration-200 hidden sm:rounded-lg border-neutral-700 bg-black/95" tabindex="-1" style="pointer-events: auto;">
          <div class="text-center sm:text-left flex flex-row items-center justify-between space-y-0">
            <h2 class="tracking-tight text-base font-semibold">
              <?php echo htmlspecialchars($trans['profile_delete_modal_title']); ?>
            </h2>
            <button type="button" id="closeDialog" class="rounded-md p-0.5 text-white transition duration-200 ease-in-out hover:bg-neutral-800 focus-visible:border-black focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-800">
              <i data-lucide="x" class="size-5"></i>
              <span class="sr-only">Close</span>
            </button>
          </div>
          <p class="text-sm text-neutral-400"><?php echo htmlspecialchars($trans['profile_delete_modal_description']); ?></p>
          <form id="delete-account-form" method="post" action="profile.php" class="space-y-8">
            <div class="space-y-2">
              <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-neutral-400">
                <?php echo htmlspecialchars($trans['profile_delete_modal_type']); ?> <span class="font-semibold text-neutral-100">DELETE</span> <?php echo htmlspecialchars($trans['profile_delete_modal_type_confirm']); ?>
                <span class="font-semibold text-red-500"><?php echo htmlspecialchars($trans['profile_delete_modal_warning']); ?></span>
              </label>
              <input id="confirmation-input" class="flex w-full rounded-md py-2 text-sm outline-none ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:border-black focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-50 h-8 border border-neutral-700 bg-neutral-900 px-2 text-neutral-100 transition duration-200 ease-in-out placeholder:text-neutral-500" autocomplete="off" value="" name="delete_confirmation">
            </div>
            <div class="flex flex-row gap-2">
              <button type="submit" id="delete-confirm-btn" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md bg-red-700/20 p-2 text-sm font-semibold text-red-400 transition duration-200 ease-in-out hover:bg-red-700/30 focus-visible:bg-red-700/30 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-800 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-red-700/40" disabled>
                <?php echo htmlspecialchars($trans['profile_delete_modal_confirm']); ?>
              </button>
              <button type="button" onclick="closeDeleteModal()" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md p-2 text-sm font-semibold text-white transition duration-200 ease-in-out hover:bg-neutral-800 focus-visible:border-black focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-800">
                <?php echo htmlspecialchars($trans['profile_delete_modal_cancel']); ?>
              </button>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
  <script>
    lucide.createIcons();

    function openDeleteModal() {
      document.getElementById('delete-modal').classList.remove('hidden');
      document.getElementById('delete-modal-background').classList.remove('hidden');
    }

    function closeDeleteModal() {
      document.getElementById('delete-modal').classList.add('hidden');
      document.getElementById('delete-modal-background').classList.add('hidden');
    }

    document.getElementById('delete-account-btn').addEventListener('click', function(event) {
      event.preventDefault();
      openDeleteModal();
    });

    document.getElementById('closeDialog').addEventListener('click', function() {
      closeDeleteModal();
    });

    const confirmationInput = document.getElementById('confirmation-input');
    const deleteConfirmBtn = document.getElementById('delete-confirm-btn');

    confirmationInput.addEventListener('input', function() {
      if (confirmationInput.value === 'DELETE') {
        deleteConfirmBtn.disabled = false;
      } else {
        deleteConfirmBtn.disabled = true;
      }
    });

    document.getElementById('delete-account-form').addEventListener('submit', function(event) {
      if (confirmationInput.value !== 'DELETE') {
        event.preventDefault();
      }
    });
  </script>
</body>

</html>