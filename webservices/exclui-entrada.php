<?php

	$id      = $_POST['id'];

	require_once('../DAL/mysql.php');
	$db = new database();
	
	$query = "delete from tb_entradas where nu_entrada = ".$id;

	$result = $db->sql_query($query);
	if($db->sql_affectedrows($result) != 1)
		exit('Erro!');

	$db->desconecta();

?>