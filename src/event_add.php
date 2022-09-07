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
  <div>
    <div>
      <h2>イベント追加</h2>
    </div>
    <form action="" method="post" id="postForm">
      <p>イベント名</p>
      <input type="text" name="event_name">
      <p>開始日時</p>
      <input type="datetime-local" name="event_start">
      <p>終了日時</p>
      <input type="datetime-local" name="event_end">
      <p>イベント詳細</p>
      <input type="text" name="event_detail">
      <input type="submit" value="送信" name="submit">
    </form>
  </div>
</body>

</html>