<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Coment&aacute;rios</title>
		<link rel="stylesheet" type="text/css" href="css/comments.css">
	</head>
	<body>
		<div id="wait">Carregando...</div>
		<div id="comments">
			<h2 id="tit">Coment&aacute;rios</h2>
		</div>
		<div id="leaveComment">
			<h2>Deixe um coment&aacute;rio</h2>
			<div class="row"><label>Nome:</label><input type="text"></div>
			<div class="row"><label>Coment&aacute;rio:</label><textarea cols="10" rows="5"></textarea></div>
			<button id="add">Enviar</button>
		</div>
		<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){ 
				
				var waitId		=	'#wait';			// wait message container
				var waitNote	=	'Carregando...';	// loading message

				$(waitId).html(waitNote).fadeIn('fast',function(){
					//retrieve comments to display on page
					$.getJSON("comments.php?jsoncallback=", function(data) {
					 
						if(data)
						{
							//loop through all items in the JSON array
							for (var x = 0; x < data.length; x++) {
							
								//create a container for each comment
								var div = $("<div>").addClass("row").appendTo("#comments");;
								
								//add author name and comment to container
								$("<label>").text(data[x].name).appendTo(div);
								$("<div>").addClass("comment").text(data[x].comment + ' em ' + data[x].date).appendTo(div);
							}
						}

						$(waitId).fadeOut('fast',function(){
							//add click handler for button
							$("#add").click(function() {
							
								$("#add").attr({ disabled:true, text:"Enviando..." });  
								$("#add").blur();

								//define ajax config object
								var ajaxOpts = {
									type: "post",
									url: "addComment.php",
									data: "&author=" + $("#leaveComment").find("input").val() + "&comment=" + $("#leaveComment").find("textarea").val(),
									success: function(data) {
			
										if(data)
										{
											alert(data);
											return;
										}
			
										//create a container for the new comment
										var div = $("<div>").addClass("row");
										$("#tit").after(div)

										//add author name and comment to container
										$("<label>").text($("#leaveComment").find("input").val()).appendTo(div);
										$("<div>").addClass("comment").text($("#leaveComment").find("textarea").val()).appendTo(div);
										
										//empty inputs
										$("#leaveComment").find("input").val("");
										$("#leaveComment").find("textarea").val("");
										$("#add").attr({ disabled:false, text:"Enviar" });
									}
								};
								
								$.ajax(ajaxOpts);					
							});
						}).html();
					});
				});	
			});			
		</script>
	</body>
</html>