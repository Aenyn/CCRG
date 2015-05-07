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
	unsetAfk();
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
			if(!locked) {
				document.getElementById('main').scrollTop = document.getElementById('main').scrollHeight;
			}
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
			if(!scrollLock) {
				document.getElementById('main').scrollTop = document.getElementById('main').scrollHeight;
			}
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
		var kick = /\/kick (\w{1,25})/;
		var ban = /\/ban (\w{1,15})/;
		var ip = /\/ip (\w{1,25})/;
		var annonce = /\/annonce ([\w\W]+)/;
		var roll = /\/roll ([0-9]+)[dD ]([0-9]+)[\+, ]?(\d+)? ?([\w, ]*)/;
		var krako = /\/krako (\w+)/;
		var krakount = /\/krakount (\w+)/;
		var clausse = /\/clausse (\w+)/;
		var clausseCount = /\/clount (\w+)/;
		var link = /\/link ([\w,\.,\/,:,\-,\+,\\,\?,\&,=]+) ?([\w,\.,\/, ]+)?/;
		var scroll = /\/scroll/;
		var noscroll = /\/noscroll/;
		var reveal = /\/reveal/;
		var afk = /\/afk ?([ ,\w]*)/;
		var noAfk = /\/not_afk/;
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
			match = kick.exec(text);			
			$.ajax({
				url: "services/kick.php",
				data: {user:match[1]},
				method: "POST"
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
			match = annonce.exec(text);
			$.ajax({
				url: "services/set_annonce.php",
				data: {annonce:match[1]},
				method: "POST"
			});
		} else if (roll.test(text)) {						
			matched = true;
			match = roll.exec(text);
			$.ajax({
				url: "services/dice_roll.php",
				data: {diceNb:match[1], sideNb:match[2], bonus:match[3], params:(match[4]+' ')},
				method: "POST",
				success: function(result) {
					if(result.length>0) {
						alert(result);
					}
				}
			});
		} else if (krako.test(text)) {						
			matched = true;
			match = krako.exec(text);
			$.ajax({
				url: "services/krako.php",
				data: {user:match[1]},
				method: "POST"
			});
		} else if (krakount.test(text)) {						
			matched = true;
			match = krakount.exec(text);
			$.ajax({
				url: "services/get_krako.php",
				data: {name:match[1]},
				method: "POST",
				success:function(result) {
					alert(result);
				}
			});
		} else if (clausse.test(text)) {						
			matched = true;
			match = clausse.exec(text);
			$.ajax({
				url: "services/clausse.php",
				data: {user:match[1]},
				method: "POST"
			});
		} else if (clausseCount.test(text)) {						
			matched = true;
			match = clount.exec(text);
			$.ajax({
				url: "services/get_clausse.php",
				data: {name:match[1]},
				method: "POST",
				success:function(result) {
					alert(result);
				}
			});
		} else if (link.test(text)) {						
			matched = true;
			match = link.exec(text);
			$.ajax({
				url: "services/link.php",
				data: {link:match[1], title:match[2]},
				method: "POST"
			});
		} else if (scroll.test(text)) {						
			matched = true;
			locked = false;
		} else if (noscroll.test(text)) {						
			matched = true;
			locked = true;
		} else if (reveal.test(text)) {						
			matched = true;
			$.ajax({
				url: "services/reveal_dice.php",
				success: function(result) {
					if(result.length>0) {
						alert(result);
					}
				}
			});
		} else if (afk.test(text)) {						
			matched = true;
			setAfk(afk.exec(text)[1]);
		} else if (noAfk.test(text)) {						
			matched = true;
			unsetAfk();
		}

		if (!matched) {
			alert('Commande inconnue');
		}
	} else {
		sendMessage(username, document.getElementById('message_prompt').value);
	}
}

function setAfk(message) {
	$.ajax({
		url: "services/afk.php",
		method:"POST",
		data:{message:message},
		success: function(result) {
			if(result.length>0) {
				alert(result);
			}
		}
	});
}

function unsetAfk() {
	$.ajax({
		url: "services/not_afk.php",
		method:"POST",
		success: function(result) {
			if(result.length>0) {
				alert(result);
			}
		}
	});
}

function toggleLock() {
	locked = !locked;
}