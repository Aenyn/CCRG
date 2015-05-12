<?php 
	$writer = str_replace("\0", "", $writer);
	if(preg_match('/\w/',$firstLetter)
	&&(strpos(strtoupper($writer),'SYSTEM ANN')===false)
	&&(strpos(strtoupper($writer),'DICE ROLLER')===false)
	&&(strpos(strtoupper($writer),'KRAKO MANAGER')===false)
	&&(strpos(strtoupper($writer),'DICE REVEAL')===false)
	&&(strpos(strtoupper($writer),'CLAUSSE MANAGER')===false)
	&&(strpos(strtoupper($writer),' ')===false)
	&&(strpos(strtoupper($writer),' ')===false)) {
		
		include 'check_special_name.php';
		if(checkSpecialName($writer, $bdd)!=='F') {
			$send = true;
		}
		
} ?>