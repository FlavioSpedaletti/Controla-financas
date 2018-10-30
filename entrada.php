<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Controla Finan&ccedil;as</title>
	<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
	<script type="text/javascript" src="js/jquery.jeditable.mini.js"></script>
	<script type="text/javascript" src="js/jquery.jeditable.masked.js"></script>
	<script type="text/javascript" src="js/jquery.jeditable.float.js?"></script>
	<script type="text/javascript" src="js/jquery.maskedinput-1.3.min.js"></script>
	<script type="text/javascript" src="js/jquery.tablesorter-update.js?"></script>
	<script type="text/javascript" src="js/jquery.calculation.min.js"></script>
	<script type="text/javascript" src="js/jquery.number_format.js"></script>
	<script type="text/javascript" src="js/entrada.js?s"></script>
	<link href="css/entrada.css?s" rel="stylesheet" media="all" />

	<style>
		.ui-state-highlight {
			height: 3.5em;
			margin:5px 0:
		}
		.ui-state-highlight td {
			background: #FFFBD8;
			border: 1px solid #FFF8AD;
		}
	</style>

	<script type="text/javascript">
	$(document).ready(function(){

		// Return a helper with preserved width of cells
		var fixHelper = function(e, ui) {
		    ui.children().each(function() {
		        $(this).width($(this).width());
		    });
		    return ui;
		};

		$(function() {
			$("table tbody").sortable({
				placeholder: "ui-state-highlight",
				helper: fixHelper,
				opacity: 1.0,
				cursor: 'move',
				/*axis: 'y',*/
				handle: 'img#imgMove',
				start: function (event, ui) {
		            ui.placeholder.html("<td colspan='6'>&nbsp;</td>");
		        },
				update: function() {
					var tipo = $(this).parent().attr("id");
					var order = $(this).sortable("serialize") + '&tipo=' + tipo;
					$.post("webservices/salva-ordem-entrada.php", order, function(theResponse){
					});
				}
			});
			$("#tbRendimentos tbody").disableSelection();
		});

	});
	</script>

</head>
<body>
	<?php include 'header.php'; ?>
	<div id="wrapper">
		<?php

			require_once('DAL/mysql.php');
			
			function ValidaPeriodo() {
				$nu_periodo_enc = $_GET['p'];
				$db = new database();

				if(empty($nu_periodo_enc))
					return "";
				
				$nu_periodo = base64_decode($nu_periodo_enc);
				if(!is_numeric($nu_periodo))
					return "";
	
				$query = "select * from tb_periodos
						   where nu_usuario = ".$_SESSION['exp_user']['nu_usuario'].
						 "   and nu_periodo = ".mysql_real_escape_string($nu_periodo);
				
				$result = $db->sql_query($query);
				if($db->sql_numrows($result) != 1)
					return "";
				$db->desconecta();

				return array(mysql_result($result, 0, 'tx_descricao'),$nu_periodo);
			}

			function RetornaFiltro($tipo) {

				$fg_rendimento = ($tipo == "Rendimentos" ? 1 : 0);

				$query = "select nu_tipo, tx_descricao
			                from tb_tipos_entrada
			               where fg_rendimento = ".$fg_rendimento;
				
				$db = new database();
				$result = $db->sql_query($query);
			
				$filtro .= "<select 'filtro".$tipo."' value='' id='filtro".$tipo."'>
						<option value=''></option>";
				while($r = mysql_fetch_assoc($result)) {
			    	$row = $r['tx_descricao'];
					$filtro .= "<option value='".$row."'>".$row."</option>";
				}
				$filtro .= "</select>";

				return $filtro;
			}

			function RetornaTabela($tipo, $nu_periodo) {

				$fg_rendimento = ($tipo == "Rendimentos" ? 1 : 0);

				$tabela = "<h2>".$tipo."</h2>";
				$query = "select e.nu_entrada, t.tx_descricao tx_descricao_tipo,
	                             e.tx_descricao tx_descricao_entrada, e.nu_valor, 
								 DATE_FORMAT(e.dt_entrada, '%d/%m/%Y') dt_entrada
	                        from tb_entradas e, tb_tipos_entrada t
	                       where e.nu_tipo = t.nu_tipo
	                         and t.fg_rendimento = $fg_rendimento
							 and e.nu_periodo = ".$nu_periodo."
	                    order by e.nu_ordem, e.dt_modificacao, e.dt_entrada, t.tx_descricao, e.tx_descricao";
	
				$db = new database();
				$result = $db->sql_query($query);

				$tabela .= "<table id='tb".$tipo."'>";
				$tabela .= "<thead>
							<tr>
							 <th width='190' valign='top'>Tipo<br/>".RetornaFiltro($tipo)."</th>
							 <th valign='top'>Entrada</th>
							 <th align='center' width='70' valign='top'>Valor</th>
							 <th width='100' valign='top'>Data</th>
							 <th width='10'>&nbsp;</th>
							 <th width='10'>&nbsp;</th>
						    </tr>
						   </thead>
						   <tbody>";
				while($row = mysql_fetch_assoc($result))
				{
					$tabela .= "<tr id='entrada_".$row['nu_entrada']."'>
								   <td id=".$row['nu_entrada']." class='edit index desc-tipo-".$tipo."'>".$row['tx_descricao_tipo']."</td>
								   <td id=".$row['nu_entrada']." class='edit desc-entrada'>".$row['tx_descricao_entrada']."</td>
								   <td align='right' id=".$row['nu_entrada']." class='edit valor'>".number_format($row['nu_valor'],2,',','.')."</td>
								   <td id=".$row['nu_entrada']." class='edit data'>".$row['dt_entrada']."</td>
								   <td id=".$row['nu_entrada']." class='delete'><img src='css/img/icon_delete.png'></td>
								   <td id=".$row['nu_entrada']." class='move'><img id='imgMove' src='css/img/icon_move.png'></td>
							    </tr>";
				}
				$tabela .= "</tbody>
						    <tfoot>
								<tr>
								   <td colspan='6'><img id='nova-entrada".$tipo."' src='css/img/icon_add.png'></td>
							    </tr>
								 <tr>
									<td>Total:</td>
									<td>&nbsp;</td>
									<td align='right' id='".strtolower($tipo)."-soma'></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								 </tr>";
				$tabela .= " </tfoot>
						   </table>";

				$db->desconecta();

				return $tabela;
			}

			$periodo = ValidaPeriodo();
			if($periodo[0] == '')
				exit("Per&iacute;odo n&atilde;o encontrado!");

			echo "<input type='hidden' id='periodo' value='" . $periodo[1] . "'>";

			//SUCESSO
			echo "<div id='periodo'>Per&iacute;odo " . $periodo[0] . "</div>
				  <div id='total-soma'></div>";

			echo RetornaTabela("Rendimentos",$periodo[1]);
			echo RetornaTabela("Despesas",$periodo[1]);

		?>
	</div>
	<?php include 'footer.php'; ?>
</body>
</html>
