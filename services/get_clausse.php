<?php
    session_start();

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);

	include 'connectbdd.php';
	if(isset($_POST['name'])) {
		$query = "SELECT points_clausse FROM ccrg_users WHERE name = :name";
			$nbKrako = $bdd->prepare($query);
			$nbKrako->execute(array(':name' => $_POST['name']));
			$nbKrako = $nbKrako->fetch();
		echo $nbKrako['points_clausse'];
	}
				
	include 'kill_connection.php';
?>