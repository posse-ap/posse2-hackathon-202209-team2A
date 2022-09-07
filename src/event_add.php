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

  // header('Location: home.php');
  exit;
}
?>
<!DOCTYPE html>
<html>

<head>
  <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet">
</head>

<body>
  <div class="change">
    <form action="" method="post" id="postForm">
      <div>
        <label for="event_name" id="event_name">イベント名</label>
        <input type="text" name="event_name">
      </div>
      <div>
        <label for="event_start">開始日時</label>
        <input type="datetime-local" name="event_start">
      </div>
      <div>
        <label for="event_end">終了日時</label>
        <input type="datetime-local" name="event_end">
      </div>
      <div>
        <label for="event_detail">イベント詳細</label>
        <textarea name="event_detail"></textarea>
      </div>
      <input type="submit" value="追加" name="submit">
    </form>
  </div>
  </div>
</body>

</html>