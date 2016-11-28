<?php 

	try
	{

		require("basedados.php");
		//alterar o userid se vier do login
		
		// inicia a transacao, esta linha nao é necessária para a versão sem transações
		$db->beginTransaction();
	
		
		if ( $_POST['email'] ) {
			$email = $_POST['email'];

			$sql = "SELECT * FROM utilizador WHERE email ='$email';";
			$result = $db->query($sql);
			foreach($result as $row)
			{
				$userid = $row['userid'];
			}
			
		}
		
		$sql = "SELECT * FROM utilizador WHERE userid ='$userid';";

		$result = $db->query($sql);
		foreach($result as $row)
		{
			$nome = $row['nome'];
		}
		echo( "<h2>Bem-vindo ". $nome. "</h2>");

		echo("<td><a href=\"addpag.php?userid={$userid}\">Criar P&aacute;gina</a></td><p>");	
		echo("<td><a href=\"addtiporegisto.php?userid={$userid}\">Criar Tipo de Registo</a></td><p>");	
		echo("<td><a href=\"addcampo.php?userid={$userid}\">Criar Campo</a></td><p>");	
		echo("<td><a href=\"criar_registo1.php?userid={$userid}\">Criar Registo</a></td><p>");	
		
		echo("<td><a href=\"rempag.php?userid={$userid}\">Remover pagina</a></td><p>");	
		echo("<td><a href=\"remtiporegisto.php?userid={$userid}\">Remover Tipo de Registo</a></td><p>");
		echo("<td><a href=\"remcampo.php?userid={$userid}\">Remover Campo</a></td><p>");	
		echo("<td><a href=\"verpagina1.php?userid={$userid}\">Ver Pagina</a></td><p>");	

			
		
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
