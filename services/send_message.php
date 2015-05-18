<?php
    session_start();

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);

	include 'connectbdd.php';

    $send = false;
    $content = '';
    $writer = '';


    if(isset($_POST['content']) && isset($_SESSION['name'])) {
        $content = htmlspecialchars($_POST['content']);
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
		
			$query = "INSERT INTO ccrg_messages(date, content, writer, ip)
				VALUES (NOW(), :content, :writer, :ip)";

			$request = $bdd->prepare($query);
			
			if((substr($content,0,7)==="http://")||(substr($content,0,8)==="https://")) {
				$content = "<a href='" . $content ."'>". $content . "</a>";
				if(strlen($content)>250) {
					$content = substr($content, 0, 250);
					$content = substr($content, 0, strlen($link)-4) . '</a>';
				}
			}
			
			$request->execute(array(':content' => $content, ':writer' => $writer, ':ip' => $_SERVER['REMOTE_ADDR']));
		}
    }
	include 'kill_connection.php';
?>