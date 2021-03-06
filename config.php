<?php
# Add additional facts you want to provide for configuration
$addFacts = array
(
  # Name on page, form info, placeholder, default value
  # Ex. array("NAMEONWEBPAGE, "FORMVALUENAME", "PLACEHOLDERNAME", "DEFAULTVALUE");
  #array("Test", "test", "Placeholder", "value"),
  #array("Test2", "test2", "Placeholder2", "value2")
);
# Directory Setup
$systemsDir = 'systems/';
$credDir = 'credentials/';
$usersDir = 'usercreds/';
$staticDir= 'static/';

# Default values you want to populate on the add system dialog
# This is good if you have settings that generally don't change or are very similair per system
$defaultDomain = 'domain.com';
$defaultHostname = 'hostname';
$defaultFQDN = $defaultHostname.".".$defaultDomain;
$defaultNetmask = '255.255.255.0';
$defaultGateway = '192.168.1.1';
$defaultDNS = '192.168.1.';
$defaultIP = '192.168.1.';

# Puppet roles file and the pattern for detecting the roles and provide a global variable $possibleRoles.
$puppetRoles = '/etc/puppet/manifests/site.pp';
$puppetRolePattern = '/\s+\'?([A-Za-z0-9-]+)\'?\:.*roles.*/'; # Only capture the name.
# This is used to rotate the direction the roles are displayed
$reverseRoles = 1;

$puppetRoles = file($puppetRoles, FILE_SKIP_EMPTY_LINES|FILE_IGNORE_NEW_LINES);
$possibleRoles = array();

foreach ($puppetRoles as $roleLine) {
  $findRole = preg_match($puppetRolePattern, $roleLine, $match);
  if ($findRole == 1) {
    array_push($possibleRoles, $match[1]);
  }
}
if ($reverseRoles == 1) {
  $possibleRoles = array_reverse($possibleRoles);
}
?>
