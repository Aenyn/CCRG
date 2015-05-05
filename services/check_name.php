<?php if(preg_match('/\w/',$firstLetter)&&(strpos(strtoupper($writer),'SYSTEM ANN')===false)&&(strpos(strtoupper($writer),'DICE ROLLER')===false)&&(strpos(strtoupper($writer),'KRAKO MANAGER')===false)&&(strpos(strtoupper($writer),'DICE REVEAL')===false)) {
	include 'check_special_name.php';
	if(checkSpecialName($writer, $bdd)!=='F') {
		$send = true;
	}
} ?>