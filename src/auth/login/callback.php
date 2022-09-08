<?php 

$code = $_GET['code'];

// if ($code == "") {
//   header('Location: http://localhost:80/index.php');
//   exit;
// }

$client_id = "Iv1.04698a71246c50e6";
$client_secret = "faf6112abed6e683ff875919650d80e83a083c00";
$url = "https://github.com/login/oauth/access_token";

$postParams = [
  'client_id' => $client_id,
  'client_secret' => $client_secret,
  'code' => $code,
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
$response = curl_exec($ch);
curl_close ($ch);

$data = json_decode($response);

// Store token
if ($data->access_token != "") {
  session_start();
  $_SESSION['my_access_token_accessToken'] = $data->access_token;

  header('Location: http://localhost/auth/login/api.php');
  exit;
}


?>

