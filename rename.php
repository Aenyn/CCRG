<?php
    session_start();

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);

	include 'connectbdd.php';

	if(isset($_POST['name'])&&isset($_SESSION['name'])) {
		$insertUser = $bdd->prepare("INSERT INTO ccrg_users(name, last_seen, ip)
			VALUES (:name, NOW(), :ip) 
			ON DUPLICATE KEY UPDATE last_seen=NOW(), ip=:ip");
		$insertUser->execute(array(':name' => ':name', ':ip' => $_SERVER['REMOTE_ADDR']));

		$query = "INSERT INTO ccrg_messages(date, content, writer, ip)
				VALUES (NOW(), :content, 'System Announcement', :ip)";

		$request = $bdd->prepare($query);
		$request->execute(array(':content' => $_SESSION['name'] . ' renamed to ' . $_POST['name'], ':ip' => $_SERVER['REMOTE_ADDR']));
		$_SESSION['name'] = $_POST['name'];
	} else echo 'fail';

	include 'kill_connection.php';
?>