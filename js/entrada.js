function retornaDataAtual() {
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1;//January is 0!
	var yyyy = today.getFullYear();
	if(dd<10){dd='0'+dd;}
	if(mm<10){mm='0'+mm;}
	return dd+'/'+mm+'/'+yyyy;
}


function somaValoresTabela(table) {
	var soma = $(table).sum();
	soma = $().number_format(soma, { numberOfDecimals:2,
 										 decimalSeparator: ',',
 										 thousandSeparator: '.',
 										 symbol: ''
 										});
	return soma;
}

function somaValoresTabelas() {
	$("#total-soma").calc(
		"rendimentos - despesas",
		{
			rendimentos: $("#rendimentos-soma"),
			despesas: $("#despesas-soma")
		},
		function (s){
			// return the number as a dollar amount
			return 'R$ ' + $().number_format(s, { numberOfDecimals:2,
		 										 decimalSeparator: ',',
		 										 thousandSeparator: '.',
		 										 symbol: ''
		 										});
		}
	);
}

function recalculaTotais() {
	$("#rendimentos-soma").text(somaValoresTabela($('#tbRendimentos tr td:visible.valor')));
	$("#despesas-soma").text(somaValoresTabela($('#tbDespesas tr td:visible.valor')));
	somaValoresTabelas();
}

function ordenaDT() {
	if($("#tbRendimentos tbody").has("tr").length > 0) {
		$("#tbRendimentos").tablesorter( {
			 //sortList: [[3,0], [0,0], [1,0]],
			 sortList: [[3,0]], //ordena apenas pela data, pq ao ordenar, o campo editável do jeditable perde o foco, e sendo assim é melhor diminuir ao máximo as chamadas de ordenação
			 locale: 'en'
		});
	}

	if($("#tbDespesas tbody").has("tr").length > 0) {
		$("#tbDespesas").tablesorter( {
			 //sortList: [[3,0], [0,0], [1,0]],
			 sortList: [[3,0]], //ordena apenas pela data, pq ao ordenar, o campo editável do jeditable perde o foco, e sendo assim é melhor diminuir ao máximo as chamadas de ordenação
			 locale: 'en'
		});
	}
}

function atribuiNovoInsertedID(td, valor)
{
	var array = valor.split('|');
	if(array.length > 1) {
		var texto = td.text(); //faço isso pq a acentuação já está ok na célula
		var tr = td.parent();
		tr.attr("id","entrada_"+array[0]);
		tr.children().attr("id",array[0]);
		td.text(texto.substring(texto.indexOf("|")+1));
		
		var t = tr.find('td:eq(0)').text().toLowerCase();
		$("<td class='indexColumn'></td>")
			.hide().text(t).appendTo(tr);

	}
}

function BindEditable() {
	$('.desc-tipo-Rendimentos').editable('webservices/salva-entrada.php', {
		 loadurl   : 'webservices/carrega-tipos-entrada.php',
		 loaddata  : { fg_rendimento: 1 },
		 type      : 'select',
	     indicator : '<img src="img/indicator.gif">',
	     tooltip   : 'Clique para editar',
	     //submit    : 'OK',
		 submitdata: { acao: "desc-tipo", periodo: $('#periodo').val() },
		 placeholder: 'CLIQUE AQUI PARA ESCOLHER',
		 callback  : function(value, settings) {
						atribuiNovoInsertedID($(this), value);
	     				},
		 onblur    : 'submit'
	 });
	
	$('.desc-tipo-Despesas').editable('webservices/salva-entrada.php', {
		 loadurl   : 'webservices/carrega-tipos-entrada.php',
		 loaddata  : { fg_rendimento: 0 },
		 type      : 'select',
	     indicator : '<img src="img/indicator.gif">',
	     tooltip   : 'Clique para editar',
	     //submit    : 'OK',
		 submitdata: { acao: "desc-tipo", periodo: $('#periodo').val() },
		 placeholder: 'CLIQUE AQUI PARA ESCOLHER',
		 callback  : function(value, settings) {
						atribuiNovoInsertedID($(this), value);
	     				},
		 onblur    : 'submit'
	 });
	
	$('.desc-entrada').editable('webservices/salva-entrada.php', {
	     indicator  : '<img src="img/indicator.gif">',
	     tooltip    : 'Clique para editar',
		 select     : true,
	     //submit     : 'OK',
		 submitdata : { acao: "desc-entrada", periodo: $('#periodo').val() },
		 placeholder: '',
		 callback   : function(value, settings) {
						atribuiNovoInsertedID($(this), value);
	     				},
		 onblur    : 'submit'
	 });
	
	$('.valor').editable('webservices/salva-entrada.php', {
		 type		: 'float',
		 indicator  : '<img src="img/indicator.gif">',
	     tooltip    : 'Clique para editar',
		 select     : true,
	     //submit     : 'OK',
		 submitdata : { acao: "valor", periodo: $('#periodo').val() },
		 placeholder: '0,00',
		 cssclass   : 'valor',
		 callback   : function(value, settings) {
						atribuiNovoInsertedID($(this), value);
						recalculaTotais();
	     				},
		 onblur    : 'submit'
	     });
	
	$('.data').editable('webservices/salva-entrada.php', {
		 type		: 'masked',
		 mask		: '99/99/9999',
		 indicator  : '<img src="img/indicator.gif">',
	     tooltip    : 'Clique para editar',
	     //submit     : 'OK',
		 select     : true,
		 submitdata : { acao: "data", periodo: $('#periodo').val() },
		 placeholder: '',
		 callback   : function(value, settings) {
						atribuiNovoInsertedID($(this), value);
						ordenaDT();
	     				},
		 onblur    : 'submit'
	     });
}

$(document).ready(function(){

	//Define formatação padrão 99.999,99 p/ efetuar os cálculos
	$.Calculation.setDefaults({
		// a regular expression for detecting European-style formatted numbers
		reNumbers: /(-|-\$)?(\d+(\.\d{3})*(,\d{1,})?|,\d{1,})?/g
		// define a procedure to convert the string number into an actual usable number
		, cleanseNumber: function (v){
			// cleanse the number one more time to remove extra data (like commas and dollar signs)
			// use this for European numbers: v.replace(/[^0-9,\-]/g, "").replace(/,/g, ".")
			return v.replace(/[^0-9,\-]/g, "").replace(/,/g, ".");
		}
	});

	//adiciona uma coluna de índice com o conteúdo da primeira td (pode ser feito com mais de um campo)
	$("table tbody tr:has(td.index)").each(function(){
		var t = $(this).find('td:eq(0)').text().toLowerCase();
		$("<td class='indexColumn'></td>")
			.hide().text(t).appendTo(this);
	});

	//Filtra rendimentos
	$("#filtroRendimentos").change(function(){
		var s = $(this).val().toLowerCase();
		if(s=='') { $("#tbRendimentos tbody tr:hidden").show(); return; }
		//hide all rows.
		$("#tbRendimentos tbody tr:visible").hide();
		$("#tbRendimentos tbody tr:hidden .indexColumn:contains('" + s + "')").filter(function(){ if($.trim($(this).text()) == s) return true; }).parent().show();
		recalculaTotais();
	});

	//Filtra despesas
	$("#filtroDespesas").change(function(){
		var s = $(this).val().toLowerCase();
		if(s=='') { $("#tbDespesas tbody tr:hidden").show(); return; }
		//hide all rows.
		$("#tbDespesas tbody tr:visible").hide();
		$("#tbDespesas tbody tr:hidden .indexColumn:contains('" + s + "')").filter(function(){ if($.trim($(this).text()) == s) return true; }).parent().show();
		recalculaTotais();
	});

	$("#nova-entradaRendimentos").click(function(){
		if($("#tbRendimentos tbody").has("tr").length > 0) {
			$("#tbRendimentos tbody tr:last").after("<tr>" +
													   "<td id='-1' class='edit index desc-tipo-Rendimentos'></td>" +
													   "<td id='-1' class='edit desc-entrada'></td>" +
													   "<td align='right' id='-1' class='edit valor'></td>" +
													   "<td id='-1' class='edit data'></td>" +
													   "<td id='-1' class='delete'><img id='imgMove' src='css/img/icon_delete.png'></td>" +
													   "<td id='-1' class='move'><img id='imgMove' src='css/img/icon_move.png'></td>" +
												    "</tr>");
			}
		else {
			$("#tbRendimentos tbody").append("<tr>" +
										   "<td id='-1' class='edit index desc-tipo-Rendimentos'></td>" +
										   "<td id='-1' class='edit desc-entrada'></td>" +
										   "<td align='right' id='-1' class='edit valor'></td>" +
										   "<td id='-1' class='edit data'></td>" +
										   "<td id='-1' class='delete'><img id='imgMove' src='css/img/icon_delete.png'></td>" +
										   "<td id='-1' class='move'><img id='imgMove' src='css/img/icon_move.png'></td>" +
									    "</tr>");
		}
		BindEditable();
	});

	$("#nova-entradaDespesas").click(function(){
		if($("#tbDespesas tbody").has("tr").length > 0) {
			$("#tbDespesas tbody tr:last").after("<tr>" +
													   "<td id='-1' class='edit index desc-tipo-Despesas'></td>" +
													   "<td id='-1' class='edit desc-entrada'></td>" +
													   "<td align='right' id='-1' class='edit valor'></td>" +
													   "<td id='-1' class='edit data'></td>" +
													   "<td id='-1' class='delete'><img id='imgMove' src='css/img/icon_delete.png'></td>" +
													   "<td id='-1' class='move'><img id='imgMove' src='css/img/icon_move.png'></td>" +
												    "</tr>");
		}
		else {
			$("#tbDespesas tbody").append("<tr>" +
										   "<td id='-1' class='edit index desc-tipo-Despesas'></td>" +
										   "<td id='-1' class='edit desc-entrada'></td>" +
										   "<td align='right' id='-1' class='edit valor'></td>" +
										   "<td id='-1' class='edit data'></td>" +
										   "<td id='-1' class='delete'><img id='imgMove' src='css/img/icon_delete.png'></td>" +
										   "<td id='-1' class='move'><img id='imgMove' src='css/img/icon_move.png'></td>" +
									    "</tr>");
		}
		BindEditable();
	});

	$("table tr td.delete").live('click', function() {
		if (confirm("Deseja mesmo excluir?")) {
			var erro = false;
			if($(this).attr("id") != -1) {
				$.post(
				  'webservices/exclui-entrada.php',
				  { id: $(this).attr("id") }
				).success(function(data) { 
							if(data != '') {
								erro = true;
								alert('Erro ao excluir');
							}
						 });
			}
			if(!erro) {
				$(this).parent().hide();
				recalculaTotais();
			}
		}
	});

	//Chamadas de inicialização
	BindEditable();
	recalculaTotais();
});

