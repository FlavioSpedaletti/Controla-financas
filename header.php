<?php

// NÃO PRECISA, JÁ ESTÁ SETADO NO php.ini
// @ error reporting setting  (  modify as needed )
// ini_set("display_errors", 1);
// error_reporting(E_ALL);

//@ explicity start session  ( remove if needless )
session_start();

//@ if logoff
if(!empty($_GET['logoff'])) { $_SESSION = array(); }

//@ is authorized?
if(empty($_SESSION['exp_user']) || @$_SESSION['exp_user']['expires'] < time()) {
	header("location:login.php");	//@ redirect
} else {
	$_SESSION['exp_user']['expires'] = time()+(45*60);	//@ renew 45 minutes
}

?>

<div id="header">
	<h1>Controla Finan&ccedil;as</h1><p>
	MENU
	<hr/>
</div>