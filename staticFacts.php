<?php
include 'header.php';
#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);
#error_reporting(E_ALL);
$queryArray = array();
$msgBox = "";
$replaceValues = array(" ", "-");

# Generate form information if mac provided
# input information from form submit
if (count($_POST) > 0 && isset($_POST['name']) && isset($_POST['value'])) {
  $value = $_POST['value'];
  $name = str_replace($replaceValues, "_", $_POST['name']);
  if(file_put_contents($staticDir.$name, $value)) {
    $msgBox = msgBox("Static fact was saved successfully.", "success");
  } else {
    $msgBox = msgBox("Static fact not saved. Please check folder permissions and try again.", "danger");
  }
  $_POST = array();
}
if (isset($_GET['name']) && isset($_GET['delete'])){
  $name = $_GET['name'];
  if (file_exists($staticDir.$name)) {
    if (unlink($staticDir.$name)) {
      $msgBox = msgBox("The fact (".$_GET['name'].") was deleted successfully.", "success");
    } else {
      $msgBox = msgBox("The fact (".$_GET['name'].") wasn't deleted. Please check folder permissions and try again.", "danger");
    }
  } else {
    $msgBox = msgBox("The fact (".$_GET['name'].") doesn't exist. You can add it below.", "danger");
    $queryArray['name'] = $name;
  }
} else if (isset($_GET['name'])){
  $name = $_GET['name'];
  if (file_exists($staticDir.$name)) {
    $fileInfo = file_get_contents($staticDir.$name);
    $queryArray['name'] = $name;
    $queryArray['value'] = $fileInfo;
  } else {
    $queryArray['name'] = $_GET['name'];
    $msgBox = msgBox("The fact (".$_GET['name'].") doesn't exist. You can add it below.", "danger");
  }
}
# Get all Credentials after they've been placed in the file. This is the for sidebar list
$allStatic = glob($staticDir.'*', GLOB_BRACE);
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
    <h1>Static Facts</h1>
    <p>This page allows you to create static facts that will be available to all systems. This is good for things such as monitoring server addresses, update server addresses, etc. Please note that fact NAMES cannot contain "-"'s or spaces. These will be automatically replaced with an underscore (_).</p>
    <form method="post" action="staticFacts.php"> 
    <div class="form-row">
      <div class="form-group col-md-2">
        <label>Name<input placeholder="" class="form-control" type="text" id="name" name="name" value="<?php if (isset($queryArray['name'])) { echo $queryArray['name']; }?>"></label>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-10">
        <label>Value<textarea placeholder="" class="form-control" rows="8" cols="100" id="value" name="value"><?php if (isset($queryArray['value'])) { echo $queryArray['value']; }?></textarea></label>
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
      if (count($allStatic) != 0) {
    ?>
      <ul>
    <?php
      foreach($allStatic as $fact) {
        echo '<li><a href="staticFacts.php?name='.basename($fact, '.json').'">'.basename($fact, '.json').'</a><a data-toggle="modal" href="#delete'.basename($fact, '.json').'"><i class="delete-cred fa fa-trash-alt"></i></a></li>';
        echo '<div id="delete'.basename($fact, '.json').'" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form action="staticFacts.php" method="get">
                      <div class="modal-body">
                        <p class="lead">Are you sure you want to remove this fact?</p> 
                      </div>
                      <div class="modal-footer">
                        <button type="input" name="name" value="'.basename($fact, '.json').'" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> Yes, Remove It</button>
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

