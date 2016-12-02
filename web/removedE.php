<!DOCTYPE html>
<html>
<body>

    <center><h1>Remover Espaço</h1></center>


   
    
<?php
    
    $morada=$_REQUEST['morada'];
    $codigo=$_REQUEST['codigo'];
    
try
 {
     $host = "db.ist.utl.pt";
     $user ="ist178742";
     $password = "hnum2031";
     $dbname = $user;

     $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $sql="delete from alugavel where morada='$morada' and codigo='$codigo');";
    $sql.="delete from espaco  where morada='$morada' and codigo='$codigo');";
    
 
    echo("O espaço foi removido com sucesso");

            
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
    