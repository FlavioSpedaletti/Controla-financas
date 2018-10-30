<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Controla Finan&ccedil;as</title>
<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="js/login.js?ab"></script>
<link href="css/login.css?" rel="stylesheet" media="all" />
</head>
<body>
	<div id="wait">Carregando...</div>

	<h1>Controlador de finanças</h1><br/>

	<div id="wrapper">
	<?php
		//@ validate inclusion
		define('VALID_ACL_',true);
	?>

	<form id="frmlogin">
		<h2>Autentica&ccedil;&atilde;o</h2>
		<hr/>
		<p class="login">
			<label for="name">Usu&aacute;rio / E-mail</label>
			<input type="text" name="u" id="u" class="textfield2" />
		</p>
		<p class="password">
			<label for="name">Senha</label>
			<input type="password" name="p" id="p" class="textfield2" />
		</p>
		<p class="submit">
			<input type="submit" value="Login" />
		</p>
	</form>
	
	</div>
		<div id="footer">
		<a href="mailto:flavio.hfs@gmail.com">Controla Finan&ccedil;as - v0.1</a>
	</div>
	</body>
</html>
