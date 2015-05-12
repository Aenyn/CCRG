<?php
    session_start();

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);

	include 'connectbdd.php';

    $send = false;
	$data = '';
	
	if(isset($_POST['data'])) {
		$data = $_POST['data'];
	}
	
	if($data==="dice") {
		if(isset($_SESSION['diceRoll'])) {
			echo $_SESSION['diceRoll'];
		}
	}
	
	include 'kill_connection.php';
?>