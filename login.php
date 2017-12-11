<?php

include 'functions.php';
#var_dump($_POST);
if (isset($_SESSION['login_user'])) {
  header("Location: index.php");
  die();
}
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $checkUser = checkUser($password, $username);
  if ($checkUser == 0) {
    session_start();
    $_SESSION['login_user'] = $username;
    header("Location: index.php");
    die();
  } else if ($checkUser == 1) {
    $msgBox = msgBox("Username or password incorrect.", "danger");
  }
}
?>
<html>
  <head>
    <title>Host configuration</title>
    <link rel="stylesheet" type="text/css" href='css/bootstrap.min.css'>
    <link rel="stylesheet" type="text/css" href='css/login.css'>
    <link rel="stylesheet" type="text/css" href='css/fontawesome-all.min.css'> 
    <script src='js/jquery-3.2.1.slim.min.js'></script>
    <script src='js/popper.min.js'></script>
    <script src='js/bootstrap.min.js'></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  </head>
<body>
<?php
if ($msgBox != "") {
  echo '<div class="msg-box col-centered col-md-6 text-center">';
  echo $msgBox;
  echo '</div>';
}
?>
  <div class="loginRow row col-md-12">
     <div class="border col-centered rounded bg-light col-md-2 text-center">
        <h2 class="headerText">Puppet-Facts</h2>
        <form method="post" action="login.php" >
              <div class="form-row">
                <div class="form-group col-md-12">
                  <input name="username" type="text" id="username" class="form-control" placeholder="username" />
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-12">
                  <input name="password" type="password" id="password" class="form-control" placeholder="password" />
                </div>
              </div>
              <div class="wrapper">
                <span class="group-btn">     
                  <button type="submit" class="btn btn-primary btn-md">login <i class="fa fa-sign-in-alt"></i></button>
                </span>
              </div>
          </form>
        </div>
    </div>
</body>
