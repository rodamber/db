<html>
    <body>
<?php
    $userid = $_REQUEST['userid'];
	$tiporegisto = $_REQUEST['typecnt'];
	$nomeregisto = $_REQUEST['nomeregisto'];
    $vals = $_REQUEST['vals'];
    try
    {
		require("basedados.php");
		
		// inicia a transacao, esta linha nao é necessária para a versão sem transações
		$db->beginTransaction();
		
		// converter tempo em TIMESTAMP
		$time = date('Y-m-d H:i:s');
		
		$sequencia = "INSERT INTO sequencia (moment, userid) VALUES ('$time' ,'$userid');";
		$db->query($sequencia);

		$sql = "SELECT MAX(contador_sequencia) as themax FROM sequencia;";
		
		$max_seq = $db->query($sql);
		$value;
		
		foreach($max_seq as $row)
		{
			$value = $row['themax']; // obter valor idseq criado
		}
		
		
		$sql = "SELECT MAX(regcounter) AS themax2 FROM registo;";
		$max_regcounter = $db->query($sql);

		$value_reg_counter;
		foreach($max_regcounter as $row)
		{
			$value_reg_counter = $row['themax2']; //valor maximo do regcounter
		}
		// valor do regcounter a usar na proxima entrada 
		$value_reg_counter = $value_reg_counter+1;
		
		// inserir o registo
		$sql = "INSERT INTO registo (userid, typecounter, nome, idseq, ativo, regcounter) VALUES ('$userid', '$tiporegisto', '$nomeregisto', '$value', true, '$value_reg_counter');";
			
        $db->query($sql);
		echo("<table border=\"0\" cellspacing=\"5\">\n");
		// inserir uma nova sequencia para cada campo no registo
		// e também é inserido o seu valor
		foreach($vals as $key => $v)
		{	
			
			$sql = "SELECT * FROM campo WHERE nome = '$key' AND ativo ORDER BY idseq DESC LIMIT 1;";
			$result = $db->query($sql);
			
			$campoid;

			foreach($result as $row)
			{
				$campoid = $row['campocnt'];
			}
			
			
			$time = date('Y-m-d H:i:s');
			
			$sequencia = "INSERT INTO sequencia (moment, userid) VALUES ('$time' ,'$userid');";
			$db->query($sequencia);
			
			$value = $value + 1;
			
			$sql = "INSERT INTO valor (userid, typeid, valor, idseq, ativo, regid, campoid) VALUES ('$userid', '$tiporegisto', '$v', '$value', true, '$value_reg_counter', '$campoid');";
			$result = $db->query($sql);
			
			echo("<tr>\n");
			echo("<td>{$key}</td>\n");
			echo("<td>{$v}</td>\n");
			echo("</tr>\n");
			
		}
		echo("</table>\n");
		
        // termina a transação efectuando as mudanças desde o inicio da transação
		// esta linha nao é necessária para a versão sem transações
		$db->commit();
	
		echo("<p>Registo adicionado com sucesso");
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
