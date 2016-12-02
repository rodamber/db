<!DOCTYPE html>
<html>
<body>

    <center><h1>Remover Posto de Trabalho</h1></center>


   
    
<?php
    
    $morada=$_REQUEST['morada'];
    $codigo=$_REQUEST['codigo'];
    $codigo_espaco=$_REQUEST['codigo_espaco'];
    $foto=$_REQUEST['foto'];
    
try
 {
     $host = "db.ist.utl.pt";
     $user ="ist178742";
     $password = "hnum2031";
     $dbname = $user;

     $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    
    
     $sql="delete from posto where morada='$morada' and codigo='$codigo' and codigo_espaco='$codigo_espaco';";
    $sql.="delete from alugavel where morada='$morada' and codigo='$codigo' and foto='$foto';";
    
 
    echo("O posto com a morada $morada e com o código $codigo foi removido com sucesso.");

            
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
    