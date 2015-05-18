<?php
    session_start();

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);

	include 'connectbdd.php';
	
    if(isset($_SESSION['name'])) {
		$check = $bdd->prepare("SELECT kicked FROM ccrg_users WHERE name = :name");
		$check->execute(array(':name' => substr($_SESSION['name'], 0, 25)));
		$check = $check->fetch();
		if($check['kicked']!=0) {
			$q = $bdd->prepare("UPDATE ccrg_users SET kicked = 0 WHERE name = :name");
			$q->execute(array(':name' => substr($_SESSION['name'], 0, 25)));
			echo "kick";
		}  else {
			if(isset($_POST['date'])) {
				$date = $_POST['date'];
				$query = $bdd->prepare("SELECT writer, date, content 
					FROM ccrg_messages
					WHERE date > :date
					ORDER BY date ASC");
				$query->execute(array(':date' => $_POST['date']));
				while($messages=$query->fetch()) {
					echo "<p>". substr($messages['date'], 11, 5) . ' ' . $messages['writer'] . "> " . $messages['content']. " </p>";
					$date = $messages['date'];
				}
				echo "<div class='hidden' id='date_last_message'>" . $date . "</div>";
			} else {	
				$query = $bdd->query("SELECT writer, date, content 
					FROM 
					(
						SELECT writer, date, content
						FROM ccrg_messages
						WHERE date > DATE_SUB(NOW(), INTERVAL 2 DAY)
						ORDER BY date DESC
						LIMIT 1000
					) SQ
					ORDER BY date ASC");
				while($messages=$query->fetch()) {
					echo "<p>". substr($messages['date'], 11, 5) . ' ' . $messages['writer'] . "> " . $messages['content']. " </p>";
					$date = $messages['date'];
				}
				echo "<div class='hidden' id='date_last_message'>" . $date . "</div>";
			}	
		}
	}
	include 'kill_connection.php';
?>