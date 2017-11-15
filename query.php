<?php
include 'header.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$queryArray = [];
$msgBox = "";

# Get possible roles into array
$roles = file('possible_roles', FILE_IGNORE_NEW_LINES);
function cleanMac($macAddress) {
	return str_replace(":", "", $macAddress);
}
# Generate form information if mac provided
# input information from form submit
if (count($_POST) > 0 && $_POST['macAddress']) {
        $jsonConfs = json_encode($_POST, JSON_PRETTY_PRINT);
        $macAddress = $_POST["macAddress"];
        file_put_contents(cleanMac($macAddress).".json", $jsonConfs);
	$msgBox = "<div class='alert alert-success alert-dismissible fade show' role='alert'>System information saved. <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
}
if (isset($_GET['macAddress'])){
	if (file_exists(cleanMac($_GET['macAddress']).".json")) {
		$jsonInfo = file_get_contents(cleanMac($_GET['macAddress']).".json");
		$queryArray = json_decode($jsonInfo, true);
		$msgBox = "<div class='alert alert-success alert-dismissible fade show' role='alert'>System information loaded.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
	} else {
		$queryArray['macAddress'] = $_GET['macAddress'];
		$msgBox = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>This system doesn't have configuration information.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
	}
}
?>
	<?php 
	if ($msgBox != "") {
		echo '<div class="container">';
		echo $msgBox;
		echo '</div>';
	}
	?>
	<div class="container">
		<form method="get" action="add.php">
			<h4>Query for current system information</h4>
				<div class="form-group">
					<label>MacAddress<input class="form-control" type="text" id="macAddress" name="macAddress"></label>
				</div>
				<input type="submit" Value="Query">
				<input type="hidden" name="page" Value="add">
				
		</form>
	</div>
	</body> 
</html>

