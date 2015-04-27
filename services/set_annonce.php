<?php
    session_start();

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);

	include 'connectbdd.php';

	if(isset($_POST['annonce'])) {
		$query = $bdd->prepare("INSERT INTO ccrg_annonces(texte, date)
			VALUES (:texte, NOW())");
		$query->execute(":texte" => $_POST['annonce']);
		$annonce = $query->fetch();
		echo $annonce['texte'];
	}
				
	include 'kill_connection.php';
?>