<!DOCTYPE html>
<html>
<body>

    <center><h1>Pagar Reserva</h1></center>


   
    
<?php
    
    $numero=$_REQUEST['numero'];
    
try
 {
     $host = "db.ist.utl.pt";
     $user ="ist178742";
     $password = "hnum2031";
     $dbname = $user;

     $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $sql="insert into paga (numero) values('$numero');";
    
    
    
    
    echo("A reserva com o número $numero foi paga com sucesso");

            
    $db-> query($sql);
    
     $db = null;
     }
     catch (PDOException $e)
     {
     echo("<p>ERROR: {$e->getMessage()}</p>");
     }

    
?>
<br>
     <?php
        $link_address1 = 'bd.php';
    echo "<a href='$link_address1'>Voltar</a>";
?>

</body>
</html>
    