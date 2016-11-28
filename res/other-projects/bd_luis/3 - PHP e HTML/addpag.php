<html>
    <body>
        <h3>Criar nova p&aacute;gina </h3>
        <form action="" method="post">
            <p><input type="hidden" name="userid" value="<?=$_REQUEST['userid']?>"/></p>
            <p>Nova P&aacute;gina: <input type="text" name="nomepagina"/></p>
            <p><input type="submit" value="Submit"/></p>
        </form>

<?php
	
		
	require("basedados.php");

	if ( $_POST['nomepagina'] ) {
		$userid = $_REQUEST['userid'];
		$nomepagina = $_REQUEST['nomepagina'];
		try
		{
			// inicia a transacao, esta linha nao é necessária para a versão sem transações
			$db->beginTransaction();

			$sql = "SELECT * FROM pagina WHERE nome = '$nomepagina' AND userid = '$userid' ORDER BY idseq DESC LIMIT 1;";
			$result = $db->query($sql);
			
			$quantasPags = $result->rowCount();
			
			// verificar se a pagina ja existe
			if($quantasPags == 1){
				
				foreach($result as $pag)
				{
					$ativa = $pag['ativa'];
					$pagecounter = $pag['pagecounter'];
				}
				
				if ($ativa == 1){
					
					echo "Pagina ". $_POST['nomepagina']  ." ja existe.";
					
				} else {
		
					$sql = "UPDATE pagina SET ativa = true WHERE nome = '$nomepagina' AND pagecounter = '$pagecounter' AND userid = '$userid';";
					$result = $db->query($sql);
					
					echo "Pagina ". $_POST['nomepagina']  ." activada com sucesso.";
					
				}

			}
			// como a pagina ainda nao existe, é preciso criá-la
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
					$value = $row['themax']; // obter o valor do idseq criado, a ser usado para a nova entrada
				}

				$sql = "SELECT MAX(pagecounter) AS themax2 FROM pagina;";
				$max_pagecounter = $db->query($sql);

				$value_page;
				foreach($max_pagecounter as $row)
				{
					$value_page = $row['themax2'];
				}
				// valor a usar para a proxima pagina
				// como tem que ser unica, usar o valor maximo anterior +1
				$value_page = $value_page+1;
				
	
				$sql = "INSERT INTO pagina (userid, pagecounter, nome, idseq, ativa) VALUES ('$userid', '$value_page', '$nomepagina', '$value', true);";
				
				echo "Pagina ". $_POST['nomepagina']  ." adicionada com sucesso.";
				
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
	
	require("back.php");
?>

    </body>
</html>
