<?php
function cleanMac($macAddress) {
  return strtoupper(str_replace(":", "", $macAddress));
}
function getAdditionals() {
  $gets = $_GET;
  return $gets;
}
function printHelp() {
  $helpInfo = "You're missing some information.<br>To query for system information please provide macAddress GET information in the url.<br>
               To add a system, please provide macAddress, role, ipAddresses, macAddresses, gateway, fqdn, hostname, domain, mountNFS GET information in the url.<br>";
  return $helpInfo;
}
function generateHash($saltLength = 16, $passString) {
  $posCharacters = 'adcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789./';
  $string = '';
  $max = strlen($posCharacters) - 1;
  for ($i = 0; $i < $saltLength; $i++) {
    $string .= $posCharacters[mt_rand(0, $max)];
  }
  return crypt($passString, '$6$'.$string); 
}
?>
