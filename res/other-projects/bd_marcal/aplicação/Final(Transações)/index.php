<html>
	<head>
		<meta charset="UTF-8">
		<title> Login </title>
	</head>
	<body>
	<?php
		if(isset($_GET["error"]) && $_GET["error"] == "invalido")
			echo "<b>Utilizador ou password invalidos</b>";
	?>
		<form action="main.php" method="post">
			Email:<br>
			<input type="text" name="email">
			<br>
			
			Password:<br>
			<input type="text" name="password">
			<br>
			
			<input type="submit" value= "Entrar">
		</form>
	</body>
</html>