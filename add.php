<?php
include 'header.php';
include 'functions.php';
#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);
#error_reporting(E_ALL);
$queryArray = [];
$msgBox = "";

# Generate form information if mac provided
# input information from form submit
if (count($_POST) > 0 && $_POST['macAddress']) {
  $_POST['macAddress'] = strtoupper($_POST['macAddress']);
  $jsonConfs = json_encode($_POST, JSON_PRETTY_PRINT);
  $macAddress = $_POST["macAddress"];
    if(file_put_contents('systems/'.cleanMac($macAddress).".json", $jsonConfs)) {
      $msgBox = "<div class='alert alert-success alert-dismissible fade show' role='alert'>System information saved. <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
    } else {
      $msgBox = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>System information not saved. Are you trying to be naughty?<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
    }
}
if (isset($_GET['macAddress'])){
  if (file_exists('systems/'.cleanMac($_GET['macAddress']).".json")) {
    if ( isset($_GET['delete'])) {
      if (unlink('systems/'.cleanMac($_GET['macAddress']).".json")) {
        $msgBox = "<div class='alert alert-success alert-dismissible fade show' role='alert'>This system configuration was deleted.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
      } else {
        $msgBox = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>This system configuration wasn't deleted. Please try again.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
      }
    } else {
      $jsonInfo = file_get_contents('systems/'.cleanMac($_GET['macAddress']).".json");
      $queryArray = json_decode($jsonInfo, true);
    }
  } else {
    $queryArray['macAddress'] = $_GET['macAddress'];
    $msgBox = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>This system doesn't have configuration information. You can add it below.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
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
<?php 
  if (isset($_GET['macAddress'])) { 
    $url = 'index.php?'.$_GET['macAddress'];
  } else if (isset($_POST['macAddress'])) {
    $url = 'index.php?'.$_POST['macAddress'];
  } else {
    $url = 'index.php';
  }
?>
<div class="container border rounded bg-light">
<h1>Insert or Update</h1>
<p>This page allows you to add new records, or if you've searched your MAC with the search button above, or clicked edit from the <a href="allSystems.php">All Systems</a> page, you will be able to modify it's information.</p>
<form method="post" action="add.php?<?php if (isset($queryArray['macAddress'])) { echo 'macAddress='.$queryArray['macAddress']; }?>"> 
  <div class="form-row">
    <div class="form-group col-md-2">
      <label>MacAddress<input placeholder="00:00:00:00:00:00" class="form-control" type="text" id="macAddress" name="macAddress" value="<?php if (isset($queryArray['macAddress'])) { echo $queryArray['macAddress']; }?>"></label>
    </div>
    <div class="form-group col-md-2">
      <label>Hostname<input placeholder="hostname" class="form-control" type="text" id="hostname" name="hostname" value="<?php if (isset($queryArray['hostname'])) { echo $queryArray['hostname']; }?>"></label>
    </div>
    <div class="form-group col-md-2">
      <label>FQDN<input placeholder="hostname.domain.com" class="form-control" type="text" id="fqdn" name="fqdn" value="<?php if (isset($queryArray['fqdn'])) { echo $queryArray['fqdn']; }?>"></label>
    </div>
    <div class="form-group col-md-2">
      <label>Domain<input placeholder="domain.com" class="form-control" type="text" id="domain" name="domain" value="<?php if (isset($queryArray['domain'])) { echo $queryArray['domain']; }?>"></label>
    </div>
    <div class="form-group col-md-2">
      <label>IPv4 (CSV)<input placeholder="ip1,ip2,ip3" class="form-control" type="text" name="ipAddresses" value="<?php if (isset($queryArray['ipAddresses'])) { echo $queryArray['ipAddresses']; }?>"></label>
    </div>
    <div class="form-group col-md-2">
      <label>DNS (CSV)<input placeholder="dns1,dns2" class="form-control" type="text" name="dnsAddresses" value="<?php if (isset($queryArray['dnsAddresses'])) { echo $queryArray['dnsAddresses']; }?>"></label>
    </div>
    <div class="form-group col-md-2"> 
      <label>Gateway (CSV)<input placeholder="gw1,gw2" class="form-control" type="text" name="gateway" value="<?php if (isset($queryArray['gateway'])) { echo $queryArray['gateway']; }?>"></label>
    </div>
    <div class="form-group col-md-2">
      <label>Netmask (CSV)<input placeholder="nm1,nm2" class="form-control" type="text" name="netmask" value="<?php if (isset($queryArray['netmask'])) { echo $queryArray['netmask']; }?>"></label>
    </div>
    <?php
      # Adding additional facts that exist in the config.php
      $factCount = count($addFacts);
      for ($row = 0; $row < $factCount; $row++) {
        if (isset($queryArray[$addFacts[$row][1]])) { 
          $factValue = $queryArray[$addFacts[$row][1]]; 
        } else {
          $factValue = '';
        }
        echo '<div class="form-group col-md-2">';
        echo '  <label>'.$addFacts[$row][0].'<input placeholder="'.$addFacts[$row][2].'" class="form-control" type="text" name="'.$addFacts[$row][1].'" value="'.$factValue.'"></label>';
        echo '</div>';
      }
    ?>
    <div class="form-group col-md-2">
      <label for="role">Role<select class="form-control" name="role" id="role">
      <?php 
        foreach ($possibleRoles as $line_num => $role) {
          if (isset($queryArray['role']) && $role == $queryArray['role']) {
            echo '<option value="'.$role.'" selected>'.$role.'</option>';
          } else {
            echo '<option value="'.$role.'">'.$role.'</option>';
          }
        }
      ?>
        </select></label>
    </div>
  </div>
  <input type="submit" name="" value="Submit">
</form> 
</div>
<?php include 'footer.php';?>
