<?php
    session_start();

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);

	include 'connectbdd.php';

    $send = false;
    $content = '';
    $writer = '';


    if(isset($_SESSION['diceRoll']) && isset($_SESSION['name'])) {
        $content = htmlspecialchars($_SESSION['diceRoll']);
        $writer = htmlspecialchars($_SESSION['name']);
		unset($_SESSION['diceRoll']);
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
		
			$query = "INSERT INTO ccrg_messages(date, content, writer, ip)
				VALUES (NOW(), :content, 'Dice revealer', :ip)";

			$request = $bdd->prepare($query);
			$request->execute(array(':content' => $writer . ' ' . $content, ':ip' => $_SERVER['REMOTE_ADDR']));
		}
    }
	include 'kill_connection.php';
?>