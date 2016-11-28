<html>
    <body>
	
	<?php
		$userid = $_REQUEST['userid'];
		$pagecounter = $_REQUEST['pagecounter'];
		try
		{
			require("basedados.php");
			
			// inicia a transacao, esta linha nao é necessária para a versão sem transações
			$db->beginTransaction();
			
			// query para obter os registos ativos na pagina
			$sql = "SELECT 
						*
					FROM
						reg_pag,
						registo
					WHERE
					pageid = '$pagecounter' AND
						reg_pag.ativa
							AND regid = regcounter
							AND registo.ativo
							AND EXISTS (SELECT * FROM pagina WHERE reg_pag.pageid = pagecounter AND pagina.ativa) ;";
		
				
		
			$result = $db->query($sql);
			$nometipo;
			$typecounter;
			
			echo("<table border=\"1\" cellspacing=\"5\">\n");
			
			echo("<tr>\n");
			echo("<th>Nome de Registo</th>\n");
			echo("<th>Campo</th>\n");
			echo("<th>Valor</th>\n");
			echo("</tr>\n");
			
			// criar uma nova linha para cada registo na pagina
			foreach($result as $row)
			{
				echo("<tr>\n");
				echo("<td>{$row['nome']}</td>\n");

				
				//saber os campos deste tipo de registo
				$regcounter = $row['regcounter'];
				$typecounter = $row['typecounter'];
				
				
				$sql2 = "SELECT * FROM campo WHERE userid ='$userid' AND ativo AND typecnt = '$typecounter' ORDER BY idseq DESC;";

				$result2 = $db->query($sql2);
				$copia = $result2;
				
				
				echo("<td>\n");
				echo("<table border=\"0\" cellspacing=\"5\">\n");
				
				// cria uma linha diferente para cada campo (mas na coluna correspondente ao seu registo)
				foreach($result2 as $campo)
				{
					echo("<tr>\n");
					echo("<td>{$campo['nome']}</td>\n");
					echo("</tr>\n");
				}
				echo("</table>\n");
				echo("</td>\n");
				
				$sql2 = "SELECT * FROM campo WHERE userid ='$userid'  AND typecnt = '$typecounter' ORDER BY idseq DESC;";

				//echo("<p>Query2: " . $sql2 . "</p>\n");

				$result2 = $db->query($sql2);
				$copia = $result2;
					
				// preenche a coluna à direita do campo com os respectivos valores
				// usa uma linha a vazio se esse campo nao tiver valores
				echo("<td>\n");
				echo("<table border=\"0\" cellspacing=\"5\">\n");
				foreach($copia as $campo)
				{
					
					$campocnt = $campo['campocnt'];
					
					//saber os valores de cada campo
					$sql3 = "SELECT * FROM valor WHERE userid ='$userid' AND ativo AND typeid = '$typecounter' AND regid = '$regcounter' AND campoid = '$campocnt' ORDER BY idseq DESC;";

					$result3 = $db->query($sql3);
					$count = 0;
					foreach($result3 as $row3)
					{
						echo("<tr>{$row3['valor']}</tr>\n");
						$count =$count +1;

					}
					if($count == 0){
						echo("<tr></tr>\n");
					}
					
				
				}
				echo("</table>\n");
				echo("</td>\n");
		
				
				echo("</tr>\n");
				$nometipo = $row['nome'];
			}
			echo("</table>\n");
			
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
