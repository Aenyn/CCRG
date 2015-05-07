<?php
    session_start();

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);

	include 'connectbdd.php';

    $send = false;
    $content = ' reçoit un point Clausse! (';
    $writer = '';


    if(isset($_POST['user']) && isset($_SESSION['name'])) {
        $user = htmlspecialchars($_POST['user']);
        $writer = htmlspecialchars($_SESSION['name']);
        if ((strlen($content)>0)&&(strlen($writer)>0)) {
			$firstLetter = $writer[0];
			include 'check_name.php';
        }
    }

    if($send) {
		$check = $bdd->prepare("SELECT COUNT(*) as isBanned FROM ccrg_blacklist WHERE ip = :ip AND end_date > NOW()");
		$check->execute(array(':ip' => $_SERVER['REMOTE_ADDR']));
		$check = $check->fetch();
		if($check['isBanned']==0) {		
			
			$slow = $bdd->prepare("INSERT INTO ccrg_blacklist (ip, end_date) VALUES (:ip, DATE_ADD(NOW(), INTERVAL 1 SECOND))");
			$slow->execute(array(':ip' => $_SERVER['REMOTE_ADDR']));		
			
			$query = "UPDATE ccrg_users SET points_clausse = points_clausse + 1 WHERE name = :name";
			$updateKrako = $bdd->prepare($query);
			$updateKrako->execute(array(':name' => $user));
			
			$query = "SELECT points_clausse FROM ccrg_users WHERE name = :name";
			$nbKrako = $bdd->prepare($query);
			$nbKrako->execute(array(':name' => $user));
			$nbKrako = $nbKrako->fetch();
		
			$query = "INSERT INTO ccrg_messages(date, content, writer, ip)
				VALUES (NOW(), :content, 'Clausse manager', :ip)";

			$request = $bdd->prepare($query);
			$request->execute(array(':content' => $user . $content . $nbKrako['points_clausse'] . ')' , ':ip' => $_SERVER['REMOTE_ADDR']));
			
		}
    }
	include 'kill_connection.php';
?>