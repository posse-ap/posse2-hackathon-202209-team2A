<?php
require('dbconnect.php');

$stmt = $db->query('SELECT events.id, events.name, events.start_at, events.end_at, count(event_attendance.id) AS total_participants FROM events LEFT JOIN event_attendance ON events.id = event_attendance.event_id GROUP BY events.id');
$events = $stmt->fetchAll();

$user_id = $_SESSION['user_id'];
$status = filter_input(INPUT_GET, 'status');

$stmt = $db->query('SELECT events.id, events.name, events.start_at, events.end_at FROM events LEFT JOIN event_attendance ON events.id = event_attendance.event_id GROUP BY events.id  ORDER BY start_at ASC');
$stmt->execute();
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
      <input type="hidden" name="user_id" value="15">
    </div>
  </header>

  <main class="bg-gray-100">
    <nav>
      <a href="./auth/login/signup.php">
        <div class="cursor-pointer w-4/5 p-3 m-10 text-xl text-white bg-blue-400 rounded-3xl bg-gradient-to-r from-blue-600 to-blue-300 flex items-center justify-center">
          新規ユーザー登録
        </div>
      </a>
      <a href="./event_add.php">
        <div class="cursor-pointer w-4/5 p-3 m-10 text-xl text-white bg-blue-400 rounded-3xl bg-gradient-to-r from-blue-600 to-blue-300 flex items-center justify-center">
          イベント追加
        </div>
      </a>
    </nav>
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
            <a href="./event_edit.php?id=<?= $event['id'] ?>">編集</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    </div>
  </main>
</body>

</html>