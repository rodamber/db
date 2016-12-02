<!DOCTYPE html>
<html>
<body>

    <center><h1>Criar Oferta</h1></center>


   <h3> <?=$_REQUEST['numero']?></h3>
 <form action="addedR.php" method="post">
 <p><input type="hidden" name="morada"
value="<?=$_REQUEST['morada']?>"/><input type="hidden" name="codigo"
value="<?=$_REQUEST['codigo']?>"/><input type="hidden" name="data_incio"
value="<?=$_REQUEST['data_inicio']?>"/><input type="hidden" name="data_fim"
value="<?=$_REQUEST['data_fim']?>"/></p>
 <p>Morada: <input type="text" name="morada"/></p>
<p>Código: <input type="number" name="codigo"/></p>
<p>Data_Inicio: <input type="text" name="data_inicio"/></p>   
<p>Data_Fim: <input type="text" name="data_fim"/></p>
<p>Tarifa: <input type="text" name="tarifa"/></p>
 <p><input type="submit" value="Submit"/></p>
 </form>
    
<?php
    
    $morada=$_REQUEST['morada'];
    $codigo=$_REQUEST['codigo'];
    $data_inicio=$_REQUEST['data_inicio'];
    $data_fim=$_REQUEST['data_fim'];
    $tarifa=$_REQUEST['tarifa'];
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
    $sql.="insert into oferta (morada, codigo, data_inicio, data_fim, tarifa) values('$morada', '$codigo', '$data_inicio', '$data_fim', '$tarifa');";
    
    
    
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
    