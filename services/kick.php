<?php
    session_start();

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);

	include 'connectbdd.php';
	
	$user = $_POST['user'];
	$pass = $_POST['pass'];

    if($_POST['pass'] == "ahts") {
		$query = $bdd->prepare("UPDATE ccrg_users SET kicked = 1 WHERE name=:name");
		$query->execute(array("name" => $user));
	} else {
		header('HTTP/1.1 500 Internal Server Error');
	}
	
	include 'kill_connection.php';
?>