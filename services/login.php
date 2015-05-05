<?php
    session_start();
	include 'connectbdd.php';
	include 'check_special_name.php';

	if(isset($_POST['pass'])) {
		$_SESSION['pass'] = $_POST['pass'];
	}
	
	if(isset($_SESSION['name'])) {
		$name = $_SESSION['name'];
	}
	
	if(isset($_POST['name'])) {
		$name = $_POST['name'];
		$_SESSION['name'] = $name;
	}
	
	$permission = checkSpecialName($name,$bdd);
	if($permission!=='F') {
		$_SESSION['permission'] = $permission;			
		
		$insertUser = $bdd->prepare("INSERT INTO ccrg_users(name, last_seen, ip)
			VALUES (:name, NOW(), :ip) 
			ON DUPLICATE KEY UPDATE last_seen=NOW(), ip=:ip");
		$insertUser->execute(array(':name' => $_SESSION['name'], ':ip' => $_SERVER['REMOTE_ADDR']));

		$query = "INSERT INTO ccrg_messages(date, content, writer, ip)
				VALUES (NOW(), :content, 'System Announcement', :ip)";

		$request = $bdd->prepare($query);
		$request->execute(array(':content' => $_SESSION['name'] . ' (' . $permission . ') vient de se connecter', ':ip' => $_SERVER['REMOTE_ADDR']));
		header('Location: http://www.panicot.fr/ccrg/index.php');			
	} else {
		//echo $_SESSION['pass'];
		header('Location: http://www.panicot.fr/ccrg/password.php');
	}

	include 'kill_connection.php';
?>