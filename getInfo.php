<?php
include 'functions.php';
include 'config.php';

#ini_set('display_errors', 1); 
#ini_set('display_startup_errors', 1); 
#error_reporting(E_ALL);
$credFiles = glob($credDir.'*.{json}', GLOB_BRACE);
$staticFiles = glob($staticDir.'*', GLOB_BRACE);
# input information from form submit
if (count($_POST) > 0 && $_POST['macAddress']) {
  $jsonConfs = json_encode($_POST);
  $macAddress = strtoupper($_POST["macAddress"]);
  $cleanMac = str_replace(":", "", $macAddress);
  file_put_contents($cleanMac.".json", $jsonConfs);
}

# Get system information
if (isset($_GET["macAddress"])) {
  # Get Mac Address
  $macAddress = strtoupper($_GET["macAddress"]);
  $cleanMac = str_replace(":", "", $_GET["macAddress"]);
  $addProps = getAdditionals();
  $jsonConfs = json_encode($addProps);
  # Open new file if it doesn't exists, removing the colon's from the file name
  if (file_exists($systemsDir.$cleanMac.".json") && count($addProps) <= 1){
    $fileContent = file_get_contents($systemsDir.$cleanMac.".json", true);
    # convert to array
    $jsonArrayBase = json_decode($fileContent, true);
    $role = $jsonArrayBase['role'];
    # Get static facts.
    if (count($staticFiles) > 0) {
      foreach($staticFiles as $factFile) {
        $staticArray = array();
        $factName = explode('/', $factFile);
        $staticArray[$factName[1]] = file_get_contents($factFile);
        $jsonArrayBase = array_merge($staticArray, $jsonArrayBase);
      } 
    }
    # Get list of credentials.
    if (file_exists($credDir.$role.".json")) {
      $getCredJson = file_get_contents($credDir.$role.".json");
      $credJsonArray = json_decode($getCredJson, true);
      $jsonArrayBase = array_merge($credJsonArray, $jsonArrayBase);
    } else if (file_exists($credDir."default.json")) {
      $getCredJson = file_get_contents($credDir."default.json");
      $credJsonArray = json_decode($getCredJson, true);
      $jsonArrayBase = array_merge($credJsonArray, $jsonArrayBase);
    }
    header('Content-Type: application/json');
    echo prettyPrint(stripslashes(json_encode($jsonArrayBase)));
  } else {
    echo "This systems configuration doesn't exist.<br>";
    echo printHelp();
  }
# Get specific user information as json
} else if (isset($_GET["username"]) && $_GET['username'] != 'admin') {
  # Get username 
  $username = $_GET["username"];
  # Open new file if it doesn't exists, removing the colon's from the file name
  if (file_exists($usersDir.$username.".json") && count($addProps) <= 1){
    $fileContent = file_get_contents($usersDir.$username.".json", true);
    header('Content-Type: application/json');
    echo prettyPrint(stripslashes($fileContent));
  } else {
    echo "This user doesn't exist.<br>";
    echo printHelp();
  }
# Get all users in one json return
} else if (isset($_GET['allusers'])){
  $userArray = array();
  $files = glob($usersDir.'*.{json}', GLOB_BRACE);
  foreach($files as $file) {
    # Skip the admin user, as it's only for web access
    if ($file !== $usersDir.'admin.json') {
      $jsonDecode = json_decode(file_get_contents($file), true);
      $userArray[] = $jsonDecode;
    }
  }
  header('Content-Type: application/json');
  echo prettyPrint(stripslashes(json_encode($userArray)));
} else {
  echo printHelp();
}
?>
