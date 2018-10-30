<?php
/*
**	@desc:		PHP ajax login form using jQuery
**	@author:	programmer@chazzuka.com
**	@url:		http://www.chazzuka.com/blog
**	@date:		15 August 2008
**	@license:	Free!, but i'll be glad if i my name listed in the credits'
*/
//@ validate inclusion
if(!defined('VALID_ACL_')) exit('O acesso direto a esse arquivo não é válido.');

class Authorization
{
	function __construct() {
		require_once('../DAL/mysql.php');
	}

	public function check_status()
		{
			if(empty($_SESSION['exp_user']) || @$_SESSION['exp_user']['expires'] < time())
				{
					return false;
				}
			else
				{
					return true;
				}
		}
		
	public function signin($u,$p)
		{
			$return = false;
			
			if($u&&$p)
				{
					$db = new database();
					$sql = "SELECT * FROM tb_usuarios WHERE ";
					$sql .= "(tx_usuario='".mysql_real_escape_string($u)."' OR tx_email='".mysql_real_escape_string($u)."')";
					$sql .= " AND tx_senha = '".md5($p)."'";
					$rs = $db->sql_query($sql);
					if(!$rs) return false;
					if(!mysql_num_rows($rs)) return false;

					if(mysql_num_rows($rs))
						{
							$this->set_session(array_merge(mysql_fetch_assoc($rs),array('expires'=>time()+(45*60))));
							$return = true;
						}
					mysql_free_result($rs);
					$db->desconecta();
					unset($rs,$sql);
				}
				
				
			return $return;		
		}

	private function set_session($a=false)
		{
			if(!empty($a))
				{
					$_SESSION['exp_user'] = $a;
				}
		}
}
?>