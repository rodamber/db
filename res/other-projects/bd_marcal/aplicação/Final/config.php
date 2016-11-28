<!DOCTYPE html>
<?php
//mb_internal_encoding("UTF-8");
try
{
	$host = "db.ist.utl.pt";
	$user ="ist179155";
	$password = "986789";
	$dbname = $user;
	$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
	echo("<p>ERROR: {$e->getMessage()}</p>");
}
?>
