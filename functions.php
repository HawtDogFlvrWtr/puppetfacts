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

function generateHash($saltLength = 16, $passString, $customSalt = '') {
  if ($customSalt == '') {
    $posCharacters = 'adcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789./';
    $string = '';
    $max = strlen($posCharacters) - 1;
    for ($i = 0; $i < $saltLength; $i++) {
      $string .= $posCharacters[mt_rand(0, $max)];
    }
  } else {
    $string = $customSalt;
  }
  return crypt($passString, '$6$'.$string); 
}

function msgBox($message, $type) {
  $msgBox = "<div class='alert alert-".$type." alert-dismissible fade show' role='alert'>
               $message
               <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                 <span aria-hidden='true'>&times;</span>
               </button>
             </div>";
  return $msgBox;
}

function checkUser($currentPassString, $userName) {
  if (file_exists('usercreds/'.$userName.'.json')) {
    $userCreds = file_get_contents('usercreds/'.$userName.'.json');
    $decodeUser = json_decode($userCreds, true);
    $splitPasswd = explode('$', $decodeUser['password']);
    $verifyKey = generateHash(strlen($splitPasswd[2]), $currentPassString, $splitPasswd[2]);
    # FOR DEBUGGING!
    #echo "CurrentKey: ".$decodeUser['password']."\n";
    #echo "NewKey:     ".$verifyKey."\n";
    if (hash_equals($decodeUser['password'], $verifyKey)) {
       $output = "0";
    } else {
       $output = "1";
    }
  } else {
    $output = "2";
  }
  return $output;
}

if (!function_exists('hash_equals')) {
  function hash_equals($str1, $str2) {
    if(strlen($str1) != strlen($str2)) {
      return false;
    } else {
      $res = $str1 ^ $str2;
      $ret = 0;
      for($i = strlen($res) - 1; $i >= 0; $i--) {
        $ret |= ord($res[$i]);
      }
      return !$ret;
    }
  }
}

?>
