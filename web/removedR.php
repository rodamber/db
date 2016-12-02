<!DOCTYPE html>
<html>
<body>

    <center><h1>Remover Reserva</h1></center>


   
    
<?php
    
    $morada=$_REQUEST['morada'];
    $codigo=$_REQUEST['codigo'];
    $data_inicio=$_REQUEST['data_inicio'];
    
try
 {
     $host = "db.ist.utl.pt";
     $user ="ist178742";
     $password = "hnum2031";
     $dbname = $user;

     $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    
    
    
    
     $sql="delete from oferta where morada='$morada' and codigo='$morada' and data_inicio= '$data_inicio';";
    $sql.="delete from edificio where morada='$morada';";
    $sql.="delete from alugavel where morada='$morada' and codigo='$morada', '$codigo');";
    
 
    echo("A reserva com o número $numRes foi removida com sucesso");

            
    $db->query($sql);
    
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
    