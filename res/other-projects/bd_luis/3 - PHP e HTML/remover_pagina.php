<html>
    <body>
<?php
    $userid = $_REQUEST['userid'];
    $nomepagina = $_REQUEST['nomepagina'];
    try
    {
		require("basedados.php");
		
		// inicia a transacao, esta linha nao é necessária para a versão sem transações
		$db->beginTransaction();
		
		$sql = "SELECT * FROM pagina WHERE nome = '$nomepagina' AND ativa ORDER BY pagecounter;";
		
		$result = $db->query($sql);
		$active_page_counter = -1;
		
		foreach($result as $row)
		{
			$active_page_counter = $row['pagecounter']; // converte o nome da pagina em pagecounter
		}
		
		if($active_page_counter == -1){
			require("back.php");
			die("Pagina nao existe ou ja esta apagada");
		}
		
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
		
		
		$sql = "SELECT MAX(pagecounter) AS themax2 FROM pagina;";
		$max_pagecounter = $db->query($sql);

		$new_page_value;
		foreach($max_pagecounter as $row)
		{
			$new_page_value = $row['themax2']; // valor mais alto do pagecounter 
		}
		// valor unico do pagecounter a usar para a entrada que vai ser criada
		$new_page_value = $new_page_value+1;

		// alterar valores conforme a especificação no histórico do pdf do projecto2
		$sql = "UPDATE pagina SET pagecounter = '$new_page_value',ativa = false  WHERE nome = '$nomepagina' AND ativa;";
		$result = $db->query($sql);
	
		// inserir uma nova row para ser se referir à entrada criada
		$sql = "INSERT INTO pagina (userid, pagecounter, nome, idseq, ativa, ppagecounter) VALUES ('$userid', '$active_page_counter', '$nomepagina', '$value', false, '$new_page_value');";


        $db->query($sql);
		
		echo "Pagina ". $nomepagina . " foi removida com sucesso.";
		

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
