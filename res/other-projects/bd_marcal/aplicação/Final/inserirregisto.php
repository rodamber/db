<?php
	require_once("config.php");
	
	session_start();
		
	$userid = $_SESSION['userid'];
	
	if(!isset($_GET['id'])){$typecnt = $_POST['tiporeg'];}
		
	if(isset($_GET['id'])){$typecnt = $_GET['id'];}
	
	if(isset($_GET['pc'])){$_SESSION['pagecnt'] = $_GET['pc'];}

	
	//MOSTRA CAMPOS DO TIPO DE REGISTO
	$stmt = $pdo->prepare("SELECT nome, campocnt FROM campo WHERE userid=? AND typecnt =? AND ativo = 1");
			
	$stmt->execute(array($userid, $typecnt));
	$fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$stmt = null;

	if(isset($_GET['id'])){
		$typecnt = $_GET['id'];
		$nomereg = $_POST['nomereg'];
		
		
		//inserir accao na tabela sequencia
		$stmt = $pdo->prepare("INSERT INTO sequencia (userid) VALUES (?)");
			
		$stmt->execute(array($userid));
		$stmt = null;
			
		//obter o idseq
		$stmt = $pdo->prepare("SELECT MAX(contador_sequencia) as contador FROM sequencia WHERE userid = ?");
		
		$stmt->execute(array($userid));
		$idseq = $stmt->fetch(PDO::FETCH_ASSOC)["contador"];
		$stmt = null;
		
		//obter o MAX regcounter
		$stmt = $pdo->prepare("SELECT MAX(regcounter) as contador FROM registo WHERE userid = ? AND typecounter = ?");
		
		$stmt->execute(array($userid, $typecnt));
		$regcounter = $stmt->fetch(PDO::FETCH_ASSOC)["contador"];
		$stmt = null;
		$regcounter++;
		

		//inserir novo registo(registos)
		$stmt = $pdo->prepare("INSERT INTO registo (userid, typecounter, regcounter, nome, ativo, idseq) VALUES (?,?,?,?,1,?)");
			
		$stmt->execute(array($userid, $typecnt, $regcounter, $nomereg, $idseq));
		$stmt = null;
		
		//inserir novo registo(reg-pag)
		$stmt = $pdo->prepare("INSERT INTO reg_pag (userid, pageid, typeid, regid, ativa, idseq) VALUES (?,?,?,?,1,?)");
			
		$stmt->execute(array($userid, $_SESSION['pagecnt'], $typecnt, $regcounter, $idseq));
		$stmt = null;
		
		
		
		foreach($fields as $row){
			$stmt = $pdo->prepare("INSERT INTO valor (userid, typeid, regid, campoid, valor, ativo, idseq) VALUES (?,?,?,?,?,1,?)");
			
			$stmt->execute(array($userid, $typecnt, $regcounter, $row['campocnt'],$_POST[$row['nome']], $idseq));
			$stmt = null;
		}
		unset($_SESSION['pagecnt']);
		header('Location:main.php');
		exit;
	}
		
	?>

<html>
	<head>
		<meta charset="UTF-8">
		<title>Projecto BD</title>
	</head>
	<body>
	
	<?php
		echo'<form action="inserirregisto.php?id='.$typecnt.'" method="post">';
		echo'<b>Nome do Registo<b> <br>';
		echo '<input type="text" name="nomereg"><br>';
		foreach($fields as $row){
			echo'<b>'.$row['nome'].'<b>';
			echo '<br>';
			echo '<input type="text" name="'.$row['nome'].'">';
			echo '<br>';
		}
	?>	
	
		<input type="submit" value= "Entrar">
		</form>
	</body>
</html>
