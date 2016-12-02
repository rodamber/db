<!DOCTYPE html>
<html>
<body>

    <center><h1>Adicionar Espaço</h1></center>


   <h3> <?=$_REQUEST['morada']?>  <?=$_REQUEST['codigo']?> </h3>
 <form action="addedE.php" method="post">
 <p><input type="hidden" name="morada"
value="<?=$_REQUEST['morada']?>"/><input type="hidden" name="codigo"
value="<?=$_REQUEST['codigo']?>"/></p>
 <p>Morada: <input type="text" name="morada"/></p>
<p>Codigo: <input type="number" name="codigo"/></p>       
 <p><input type="submit" value="Submit"/></p>
 </form>
   
    
    
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

    $sql="insert into edificio (morada) values('$morada');";
    $sql.="insert into alugavel (morada, codigo) values('$morada', '$codigo');";
    $sql.="insert into espaco (morada, codigo) values('$morada', '$codigo');";
    
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
    