<?php
include 'header.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$queryArray = array();
$msgBox = "";

# Generate form information if username provided
# input information from form submit
if (count($_POST) > 0 && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['cpassword']) && isset($_POST['pki']) && isset($_POST['opassword'])) {
  if ( $_POST['password'] === $_POST['cpassword'] ) {
    unset($_POST['cpassword']);
    # Checking saved password vs user submitted old password
    $checkUser = checkUser($_POST['opassword'], $_POST['username'], $usersDir);
    if ($checkUser == 0) {
      $username = $_POST['username'];
      if ($_POST['pki'] != '') {
        $pkiDirty = explode(' ', $_POST['pki']);
        $_POST['pki'] = $pkiDirty[0]." ".$pkiDirty[1];
      }
      $_POST['password'] = generateHash(16, $_POST['password']);
      unset($_POST['opassword']);
      $jsonConfs = stripslashes(json_encode($_POST));
      if(file_put_contents($usersDir.$username.".json", $jsonConfs)) {
        $msgBox = msgBox("Credentials for ".$_POST['username']." saved.", "success");
      } else {
        $msgBox = msgBox("Credentials for ".$_POST['username']." not saved. Are you trying to be naughty?", "danger");
      }
      $_POST = array();
    # Wrong old password
    } else if ($checkUser == 1) {
        $msgBox = msgBox("Your old password for ".$_POST['username']." was not correct. Your new password hasn't been saved.", "danger");
    # User doesn't exist
    } else if ($checkUser == 2) {
        $msgBox = msgBox("The user ".$_POST['username']." doesn't exist. Are you trying to be naughty?", "danger");
    }
  } else {
        $msgBox = msgBox("Your new passwords didn't match. Please try again.", "danger");
  }
} else if (count($_POST) > 0 && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['pki'])) {
  if ($_POST['pki'] != '') {
    $pkiDirty = explode(' ', $_POST['pki']);
    $_POST['pki'] = $pkiDirty[0]." ".$pkiDirty[1];
  }
  if ($_POST['pki'] != '' && strpos($_POST['pki'], 'ssh-rsa') === false ) {
    # Dump if pki is invalid
    $msgBox = msgBox("Credentials for ".$_POST['username']." NOT saved. It appears your Certificate is invalid. It should begin with 'ssh-rsa'.", "danger");
  } else if ( $_POST['password'] === $_POST['cpassword'] ) {
    unset($_POST['cpassword']);
    $username = $_POST['username'];
    $_POST['password'] = generateHash(16, $_POST['password']);
    $jsonConfs = stripslashes(json_encode($_POST));
    if(file_put_contents($usersDir.$username.".json", $jsonConfs)) {
      $msgBox = msgBox("Credentials for ".$_POST['username']." saved.", "success");
    } else {
      $msgBox = msgBox("Credentials for ".$_POST['username']." not saved. Are you trying to be naughty?", "danger");
    }
    $_POST = array();
  } else {
    $msgBox = msgBox("Your new passwords didn't match. Please try again.", "danger");
  }
}
if (isset($_GET['username'])){
  $username = $_GET['username'];
  if (file_exists($usersDir.$username.".json")) {
    if ( isset($_GET['delete'])) {
      if (unlink($usersDir.$username.".json")) {
        $msgBox = msgBox("This credential was deleted.", "success");
      } else {
        $msgBox = msgBox("This credential wasn't deleted. Please try again.", "danger");
      }
    } else {
      $jsonInfo = file_get_contents($usersDir.$username.'.json');
      $queryArray = json_decode($jsonInfo, true);
    }
  } else {
    $queryArray['username'] = $_GET['username'];
    $msgBox = msgBox("This user doesn't have configuration information. You can add it below.", "danger");
  }
}
# Get all Credentials after they've been placed in the file. This is the for sidebar list
$allCreds = glob($usersDir.'*.{json}', GLOB_BRACE);
?>
<?php 
if ($msgBox != "") {
  echo '<div class="container">';
  echo $msgBox;
  echo '</div>';
}
?>
<div class="container">
  <div class="row">
  <div class="border rounded-left bg-light col-md-10">
    <h1>User Credentials</h1>
    <p>This page allows you to generate NEW credentials per user. To set specific credentials for a user, type the username and password that you would like. If you have  your PKI certificate also, you can include that when saving. Please note, that the passwords are stored in a SHA512 format, and cannot be reversed. Because of this, we provide no way of viewing the current passwords.</p>
        <?php 
         if (isset($queryArray['username'])) {
           $urlGet = "?username=".$queryArray['username'];
         } else {
           $urlGet = "";
         }
        ?>
    <form method="post" action="userCreds.php<?php echo $urlGet;?>"> 
    <div class="form-row">
      <?php if (isset($_GET['username'])) {?>
      <div class="form-group col-md-12">
        <label>Old Password<input aria-describedby="opasswordHelp" placeholder="Password" class="form-control" type="text" id="opassword" name="opassword" value=""></label>
        <small id="opasswordHelp" class="form-text text-muted">Please type your old password, to make changes to your account. If you don't know your password, You will have to delete your account and recreate it.</small>
      </div>
      <?php } ?>
    </div>
    <div class="form-row">
      <div class="form-group col-md-2">
        <?php 
         if (isset($queryArray['username'])) {
           $readonly = "readonly";
         } else {
           $readonly = "";
         }
        ?>
        <label>Username<input <?php echo $readonly; ?> placeholder="Username" class="form-control" type="text" id="username" name="username" value="<?php if (isset($queryArray['username'])) { echo $queryArray['username']; }?>"></label>
      </div>
      <div class="form-group col-md-2">
        <label>New Password<input placeholder="Password" class="form-control" type="text" id="password" name="password" value=""></label>
      </div>
      <div class="form-group col-md-2">
        <label>Confirm Password<input placeholder="Password" class="form-control" type="text" id="cpassword" name="cpassword" value=""></label>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-2">
        <label>Certificate String<textarea rows="8" cols="100" id="pki" name="pki" placeholder="PKI String"><?php if (isset($queryArray['pki'])) { echo $queryArray['pki']; }?></textarea></label>
      </div>
    </div>
    <input class="btn btn-success" type="submit" name="" value="Submit">
    </form>
  </div>
  <div class="col-md-0"> 
  </div>
  <div class="border rounded-right border-left-0 col-md-2">
    <h5 class="current-set-margin" >Currently Set</h5>
    <?php
      if (count($allCreds) != 0) {
    ?>
      <ul>
    <?php
      foreach($allCreds as $cred) {
        echo '<li><a href="userCreds.php?username='.basename($cred, '.json').'">'.basename($cred, '.json').'</a></li>';
      }
    ?>
      </ul>
    <?php
      } else {
        echo '<p class="text-left">There are no users</p>';
      }
    ?>
  </div>
  </div>
</div>
<?php include 'footer.php';?>

