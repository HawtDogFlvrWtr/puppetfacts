<?php

# Directory Setup
$systemsDir = 'systems/';
$credDir = 'credentials/';

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
