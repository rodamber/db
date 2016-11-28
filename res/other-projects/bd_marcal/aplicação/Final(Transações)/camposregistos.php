<?php

	require_once("config.php");

	session_start();
	
	$userid = $_SESSION['userid'];
	$typecnt = $_GET['id'];
	//echo($userid);
	//echo($typecnt);
	try{
		$pdo->beginTransaction();
		//ADICIONAR UM CAMPO NOVO
		if(isset($_POST['nomedocampo'])){

			$nomedocampo = $_POST['nomedocampo'];
			
			//inserir accao na tabela sequencia
			$stmt = $pdo->prepare("INSERT INTO sequencia (userid) VALUES (?)");
			
			$stmt->execute(array($userid));
			$stmt = null;
			
			//obter o idseq
			$stmt = $pdo->prepare("SELECT MAX(contador_sequencia) as contador FROM sequencia WHERE userid = ?");
			
			$stmt->execute(array($userid));
			$idseq = $stmt->fetch(PDO::FETCH_ASSOC)["contador"];
			$stmt = null;
			
			//ir buscar o maior campocnt existente da tabela campo
			$stmt = $pdo->prepare("SELECT MAX(campocnt) as maxcampocnt FROM campo WHERE userid = ? AND typecnt = ?");
			
			$stmt->execute(array($userid, $typecnt));
			$maxcampocnt = $stmt->fetch(PDO::FETCH_ASSOC)["maxcampocnt"];
			$maxcampocnt++;
			$stmt = null;
			
			//inserir na tabela campo
			$stmt = $pdo->prepare("INSERT INTO campo (userid, typecnt, campocnt, idseq, nome, ativo) VALUES (?,?,?,?,?,1)");

			$stmt->execute(array($userid, $typecnt, $maxcampocnt, $idseq, $nomedocampo));
			$stmt = null;
		}
		//APAGAR UM CAMPO
		if(isset($_GET['msg']) && isset($_GET['id']) && isset($_GET['campoid']) && $_GET['msg'] == "1"){
			$campoid = $_GET['campoid'];
			$id = $_GET['id'];
			//inserir accao na tabela sequencia
			$stmt = $pdo->prepare("INSERT INTO sequencia (userid) VALUES (?)");
			
			$stmt->execute(array($userid));
			$stmt = null;
			
			//obter o idseq
			$stmt = $pdo->prepare("SELECT MAX(contador_sequencia) as contador FROM sequencia WHERE userid = ?");
			
			$stmt->execute(array($userid));
			$idseq = $stmt->fetch(PDO::FETCH_ASSOC)["contador"];
			$stmt = null;
			
			//mudar ativo de todos os valores com o campoid em questao
			$stmt = $pdo->prepare("UPDATE valor SET ativo = 0, idseq = ? WHERE userid = ? AND typeid = ? AND campoid = ? AND ativo = 1");
			$stmt->execute(array($idseq, $campoid, $userid, $typecnt));
			$stmt = null;
			
			//mudar ativo para 0 no campo
			$stmt = $pdo->prepare("UPDATE campo SET ativo = 0, idseq = ? WHERE userid = ? AND typecnt = ? AND campocnt = ? AND ativo = 1");
			$stmt->execute(array($idseq,$userid, $typecnt, $campoid));
			$stmt = null;
		}
		$pdo->commit();
	} 
	catch(pdoException $error) {
		$pdo->rollBack();
		echo $error;
	}
	
	//MOSTRA CAMPOS DO TIPO DE REGISTO
	$stmt = $pdo->prepare("SELECT * FROM campo WHERE userid=? AND typecnt =? AND ativo = 1");
			
	$stmt->execute(array($userid, $typecnt));
	$fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$stmt = null;
	
?>
<html>
<head>
	<meta charset="UTF-8">
	<title>Projecto BD</title>
</head>
<body>
	<table border="2" cellspacing="5">
		<p><b> Campos do Tipo de Registo: </b></p>
		<tr>
			<td>Nome</td>
			<td>Acções</td>
		</tr>
		<?php 
			foreach($fields as $row) {
				echo '<tr>';
				echo '<td>'.$row["nome"].'</td>';
				echo '<td> <a href="camposregistos.php?id='.$typecnt.'&campoid='.$row["campocnt"].'&msg=1">Apagar</a>';
				echo '</tr>';
			}
		?>
	</table>
	<?php echo'<a href="inserircampo.php?id='.$typecnt.'"<b> Adicionar Campo </b></a>'; ?>
</body>
</html>
