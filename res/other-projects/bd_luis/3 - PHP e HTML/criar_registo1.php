<html>
    <body>
	
        <h3>Inserir registo</h3>
		<?php
		
		try
		{

			require("basedados.php");
			
			// inicia a transacao, esta linha nao é necessária para a versão sem transações
			$db->beginTransaction();
			
			echo('<form action="criar_registo2.php" method="post">');
			
			?>
			<p><input type="hidden" name="userid" value="<?=$_REQUEST['userid']?>"/></p>
			<p>Nome do registo: <input type="text" name="nomeregisto"/></p>
			<?php
			echo("Tipo de Registo:");
			echo("<select name='typecnt'>");
			
			// Tipos de registos existentes e ativos
			$sql = "SELECT typecnt, nome FROM tipo_registo WHERE userid ='$userid' AND ativo;";
			$result = $db->query($sql);
					
			foreach($result as $row)
			{
				echo('<option value="'.$row['typecnt'].'">'.$row['nome'].'</option>');
			}
			echo("</select>");
			
			echo('<p><input type="submit" value="Submit"/></p></form>');

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
