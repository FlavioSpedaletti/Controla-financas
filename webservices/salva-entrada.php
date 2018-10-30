<?php

	function converter_data($strData) {
		// Recebemos a data no formato: dd/mm/aaaa
		// Convertemos a data para o formato: aaaa-mm-dd
		if ( preg_match("#/#",$strData) == 1 ) {
			$strDataFinal = "'";
			$strDataFinal .= implode('-', array_reverse(explode('/',$strData)));
			$strDataFinal .= "'";
		}
		return $strDataFinal;
	}

	$id      = $_POST['id'];
	$value   = $_POST['value'];
	$acao    = $_POST['acao'];
	$periodo = $_POST['periodo'];
	$insert  = $id == -1;

	require_once('../DAL/mysql.php');
	$db = new database();
	
	switch ($acao) {
    case 'desc-tipo':
        $campo = 'nu_tipo';
		$valor = $value;
        break;
    case 'desc-entrada':
        $campo = 'tx_descricao';
		$valor = "'".htmlentities($value,ENT_QUOTES,'UTF-8')."'";
		$value = htmlentities($value,ENT_QUOTES,'UTF-8'); //usado para impedir que retorno, por exemplo, texto em negrito (<b>)
        break;
    case 'valor':
        $campo = 'nu_valor';
		$valor = floatval(str_replace(',','.',$value));
        break;
	case 'data':
        $campo = 'dt_entrada';
		if($value == "") {
			$valor = "CURDATE()";
			$value = date("d/m/Y");
		}
		else
			$valor = converter_data($value);
        break;
	}

	if($insert) {
		$query = "insert into tb_entradas
	    			 (".$campo.", nu_periodo, dt_entrada, dt_modificacao, fl_rand, nu_ordem)
					values (".$valor.", ".$periodo.", CURDATE(), NOW(), RAND()*99, 9999)";
	}
	else {
		$query = "update tb_entradas
	    			 set ".$campo." = ".$valor.", dt_modificacao = NOW(), fl_rand = RAND()*99
				   where nu_entrada = ".$id;
	}

	$result = $db->sql_query($query);
	if($db->sql_affectedrows($result) != 1)
		exit('Erro!');

	if($insert)
		$insertedid = $db->sql_insertedid();
	
	if($acao=='desc-tipo') {
		$query = "select tx_descricao
                        from tb_tipos_entrada
    			       where nu_tipo = ".$valor;
		$result = $db->sql_query($query);
		$row = mysql_fetch_row($result);
		print ($insert ? $insertedid."|" : "") . htmlentities($row[0]);
	}
	else if($acao=='valor') {
		print ($insert ? $insertedid."|" : "") . number_format($valor,2,',','.');
	}
	else
		print ($insert ? $insertedid."|" : "") . $value;


	$db->desconecta();

?>