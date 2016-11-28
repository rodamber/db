<html>
    <body>

	<?php
		$userid = $_REQUEST['userid'];
		$tiporegisto = $_REQUEST['typecnt'];
		$nomeregisto = $_REQUEST['nomeregisto'];
		try
		{
			require("basedados.php");
			
			// inicia a transacao, esta linha nao é necessária para a versão sem transações
			$db->beginTransaction();
			
			
			$sql = "SELECT * FROM registo WHERE nome = '$nomeregisto' AND typecounter = '$tiporegisto' AND userid = '$userid' ORDER BY idseq DESC LIMIT 1;";
			$result = $db->query($sql);
			
			$quantosregistos = $result->rowCount();
			
			// verificar se o registo ja existe
			if($quantosregistos == 1){
				
				foreach($result as $row)
				{
					$ativo = $row['ativo'];
				}
				
				if ($ativo == 1){
					
					echo "Registo ". $_POST['nomeregisto']  ." ja existe.";
					
				} else {
		
					$sql = "UPDATE registo SET ativo = true WHERE nome = '$nomeregisto' AND typecounter = '$tiporegisto' AND userid = '$userid' ORDER BY idseq DESC LIMIT 1;";
					$result = $db->query($sql);
					
					echo "Registo ". $_POST['nomeregisto']  ." activado com sucesso.";
					
				}

			}
			// como o registo ainda nao existe, é preciso criá-lo
			else{
					
				$sql = "SELECT * FROM tipo_registo WHERE typecnt = '$tiporegisto' AND ativo ORDER BY typecnt DESC;";
				
				$result = $db->query($sql);
				$nometipo;

				foreach($result as $row)
				{
					$nometipo = $row['nome']; // transformar o typeid no nome do tipo de registo
				}
				

				echo('<form action="criar_registo3.php" method="post">');
				echo("Nome de Registo: ".$nomeregisto."<br>");
				echo("Tipo de Registo: ".$nometipo."<br>");
				
				// Obter os campos ligados ao tipo de registo
				$sql = "SELECT * FROM campo WHERE userid ='$userid' AND ativo AND typecnt = '$tiporegisto' ORDER BY idseq ASC;";
				$result = $db->query($sql);
				
				if($result->rowCount() == 0){
					echo("Nao tem campos a preencher");
				}
				
				echo("<table border=\"0\" cellspacing=\"5\">\n");
				?>
					
				<p><input type="hidden" name="userid" value="<?=$_REQUEST['userid']?>"/></p>
				<p><input type="hidden" name="typecnt" value="<?=$_REQUEST['typecnt']?>"/></p>
				<p><input type="hidden" name="nomeregisto" value="<?=$_REQUEST['nomeregisto']?>"/></p>
				
				<?php
				
				// Imprimir na pagina os campos, e uma área para escrever os seus valores
				$vals;
				foreach($result as $row)
				{
					echo("<tr>");
					echo("<td>{$row['nome']}: </td>");
					echo('<td><input type="text" name="vals['.$row['nome'].']"/></td>');

					echo("</tr>");

				}
				echo("</table>\n");
			
				echo('<p><input type="submit" value="Criar"/></p></form>');
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
