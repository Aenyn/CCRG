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
	
	$fund = '';
	
	if(isset($_POST['fund'])) {
		$fund = $_POST['fund'];
	}
	
	$query = 'SELECT fund_name, fund_value FROM fund_values WHERE fund_name=:fund';
	$req = $bdd->prepare($query);
	$req->execute(array(
		'fund' => $fund
		));
	$res = $req->fetch();
	$data = array (
		(object)array(
			'fund_name' => $res['fund_name'],
			'fund_value' => $res['fund_value']
		)
	);
	
	$json = json_encode($data);
	echo $json;
?>