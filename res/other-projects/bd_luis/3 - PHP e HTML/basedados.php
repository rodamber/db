<?php
	$userid = $_REQUEST['userid'];
	$host="db.ist.utl.pt";		// o MySQL esta disponivel nesta maquina
	$user="ist******";			// substituir pelo nome de utilizador
	$password="*******";		// substituir pela password (dada pelo mysql_reset, ou atualizada pelo utilizador)
	$dbname = $user;			// a BD tem nome identico ao utilizador

	$db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_USE_BUFFERED_QUERY);

?>


