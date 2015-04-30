<?php
    session_start();

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);

	include 'connectbdd.php';

    $send = false;
    $content = '';
	$diceNb = '';
    $sideNb = '';
    $writer = '';


    if(isset($_POST['diceNb']) && isset ($_POST['sideNb']) && isset($_SESSION['name'])) {
        $diceNb = htmlspecialchars($_POST['diceNb']);
        $sideNb = htmlspecialchars($_POST['sideNb']);
        $writer = htmlspecialchars($_SESSION['name']);
        if ((strlen($writer)>0)) {
			$firstLetter = $writer[0];
			if(preg_match('/\w/',$firstLetter)&&(strpos(strtoupper($writer),'SYSTEM ANN')===false)) {
				$total = 0;
				$detail = '(';
				for($i=0; $i<$diceNb; $i++) {
					$res = rand(1,$sideNb);
					$total = $total + $res;
					$detail = $detail . ' ' . $res;
				}
				$detail = $detail . ' )';
				$content = $writer . ' rolled ' . $diceNb . 'D' . $sideNb . ' and got: ' . $total . ' ' . $detail;
				$send = true;
			}
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
				VALUES (NOW(), :content, 'Dice roller', :ip)";

			$request = $bdd->prepare($query);
			$request->execute(array(':content' => $content, ':ip' => $_SERVER['REMOTE_ADDR']));
		}
    }
	include 'kill_connection.php';
?>