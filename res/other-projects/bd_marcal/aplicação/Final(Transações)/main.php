<?php

	require_once("config.php");
	
	session_start();
	
	if(!isset($_SESSION['email']) || !isset($_SESSION['password'])){
		if(isset($_POST["email"]) and isset($_POST["password"])){
			$_SESSION['email'] = $_POST["email"];
			$_SESSION['password'] = $_POST["password"];
		}
		else
			die("Erro de Login");
	
	}
	
	$user_email = $_SESSION["email"];
	$password = $_SESSION["password"];
	

	try{
		$pdo->beginTransaction();
		$stmt = $pdo->prepare("SELECT * FROM utilizador WHERE email = ? AND password = ?");

		$stmt->execute(array($user_email,$password));
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		$_SESSION['userid'] = $user['userid'];

		if($stmt->rowCount() == 0) {
			header("Location: index.php?error=invalido");
			session_destroy();
			die();
		}

		$pdo->commit();
	}
	catch(PDOException $error) {
        $pdo->rollBack();
		echo '$error';
    }
	
	
	$user_id = $user['userid'];
	
	try{
		$pdo->beginTransaction();
		//INSERIR TIPO DE REGISTO NOVO
		if(isset($_POST['nometiporeg'])){
			$nometiporeg = $_POST['nometiporeg'];
			
			//inserir accao na tabela sequencia
			$stmt = $pdo->prepare("INSERT INTO sequencia (userid) VALUES (?)");
			
			$stmt->execute(array($user_id));
			$stmt = null;
			
			//obter o idseq
			$stmt = $pdo->prepare("SELECT MAX(contador_sequencia) as contador FROM sequencia WHERE userid = ?");
			
			$stmt->execute(array($user_id));
			$idseq = $stmt->fetch(PDO::FETCH_ASSOC)["contador"];
			$stmt = null;
			
			//ir buscar o maior typecnt existente da tabela tipo_registo
			$stmt = $pdo->prepare("SELECT MAX(typecnt) as maxtypecnt FROM tipo_registo WHERE userid = ?");
			
			$stmt->execute(array($user_id));
			$maxtypecnt = $stmt->fetch(PDO::FETCH_ASSOC)["maxtypecnt"];
			$maxtypecnt++;
			$stmt = null;

			//inserir na tabela tipo_registo
			$stmt = $pdo->prepare("INSERT INTO tipo_registo (userid, typecnt, idseq, nome, ativo) VALUES (?,?,?,?,1)");
			
			$stmt->execute(array($user_id, $maxtypecnt, $idseq, $nometiporeg));
			$stmt = null;
		}
		//INSERIR PAGINA NOVA
		if(isset($_POST['nomepagina'])){
			$nomepagina = $_POST['nomepagina'];
			
			$stmt = $pdo->prepare("SELECT * FROM pagina WHERE userid=? AND nome=?");
			
			$stmt->execute(array($user_id, $nomepagina));
			$tmppag = $stmt->fetch(PDO::FETCH_ASSOC); //se nao houver uma pagina com o nome escolhido, $tmppag esta vazio
			$aux = $stmt->rowCount();
			$stmt = null;

			
			if($aux != 0 && $tmppag["ativa"] == 0){
					//inserir accao na tabela sequencia
					$stmt = $pdo->prepare("INSERT INTO sequencia (userid) VALUES (?)");
					
					$stmt->execute(array($user_id));
					$stmt = null;
					
					//obter o idseq
					$stmt = $pdo->prepare("SELECT MAX(contador_sequencia) as contador FROM sequencia WHERE userid = ?");
					
					$stmt->execute(array($user_id));
					$idseq = $stmt->fetch(PDO::FETCH_ASSOC)["contador"];
					$stmt = null;
			
					//mete ativa da pagina a 1
					$stmt = $pdo->prepare("UPDATE pagina SET ativa = 1, idseq = ? WHERE userid = ? AND pagecounter = ?");

					$stmt->execute(array($idseq, $user_id, $tmppag['pagecounter']));
					$stmt = null;
					
			}
			if($aux != 0 && $tmppag["ativa"] == 1){
					echo("Página já existente. Dê outro nome...");
			}
			if($aux == 0){
				//inserir accao na tabela sequencia
				$stmt = $pdo->prepare("INSERT INTO sequencia (userid) VALUES (?)");
				
				$stmt->execute(array($user_id));
				$stmt = null;
				
				//obter o idseq
				$stmt = $pdo->prepare("SELECT MAX(contador_sequencia) as contador FROM sequencia WHERE userid = ?");
				
				$stmt->execute(array($user_id));
				$idseq = $stmt->fetch(PDO::FETCH_ASSOC)["contador"];
				$stmt = null;
				
				//ir buscar o maior pagecounter existente da tabela pagina
				$stmt = $pdo->prepare("SELECT MAX(pagecounter) as maxpagecounter FROM pagina WHERE userid = ?");
				
				$stmt->execute(array($user_id));
				$maxpagecounter = $stmt->fetch(PDO::FETCH_ASSOC)["maxpagecounter"];
				$maxpagecounter++;
				$stmt = null;
				
				//inserir na tabela pagina
				$stmt = $pdo->prepare("INSERT INTO pagina (userid, pagecounter, idseq, nome, ativa) VALUES (?,?,?,?,1)");
				
				$stmt->execute(array($user_id, $maxpagecounter, $idseq, $nomepagina));
				$stmt = null;
			}
		}
				
		//APAGAR UMA PAGINA
		if(isset($_GET['msg']) && isset($_GET['id']) && $_GET["msg"] == "1"){
			$id = $_GET['id'];
			
			//inserir accao na tabela sequencia
			$stmt = $pdo->prepare("INSERT INTO sequencia (userid) VALUES (?)");
			
			$stmt->execute(array($user_id));
			$stmt = null;
			
			//obter o idseq
			$stmt = $pdo->prepare("SELECT MAX(contador_sequencia) as contador FROM sequencia WHERE userid = ?");
			
			$stmt->execute(array($user_id));
			$idseq = $stmt->fetch(PDO::FETCH_ASSOC)["contador"];
			$stmt = null;
			
			//obter pagecounter max
			$stmt = $pdo->prepare("SELECT MAX(pagecounter) as maxpagecounter FROM pagina WHERE userid = ?");

			$stmt->execute(array($user_id));
			$novopagecounter = $stmt->fetch(PDO::FETCH_ASSOC)["maxpagecounter"];
			$stmt = null;
			$novopagecounter++;

			//buscar os valores da entrada antiga
			$stmt = $pdo->prepare("SELECT * FROM pagina WHERE userid = ? AND pagecounter = ?");

			$stmt->execute(array($user_id, $id));
			$nomepag = $stmt->fetch(PDO::FETCH_ASSOC);
			$stmt = null;

			//inserir nova entrada
			$stmt = $pdo->prepare("INSERT INTO pagina (userid, pagecounter, nome, idseq, ativa) VALUES (?, ?, ?, ?, 0)");

			$stmt->execute(array($user_id, $novopagecounter, $nomepag['nome'], $nomepag['idseq']));
			$stmt = null;
			
			//actualizar pagecounter da entrada antiga
			$stmt = $pdo->prepare("UPDATE pagina SET ppagecounter = ?, ativa = 0,idseq = ? WHERE userid = ? AND pagecounter = ?");

			$stmt->execute(array($novopagecounter, $idseq, $user_id, $id,));
			$stmt = null;
			
			//actualizar o idseq na tabela reg_pag
			$stmt = $pdo->prepare("UPDATE reg_pag SET idseq = ? WHERE pageid = ? AND userid = ?");
			
			$stmt->execute(array($idseq, $id, $user_id));
			$stmt = null;
			
	
		}
		
		//REMOVE TIPO DE REGISTO
		if(isset($_GET['msg']) && isset($_GET['id']) && $_GET['msg'] == "2"){
			$id = $_GET['id'];
					
			//inserir accao na tabela sequencia
			$stmt = $pdo->prepare("INSERT INTO sequencia (userid) VALUES (?)");
			
			$stmt->execute(array($user_id));
			$stmt = null;
			
			//obter o idseq
			$stmt = $pdo->prepare("SELECT MAX(contador_sequencia) as contador FROM sequencia WHERE userid = ?");
			
			$stmt->execute(array($user_id));
			$idseq = $stmt->fetch(PDO::FETCH_ASSOC)["contador"];
			$stmt = null;
			
			//obter max typecnt
			$stmt = $pdo->prepare("SELECT MAX(typecnt) as contador FROM tipo_registo WHERE userid = ?");
			
			$stmt->execute(array($user_id));
			$novotypecnt = $stmt->fetch(PDO::FETCH_ASSOC)["contador"];
			$novotypecnt++;
			$stmt = null;
			
			//buscar os valores da entrada antiga
			$stmt = $pdo->prepare("SELECT * FROM tipo_registo WHERE userid = ? AND typecnt = ?");

			$stmt->execute(array($user_id, $id));
			$nomereg = $stmt->fetch(PDO::FETCH_ASSOC);
			$stmt = null;
			
			//inserir nova entrada
			$stmt = $pdo->prepare("INSERT INTO tipo_registo (userid, typecnt, nome, idseq, ativo, ptypecnt) VALUES (?, ?, ?, ?, 0, ?)");

			$stmt->execute(array($user_id, $novotypecnt, $nomereg['nome'], $nomereg['idseq'], $id));
			$stmt = null;
			
			//actualizar typecnt da entrada antiga
			$stmt = $pdo->prepare("UPDATE tipo_registo SET ptypecnt = ?, idseq = ?,ativo = 0 WHERE userid = ? AND typecnt = ?");

			$stmt->execute(array($novotypecnt, $idseq, $user_id, $id));
			$stmt=null;
			
			//apagar registos do tipo em questao(reg_pag)
			$stmt = $pdo->prepare("UPDATE reg_pag SET ativa = 0, idseq = ? WHERE typeid = ? AND userid = ?");
			
			$stmt->execute(array($idseq, $id, $user_id));
			$stmt = null;
			
			//apagar registos do tipo em questao(registo)
			$stmt = $pdo->prepare("UPDATE registo SET ativo = 0, idseq = ? WHERE typecounter = ? AND userid = ?");
			
			$stmt->execute(array($idseq, $id, $user_id));
			$stmt = null;
			
			//remover tipo de registo
			$stmt = $pdo->prepare("UPDATE tipo_registo SET ativo = 0, idseq = ? WHERE typecnt = ? AND userid = ?");
			
			$stmt->execute(array($idseq, $id, $user_id));
			$stmt = null;
		}
			
		
		//MOSTRA AS PÁGINAS DO UTILIZADOR
		$stmt = $pdo->prepare("SELECT * FROM pagina WHERE userid = ? AND ativa = 1"); //falta ativa = 1

		$stmt->execute(array($user_id));
		$pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;
		
		//MOSTRA OS TIPOS DE REGISTOS DO UTILIZADOR
		$stmt = $pdo->prepare("SELECT * FROM tipo_registo WHERE userid = ? AND ativo = 1");
		
		$stmt->execute(array($user_id));
		$registtype = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;

		
		$pdo->commit();
	}
	catch(PDOException $error) {
		$pdo->rollBack();
		echo $error;
    }
	
	
	

?>
<html>
<head>
<meta charset="UTF-8">
<title>Projecto BD</title>
</head>
<body>
	<table border="2" cellspacing="5">
		<p><b> Páginas do Utilizador: </b></p>
		<tr>
			<td>Nome</td>
			<td>Acções</td>
		</tr>
		<?php 
			foreach($pages as $row) {
				echo '<tr>';
				echo '<td>'.$row["nome"].'</td>';
				echo '<td> <a href="main.php?id='.$row["pagecounter"].'&msg=1">Apagar</a> <a href="mostraregistos.php?id='.$row["pagecounter"].'">Registos da Página</a> </td>';
				echo '</tr>';
			}
		?>
	</table>
	<a href="inserirpagina.php"> <b> Adicionar Página </b></a>
	
	<table border="2" cellspacing="5">
		<p><b>Tipos de Registos do Utilizador: </b></p>
		<tr>
			<td>Nome</td>
			<td>Acções</td>
		</tr>
		<?php 
			foreach($registtype as $row) {
				echo '<tr>';
				echo '<td>'.$row["nome"].'</td>';
				echo '<td> <a href="main.php?id='.$row["typecnt"].'&msg=2">Apagar</a> <a href="camposregistos.php?id='.$row["typecnt"].'">Alterar Campos</a> </td>' ;
				echo '</tr>';
			}
		?>
	</table>
	 <a href="inserirtiporeg.php?id=<?php echo($user_id); ?>"> <b> Adicionar Tipo de Registo </b></a> 
</body>
</html>