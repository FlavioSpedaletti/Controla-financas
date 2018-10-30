<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Controla Finan&ccedil;as</title>
<link href="css/style.css?s" rel="stylesheet" media="all" />
</head>
<body>

	<?php include 'header.php'; ?>

	<div id="wrapper">
		<h2>Selecione um per&iacute;odo</h2>
			
		<?php
			require_once('DAL/mysql.php');
			$db = new database();
			
			$query = "select *
                        from tb_periodos
                       where nu_usuario = " . $_SESSION['exp_user']['nu_usuario'] . "
                    order by dt_inicio";
			$result = $db->sql_query($query);
			while($row = mysql_fetch_assoc($result))
			{
				$nu_periodo_cod = base64_encode($row['nu_periodo']);
				echo "<a href='entrada.php?p=".$nu_periodo_cod."'>".$row['tx_descricao']."</a><br/>";
			}

			$db->desconecta();
		?>
	</div>

	<?php include 'footer.php'; ?>

</body>
</html>
