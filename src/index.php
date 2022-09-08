<?php
require('dbconnect.php');
session_start();

require('./auth/login/login-check.php');

$user_id = $_SESSION['user_id'];
// URLで受け渡した参加ステータスを取得
$status = filter_input(INPUT_GET, 'status');

// ステータスに値がある場合（参加or不参加）
if (isset($status)) {
  if ($status == 'all') {
    $stmt = $db->query('SELECT events.id, events.name, events.start_at, events.end_at, count(event_attendance.id) AS total_participants FROM events LEFT JOIN event_attendance ON events.id = event_attendance.event_id GROUP BY events.id  ORDER BY start_at ASC');
    $stmt->execute();
    // URLで受け渡した、参加不参加情報をもとに絞り込み
  } else {
    $stmt = $db->prepare("SELECT events.id, events.name, events.start_at, events.end_at, count(event_attendance.id) AS total_participants FROM events LEFT JOIN event_attendance ON events.id = event_attendance.event_id WHERE event_attendance.user_id = ? AND event_attendance.status = ? GROUP BY events.id ORDER BY events.start_at ASC");
    $stmt->execute(array($user_id, $status));
  }
  // ステータスに値がない場合（未回答）event tableには存在するがevent_attendance tableにはないレコードを取得
} else {
  $stmt = $db->prepare("SELECT events.id, events.name, events.start_at, events.end_at, count(event_attendance.id) AS total_participants FROM event_attendance RIGHT OUTER JOIN events ON events.id = event_attendance.event_id WHERE event_attendance.status IS NULL GROUP BY events.id ORDER BY events.start_at ASC;");
  $stmt->execute(array($_SESSION['user_id']));
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
          <a href="./index.php?status=all" class="px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md bg-white <?php if ($status == "all") {
                                                                                                                    echo 'bg-blue-600 text-white';
                                                                                                                  } ?>">全て</a>
          <a href="./index.php?status=presence" class="px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md bg-white <?php if ($status == "presence") {
                                                                                                                        echo 'bg-blue-600 text-white';
                                                                                                                      } ?>">参加</a>
          <a href="./index.php?status=absence" class="px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md bg-white <?php if ($status == "absence") {
                                                                                                                        echo 'bg-blue-600 text-white';
                                                                                                                      } ?>">不参加</a>
          <a href="./index.php" class="px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md bg-white <?php if ($status == null) {
                                                                                                        echo 'bg-blue-600 text-white';
                                                                                                      } ?>">未回答</a>
        </div>
      </div>

      <!-- ページング関係 表示されるべきイベントを数えて$countに入る。-->
      <?php
      $count = 0;
      $today_time = strtotime("today");
      foreach ($events as $event) :
        $event_start = strtotime($event['start_at']);
        if ($today_time <= $event_start) {
          $count++;
        } else {
          continue;
        }
      endforeach;
      echo $count;
      ?>
      <?php

      // $books_numは$countに置き換える。

      define('MAX', '10'); // 1ページの記事の表示数
      $max_page = ceil($books_num / MAX); // トータルページ数
      if (!isset($_GET['page_id'])) { // $_GET['page_id'] はURLに渡された現在のページ数
        $now = 1; // 設定されてない場合は1ページ目にする
      } else {
        $now = $_GET['page_id'];
      }

      $start_no = ($now - 1) * MAX; // 配列の何番目から取得すればよいか
      // array_sliceは、配列の何番目($start_no)から何番目(MAX)まで切り取る関数
      // $disp_data = array_slice($events, $start_no, MAX, true);

      // var_dump($events);
      // var_dump($disp_data);
      // foreach ($disp_data as $val) { // データ表示
      //   echo $val['book_kind'] . '　' . $val['book_name'] . '<br />';
      // }
      // 上の３ぎょうは下のほうほforeachです。


      ?>




      <!-- 各イベントカード -->
      <div id="events-list">
        <div class="flex justify-between items-center mb-3">
          <h2 class="text-sm font-bold">一覧</h2>
        </div>
        <?php 
          foreach ($events as $event) : 
          // foreach ($disp_data as $event) : ?>
          <?php
          $start_date = strtotime($event['start_at']);
          $end_date = strtotime($event['end_at']);
          $day_of_week = get_day_of_week(date("w", $start_date));
          $today = strtotime("today");

          // strtotimeで今日の0:00を取得 start_dateがそれより前であれば、continueで処理をスキップ
          if ($start_date < $today) {
            continue;
          };
          ?>

          <!-- ここから単体のイベント -->
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
                <?php if ($event['id'] % 3 === 1) : ?>
                  <!--
                  <p class="text-sm font-bold text-yellow-400">未回答</p>
                  <p class="text-xs text-yellow-400">期限 <?php echo date("m月d日", strtotime('-3 day', $end_date)); ?></p>
                  -->
                <?php elseif ($event['id'] % 3 === 2) : ?>
                  <!-- 
                  <p class="text-sm font-bold text-gray-300">不参加</p>
                  -->
                <?php else : ?>
                  <!-- 
                  <p class="text-sm font-bold text-green-400">参加</p>
                  -->
                <?php endif; ?>
              </div>
              <p class="text-sm"><span class="text-xl"><?php echo $event['total_participants']; ?></span>人参加 ></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div>
        <?php
        for ($i = 1; $i <= $max_page; $i++) { // 最大ページ数分リンクを作成
          if ($i == $now) { // 現在表示中のページ数の場合はリンクを貼らない
            echo $now . ' ';
            echo "<p class=>aaaaaa</p>";

          } else {

            // $page_link_ref = "/test.php?page_id=";
            // $page_link_html = "<a href='$page_link_ref. $i'> . $i . '</a>' . ' ' ";
            // echo $page_link_html;
            echo "<p class=>aaaaaa</p>";

          }
        }
        ?>

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
