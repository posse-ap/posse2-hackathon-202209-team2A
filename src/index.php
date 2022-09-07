<?php
require('dbconnect.php');
session_start();

require('./auth/login/login-check.php');

$user_id = $_SESSION['user_id'];
$status = filter_input(INPUT_GET, 'status');

if (isset($status)) {
  $stmt = $db->prepare("SELECT events.id, events.name, events.start_at, events.end_at, count(event_attendance.id) AS total_participants FROM events LEFT JOIN event_attendance ON events.id = event_attendance.event_id WHERE event_attendance.user_id = ? AND event_attendance.status = ? GROUP BY events.id ORDER BY events.start_at ASC");
  $stmt->execute(array($user_id, $status));
} else {
  $stmt = $db->query('SELECT events.id, events.name, events.start_at, events.end_at, count(event_attendance.id) AS total_participants FROM events LEFT JOIN event_attendance ON events.id = event_attendance.event_id GROUP BY events.id  ORDER BY start_at ASC');
  $stmt->execute();
}
$events = $stmt->fetchAll();

function get_day_of_week($w)
{
  $day_of_week_list = ['日', '月', '火', '水', '木', '金', '土'];
  return $day_of_week_list["$w"];
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <title>Schedule | POSSE</title>
</head>

<body>
  <header class="h-16">
    <div class="flex justify-between items-center w-full h-full mx-auto pl-2 pr-5">
      <div class="h-full">
        <img src="img/header-logo.png" alt="" class="h-full">
      </div>
      <!-- 
      <div>
        <a href="/auth/login" class="text-white bg-blue-400 px-4 py-2 rounded-3xl bg-gradient-to-r from-blue-600 to-blue-200">ログイン</a>
      </div>
      -->
      <!-- ここにユーザーidを埋め込む -->
      <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
    </div>
  </header>

  <main class="bg-gray-100">
    <div class="w-full mx-auto p-5">
      <!-- イベント参加状況フィルターのボタン -->
      <div id="filter" class="mb-8">
        <h2 class="text-sm font-bold mb-3">フィルター</h2>
        <div class="flex">
          <a href="./index.php" class="px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md bg-white <?php if ($status == null) {
                                                                                                        echo 'bg-blue-600 text-white';
                                                                                                      } ?>" id="filter_status_all">全て</a>
          <a href="./index.php?status=presence" class="px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md bg-white <?php if ($status == "presence") {
                                                                                                                        echo 'bg-blue-600 text-white';
                                                                                                                      } ?>" id="filter_status_presence">参加</a>
          <!-- <a href="./index.php?status=absense" class="px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md bg-white">不参加</a>
          <a href="./index.php" class="px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md bg-white">未回答</a> -->
        </div>
      </div>

      <!-- 各イベントカード -->
      <div id="events-list">
        <div class="flex justify-between items-center mb-3">
          <h2 class="text-sm font-bold">一覧</h2>
        </div>
        <?php foreach ($events as $event) : ?>
          <?php
          $start_date = strtotime($event['start_at']);
          $end_date = strtotime($event['end_at']);
          $day_of_week = get_day_of_week(date("w", $start_date));
          $today = strtotime("today");

          // イベントごとのステータス取得
          $stmt = $db->prepare('SELECT user_id, status FROM event_attendance WHERE event_id = ? AND user_id = ?');
          $stmt->execute(array($event['id'], $user_id));
          $participation_status = $stmt->fetch();

          // 参加者の合計を求める
          $stmt = $db->prepare("SELECT COUNT(user_id) FROM event_attendance WHERE event_id = ? AND status = 'presence'");
          $stmt->execute(array($event['id']));
          $participants_total = $stmt->fetch();

          // strtotimeで今日の0:00を取得 star_dateがそれより前であれば、continueで処理をスキップ
          if ($start_date < $today) {
            continue;
          };
          ?>

          <div class="modal-open bg-white mb-3 p-4 flex justify-between rounded-md shadow-md cursor-pointer" id="event-<?php echo $event['id']; ?>">
            <div>
              <h3 class="font-bold text-lg mb-2"><?php echo $event['name'] ?></h3>
              <p><?php echo date("Y年m月d日（${day_of_week}）", $start_date); ?></p>
              <p class="text-xs text-gray-600">
                <?php echo date("H:i", $start_date) . "~" . date("H:i", $end_date); ?>
              </p>
            </div>
            <div class="flex flex-col justify-between text-right">
              <div>
                <?php if (is_null($participation_status['status'])) : ?>
                  <p class="text-sm font-bold text-yellow-400">未回答</p>
                  <p class="text-xs text-yellow-400">期限 <?php echo date("m月d日 H:i:s", strtotime('-3 day', $end_date)); ?></p>
                <?php elseif ($participation_status['status'] == 'absence') : ?>
                  <p class="text-sm font-bold text-gray-300">不参加</p>
                <?php elseif ($participation_status['status'] == 'presence') : ?>
                  <p class="text-sm font-bold text-green-400">参加</p>
                <?php endif; ?>
              </div>
              <p class="text-sm"><span class="text-xl"><?= $participants_total[0] ?></span>人参加 ></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </main>

  <div class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center">
    <div class="modal-overlay absolute w-full h-full bg-black opacity-80"></div>

    <div class="modal-container absolute bottom-0 bg-white w-screen h-4/5 rounded-t-3xl shadow-lg z-50">
      <div class="modal-content text-left py-6 pl-10 pr-6">
        <div class="z-50 text-right mb-5">
          <svg class="modal-close cursor-pointer inline bg-gray-100 p-1 rounded-full" xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 18 18">
            <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
          </svg>
        </div>

        <div id="modalInner"></div>

      </div>
    </div>
  </div>

  <script src="/js/main.js"></script>
</body>

</html>