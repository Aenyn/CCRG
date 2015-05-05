<?php
    session_start();

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);

	include 'connectbdd.php';
	include 'check_special_name.php';
	
	$user = $_POST['user'];

    if(($_SESSION['permission']=='A')||($_SESSION['permission']=='M')) {
		$query = $bdd->prepare("UPDATE ccrg_users SET kicked = 1 WHERE name=:name");
		$query->execute(array("name" => $user));
		
		$message = "INSERT INTO ccrg_messages(date, content, writer, ip)
				VALUES (NOW(), :content, 'System Announcement', :ip)";

		$request = $bdd->prepare($message);
		$request->execute(array(':content' => $user . ' has been kicked', ':ip' => $_SERVER['REMOTE_ADDR']));
		
	} else {
		header('HTTP/1.1 500 Internal Server Error');
	}
	
	include 'kill_connection.php';
?>