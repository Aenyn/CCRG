<?php 
	session_start();
	if(!isset($_SESSION['name'])) {
		header('Location: http://www.panicot.fr/ccrg/login.php');
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
		<script src='js/ccrg_exp.js'></script>
		<script>	
			<?php echo "var username = '" . $_SESSION['name'] . "'";?>
			
		</script>
		<div id="newdate" class="hidden"></div>
		<div id="left">
			<div id="hide_scroll">
				<div id="main">

				</div>
			</div>
			<div id="prompt">
				<form onsubmit="return submitForm();">
				<span id="user_name"></span> > <input type="text" id="message_prompt" autocomplete="off" maxlength="255"/>
				<!--input type="checkbox" onclick="toggleLock()" /-->
				</form>
			</div>
			<div id="announcement">
				<p></p>
			</div>
		</div>
		<div id="online"></div>
		<script src="jquery.js"></script>
		<script>
			var locked = false;
			document.getElementById('user_name').innerHTML = username;
			document.getElementById('online').innerHTML = "<p>" + username + "</p>" + document.getElementById('online').innerHTML;
			document.getElementById('message_prompt').focus();
			document.onmousemove = restoreTitle;
			document.onmousedown = restoreTitle;
			document.onkeypress = restoreTitle;
			<?php 
			echo	" var messages = [];
					getMessages();
					setInterval(checkMessages, 100);
					getOnline();
					setInterval(getOnline, 1000);
					getAnnonce()";
			?>
		</script>
	</body>
</html>