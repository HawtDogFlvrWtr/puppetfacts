<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
$credFiles = glob('credentials/*.{json}', GLOB_BRACE);
# input information from form submit
if (count($_POST) > 0 && $_POST['macAddress']) {
	$jsonConfs = json_encode($_POST, JSON_PRETTY_PRINT);
        $macAddress = strtoupper($_POST["macAddress"]);
        $cleanMac = str_replace(":", "", $macAddress);
	file_put_contents($cleanMac.".json", $jsonConfs);
}

function getAdditionals() {
	$gets = $_GET;
	return $gets;
}

function printHelp() {
	$helpInfo = "You're missing some information.<br>To query for system information please provide macAddress GET information in the url.<br>To add a system, please provide macAddress, role, ipAddresses, macAddresses, gateway, fqdn, hostname, domain, mountNFS GET information in the url.<br>";
	return $helpInfo;
}

if (isset($_GET["macAddress"])) {
	# Get Mac Address
	$macAddress = strtoupper($_GET["macAddress"]);
	$cleanMac = str_replace(":", "", $_GET["macAddress"]);
	$addProps = getAdditionals();
	$jsonConfs = json_encode($addProps, JSON_PRETTY_PRINT);
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
			$jsonArrayBase = array_merge($jsonArrayBase, $credJsonArray);
		} else if (file_exists("credentials/default.json")) {
                        $getCredJson = file_get_contents("credentials/default.json");
                        $credJsonArray = json_decode($getCredJson, true);
			$jsonArrayBase = array_merge($jsonArrayBase, $credJsonArray);
		}
		header('Content-Type: application/json');
	  	echo json_encode($jsonArrayBase, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
	} else {
		echo "This systems configuration doesn't exist.<br>";
		echo printHelp();
	}
} else {
	echo printHelp();
}

?>
