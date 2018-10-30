<?php

	$id         = $_GET['id'];
	$rendimento = $_GET['fg_rendimento'];

	require_once('../DAL/mysql.php');
	$db = new database();
	$rows = array();

	$query = "select nu_tipo, tx_descricao,
				case tx_descricao when 'Outros' then '1'
                        else '0' END ord
                from tb_tipos_entrada
               where fg_rendimento = ".$rendimento."
			order by ord, tx_descricao";
	
	$result = $db->sql_query($query);

	while($r = mysql_fetch_assoc($result)) {
    	$rows[$r['nu_tipo']] = htmlentities($r['tx_descricao']);
	}

	//$array['selected'] = $id;
	header('Content-type: application/json');
	print json_encode($rows);

	$db->desconecta();
?>