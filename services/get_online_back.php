<?php
    session_start();

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);

	try
	{
		$bdd = new PDO('mysql:host=mysql51-119.perso;dbname=panicotmail', 'panicotmail', '2007Panicot');
	}
		catch(Exception $e)
	{
		die('Erreur : '.$e->getMessage());
    }
	
	if(isset($_GET['name'])) {
		$insertUser = $bdd->prepare("INSERT INTO ccrg_users(name, last_seen, ip)
			VALUES (:name, NOW(), :ip) 
			ON DUPLICATE KEY UPDATE last_seen=NOW(), ip=:ip");
		$insertUser->execute(array(':name' => $_GET['name'], ':ip' => $_SERVER['REMOTE_ADDR']));

		$query = $bdd->query("SELECT name
			FROM ccrg_users
			WHERE last_seen > DATE_SUB(NOW(), INTERVAL 30 SECOND)
			ORDER BY last_seen DESC");
		while($messages=$query->fetch()) {
			echo '<p>' . $messages['name'] . '</p>';
		}
	}

?>