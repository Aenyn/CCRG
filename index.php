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

			function checkMessages() {
				$.ajax({
					url: "services/check_messages.php",
					method: "GET",
					success:function(result) {
						var t = result.split(/[- :]/);
						var curdate = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);					
						t = document.getElementById('date_last_message').innerHTML.split(/[- :]/);
						var olddate = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
						document.getElementById('newdate').innerHTML = curdate;
						if (curdate>olddate) {
							getMessages();
							getAnnonce();
							document.title = 'CCRG (New messages)';
						}
					}
				})
			}

			function sendMessage(name, message) {
				$.ajax({
					url: "services/send_message.php",
					method: "POST",
					data: {writer:name, content:message},
					success:function() {
						getMessages();
					}
				})
			}

			function getMessages() {
				$.ajax({
					url: "services/get_messages.php",
					success:function(result) {
						if(result=='kick') {
							window.alert('Vous avez été kické de ce channel');
							window.location.replace('http://www.staggeringbeauty.com/');
						}
						document.getElementById('main').innerHTML=result;
						document.getElementById('main').scrollTop = document.getElementById('main').scrollHeight;
					}
				});
			}
			
			function getAnnonce() {
				$.ajax({
					url: "services/get_annonce.php",
					success:function(result) {
						document.getElementById('announcement').innerHTML=result;
					}
				});
			}
			
			function getNewMessages() {
				var date = document.getElementById('date_last_message').innerHTML;
				$.ajax({
					url: "services/get_messages.php",
					data:{date:date},
					success:function(result) {
						var toRemove = document.getElementById('date_last_message');
						toRemove.parentNode.removeChild(toRemove);
						document.getElementById('main').innerHTML = document.getElementById('main').innerHTML + result;
						document.getElementById('main').scrollTop = document.getElementById('main').scrollHeight;
					}
				});
			}
			
			function getOnline() {
				$.ajax({
					url: "services/get_online.php",
					data: {name:username},
					success:function(result) {
						document.getElementById('online').innerHTML=result;
					}
				});
			}

			function submitForm() {
				parseCommand();
				document.getElementById('message_prompt').value = '';
				return false;
			}
			
			function restoreTitle() {
				document.title = 'CCRG';
			}
			
			function parseCommand() {
				var commandPattern = /^\//;
				var text = document.getElementById('message_prompt').value;
				if(commandPattern.test(text)) {
					var nick = /\/nick \w{1,25}/;
					var kick = /\/kick (\w{1,25}) (\w+)/;
					var ban = /\/ban (\w{1,15})/;
					var ip = /\/ip (\w{1,25})/;
					var annonce = /\/annonce (\w+)/;
					var matched = false;
					if(nick.test(text)) {
						matched = true;
						username = text.substring(6);
						$.ajax({
								url: "services/rename.php",
								method : "POST",
								data: {name:username}
						});
						document.getElementById('user_name').innerHTML = username;
					}
					else if(kick.test(text)) {
						matched = true;
						$.ajax({
							url: "services/kick.php",
							data: {user:text.substring(6)},
							method: "POST",
							success: function() {
								sendMessage('System announcement', text.substring(11) + ' has been kicked');
							}
						});
					} else if (ban.test(text)) {
						matched = true;
						$.ajax({
							url: "services/ban.php",
							data: {user:text.substring(4)},
							method: "POST",
							success: function(result) {
								alert(result);
							}
						});
					} else if (ip.test(text)) {
						matched = true;
						$.ajax({
							url: "services/ip.php",
							data: {user:text.substring(3)},
							method: "POST",
							success: function(result) {
								alert(result);
							}
						});
					} else if (annonce.test(text)) {						
						matched = true;
						$.ajax({
							url: "services/set_annonce.php",
							data: {annonce:text.substring(8)},
							method: "POST",
							success: function(result) {
								document.getElementById('announcement').innerHTML = result;
							}
						});
					}
					if (!matched) {
						alert('Commande inconnue');
					}
				} else {
					sendMessage(username, document.getElementById('message_prompt').value);
				}
			}
			<?php if(!isset($_SESSION['name'])) {
			echo "var username = window.prompt('Username', 'Noob');\n
				if(username == null) {\n
					username = 'IMAFAG';\n
				}";
			} else echo "var username = '" . $_SESSION['name'] . "'";?>
			
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
				</form>
			</div>
			<div id="announcement">
				<p>Prochainement ici: une annonce intéressante</p>
			</div>
		</div>
		<div id="online"></div>
		<script src="jquery.js"></script>
		<script>
			document.getElementById('user_name').innerHTML = username;
			document.getElementById('online').innerHTML = "<p>" + username + "</p>" + document.getElementById('online').innerHTML;
			document.getElementById('message_prompt').focus();
			document.onmousemove = restoreTitle;
			document.onmousedown = restoreTitle;
			document.onkeypress = restoreTitle;
			<?php if(!isset($_SESSION['name'])) {
			echo "$.ajax({
					url: 'services/connexion_std.php',
					method : 'POST',
					data: {name:username},
					success : function() {
						getMessages();
						setInterval(checkMessages, 500);
						getOnline();
						setInterval(getOnline, 5000);
						}						
			});";
			} else {
				echo "getMessages();
						setInterval(checkMessages, 500);
						getOnline();
						setInterval(getOnline, 5000);";
			}?>
		</script>
	</body>
</html>