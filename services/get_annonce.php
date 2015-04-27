<?php
    session_start();

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);

	include 'connectbdd.php';

	$query = $bdd->prepare("SELECT texte 
		FROM ccrg_annonces
		ORDER BY date DESC
		LIMIT 1");
	$query->execute();
	$annonce = $query->fetch();
	echo $annonce['texte'];
				
	include 'kill_connection.php';
?>