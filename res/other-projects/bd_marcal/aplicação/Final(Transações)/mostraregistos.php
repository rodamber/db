<?php
	require_once("config.php");

	session_start();
	
	$userid = $_SESSION['userid'];
	$pagecnt = $_GET['id'];

	try{
		$pdo->beginTransaction();
		
		//MOSTRA REGISTOS DA PAGINA
		$stmt = $pdo->prepare("SELECT * FROM registo WHERE ativo = 1 AND regcounter IN(SELECT regid FROM reg_pag WHERE userid = ? AND pageid = ? AND ativa = 1) AND typecounter IN(SELECT typecnt FROM tipo_registo WHERE ativo = 1)");
			
		$stmt->execute(array($userid, $pagecnt));
		$regists = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;
		
		//MOSTRA OS TIPOS DE REGISTOS DO UTILIZADOR
		$stmt = $pdo->prepare("SELECT * FROM tipo_registo WHERE userid = ? AND ativo = 1");
			
		$stmt->execute(array($userid));
		$registtype = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;
		
		$pdo->commit();
	}
	catch(PDOException $error){
		echo($error);
		$pdo->rollBack();
	}
?>
<html>
<head>
	<meta charset="UTF-8">
	<title>Projecto BD</title>
</head>
<body>
	<table border="2" cellspacing="5">
		<p><b> Registos: </b></p>
		<tr>
			<td>Nome</td>
		</tr>
		<?php 
			foreach($regists as $row) {
				echo '<tr>';
				echo '<td>'.$row["nome"].'</td>';
				echo '</tr>';
			}
		?>
	</table>
	<p><b>ESCOLHER TIPO DE REGISTO A ADICIONAR:</b></p>
	<?php echo'<form action="inserirregisto.php?pc='.$pagecnt.'" method="post">';?>
		<select name = "tiporeg">
			<?php
				foreach($registtype as $row){
					echo '<option value="'.$row['typecnt'].'" name="tiporegisto">'.$row['nome'].'</option>';
				}
			?>
		</select>
		<input type="submit" value= "Escolher">
	</form>
</body>
</html>