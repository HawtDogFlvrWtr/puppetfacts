<?php
include 'header.php';
if (isset($_GET['hostname'])) {
  $hostname = " - ".strtoupper($_GET['hostname']);
} else {
  $hostname = "";
}

function printTable($base64Data, $hostname) {
    $decoded = base64_decode($base64Data);
    # CSV Linux
    if (strpos($decoded, '|')) {
      echo '<h1>'.$hostname.' Software</h1>';
      echo "<table class='table table-hover table-responsive-md'>";
      echo "<thead>";
      echo "<tr>";
      echo "<th scope='col'>Software</th>";
      echo "<th scope='col'>Info</th>";
      echo "<th scope='col'>Install Date</th>";
      echo "</tr>";
      echo "</thead>";
      echo "<tbody>";
      $records = explode("|", $decoded);
      foreach ($records as &$record) {
        $csv = explode("~", $record);
        if ($csv[0] != "") {
          $replacePath = str_replace('node_modules\\', '', $csv[0]);
          if (strlen($csv[2]) == 8 or strlen($csv[2]) > 11) {
            $date = date("m-d-Y", strtotime($csv[2]));
          } else if ($csv[2] == 0) {
            $date = "Unknown";
          } else {
            $date = date("m-d-Y", $csv[2]);
          }
          echo "<tr>";
          echo "<td>$replacePath</td>";
          echo "<td>$csv[1]</td>";
          echo "<td style='width:110px;'>".$date."</td>";
          echo "</tr>";
        }
      }
      echo "</tbody>";
      echo "</table>";
    } else {
      echo $decoded;
    }
}
?>

<div class="container-margin container border rounded bg-light">
<?php
if (isset($_GET['all'])) {
  $hosts = glob($systemsDir.'*.{software}', GLOB_BRACE);
  echo '<a class="btn btn-warning btn-sm hide-from-printer btn-padding btn-right" href="#" onClick="window.print()"><i class="fa fa-print" onClick="window.print()"></i> Print List</a>';
  foreach ($hosts as $host) {
    $hostname = json_decode(file_get_contents(str_replace('software', 'json', $host)), true);
    $fileContent = file_get_contents($host);
    printTable($fileContent, strtoupper($hostname['hostname']));
  }
} else if (isset($_GET["macAddress"]) ){
  echo '<a class="btn btn-warning btn-sm hide-from-printer btn-padding btn-right" href="#" onClick="window.print()"><i class="fa fa-print" onClick="window.print()"></i> Print List</a>';
  $cleanMac = str_replace(":", "", $_GET["macAddress"]);
  if (file_exists($systemsDir.$cleanMac.".software")) {
    $fileContent = file_get_contents($systemsDir.$cleanMac.".software", $_POST["software"]);
    printTable($fileContent, strtoupper($_GET['hostname']));
  } else {
    echo "This system information doesn't exist";
  }
} else {
  echo "You're missing something";
}
?>
</div>
<?php
include 'footer.php';

?>
