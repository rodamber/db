<html>
    <body>
        <h3>Remover tipo de registo</h3>
        <form action="remover_tiporegisto.php" method="post">
            <p><input type="hidden" name="userid" value="<?=$_REQUEST['userid']?>"/></p>

		<?php
			
			try
			{
				require("basedados.php");
				
				// inicia a transacao, esta linha nao é necessária para a versão sem transações
				$db->beginTransaction();
		
				echo("Tipo de registo a remover:");
				echo("<select name='tiporegisto'>\n");
				
				$sql = "SELECT typecnt, nome FROM tipo_registo WHERE userid ='$userid' AND ativo;";
				$result = $db->query($sql);

				foreach($result as $row)
				{
					echo('<option value="'.$row['nome'].'">'.$row['nome'].'</option>\n');
				}
				echo("</select>\n");
				
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

	
		?>
			

		<p><input type="submit" value="Submit"/></p>
        </form>
		
		<?php
		require("back.php");
		?>
    </body>
</html>


	


