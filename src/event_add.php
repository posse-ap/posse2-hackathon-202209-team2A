<?php
session_start();
require('./dbconnect.php');

// 画像以外の更新
if (isset($_POST['submit'])) {

  $event_name = $_POST['event_name'];
  $event_start = $_POST['event_start'];
  $event_end = $_POST['event_end'];

  $sql = 'INSERT INTO events(name, start_at, end_at) VALUES (?, ?, ?)';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($event_name, $event_start, $event_end));

  header('Location: admin.php');
  exit;
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet">
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <title>Schedule | POSSE</title>

</head>

<body>
<header class="h-16">
    <div class="flex justify-between items-center w-full h-full mx-auto pl-2 pr-5">
      <div class="h-full">
        <img src="/img/header-logo.png" alt="" class="h-full">
      </div>
    </div>
  </header>
  <div class="bg-gray-100 p-4">
    <div>
      <h2 class="text-2xl mt-3 mb-3 font-bold">イベント追加</h2>
    </div>
    <form action="" method="post" id="postForm" class="p-3">
      <p class="text-xl pt-4 pb-4">イベント名</p>
      <input type="text" name="event_name" class="w-full">
      <p class="text-xl pt-5 pb-4">開始日時</p>
      <input type="datetime-local" name="event_start" class="w-full">
      <p class="text-xl pt-5 pb-4">終了日時</p>
      <input type="datetime-local" name="event_end" class="w-full">
      <p class="text-xl pt-5 pb-4">イベント詳細</p>
      <input type="text" name="event_detail" class="w-full">
      <input type="submit" value="送信" name="submit" class="cursor-pointer w-4/5 p-3 m-10 text-xl text-white bg-blue-400 rounded-3xl bg-gradient-to-r from-blue-600 to-blue-300 flex items-center justify-center"> 
    </form>
  </div>
</body>

</html>