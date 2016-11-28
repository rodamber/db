<html>
    <body>
	
        <h3>Ver Pagina</h3>
		<?php
		
			try
			{

				require("basedados.php");
				
				// inicia a transacao, esta linha nao é necessária para a versão sem transações
				$db->beginTransaction();
				
	
				
				echo('<form action="verpagina2.php" method="post">');
				
		?>
				<p><input type="hidden" name="userid" value="<?=$_REQUEST['userid']?>"/></p>
		<?php
				echo("Pagina:");
				echo("<select name='pagecounter'>");
				
				$sql = "SELECT pagecounter, nome FROM pagina WHERE userid ='$userid' AND ativa;";
				$result = $db->query($sql);
				
				// Imprimir lista de paginas a escolher (select menu)
				foreach($result as $row)
				{
					echo('<option value="'.$row['pagecounter'].'">'.$row['nome'].'</option>');
				}
				echo("</select>");
				
				echo('<p><input type="submit" value="Submit"/></p></form>');
				
						
				$sql = "SELECT * FROM pagina WHERE userid ='$userid' AND ativa;";
				$result = $db->query($sql);
	
				echo "Lista de Paginas";
				echo("<table border=\"0\" cellspacing=\"5\">\n");
				foreach($result as $row)
				{
					echo("<tr>\n");
					echo("<td>{$row['nome']}</td>\n");
					echo("<td><a href=\"verpagina2.php?pagecounter={$row['pagecounter']}&userid={$userid}\">Ver</a></td>\n");
					echo("</tr>\n");
				}
				echo("</table>\n");
			
			
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
		
			require("back.php");

		?>
		  
    </body>
</html>
