<?php 
	session_start();
	if(isset($_SESSION['name'])) {
		unset($_SESSION['name']);
	}
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/standard.css">
		<title>CCRG</title>
	</head>
	<body>
		<div >
			<form action="services/login.php" method="POST">
				Username : <input type="text" id="login" name="name" autocomplete="off" maxlength="255"/>
			</form>
		</div>
	</body>
	<script>
		document.getElementById('login').focus();
	</script>
</html>