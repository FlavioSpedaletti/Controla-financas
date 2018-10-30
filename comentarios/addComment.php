<?php

  //db connection detils
  $host = "187.45.196.237";
  $user = "importarcontatos";
  $password = "W4qvhf6c";
  $database = "importarcontatos";
	
  //make connection
  $server = mysql_connect($host, $user, $password);
  $connection = mysql_select_db($database, $server);
	
  //get POST data
  $name = mysql_real_escape_string($_POST["author"]);
  $comment = mysql_real_escape_string($_POST["comment"]);

  //add new comment to database
  $res = @mysql_query("INSERT INTO tb_mensagens (tx_usuario, tx_mensagem) VALUES(' $name ',' $comment ')");  
  if(!$res)  
      die("Error: ".mysql_error());  
  else  
      return $res;  

?>