<?php
session_start();
require('../../dbconnect.php');
$_SESSION = array();
session_destroy();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログアウト完了画面</title>
</head>

<body>

  <div class="util_fullscreen_container">
    <div class="util_fullscreen util_fullscreen--small">
      <h1 class="util_login_title util_login_title--long">ログアウト完了</h1>
      <div class="signup_done">
        <p class="signup_done_text">ログアウトしました。</p>
        <a class="util_login_link" href="./index.php">ログイン画面に戻る</a>
      </div>
    </div>
  </div>
</body>

</html>