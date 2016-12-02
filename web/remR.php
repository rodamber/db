<!DOCTYPE html>
<html>
<body>

    <center><h1>Remover Oferta</h1></center>


   <h3> <?=$_REQUEST['numero']?></h3>
 <form action="addedR.php" method="post">
 <p><input type="hidden" name="morada"
value="<?=$_REQUEST['morada']?>"/><input type="hidden" name="codigo"
value="<?=$_REQUEST['codigo']?>"/><input type="hidden" name="data_incio"
value="<?=$_REQUEST['data_inicio']?>"/></p>
 <p>Morada: <input type="text" name="morada"/></p>
<p>Código: <input type="number" name="codigo"/></p>
<p>Data_Inicio: <input type="text" name="data_inicio"/></p>
 <p><input type="submit" value="Submit"/></p>
 </form>
    
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
    
     $db = null;
     }
     catch (PDOException $e)
     {
     echo("<p>ERROR: {$e->getMessage()}</p>");
     }


?>
    
    
    <?php
        $link_address1 = 'bd.php';
    echo "<a href='$link_address1'>Voltar</a>";
?>
   
    

</body>
</html>
    