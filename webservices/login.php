<?php

// NÃO PRECISA, JÁ ESTÁ SETADO NO php.ini
// @ error reporting setting  (  modify as needed )
// ini_set("display_errors", 1);
// error_reporting(E_ALL);

//@ explicity start session just if not automatically started at php.ini
session_start();

//@ validate inclusion
define('VALID_ACL_',		true);

//@ load dependency files
require('../BLL/acl.php');

//@ new acl instance
$acl = new Authorization;

//@check session status 
$status = $acl->check_status();

if($status)
	{
		// @ session already active
		$arr = array ('status'=>true,'message'=>"Sess&atilde;o ativa", 'url'=>"index.php");
	}
else
	{
		//@ session not active
		if($_SERVER['REQUEST_METHOD']=='GET')
			{
				//@ first load
				$arr = array ('status'=>false,'message'=>"");
			}
		else
			{
				//@ form submission
				$u = (!empty($_POST['u']))?trim($_POST['u']):false;	// retrive user var
				$p = (!empty($_POST['p']))?trim($_POST['p']):false;	// retrive password var
				
				// @ try to signin
				$is_auth = $acl->signin($u,$p);
				
				if($is_auth)
					{
						//@ success
						$arr = array ('status'=>true,'message'=>"Login com sucesso", 'url'=>"index.php");
					}
				else
					{
						//@ failed
						$arr = array ('status'=>false,'message'=>"Falha no login");
					}
			}
	}
echo json_encode($arr);
//@ destroy instance
unset($acl);
?>