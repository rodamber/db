<!DOCTYPE html>
<html>
<body>

    <center><h1>Adicionar Posto de Trabalho</h1></center>


   <h3> <?=$_REQUEST['morada']?>  <?=$_REQUEST['codigo']?> </h3>
 <form action="addedWP.php" method="post">
 <p><input type="hidden" name="morada"
value="<?=$_REQUEST['morada']?>"/><input type="hidden" name="codigo"
value="<?=$_REQUEST['codigo']?>"/><input type="hidden" name="codigo_espaco"
value="<?=$_REQUEST['codigo_espaco']?>"/><input type="hidden" name="foto"
value="<?=$_REQUEST['foto']?>"/></p>
<p>Morada: <input type="text" name="morada"/></p>
<p>Codigo: <input type="number" name="codigo"/></p>
<p>Codigo_espaço: <input type="text" name="codigo_espaco"/></p>
<p>Foto: <input type="text" name="foto"/></p>
 <p><input type="submit" value="Submit"/></p>
 </form>
   
    
    
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


    $sql="insert into alugavel (morada, codigo, foto) values('$morada', '$codigo', '$foto');";
    $sql.="insert into posto (morada, codigo, codigo_espaco) values('$morada', '$codigo', '$codigo_espaco');";
    
    
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
    