<html>
    <body>
<?php
    $userid = $_REQUEST['userid'];
    $tiporegisto = $_REQUEST['tiporegisto'];
	$campo = $_REQUEST['campo'];
	
    try
    {
		require("basedados.php");
		
		// inicia a transacao, esta linha nao é necessária para a versão sem transações
		$db->beginTransaction();
		

		$sql = "SELECT * FROM tipo_registo WHERE nome = '$tiporegisto' AND ativo ORDER BY typecnt DESC;";
		
		$result = $db->query($sql);
		$active_type_counter = -1;
		
		foreach($result as $row)
		{
			$active_type_counter = $row['typecnt']; // obter typecounter a partir do nome do tipo
		}
	
		if($active_type_counter == -1){
			require("back.php");
			die("Tipo de registo nao existe ou ja esta apagado");
			
		}
		

		$sql = "SELECT * FROM campo WHERE nome = '$campo' AND ativo AND typecnt = '$active_type_counter' ORDER BY idseq DESC;";
		
		
		$result = $db->query($sql);
		$active_campo_counter = -1;
		
		foreach($result as $row)
		{
			$active_campo_counter = $row['campocnt']; // obter campocounter a partir do nome do campo

		}
		
		if($active_campo_counter == -1){
			require("back.php");
			die("Campo nao existe ou ja esta apagado");
		}
		
		// tmepo actual para TIMESTAMP
		$time = date('Y-m-d H:i:s');
		
		$sequencia = "INSERT INTO sequencia (moment, userid) VALUES ('$time' ,'$userid');";
		
        $db->query($sequencia);

		
		$sql = "SELECT MAX(contador_sequencia) as themax FROM sequencia;";
		
		$max_seq = $db->query($sql);
		$value;
		
		foreach($max_seq as $row)
		{
			$value = $row['themax']; // valor idseq da sequencia adicionada
		}
		
		
		$sql = "SELECT MAX(campocnt) AS themax2 FROM campo;";
		$max_campocounter = $db->query($sql);

		$new_campo_value;
		foreach($max_campocounter as $row)
		{
			$new_campo_value = $row['themax2']; // valor mais alto do campocounter que existe actualmente
		}
		//usado para ter um valor do campocounter unico
		$new_campo_value = $new_campo_value+1;
		
		// criado de acordo com as Observações no fim do pdf projecto 2
		$sql = "UPDATE campo SET campocnt = '$new_campo_value',ativo = false  WHERE nome = '$campo' AND ativo;";
		$result = $db->query($sql);
	
	
		$sql = "INSERT INTO campo (userid, campocnt, nome, idseq, ativo, pcampocnt, typecnt) VALUES ('$userid', '$active_campo_counter', '$campo', '$value', false, '$new_campo_value' ,'$active_type_counter');";
       
        $db->query($sql);
		

		// termina a transação efectuando as mudanças desde o inicio da transação
		// esta linha nao é necessária para a versão sem transações
		$db->commit();
		echo (" Campo ".$campo." removido com sucesso.");

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
