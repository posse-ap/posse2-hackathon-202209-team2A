<?php
session_start();
require('./dbconnect.php');

$event_id = $_GET['id'];

$stmt = $db->prepare('SELECT * FROM events WHERE id = ?');
$stmt->execute(array($event_id));
$event = $stmt->fetch();

// 画像以外の更新
if (isset($_POST['submit'])) {

  $event_name = $_POST['event_name'];
  $event_start = $_POST['event_start'];
  $event_end = $_POST['event_end'];
  $event_detail = $_POST['event_detail'];

  $sql = 'UPDATE events SET name = ?, start_at = ?, end_at = ?, detail = ? WHERE id = ?';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($event_name, $event_start, $event_end, $event_detail, $event_id));

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
  <div class="change">
    <form action="" method="post" id="postForm">
      <div>
        <label for="event_name" id="event_name">イベント名</label>
        <input type="text" name="event_name" value="<?= $event['name'] ?>">
      </div>
      <div>
        <label for="event_start">開始日時</label>
        <input type="datetime-local" name="event_start" value="<?= $event['start_at'] ?>">
      </div>
      <div>
        <label for="event_end">終了日時</label>
        <input type="datetime-local" name="event_end" value="<?= $event['end_at'] ?>">
      </div>
      <div>
        <label for="event_detail">イベント詳細</label>
        <input type="text" name="event_detail" value="<?= $event['detail'] ?>">
      </div>
      <input type="submit" value="編集" name="submit">
    </form>
  </div>
  </div>
</body>

</html>