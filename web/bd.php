<!DOCTYPE html>
<html>
<body>

    <center><h1>Projecto de Bases de Dados</h1></center>
    <p><center><h1><font size="4">Grupo 67<br>Professor Gabriel Pestana</font></h1></center></p>
    <p><center><h1><font size="3">Turno: Quinta-feira 12h30</font></h1></center></p>
    
    
    <p>Opções de manipulação da Base de Dados</p>


<?php
try
 {
     $host = "db.ist.utl.pt";
     $user ="ist178742";
     $password = "hnum2031";
     $dbname = $user;

     $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    
    
     $db = null;
     }
     catch (PDOException $e)
     {
     echo("<p>ERROR: {$e->getMessage()}</p>");
     }

    
    
    $link_address1 = 'addB.php';
    echo "<a href='$link_address1'>Adicionar Edifício</a><br>";
    
    $link_address1 = 'remB.php';
    echo "<a href='$link_address1'>Remover Edifício</a><br><br>";
    
    
    
    $link_address1 = 'addE.php';
    echo "<a href='$link_address1'>Adicionar Espaço</a><br>";
    
    $link_address1 = 'remE.php';
    echo "<a href='$link_address1'>Remover Espaço</a><br><br>";
    
    
    
    $link_address1 = 'addWP.php';
    echo "<a href='$link_address1'>Adicionar Posto de Trabalho</a><br>";
    
    $link_address1 = 'remWP.php';
    echo "<a href='$link_address1'>Remover Posto de Trabalho</a><br><br>";
    
    
    
     $link_address1 = 'addR.php';
    echo "<a href='$link_address1'>Adicionar Oferta</a><br>";
    
    $link_address1 = 'remR.php';
    echo "<a href='$link_address1'>Remover Oferta</a><br><br>";
    
    
    
    $link_address1 = 'resOffer.php';
    echo "<a href='$link_address1'>Criar Reserva sobre Oferta</a><br><br>";
    
    
    $link_address1 = 'payRes.php';
    echo "<a href='$link_address1'>Pagar Reserva(s)</a><br><br>";
    
    
    $link_address1 = 'resB.php';
    echo "<a href='$link_address1'>Mostrar o Total Realizado Para Cada Espaço</a><br><br>"; 
    
?>
    
    <!-- para o resB(reservas do edificio)
    
    o user da uma morada e para esse edificio mostrar o toltal realizado que são todas as reservas associadas a esse espaço que são pagas e não interessa o ano 

para mostrar a tabela, if needed eentually (to place before all of the links):


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



-->
  

</body>
</html>
    