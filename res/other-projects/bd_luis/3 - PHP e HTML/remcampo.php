<html>
    <body>
        <h3>Remover Campo</h3>

		<?php
		require("basedados.php");
		
		try
		{

			// inicia a transacao, esta linha nao é necessária para a versão sem transações
			$db->beginTransaction();
			
			// Se já se tiver submetido algum valor para apagar
			if ( $_POST['tiporegisto'] ) {
				
				// criar o html para ter um form bem feito
				echo('<form action="remover_campo.php" method="post">');
				
				// usado para poder passar o valor do tiporegisto sem campo extra à vista do utilizador
				echo (' <p><input type="hidden" name="tiporegisto" value="'.$_POST['tiporegisto'].'"/></p>');
				
				print ' Tipo de registo: ' . $_POST['tiporegisto'];
				$tiporegistonome =  $_POST['tiporegisto'];
				
				
				$sql = "SELECT typecnt, nome FROM tipo_registo WHERE userid ='$userid' AND ativo AND nome = '$tiporegistonome';";
				$result = $db->query($sql);
				
				foreach($result as $row)
				{
					$tiporegistoid =$row['typecnt']; // obter typecounter do campo a remover
				}		
				
				$sql = "SELECT * FROM campo WHERE userid ='$userid' AND ativo AND typecnt = '$tiporegistoid';";
				$result = $db->query($sql);
				
				echo("<p>Campo:");
				echo("<select name='campo'>");
				
				// imprimir os valores do campo que é possivel eliminar
				foreach($result as $row)
				{
					echo('<option value="'.$row['nome'].'">'.$row['nome'].'</option>');
				}
				echo("</select>");

				
			}
			// primeira vez que o utlizador entra na página
			else
			{
				echo('<form action="" method="post">');
				
				$sql = "SELECT typecnt, nome FROM tipo_registo WHERE userid ='$userid' AND ativo;";
				$result = $db->query($sql);
				
				echo("Escolher Tipo de registo associado ao campo: ");
				echo("<select name='tiporegisto'>");
				
				// mostra ao utilizador que tipos de registo existem ativos
				foreach($result as $row)
				{
					echo('<option value="'.$row['nome'].'">'.$row['nome'].'</option>');
				}
				echo("</select>");
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
	 
		?>
			<p><input type="hidden" name="userid" value="<?=$_REQUEST['userid']?>"/></p>
            <p><input type="submit" value="Submit"/></p>
        </form>
				
		<?php
			require("back.php");
		?>
	
    </body>
</html>

		