<?php
$currentPage = basename($_SERVER['PHP_SELF'],'.php');
if (!file_exists("credentials/default.json")) {
		if ($currentPage == 'generateCreds') {
                $msgBox = "<div class='alert alert-danger' role='alert'>You have no default credentials configured. This will prevent puppet from running correctly.</div>";
		} else {
                $msgBox = "<div class='alert alert-danger' role='alert'>You have no default credentials configured. This will prevent puppet from running correctly. Please set one by clicking <a href='generateCreds.php'>Generate Credentials</a> above.</div>";

		}
}
?>
<html>
        <head>
                <title>Host configuration</title>
                <link rel="stylesheet" type="text/css" href='css/bootstrap.min.css'>
                <style>
                        label{display:inline-block;}
                        input, select{display:block;}
                </style>
                <script src='js/jquery-3.2.1.slim.min.js'></script>
                <script src='js/popper.min.js'></script>
                <script src='js/bootstrap.min.js'></script>
        </head>
<body>
<div class="container" style="padding-bottom:20px;">
<nav class="border rounded navbar navbar-expand-lg navbar-dark bg-dark">
	<a class="navbar-brand" href="index.php">Puppet-Facts</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	  <span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
	  <ul class="navbar-nav mr-auto">
	    <li class="nav-item <?php if ($currentPage == 'add') { echo 'active'; } ?>">
	      <a class="nav-link" href="add.php">Add System <?php if ($currentPage == 'add') { echo '<span class="sr-only">(current)</span>'; } ?></a>
	    </li>
	    <li class="nav-item <?php if ($currentPage == 'allSystems') { echo 'active'; } ?>">
	      <a class="nav-link" href="allSystems.php">All System <?php if ($currentPage == 'allSystems') { echo '<span class="sr-only">(current)</span>'; }?></a>
	    </li>
	    <li class="nav-item <?php if ($currentPage == 'generateCreds') { echo 'active'; } ?>">
	      <a class="nav-link" href="generateCreds.php">Generate Credentials <?php if ($currentPage == 'generateCreds') { echo '<span class="sr-only">(current)</span>'; }?></a>
	    </li>
	  </ul>
	  <form class="form-inline my-2 my-lg-0" method="get" action="add.php">
	    <input class="form-control mr-sm-2" id="macAddress" name="macAddress" type="search" placeholder="Search MAC" aria-label="Search">
	    <button class="btn btn-outlline-success my-2 my-sm-0" type="submit">Search</button>
	  </form>
	</div>
</nav>
</div>
<?php
if ($msgBox != "") {
	echo '<div class="container">';
	echo $msgBox;
	echo '</div>';
}
?>
