<?php

function makeToken() {
    echo "in make<br>";
    if(!isset($_SESSION['csrf_token'])){
      $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
    return $_SESSION['csrf_token'];
}




function checkToken() {
  $token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : (isset($_GET['csrf_token']) ? $_GET['csrf_token'] : '');
  if (empty($token) || $token !== $_SESSION['csrf_token']) {
    echo($token);
    echo "===";
    echo($_SESSION['csrf_token']);
    echo "_____";
    die('CSRF token does not match, Please logout of your account , close any other tab and log back in');
  }
  // else{
  //   die('all good');
  //   }
  return true;
}