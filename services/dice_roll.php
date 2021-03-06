<?php
    session_start();

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);

	include 'connectbdd.php';

    $send = false;
	$diceNb = '';
    $sideNb = '';
    $writer = '';
	$params = '';
	$bonus = 0;
	$content = '';
	
	if(isset($_POST['params'])) {
		$params = $_POST['params'];
	}
	
	if($params[0]==' ') {
		$params = 'sum detail ';
	}
	
	if($params=='hidden ') {
		$params = 'sum detail hidden ';
	}

	$resultArray[] = array();
    if(isset($_POST['diceNb']) && isset ($_POST['sideNb']) && isset($_SESSION['name'])) {
        $diceNb = htmlspecialchars($_POST['diceNb']);
        $sideNb = htmlspecialchars($_POST['sideNb']);
        $writer = htmlspecialchars($_SESSION['name']);
        if ((strlen($writer)>0)) {
			$firstLetter = $writer[0];
			include 'check_name.php';
			if($send) {
				$send = true;				
				$total = 0;
				$high = 0;
				$low = $sideNb+1;
				$detail = '(';
				for($i=0; $i<$diceNb; $i++) {
					$res = mt_rand(1,$sideNb);
					$resultArray[] = $res;
					if($res>$high) {
						$high = $res;
					}
					if($res<$low) {
						$low = $res;
					}
					$total = $total + $res;
					$detail = $detail . ' ' . $res;
				}
				$detail = $detail . ' )';
			}
        }
    }
	
	if(isset($_POST['bonus'])) {
		$bonus =$_POST['bonus'];
		if($bonus>0) {
			$total += $bonus;
			$content = $content . '+' . $bonus;
		} else if ($bonus <0) {
			$total += $bonus;
			$content = $content . $bonus;
		}
	}
	
	if(strpos($params, 'sum ')!==false) {		
		$content = $content . ' total: ' . $total;
	}
	
	if(strpos($params, 'high ')!==false) {		
		$content = $content . ' high: ' . $high;
	}
	
	if(strpos($params, 'low ')!==false) {		
		$content = $content . ' low: ' . $low;
	}
	
	if(strpos($params, 'detail ')!==false) {		
		$content = $content . ' ' . $detail;
	}
	
	// repetition
	$repeatPattern = '/obj (<|>|<=|>=)?(\d+) (total|tot|each)/';
	$match = array();
	if(preg_match($repeatPattern, $params, $match)==1) {
		if($match[3]=='each') {
			$successNb = 0;
			foreach($resultArray as $roll) {
				if($match[1]=='>') {
					if($roll>$match[2]) {
						$successNb++;
					}
				} else if ($match[1]=='<'){
					if($roll<$match[2]) {
						$successNb++;
					}
				} else if ($match[1]=='>='){
					if($roll>=$match[2]) {
						$successNb++;
					}
				} else {
					if($roll<=$match[2]) {
						$successNb++;
					}
				}
			}
			$content = $content . ' diff: ' . $match[2] . ' success: ' . $successNb;
			echo $match[0];
		}
	}
	
	if(strpos($params, 'hidden ')!==false) {
		$_SESSION['diceRoll'] = $content;
		$content = ' rolled ' . $diceNb . 'D' . $sideNb . $content;
		$hidden = 'You ' . $content;
		$content = $writer . ' rolled ' . $diceNb . 'D' . $sideNb;
	} else {
		$content = ' rolled ' . $diceNb . 'D' . $sideNb . $content;
		$content = $writer . $content;
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
	
	if(strpos($params, 'hidden ')!==false) {
		echo $hidden;
	}
	
	include 'kill_connection.php';
?>