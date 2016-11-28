<html>
    <body>
<?php
    $userid = $_REQUEST['userid'];
    $tiporegisto = $_REQUEST['tiporegisto'];
    try
    {
		require("basedados.php");	
		
		
		// inicia a transacao, esta linha nao é necessária para a versão sem transações
		$db->beginTransaction();
		
		$sql = "SELECT * FROM tipo_registo WHERE nome = '$tiporegisto' AND ativo ORDER BY typecnt;";
		
		$result = $db->query($sql);
		$active_type_counter = -1;
		
		foreach($result as $row)
		{
			$active_type_counter = $row['typecnt']; // converte o nome do tipo registo no typecounter
		}
		
		
		if($active_type_counter == -1){
			require("back.php");
			die("Tipo de registo nao existe ou ja esta apagada");
			
		}
		
		//converte tempo para TIMESTAMP
		$time = date('Y-m-d H:i:s');
		
		//Insere uma nova sequencia
		$sequencia = "INSERT INTO sequencia (moment, userid) VALUES ('$time' ,'$userid');";
		
        $db->query($sequencia);

		
		$sql = "SELECT MAX(contador_sequencia) as themax FROM sequencia;";
		$max_seq = $db->query($sql);
		$value;
		
		foreach($max_seq as $row)
		{
			$value = $row['themax']; // guarda o valor idseq da sequencia ligada
		}
	
		$sql = "SELECT MAX(typecnt) AS themax2 FROM tipo_registo;";
		$max_typecounter = $db->query($sql);

		$new_type_value;
		foreach($max_typecounter as $row)
		{
			$new_type_value = $row['themax2']; // typecounter mais alto
		}
		// somar mais um para ter um valor typecounter unico
		$new_type_value = $new_type_value+1;
	
		// usado pois esta tabela a ser alterada é usada em vários sitios e nao permite alterar
		// enquanto as verificações de foreign keys estão activas
		$db->query("SET FOREIGN_KEY_CHECKS=0;");
		
		$sql = "UPDATE tipo_registo SET typecnt = '$new_type_value',ativo = false  WHERE nome = '$tiporegisto' AND ativo;";
		$result = $db->query($sql);
				
		$sql = "INSERT INTO tipo_registo (userid, typecnt, nome, idseq, ativo, ptypecnt) VALUES ('$userid', '$active_type_counter', '$tiporegisto', '$value', false, '$new_type_value');";

        $db->query($sql);
		
		$db->query("SET FOREIGN_KEY_CHECKS=1;");
	
			
		print "Tipo de registo: ".$tiporegisto . " removido com sucesso.";

        // termina a transação efectuando as mudanças desde o inicio da transação
		// esta linha nao é necessária para a versão sem transações
		$db->commit();
		
		require("back.php");
    }
    catch (PDOException $e)
    {
        // feito o rollback se for apanhada alguma exceção durante a transação
		// esta linha não é necessária para a versão sem transações
		$db->rollBack();
        echo("<p>ERROR: {$e->getMessage()}</p>");
    }
?>
    </body>
</html>
