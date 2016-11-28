<?php 
	$typecnt = $_GET['id'];
?>
<html>
	<head>
		<meta charset="UTF-8">
		<title> Login </title>
	</head>
	<body>
		<?php echo'<form action="camposregistos.php?id='.$typecnt.'" method="post">'?>
			Nome do Tipo do Campo:<br>
			<input type="text" name="nomedocampo">
			<br>

			<input type="submit" value= "Inserir">
		</form>
	</body>
</html>