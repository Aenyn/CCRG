<?php	
	session_start();
	try
	{
		$bdd = new PDO('mysql:host=panicotbdd.mysql.db;dbname=panicotbdd', 'panicotbdd', '2007Panicot');
	}
		catch(Exception $e)
	{
		die('Erreur : '.$e->getMessage());
	}
	
	$query = 'SELECT * FROM fablab_posts';
	$results = $bdd->query($query);
	
	$json = array();
	while($res = $results->fetch()) {
		$data = array (
    		(object)array(
    		    'title' => $res['title'],
    		    'date' => $res['date'],
    		    'type' => $res['type'],
    		    'content' => $res['content']
    		)
		);
		$json = array_merge($json, $data);
	}
	$json = json_encode($json);
	echo $json;
?>