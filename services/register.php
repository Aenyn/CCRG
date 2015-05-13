<?php
    session_start();

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);

	include 'connectbdd.php';
	
	function randomString($length = 20) {	// returns a random string for use as salt
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

    $send = false;
    $pass = '';
    $writer = '';


    if(isset($_POST['pass']) && isset($_SESSION['name'])) {
        $pass = $_POST['pass'];
		$_SESSION['pass'] = $pass;
        $writer = htmlspecialchars($_SESSION['name']);
        if ((strlen($pass)>0)&&(strlen($writer)>0)) {
			$firstLetter = $writer[0];
			include 'check_name.php';
        }
    }

    if($send) {
		$check = $bdd->prepare("SELECT COUNT(*) as isBanned FROM ccrg_blacklist WHERE ip = :ip AND end_date > NOW()");
		$check->execute(array(':ip' => $_SERVER['REMOTE_ADDR']));
		$check = $check->fetch();
		if($check['isBanned']==0) {		
			
			
			$salt = substr(bin2hex(openssl_random_pseudo_bytes(20)),0,20);
			//$salt = "aaa";
			//$salt = randomString();
			$passHash = hash("sha256", $pass.$salt);
			
			$query = "INSERT INTO ccrg_special_users(name, salt, permission, pass_hash) VALUES (:name, :salt, 'U', :pass_hash)";
			$request = $bdd->prepare($query);
			$request->execute(array(":name" => $writer, ":salt" => $salt, ":pass_hash" => $passHash));		
		}
    }
	include 'kill_connection.php';
?>