<?php
    session_start();

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);

	include 'connectbdd.php';
	
	$user = $_POST['user'];
	$pass = $_POST['pass'];

    if(strpos($pass,"twbatstdgagitw")) {
		$query = $bdd->prepare("SELECT ip FROM ccrg_users WHERE name=:name");
		$query->execute(array("name" => $user));
		$query = $query->fetch();
		echo $query['ip'];
	} else {
		echo $pass;
	}
?>