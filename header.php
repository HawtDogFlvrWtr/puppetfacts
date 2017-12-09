<?php
include 'config.php';
# Start session across pages
session_start();
# redirect if not logged in, or if logging out.
if (!isset($_SESSION['login_user']) OR isset($_GET['logout'])) {
  unset($_SESSION['login_user']);
  header("Location: login.php");
  die();
}

$currentPage = basename($_SERVER['PHP_SELF'],'.php');
if (!file_exists("credentials/default.json")) {
  if ($currentPage == 'generateCreds') {
    $msgBox = "<div class='alert alert-danger' role='alert'>
                 You have no default credentials configured. This will prevent puppet from running correctly. If you have already set a default credential, please refresh this page for them to take effect.
               </div>";
  } else {
    $msgBox = "<div class='alert alert-danger' role='alert'>
                 You have no default credentials configured. This will prevent puppet from running correctly. Please set one by clicking <a href='generateCreds.php'>Role Credentials</a> above.
               </div>";
  }
}
?>
<html>
  <head>
    <title>Host configuration</title>
    <link rel="stylesheet" type="text/css" href='css/bootstrap.min.css'>
    <link rel="stylesheet" type="text/css" href='css/fontawesome-all.min.css'>
    <style>
      label{display:inline-block;}
      input, select{display:block;}
    </style>
    <script src='js/jquery-3.2.1.slim.min.js'></script>
    <script src='js/popper.min.js'></script>
    <script src='js/bootstrap.min.js'></script>
  </head>
<body>
<div class="container" style="padding-bottom:20px;">
<nav class="border rounded navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php">Puppet-Facts</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item <?php if ($currentPage == 'add') { echo 'active'; } ?>">
        <a style="margin-left:5px;" class="btn btn-success  btn-sm" href="add.php"><i class="fa fa-plus"></i> Add System <?php if ($currentPage == 'add') { echo '<span class="sr-only">(current)</span>'; } ?></a>
      </li>
      <li class="nav-item <?php if ($currentPage == 'allSystems') { echo 'active'; } ?>">
        <a style="margin-left:5px;" class="btn btn-success  btn-sm" href="allSystems.php"><i class="fa fa-desktop"></i> All Systems <?php if ($currentPage == 'allSystems') { echo '<span class="sr-only">(current)</span>'; }?></a>
      </li>
      <li class="nav-item <?php if ($currentPage == 'userCreds') { echo 'active'; } ?>">
        <a style="margin-left:5px;" class="btn btn-success  btn-sm" href="userCreds.php"><i class="fa fa-user-plus"></i> Add User <?php if ($currentPage == 'userCreds') { echo '<span class="sr-only">(current)</span>'; }?></a>
      </li>
      <li class="nav-item <?php if ($currentPage == 'allUsers') { echo 'active'; } ?>">
        <a style="margin-left:5px;" class="btn btn-success  btn-sm" href="allUsers.php"><i class="fa fa-users"></i> All Users <?php if ($currentPage == 'allUsers') { echo '<span class="sr-only">(current)</span>'; }?></a>
      </li>
      <li class="nav-item <?php if ($currentPage == 'generateCreds') { echo 'active'; } ?>">
        <a style="margin-left:5px;" class="btn btn-success  btn-sm" href="generateCreds.php"><i class="fa fa-unlock-alt"></i> Role Credentials <?php if ($currentPage == 'generateCreds') { echo '<span class="sr-only">(current)</span>'; }?></a>
      </li>
    </ul>
    <ul class="navbar-nav">
      <li class="nav-item active" >
        <a style="margin-left:5px;" class="btn btn-danger  btn-sm" href="index.php?logout"><i class="fa fa-sign-out-alt"></i> Log Out</a>
      </li>
    </ul>
  </div>
</nav>
  <div style="margin-top:15px;" class="col-md-12">
    <form class="form-inline my-2 my-lg-0" method="get" action="add.php">
      <input class="form-control col-md-11" id="macAddress" name="macAddress" type="search" placeholder="Search for a MAC Address" aria-label="Search">
        <button style="margin-left:2px;" class="btn btn-outlline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</div>

<?php
if ($msgBox != "") {
  echo '<div class="container">';
  echo $msgBox;
  echo '</div>';
}
?>
