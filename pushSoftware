<?php

include 'functions.php';
include 'config.php';
if (isset($_GET["macAddress"]) AND isset($_POST["software"])) {
  // CAPTURE SOFTWARE ON SYSTEM
  $cleanMac = str_replace(":", "", $_GET["macAddress"]);
  file_put_contents($systemsDir.$cleanMac.".software", $_POST["software"]);
  echo "Saved for system $cleanMac";
} else {
  echo "You're missing something";
}
?>
