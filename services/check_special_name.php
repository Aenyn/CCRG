<?php 
	function checkSpecialName($writer, $bdd) {	// returns the permission of the user or F if the user has failed to login
		$query = 'SELECT name, permission, salt, pass_hash FROM ccrg_special_users';
		$req = $bdd->prepare($query);
		$req->execute();
		while($res = $req->fetch()) {
			$nameCheck = $res['name'];
			$passCheck = $res['pass_hash'];
			$salt = $res['salt'];
			if(strpos(strtoupper($nameCheck), strtoupper($writer))!==false) {
				if(isset($_SESSION['pass'])) {
					$pass = $_SESSION['pass'];
					$passHash = hash("sha256", $pass.$salt);
					if($passHash===$passCheck) {
						return $res['permission'];
					} else {
						return 'F';
					}
				} else {
					return 'F';
				}
			}		
		}
		return 'U';
	}
 ?>