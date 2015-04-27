<?php
    session_start();

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);

	include 'connectbdd.php';

	if(isset($_POST['name'])) {
		$_SESSION['name'] = $_POST['name'];
		
		$insertUser = $bdd->prepare("INSERT INTO ccrg_users(name, last_seen, ip)
			VALUES (:name, NOW(), :ip) 
			ON DUPLICATE KEY UPDATE last_seen=NOW(), ip=:ip");
		$insertUser->execute(array(':name' => $_SESSION['name'], ':ip' => $_SERVER['REMOTE_ADDR']));

		$query = "INSERT INTO ccrg_messages(date, content, writer, ip)
				VALUES (NOW(), :content, 'System Announcement', :ip)";

		$request = $bdd->prepare($query);
		$request->execute(array(':content' => $_SESSION['name'] . ' vient de se connecter', ':ip' => $_SERVER['REMOTE_ADDR']));
	} else echo 'fail';

	include 'kill_connection.php';
?>