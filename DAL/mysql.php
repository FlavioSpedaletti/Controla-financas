<?php
class database {
    // Coloque aqui as Informações do Banco de Dados
    var $host = "187.45.196.237";
    var $user = "importarcontatos";
    var $pass = "W4qvhf6c";
    var $db = "importarcontatos";
    var $query;
    var $link;
    var $resultado;
        
	function __construct() {
       $this->conecta();
    }

    function conecta(){
        $this->link = @mysql_connect($this->host,$this->user,$this->pass);
        // Conecta ao Banco de Dados
        if(!$this->link){
            print "Ocorreu um Erro na conexão MySQL:";
            print "<b>".mysql_error()."</b>";
            die();
        }elseif(!mysql_select_db($this->db,$this->link)){
            print "Ocorreu um Erro em selecionar o Banco:";
            print "<b>".mysql_error()."</b>";
            die();
        }
    }

    function sql_query($query){
        $this->query = $query;
        // Conecta e faz a query no MySQL
        if($this->resultado = mysql_query($this->query)){
            return $this->resultado;
        }else{
            // Caso ocorra um erro, exibe uma mensagem com o Erro
            print "Ocorreu um erro ao executar a Query MySQL: <b>$query</b>";
            print "<br /><br />";
            print "Erro no MySQL: <b>".mysql_error()."</b>";
            die();
            $this->desconecta();
        }        
    }

	function sql_numrows($result = NULL){
		if($result){
			return mysql_num_rows($result);
		}else{
			if($this->resultado){
				return mysql_num_rows($this->resultado);
			}else{
				return 0;
			}
		}
	}

	function sql_affectedrows(){
		if(!$this->link){
            print "Ocorreu um Erro na conexão MySQL:";
            print "<b>".mysql_error()."</b>";
            die();
        }else
			return mysql_affected_rows($this->link);
	}

	function sql_insertedid(){
		if(!$this->link){
            print "Ocorreu um Erro na conexão MySQL:";
            print "<b>".mysql_error()."</b>";
            die();
        }else
			return mysql_insert_id($this->link);
	}

    function desconecta(){
        return mysql_close($this->link);
    }
}
?>