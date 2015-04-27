<?php

	try
	{
		$bdd = new PDO('', '', '');
	}
		catch(Exception $e)
	{
		die('Erreur : '.$e->getMessage());
	}

?>