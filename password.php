<?php 
	session_start();
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
				Password : <input type="text" id="login" name="pass" autocomplete="off" maxlength="255"/>
			</form>
		</div>
	</body>
	<script>
		document.getElementById('login').focus();
	</script>
</html>