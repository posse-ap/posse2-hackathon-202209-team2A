<?php
require('dbconnect.php');

$stmt = $db->query('SELECT events.id, events.name, events.start_at, events.end_at, count(event_attendance.id) AS total_participants FROM events LEFT JOIN event_attendance ON events.id = event_attendance.event_id GROUP BY events.id');
$events = $stmt->fetchAll();

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
      <a href="./login/signup.php">
        <div>
          新規ユーザー登録
        </div>
      </a>
      <a href="./event_add.php">
        <div>
          イベント追加
        </div>
      </a>
    </nav>
  </main>
</body>

</html>