<?php
    session_start();

	include 'connectbdd.php';
	
	$user = $_POST['user'];
	
	$check = $bdd->prepare('SELECT COUNT(*) as isAdmin
							FROM ccrg_special_users
							WHERE name = :name 
							AND pass_hash = :pass
							AND permission = "A"');
	$check->execute(array(':name'=>$_SESSION['name'], ':pass'=>$_SESSION['pass']));
	$check = $check->fetch();
	
    if($check[isAdmin]>=1) {
		$query = $bdd->prepare("INSERT INTO ccrg_blacklist(ip, end_date) VALUES (:name, DATE_ADD(NOW(), INTERVAL 1 DAY))");
		$query->execute(array("name" => $user));

		$request = $bdd->prepare("INSERT INTO ccrg_messages(date, content, writer, ip)
								VALUES (NOW(), :content, 'System Announcement', :ip)");
		$request->execute(array(':content' => 'L\'adresse ip ' . $user . ' a été banni pour 24h', ':ip' => $_SERVER['REMOTE_ADDR']));
	} else {
		echo 'Vous n\'avez pas les autorisations nécessaires pour lancer cette commande.';
	}
	include 'kill_connection.php';
?>