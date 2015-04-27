<?php
    session_start();

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);

	include 'connectbdd.php';

	if(isset($_SESSION['name'])) {
		$insertUser = $bdd->prepare("INSERT INTO ccrg_users(name, last_seen, ip)
			VALUES (:name, NOW(), :ip) 
			ON DUPLICATE KEY UPDATE last_seen=NOW(), ip=:ip");
		$insertUser->execute(array(':name' => $_SESSION['name'], ':ip' => $_SERVER['REMOTE_ADDR']));
	}

	$query = $bdd->query("SELECT date
		FROM ccrg_messages
		WHERE date > DATE_SUB(NOW(), INTERVAL 2 DAY)
		ORDER BY date DESC");
	$messages=$query->fetch();
	echo $messages['date'];

	include 'kill_connection.php';
?>