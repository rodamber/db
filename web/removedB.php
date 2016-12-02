<!DOCTYPE html>
<html>
<body>

    <center><h1>Remover Edifício</h1></center>


   
    
<?php
    
    $morada=$_REQUEST['morada'];
    
try
 {
     $host = "db.ist.utl.pt";
     $user ="ist178742";
     $password = "hnum2031";
     $dbname = $user;

     $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    
    
    $sql="delete from edificio where morada ='$morada';";
    
 
    echo("A morada $morada foi removida com sucesso.");

            
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
    