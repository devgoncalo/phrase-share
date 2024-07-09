<?php
include '../database/connection.php';
include '../translations.php';

session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: ../auth/login.php');
  exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT admin FROM users WHERE id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch();

if (!$user || $user['admin'] != 1) {
  header('Location: ../dashboard.php');
  exit();
}

$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
$trans = $translations[$language] ?? $translations['en'];

$users = [];

$filter_start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-7 days'));
$filter_end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
$filter_start_date_sql = $filter_start_date . ' 00:00:00';
$filter_end_date_sql = $filter_end_date . ' 23:59:59';

$stmt_users = $pdo->prepare("
    SELECT DATE(signup_time) as date, COUNT(*) as count 
    FROM users 
    WHERE signup_time BETWEEN :start_date AND :end_date 
    GROUP BY DATE(signup_time)
");
$stmt_users->execute(['start_date' => $filter_start_date_sql, 'end_date' => $filter_end_date_sql]);
$user_data = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
$user_data = json_encode($user_data);

$stmt_phrases = $pdo->prepare("
    SELECT DATE(creation_time) as date, COUNT(*) as count 
    FROM phrases 
    WHERE creation_time BETWEEN :start_date AND :end_date 
    GROUP BY DATE(creation_time)
");
$stmt_phrases->execute(['start_date' => $filter_start_date_sql, 'end_date' => $filter_end_date_sql]);
$phrase_data = $stmt_phrases->fetchAll(PDO::FETCH_ASSOC);
$phrase_data = json_encode($phrase_data);
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($language); ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($trans['admin_page_title']); ?> · <?php echo htmlspecialchars($trans['admin_dashboard_page_title']); ?></title>
  <link rel="shortcut icon" href="../assets/favicon.ico" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
</head>

<body class="min-h-screen bg-black bg-gradient-to-tr from-neutral-900/50 to-neutral-700/30 overflow-hidden text-neutral-100">
  <div class="container mx-auto py-8">
    <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-8">
      <h1 class="text-[28px] font-bold leading-[34px] tracking-[-0.416px] text-neutral-100">
        <?php echo $trans['admin_dashboard_page_title']; ?>
      </h1>
      <div>
        <a href="<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../dashboard.php'; ?>" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md border border-neutral-700 bg-white pl-3 pr-3 text-sm font-semibold text-black transition duration-200 ease-in-out hover:bg-white/90 focus-visible:bg-white/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400">
          <span class="inline-flex flex-row items-center gap-2">
            <i data-lucide="arrow-left" class="size-4"></i>
            <?php echo $trans['general_go_back']; ?>
          </span>
        </a>
        <a href="../dashboard.php" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md px-2 text-sm font-semibold text-white transition duration-200 ease-in-out bg-neutral-800 hover:bg-neutral-700 focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400">
          <i data-lucide="home" class="size-4"></i>
        </a>
      </div>
    </div>
    <div class="flex flex-col gap-8 mx-auto max-w-5xl px-6 overflow-auto max-h-[calc(100vh-152px)]">
      <div class="w-full">
        <div class="flex h-fit flex-col items-start justify-center rounded-lg border border-neutral-700 hover:shadow-md cursor-pointer">
          <div class="w-full flex flex-row items-center justify-between border-b border-neutral-700 p-4">
            <div>
              <h1 class="text-xl font-bold tracking-[-0.16px] text-neutral-100">
                <?php echo $trans['admin_dashboard_users_title']; ?>
              </h1>
              <span class="text-sm font-normal text-neutral-400">
                <?php echo $trans['admin_dashboard_users_explanation']; ?> <?php echo $trans['admin_dashboard_click']; ?> <a href="./users/overview.php" class="text-neutral-100 underline"><?php echo $trans['admin_dashboard_here']; ?></a> <?php echo $trans['admin_dashboard_to_manage_users']; ?>
              </span>
            </div>
            <div class="hidden md:flex items-center">
              <form method="get" class="flex flex-row items-center gap-1">
                <input type="date" name="start_date" value="<?php echo htmlspecialchars($filter_start_date); ?>" class="ml-2 py-0.5 px-2 border border-neutral-700 rounded-md bg-transparent text-neutral-100 focus:outline-none focus:ring-1 focus:ring-neutral-600">
                <input type="date" name="end_date" value="<?php echo htmlspecialchars($filter_end_date); ?>" class="py-0.5 px-2 border border-neutral-700 rounded-md bg-transparent text-neutral-100 focus:outline-none focus:ring-1 focus:ring-neutral-600">
                <button type="submit" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md px-2 text-sm font-semibold text-white transition duration-200 ease-in-out bg-neutral-800 hover:bg-neutral-700 focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400"">
                  <i data-lucide="filter" class="size-4"></i>
                </button>
                <button type="button" onclick="exportChart('users_chart', 'users_chart_export')" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md px-2 text-sm font-semibold text-white transition duration-200 ease-in-out bg-neutral-800 hover:bg-neutral-700 focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400">
                  <i data-lucide="arrow-down-to-line" class="size-4"></i>
                </button>
              </form>
            </div>
          </div>
          <div id="users_chart" style="width: 100%; height: 200px;" class="py-3"></div>
          <div class="w-full flex items-center gap-1 text-blue-300 border-t border-neutral-700 px-4 py-3">
            <i data-lucide="info" class="size-3"></i>
            <span class="text-xs font-normal">
              <?php echo $trans['admin_dashboard_users_information']; ?>
            </span>
          </div>
        </div>
      </div>
      <div class="w-full">
        <div class="flex h-fit flex-col items-start justify-center rounded-lg border border-neutral-700 hover:shadow-md cursor-pointer">
          <div class="w-full flex flex-row items-center justify-between border-b border-neutral-700 p-4">
            <div>
              <h1 class="text-xl font-bold tracking-[-0.16px] text-neutral-100">
                <?php echo $trans['admin_dashboard_phrases_title']; ?>
              </h1>
              <span class="text-sm font-normal text-neutral-400">
                <?php echo $trans['admin_dashboard_phrases_explanation']; ?> <?php echo $trans['admin_dashboard_click']; ?> <a href="./phrases/overview.php" class="text-neutral-100 underline"><?php echo $trans['admin_dashboard_here']; ?></a> <?php echo $trans['admin_dashboard_to_manage_phrases']; ?>
              </span>
            </div>
            <div class="hidden md:flex items-center">
              <form method="get" class="flex flex-row items-center gap-1">
                <input type="date" name="start_date" value="<?php echo htmlspecialchars($filter_start_date); ?>" class="ml-2 py-0.5 px-2 border border-neutral-700 rounded-md bg-transparent text-neutral-100 focus:outline-none focus:ring-1 focus:ring-neutral-600">
                <input type="date" name="end_date" value="<?php echo htmlspecialchars($filter_end_date); ?>" class="py-0.5 px-2 border border-neutral-700 rounded-md bg-transparent text-neutral-100 focus:outline-none focus:ring-1 focus:ring-neutral-600">
                <button type="submit" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md px-2 text-sm font-semibold text-white transition duration-200 ease-in-out bg-neutral-800 hover:bg-neutral-700 focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400">
                  <i data-lucide="filter" class="size-4"></i>
                </button>
                <button type="button" onclick="exportChart('phrases_chart', 'phrases_chart_export')" class="inline-flex h-8 cursor-pointer select-none items-center justify-center gap-1 rounded-md px-2 text-sm font-semibold text-white transition duration-200 ease-in-out bg-neutral-800 hover:bg-neutral-700 focus-visible:bg-neutral-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-700 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:bg-neutral-400">
                  <i data-lucide="arrow-down-to-line" class="size-4"></i>
                </button>
              </form>
            </div>
          </div>
          <div id="phrases_chart" style="width: 100%; height: 200px;" class="py-3"></div>
          <div class="w-full flex items-center gap-1 text-blue-300 border-t border-neutral-700 px-4 py-3">
            <i data-lucide="info" class="size-3"></i>
            <span class="text-xs font-normal">
              <?php echo $trans['admin_dashboard_phrases_information']; ?>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    lucide.createIcons();

    document.addEventListener('DOMContentLoaded', function() {
      var userData = <?php echo $user_data; ?>;
      var phraseData = <?php echo $phrase_data; ?>;

      function updateChart(data, chartId) {
        var dates = data.map(function(item) {
          return new Date(item.date).getTime();
        });

        var counts = data.map(function(item) {
          return item.count;
        });

        var options = {
          series: [{
            name: 'Data',
            data: counts
          }],
          chart: {
            id: chartId.substring(1),
            toolbar: {
              show: false
            },
            height: 250,
            type: 'area'
          },
          grid: {
            show: false
          },
          dataLabels: {
            enabled: false
          },
          stroke: {
            curve: 'smooth'
          },
          xaxis: {
            type: 'datetime',
            categories: dates,
            labels: {
              datetimeFormatter: {
                year: 'yyyy',
                month: 'MMM \'yy',
                day: 'dd MMM',
                hour: 'HH:mm'
              }
            }
          },
          tooltip: {
            theme: 'dark',
            x: {
              format: 'dd/MM/yy'
            },
          },
        };

        var chart = new ApexCharts(document.querySelector(chartId), options);
        chart.render();
      }

      updateChart(userData, "#users_chart");
      updateChart(phraseData, "#phrases_chart");
    });

    function exportChart(chartId, chartTitle) {
      const chart = ApexCharts.exec(chartId, 'dataURI').then(({
        imgURI,
        blob
      }) => {
        const link = document.createElement('a');
        link.href = imgURI;
        link.download = chartTitle + '.png';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      });
    }
  </script>
</body>

</html>