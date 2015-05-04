<?php
    session_start();

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);

	include 'connectbdd.php';

    $send = false;
    $message = '';
    $writer = '';


    if(isset($_SESSION['name'])) {
        $writer = htmlspecialchars($_SESSION['name']);
        if ((strlen($writer)>0)) {
			$firstLetter = $writer[0];
			include 'check_name.php';
        }
    }
	
	if(isset($_POST['message'])) {
        $message = htmlspecialchars($_POST['message']);		
		if(strlen($message)==0) {
			$message = 'afk';		
		}
	} else {
		$message = 'afk';		
	}

    if($send) {
		$check = $bdd->prepare("SELECT COUNT(*) as isBanned FROM ccrg_blacklist WHERE ip = :ip AND end_date > NOW()");
		$check->execute(array(':ip' => $_SERVER['REMOTE_ADDR']));
		$check = $check->fetch();
		if($check['isBanned']==0) {				
			$query = "UPDATE ccrg_users SET afk = '1', afk_message = :message
				WHERE name = :writer";

			$request = $bdd->prepare($query);
			$request->execute(array(':message' => $message, ':writer' => $writer));
			
			$query = "INSERT INTO ccrg_messages(date, content, writer, ip)
				VALUES (NOW(), :content, 'System announcement', :ip)";

			$sendMessage = $bdd->prepare($query);
			$sendMessage->execute(array(':content' => $writer . ' is now afk (' . $message . ')', ':ip' => $_SERVER['REMOTE_ADDR']));
		}
    }
	include 'kill_connection.php';
?>