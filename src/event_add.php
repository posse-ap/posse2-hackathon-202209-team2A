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
  <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet">
</head>

<body>
  <div class="bg-gray-100 h-screen w-full py-8 px-7">
    <div class="form w-full mx-auto py-10">
      <h2 class="title text-md font-bold mb-3">イベント追加</h2>
    </div>
    <form action="" method="post" id="postForm">
      <p class="sub mb-0 mt-3">イベント名</p>
      <input type="text" name="event_name" class="event__add__form__event__name  event__add__form__item w-full p-4 text-sm mb-2 h-14 rounded">
      <p class="sub mb-0 mt-3">開始日時</p>
      <input type="datetime-local" name="event_start" class="event__add__form__event__date event__add__form__item w-full p-4 text-sm mb-3 rounded">
      <p class="sub mb-0 mt-3">終了日時</p>
      <input type="datetime-local" name="event_end" class="event__add__form__event__date event__add__form__item w-full p-4 text-sm mb-3 rounded">
      <p class="sub mb-0 mt-3">イベント詳細</p>
      <input type="text" name="event_detail" class="event__add__form__event__date event__add__form__item w-full p-4 text-sm mb-3 rounded">
      <input type="submit" value="送信" name="submit" class="event__add__form__button cursor-pointer w-full p-3 text-md text-white rounded-3xl posse-gradatation-blue">
    </form>
  </div>
</body>

</html>