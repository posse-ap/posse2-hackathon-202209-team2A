<?php
session_start();
require('../../dbconnect.php');

$err_msg = "";

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = sha1($_POST['password']);

  $sql = 'SELECT count(*) FROM users WHERE email = ? AND password = ?';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($email, $password));
  $result = $stmt->fetch();

  $sql_session = "SELECT * FROM users WHERE email = ? AND password = ?";
  $stmt = $db->prepare($sql_session);
  $stmt->execute(array($email, $password));
  $login_info = $stmt->fetch();


  // result に一つでも値が入っているなら、ログイン情報が存在するということ
  if ($result[0] != 0) {
    // 成功した場合管理画面に遷移

    $_SESSION['user_id'] = $login_info['id'];
    $_SESSION['email'] = $login_info['email'];

    header('Location: http://localhost/index.php');
    exit;
  } else {
    $err_msg = "ユーザー名またはパスワードが間違っています";
  }
}
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
        <img src="/img/header-logo.png" alt="" class="h-full">
      </div>
    </div>
  </header>

  <main class="bg-gray-100 h-screen">
    <div class="w-full mx-auto py-10 px-5">
      <h2 class="text-md font-bold mb-5">ログイン</h2>
      <form action="index.php" method="POST">
        <input name="email" type="email" placeholder="メールアドレス" class="w-full p-4 text-sm mb-3">
        <input name="password" type="password" placeholder="パスワード" class="w-full p-4 text-sm mb-3">
        <!-- <label class="inline-block mb-6">
          <input type="checkbox" checked>
          <span class="text-sm">ログイン状態を保持する</span>
        </label> -->
        <input name="login" type="submit" value="ログイン" class="cursor-pointer w-full p-3 text-md text-white bg-blue-400 rounded-3xl bg-gradient-to-r from-blue-600 to-blue-300">
      </form>
      <?php if ($err_msg !== null && $err_msg !== '') {
        echo "<p>" . $err_msg .  "</p>";
      } ?>
      <div class="text-center text-xs text-gray-400 mt-6">
        <a href="./forget.php">パスワードを忘れた方はこちら</a>
      </div>
    </div>
  </main>
</body>

</html>