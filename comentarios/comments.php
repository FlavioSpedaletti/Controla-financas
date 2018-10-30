<?php

	//db connection detils
	$host = "187.45.196.237";
  $user = "importarcontatos";
  $password = "W4qvhf6c";
  $database = "importarcontatos";
	
	//make connection
  $server = mysql_connect($host, $user, $password);
  $connection = mysql_select_db($database, $server);
	
	//query the database
  $query = mysql_query("SELECT * FROM tb_mensagens ORDER BY dt_mensagem DESC");
	
	//loop through and return results
  for ($x = 0, $numrows = mysql_num_rows($query); $x < $numrows; $x++) {
		$row = mysql_fetch_assoc($query);
    
		$comments[$x] = array("name" => $row["tx_usuario"], "comment" => $row["tx_mensagem"], "date" => $row["dt_mensagem"]);		
	}
	
	//echo JSON to page
	$response = $_GET["jsoncallback"] . "(" . json_encode($comments) . ")";
	//sleep(3);
	echo $response;

?>