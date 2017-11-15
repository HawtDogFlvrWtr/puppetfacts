<?php
  include 'header.php';
?>
<div class="container" style="padding-bottom:20px;">
</div>

<div class="container rounded border bg-light">
  <h1>Puppet Facts</h1>
  <p>This website will allow you to add facts, that puppet will use to configure the system it's installed on. It is MAC address driven, because its the only known information the system has about itself on first boot. When puppet runs for the first time, it will pull it's system information from this site, and before each consecutive puppet run. This allows you to make changes to the systems configuration, without shutting down the system and editing the VMWARE OVF facts.</p>

  <p>To make a configuration, simply click the <a href="add.php">Add System</a> link above, and fill in the information. If you already have a system added that you wish to edit, you can view all systems by clicking <a href="allSystems.php">All Systems</a> above, or by entering the MAC Address in the search button on the top right.</p>
</div>

<?php
  include 'footer.php';
?>
