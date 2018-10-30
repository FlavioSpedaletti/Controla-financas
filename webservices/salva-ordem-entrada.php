<?php
	$modo      = $_POST['modo'];
	$rowsArray = $_POST['entrada'];

	$cont = 1;

	require_once('../DAL/mysql.php');
	$db = new database();

	foreach ($rowsArray as $rowId) {
		$query = "update tb_entradas
			      set nu_ordem = " . $cont . "
                  where nu_entrada = " . $rowId;
		$result = $db->sql_query($query);
		//if($db->sql_affectedrows($result) != 1)
		//	exit('Erro!');
		$cont = $cont + 1;

		echo '';
	}
?>