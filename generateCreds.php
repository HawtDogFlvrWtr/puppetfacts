<?php
include 'header.php';
include 'functions.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$queryArray = array();
$msgBox = "";

# Generate form information if mac provided
# input information from form submit
if (count($_POST) > 0 && isset($_POST['root']) && isset($_POST['recovery']) && isset($_POST['role'])) {
  $role = $_POST['role'];
  $_POST['root'] = generateHash(16, $_POST['root']);
  $_POST['recovery'] = generateHash(16, $_POST['recovery']);
  $jsonConfs = stripslashes(json_encode($_POST));
  if(file_put_contents('credentials/'.$role.".json", $jsonConfs)) {
    $msgBox = msgBox("Credentials for ".$_POST['role']." saved.", "success");
  } else {
    $msgBox = msgBox("Credentials for ".$_POST['role']." not saved. Are you trying to be naughty?", "danger");
  }
  $_POST = array();
}
if (isset($_GET['role']) && isset($_GET['delete'])){
  $role = $_GET['role'];
  if (file_exists('credentials/'.$role.".json")) {
    if (unlink('credentials/'.$role.".json")) {
      $msgBox = msgBox("This credential was deleted.", "success");
    } else {
      $msgBox = msgBox("This credential wasn't deleted. Please try again.", "danger");
    }
  } else {
    $msgBox = msgBox("This credential isn't set. You can add it below.", "danger");
  }
} else if (isset($_GET['role'])){
  $role = $_GET['role'];
  if (file_exists('credentials/'.$role.".json")) {
    $jsonInfo = file_get_contents('credentials/'.$role.'.json');
    $queryArray = json_decode($jsonInfo, true);
  } else {
    $queryArray['role'] = $_GET['role'];
    $msgBox = msgBox("This system doesn't have configuration information. You can add it below.", "danger");
  }
}
# Get all Credentials after they've been placed in the file. This is the for sidebar list
$allCreds = glob('credentials/*.{json}', GLOB_BRACE);
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
    <h1>Role Credentials</h1>
    <p>This page allows you to generate credentials per role, or at the default level. To set specific root credentials for a role, select it and enter the credentials you would like to use. For All other systems that use the default root and recovery user passwords, select "default" as the role. Please note, that the passwords are stored in a SHA512 format, and cannot be reversed. Because of this, we provide no way of viewing the current passwords.</p>
    <form method="post" action="generateCreds.php"> 
    <div class="form-row">
      <div class="form-group col-md-2">
        <label>Root<input placeholder="Password" class="form-control" type="text" id="root" name="root" value="<?php if (isset($queryArray['root'])) { echo $queryArray['root']; }?>"></label>
      </div>
      <div class="form-group col-md-2">
        <label>Recovery User<input placeholder="Password" class="form-control" type="text" id="recovery" name="recovery" value="<?php if (isset($queryArray['recovery'])) { echo $queryArray['recovery']; }?>"></label>
      </div>
      <div class="form-group col-md-2">
        <label for="role">Role<select class="form-control" name="role" id="role">
	<?php 
          foreach ($possibleRoles as $line_num => $role) {
            if (isset($queryArray['role']) && $role == $queryArray['role']) {
              echo '<option value="'.$role.'" selected>'.$role.'</option>';
            } else {
              echo '<option value="'.$role.'">'.$role.'</option>';
            }
          }
        ?>
        </select></label>
      </div>
    </div>
    <input type="submit" name="" value="Submit">
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
        echo '<li>'.basename($cred, '.json').'<a data-toggle="modal" href="#delete'.basename($cred, '.json').'"><i class="delete-cred fa fa-trash-alt"></i></a></li>';
        echo '<div id="delete'.basename($cred, '.json').'" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form action="generateCreds.php" method="get">
                      <div class="modal-body">
                        <p class="lead">Are you sure you want to remove this credential?</p> 
                      </div>
                      <div class="modal-footer">
                        <button type="input" name="role" value="'.basename($cred, '.json').'" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> Yes, Remove It</button>
                        <input type="hidden" name="delete" value="true">
                        <button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> Cancel</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>';
      }
    ?>
      </ul>
    <?php
      } else {
        echo '<p class="text-left">There are none set</p>';
      }
    ?>
  </div>
  </div>
</div>
<?php include 'footer.php';?>

