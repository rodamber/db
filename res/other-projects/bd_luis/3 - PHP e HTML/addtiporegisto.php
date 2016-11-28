<html>
    <body>
        <h3>Criar novo tipo de registo</h3>
        <form action="" method="post">
            <p><input type="hidden" name="userid" value="<?=$_REQUEST['userid']?>"/></p>
            <p>Novo tipo de registo: <input type="text" name="tiporegisto"/></p>
            <p><input type="submit" value="Submit"/></p>
        </form>
		
		<?php

		require("basedados.php");
	
		require("back.php");
		
		if ( $_POST['tiporegisto'] ) {
			
			$userid = $_REQUEST['userid'];
			$tiporegisto = $_REQUEST['tiporegisto'];
			try
			{
				
				// inicia a transacao, esta linha nao é necessária para a versão sem transações
				$db->beginTransaction();
				
				$sql = "SELECT * FROM tipo_registo WHERE nome = '$tiporegisto' AND userid = '$userid' ORDER BY idseq DESC LIMIT 1;";
				$result = $db->query($sql);
				
				$quantosTiposDeRegisto = $result->rowCount();
				
				// verificar se o tipo de registo ja existe
				if($quantosTiposDeRegisto == 1){
					
					foreach($result as $tipo)
					{
						$ativo = $tipo['ativo'];
					}
					
					if ($ativo == 1){
						
						echo "Tipo de Registo ". $_POST['tiporegisto']  ." ja existe.";
						
					} else {
			
						$sql = "UPDATE tipo_registo SET ativo = true WHERE nome = '$tiporegisto' AND userid = '$userid' ORDER BY idseq DESC LIMIT 1;";
						$result = $db->query($sql);
						
						echo "Tipo de Registo ". $_POST['tiporegisto']  ." activado com sucesso.";
						
					}

				}
				// como o Tipo de Registo ainda nao existe, é preciso criá-lo
				else{
					//converter tempo para formato TIMESTAMP
					$time = date('Y-m-d H:i:s');
					$sequencia = "INSERT INTO sequencia (moment, userid) VALUES ('$time' ,'$userid');";
		
					$db->query($sequencia);

					
					$sql = "SELECT MAX(contador_sequencia) as themax FROM sequencia;";

					$max_seq = $db->query($sql);
					$value;
					
					foreach($max_seq as $row)
					{
						$value = $row['themax'];// obter o valor do idseq criado, a ser usado para a nova entrada
					}
		
					
					$sql = "SELECT MAX(typecnt) AS themax2 FROM tipo_registo;";

					$max_typecnt = $db->query($sql);

					$value_type_counter;
					foreach($max_typecnt as $row)
					{

						$value_type_counter = $row['themax2'];
					}
					// valor a usar para o proximo registo
					// maximo anterior +1
					$value_type_counter = $value_type_counter+1;
					
					$sql = "INSERT INTO tipo_registo (userid, typecnt, nome, idseq, ativo) VALUES ('$userid', '$value_type_counter', '$tiporegisto', '$value', true);";
										
					echo "Registo " . $_POST['tiporegisto'] . " adicionado com sucesso.";

					$db->query($sql);
				
				}
				
				// termina a transação efectuando as mudanças desde o inicio da transação
				// esta linha nao é necessária para a versão sem transações
				$db->commit();
				
			}
			catch (PDOException $e)
			{
				// feito o rollback se for apanhada alguma exceção durante a transação
				// esta linha não é necessária para a versão sem transações
				$db->rollBack();
				echo("<p>ERROR: {$e->getMessage()}</p>");
			}

			
		}
		
	?>	
		
    </body>
</html>
