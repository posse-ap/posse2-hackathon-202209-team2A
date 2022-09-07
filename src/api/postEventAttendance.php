<?php
require('../dbconnect.php');
header('Content-Type: application/json; charset=UTF-8');

$eventId = $_POST['eventId'];
$userId = $_POST['userId'];

if ($eventId > 0) {
  $stmt = $db->prepare('INSERT INTO event_attendance SET event_id=?, user_id = ?');
  $stmt->execute(array($eventId, $userId));
}
