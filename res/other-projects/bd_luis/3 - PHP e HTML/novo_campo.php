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


		$sql = "SELECT * FROM campo WHERE nome = '$campo' AND userid = '$userid' ORDER BY idseq DESC LIMIT 1;";
		$result = $db->query($sql);
		
		$quantosCampos = $result->rowCount();
		
		// verificar se o campo ja existe
		if($quantosCampos == 1){
			
			foreach($result as $row)
			{
				$ativo = $row['ativo'];
			}
			
			if ($ativo == 1){
				
				echo "Campo ". $_POST['campo']  ." ja existe.";
				
			} else {
	
				$sql = "UPDATE campo SET ativo = true WHERE nome = '$campo' AND userid = '$userid' ORDER BY idseq DESC LIMIT 1;";
				$result = $db->query($sql);
				
				echo "Campo ". $_POST['campo']  ." activado com sucesso.";
				
			}

		}
		// como o campo ainda nao existe, é preciso criá-lo
		else{
		
			/* order by desc para o caso de se criar um registo e haver outros inativos com o mesmo nome*/
			$sql = "SELECT typecnt FROM tipo_registo WHERE nome ='$tiporegisto' ORDER BY idseq DESC LIMIT 1;";
			$result = $db->query($sql);
			$typecnt = $result->fetchColumn(0); //obter o valor do typecounter
			
			
			$sql = "SELECT MAX(campocnt) as themax FROM campo LIMIT 1;";
			$max_campocnt = $db->query($sql);
			
			$value_campocnt = $max_campocnt->fetchColumn(0); //obter valor mais alto do campo existente
		
			$value_campocnt = $value_campocnt+1; // somar mais um para ter um id unico
		
			// tempo actual para TIMESTAMP
			$time = date('Y-m-d H:i:s');
			
			$sequencia = "INSERT INTO sequencia (moment, userid) VALUES ('$time' ,'$userid');";
			$db->query($sequencia);

			
			$sql = "SELECT MAX(contador_sequencia) as themax FROM sequencia;";
			$max_seq = $db->query($sql);
			$value;
			
			foreach($max_seq as $row)
			{
				$value = $row['themax']; //valor idseq da sequencia criada
			}
			
			$sql = "INSERT INTO campo (userid, typecnt, campocnt, idseq, ativo, nome) VALUES ('$userid', '$typecnt', '$value_campocnt', '$value', true, '$campo');";
			$db->query($sql);
			
			echo ( "Campo " . $campo . " adicionado com sucesso.");
			
		}
			
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
