<?php
session_start();
require('../../dbconnect.php');

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザー新規登録完了画面</title>
</head>


<body>
  <div class="util_fullscreen_container">
    <div class="util_fullscreen util_fullscreen--small">
      <h1 class="util_login_title util_login_title--long">ユーザー新規登録</h1>
      <div class="signup_done">
        <p class="signup_done_text">ユーザー新規登録が完了いたしました。</p>
        <a class="util_login_link" href="../../auth/login/index.php">ログイン画面に戻る</a>
      </div>
    </div>
  </div>
</body>

</html>