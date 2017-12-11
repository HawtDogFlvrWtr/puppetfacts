<?php 
include 'header.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$row = 1;
$msgBox = '';
# Delete Record
if (isset($_GET['username'])){
  if (file_exists($usersDir.$_GET['username'].".json")) {
    if ( isset($_GET['delete'])) {
      if (unlink($usersDir.$_GET['username'].".json")) {
        $msgBox = msgBox("User (".$_GET['username'].") was deleted successfully.", "success");
        # Check and log out if the current user was deleted
        if (isset($_SESSION['login_user']) && $_SESSION['login_user'] === $_GET['username']) {
          unset($_SESSION['login_user']);
          header("Location: login.php");
          die();
        }
      } else {
        $msgBox = msgBox($_GET['username']." wasn't deleted. There was an issue deleting the information, or the user no longer exists. Please try again.", "danger");
      }
    }
  }
}
# Get list of systems, This after the delete statement above, so it updates the page on post.
$files = glob($usersDir.'*.{json}', GLOB_BRACE);
if ($msgBox != "") {
  echo '<div class="red-text container">';
  echo $msgBox;
  echo '</div>';
}
?>
<div class="container-margin container border rounded bg-light">
<h1>All Users</h1>
<p>These are the current users that you have configuration information for. You can edit or delete any record.</p>
<?php 
if (count($files) > 0) {
?>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Username</th>
      <th scope="col">Password</th>
      <th scope="col">Certificate</th>
      <th scope="col">Options</th>
    </tr>
  </thead>
  <tbody>
<?php
   foreach($files as $file) {
     $jsonDecode = json_decode(file_get_contents($file), true);
     if (isset($jsonDecode['pki']) && $jsonDecode['pki'] != '') {
       $pkiOut = "<b class='green-text'>SET</b>";
     } else {
       $pkiOut = "<b class='red-text'>UNSET</b>";
     }
     if (isset($jsonDecode['password']) && $jsonDecode['password'] != '') {
       $passOut = "<b class='green-text'>SET</b>";
     } else {
       $passOut = "<b class='red-text'>UNSET</b>";
     }
     echo '<tr>';
     echo '<td>'.$jsonDecode['username'].'</td>';
     echo '<td>'.$passOut.'</td>';
     echo '<td>'.$pkiOut.'</td>';
     echo '<td>
	<a class="btn btn-warning btn-icon" href="userCreds.php?username='.$jsonDecode['username'].'"><i class="fa fa-edit"></i> Edit</a>
	<a data-toggle="modal" href="#delete'.$row.'" class="btn btn-danger btn-icon" data-dismiss="modal"><i class="fa fa-trash-alt"></i> Delete</a>
     ';
     # don't show the json information for the admin user
     if ($jsonDecode['username'] != "admin") {
	echo '<a target="_blank" href="getInfo.php?username='.$jsonDecode['username'].'" class="btn btn-success btn-icon"><i class="fa fa-code"></i> JSON</a>';

     }
     echo '</td>';
     echo '</tr>';
     echo '<div id="delete'.$row.'" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
             <div class="modal-dialog">
               <div class="modal-content">
                 <form action="allUsers.php" method="get">
                   <div class="modal-body">
                     <p class="lead">Are you sure you want to remove this system configuration?</p>
                   </div>
                   <div class="modal-footer">
                     <button type="input" name="username" value="'.$jsonDecode['username'].'" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> Yes, Remove It</button>
                     <input type="hidden" name="delete">
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
  echo '<h3><em>There are currently no users to display</em></h3>';
}
?>
</div>
<?php
include 'footer.php';
?>
