<?php 
include 'header.php';
#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);
#error_reporting(E_ALL);
$row = 1;
$msgBox = '';
# Delete Record
if (isset($_GET['macAddress'])){
  if (file_exists($systemsDir.cleanMac($_GET['macAddress']).".json")) {
    if ( isset($_GET['delete'])) {
      if (unlink($systemsDir.cleanMac($_GET['macAddress']).".json")) {
        $msgBox = msgBox("This systems (".$_GET['macAddress'].") configuration was deleted.", "success");
      } else {
        $msgBox = msgBox("This systems (".$_GET['macAddress'].") configuration wasn't deleted. Please try again.", "danger");
      }
    }
  }
}
# Get list of systems, This after the delete statement above, so it updates the page on post.
$files = glob($systemsDir.'*.{json}', GLOB_BRACE);
if ($msgBox != "") {
  echo '<div class="red-text container">';
  echo $msgBox;
  echo '</div>';
}
?>
<div class="container-margin container border rounded bg-light">
<h1>All Systems</h1>
<p>These are the current systems that you have configuration information for. You can edit or delete any record.</p>
<?php 
if (count($files) > 0) {
?>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Hostname</th>
      <th scope="col">Role</th>
      <th scope="col">Password Role</th>
      <th scope="col">MAC</th>
      <th scope="col">IPAddresses</th>
      <th scope="col">Options</th>
    </tr>
  </thead>
  <tbody>
<?php
   foreach($files as $file) {
     $jsonDecode = json_decode(file_get_contents($file), true);
     echo '<tr>';
     echo '<td>'.$jsonDecode['hostname'].'</td>';
     echo '<td>'.$jsonDecode['role'].'</td>';
     if (file_exists($credDir.$jsonDecode['role'].".json")) {
       echo '<td>'.$jsonDecode['role'].'</td>';
     } else if (file_exists($credDir."default.json")) {
       echo '<td>default</td>';
     } else {
       echo '<td class="red-text">NOT SET</td>';
     }
     echo '<td>'.$jsonDecode['macAddress'].'</td>';
     echo '<td>'.$jsonDecode['ipAddresses'].'</td>';
     echo '<td>
	<a class="btn btn-warning btn-icon" href="add.php?macAddress='.$jsonDecode['macAddress'].'"><i class="fa fa-edit"></i> Edit</a>
	<a data-toggle="modal" href="#delete'.$row.'" class="btn btn-danger btn-icon" data-dismiss="modal"><i class="fa fa-trash-alt"></i> Delete</a>
	<a target="_blank" href="getInfo.php?macAddress='.$jsonDecode['macAddress'].'" class="btn btn-success btn-icon"><i class="fa fa-code"></i> JSON</a>
	  </td>';
     echo '</tr>';
     echo '<div id="delete'.$row.'" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
             <div class="modal-dialog">
               <div class="modal-content">
                 <form action="allSystems.php" method="get">
                   <div class="modal-body">
                     <p class="lead">Are you sure you want to remove this system configuration?</p>
                   </div>
                   <div class="modal-footer">
                     <button type="input" name="macAddress" value="'.$jsonDecode['macAddress'].'" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> Yes, Remove It</button>
                     <input type="hidden" name="delete" value="true">
                     <button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle-o"></i> Cancel</button>
                   </div>
                 </form>
               </div>
             </div>
           </div>';
     $row++;
   }
?>
  </tbody>
</table>
<?php
} else {
  echo '<h3><em>There are currently no systems to display</em></h3>';
}
?>
</div>
<?php
include 'footer.php';
?>
