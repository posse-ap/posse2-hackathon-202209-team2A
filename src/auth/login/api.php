<?php 
// アクセストークンを用いてPHPリクエスト
require('../../dbconnect.php');

function error($msg) {
  $response = [];
  $response['success'] = false;
  $response['message'] = $msg;
  return json_encode($response);
}

session_start();
$accessToken = $_SESSION['my_access_token_accessToken'];

if ($accessToken == "") {
  die(error('Error: Invalid access token'));
}

$url = "https://api.github.com/user";

$authHeader = "Authorization: token " . $accessToken;
$userAgentHeader = "User-Agent: Demo";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', $authHeader, $userAgentHeader));
$response = curl_exec($ch);
curl_close ($ch);

$data = json_decode($response);

$username = $data->login;

// var_dump($data);
// var_dump($data->login);

$stmt = $db->prepare('SELECT COUNT(*) FROM users WHERE github_id = ?');
$stmt->execute(array($username));
$isSignedUp = $stmt->fetch();

$stmt_user = $db->prepare('SELECT id, github_id FROM users WHERE github_id = ?');
$stmt_user->execute(array($username));
$userInfo = $stmt_user->fetch();

if ($isSignedUp[0] != 0) {
  $_SESSION['user_id'] = $userInfo['id'];
  $_SESSION['github_id'] = $userInfo['github_id'];
  header('Location: ../../index.php');
} else {
  echo 'ユーザー登録していないためログインできません。';
  header('Location: ./index.php');
}


?>