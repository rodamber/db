<!DOCTYPE html>
<html>
<body>

    <center><h1>Projecto de Bases de Dados</h1></center>
    <p><center><h1>Grupo 67</h1></center></p>
    <p><center><h1>Professor Gabriel Pestana</h1></center></p>
    <p><center><h1>Turno: Quinta-feira 12h30</h1></center></p>
    
    <p>User (example)</p>


<?php
try
 {
     $host = "db.ist.utl.pt";
     $user ="ist178742";
     $password = "hnum2031";
     $dbname = $user;

     $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

     $sql = "SELECT nif, nome, telefone FROM user;";

     $result = $db->query($sql);

     echo("<table border=\"1\">\n");
     echo("<tr><td>nif</td><td>nome</td><td>telefone</td></tr>\n");
     foreach($result as $row)
     {
     echo("<tr><td>");
     echo($row['nif']);
     echo("</td><td>");
     echo($row['nome']);
     echo("</td><td>");
     echo($row['telefone']);
     echo("</td></tr>\n");
     }
     echo("</table>\n");

     $db = null;
     }
     catch (PDOException $e)
     {
     echo("<p>ERROR: {$e->getMessage()}</p>");
     }
?>
    
    
  

</body>
</html>
    
