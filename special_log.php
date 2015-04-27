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
		<script>
		
			var username = window.prompt("Username", "Noob");
			var pass = window.prompt("pass");
			if(username == null) {
				username = 'IMAFAG';
			}
			$.ajax({
				url:'services/connexion_spe.php',
				date: {name:username, pass:pass},
				success: function() {
					
				}
			});
			
		</script>
		<script src="jquery.js"></script>
	</body>
</html>