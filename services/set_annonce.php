<?php
    session_start();

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);

	include 'connectbdd.php';

	if(isset($_POST['annonce'])) {
		if($_SESSION['permission'] == 'A') {

			$annonce = "INSERT INTO ccrg_annonces(date, texte)
				VALUES (NOW(), :texte)";
		
			$query = $bdd->prepare($annonce);
			$query->execute(array(":texte" => $_POST['annonce']));
			
			$message = "INSERT INTO ccrg_messages(date, content, writer, ip)
				VALUES (NOW(), :content, 'System Announcement', :ip)";

			$request = $bdd->prepare($message);
			$request->execute(array(':content' => "Nouvelle annonce: " . $_POST['annonce'], ':ip' => $_SERVER['REMOTE_ADDR']));
		}
	}
				
	include 'kill_connection.php';
?>