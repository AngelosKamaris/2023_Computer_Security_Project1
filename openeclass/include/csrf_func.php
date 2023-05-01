<?php


//This function checks if the current session has a csrf token and if not it creates a token and stores it in the SESSION array
function makeToken() {
    if(!isset($_SESSION['csrf_token'])){
      $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
    return $_SESSION['csrf_token'];
}



//This function checks if a POST or GET token is given in a form or a usr and if not it removes the currently stored token of the session and prints a message accordingly
function checkToken() {
  $token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : (isset($_GET['csrf_token']) ? $_GET['csrf_token'] : '');
  if (empty($token) || $token !== $_SESSION['csrf_token']) {  //USER pressed a link with a form or a url that doesn't have the correct token
    if(isset($_SESSION['csrf_token'])){
      unset($_SESSION['csrf_token']);
    }
    die('CSRF token does not match, Please logout of your account , close any other tab and log back in');
  }
}