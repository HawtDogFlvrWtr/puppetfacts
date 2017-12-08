<?php
include 'functions.php';
#ini_set('display_errors', 1); 
#ini_set('display_startup_errors', 1); 
#error_reporting(E_ALL);
$credFiles = glob('credentials/*.{json}', GLOB_BRACE);
# input information from form submit
if (count($_POST) > 0 && $_POST['macAddress']) {
  $jsonConfs = json_encode($_POST);
  $macAddress = strtoupper($_POST["macAddress"]);
  $cleanMac = str_replace(":", "", $macAddress);
  file_put_contents($cleanMac.".json", $jsonConfs);
}

if (isset($_GET["macAddress"])) {
  # Get Mac Address
  $macAddress = strtoupper($_GET["macAddress"]);
  $cleanMac = str_replace(":", "", $_GET["macAddress"]);
  $addProps = getAdditionals();
  $jsonConfs = json_encode($addProps);
  # Open new file if it doesn't exists, removing the colon's from the file name
  if (file_exists("systems/".$cleanMac.".json") && count($addProps) <= 1){
    $fileContent = file_get_contents("systems/".$cleanMac.".json", true);
    # convert to array
    $jsonArrayBase = json_decode($fileContent, true);
    $role = $jsonArrayBase['role'];
    # Get list of credentials
    if (file_exists("credentials/".$role.".json")) {
      $getCredJson = file_get_contents("credentials/".$role.".json");
      $credJsonArray = json_decode($getCredJson, true);
      $jsonArrayBase = array_merge($credJsonArray, $jsonArrayBase);
    } else if (file_exists("credentials/default.json")) {
      $getCredJson = file_get_contents("credentials/default.json");
      $credJsonArray = json_decode($getCredJson, true);
      $jsonArrayBase = array_merge($credJsonArray, $jsonArrayBase);
    }
    header('Content-Type: application/json');
    echo stripslashes(json_encode($jsonArrayBase));
  } else {
    echo "This systems configuration doesn't exist.<br>";
    echo printHelp();
  }
} else if (isset($_GET["username"])) {
  # Get username 
  $username = $_GET["username"];
  # Open new file if it doesn't exists, removing the colon's from the file name
  if (file_exists("usercreds/".$username.".json") && count($addProps) <= 1){
    $fileContent = file_get_contents("usercreds/".$username.".json", true);
    header('Content-Type: application/json');
    echo stripslashes($fileContent);
  } else {
    echo "This user doesn't exist.<br>";
    echo printHelp();
  }
} else {
  echo printHelp();
}
?>
