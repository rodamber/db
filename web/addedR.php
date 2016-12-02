<!DOCTYPE html>
<html>
<body>

    <center><h1>Criar Oferta</h1></center>


   
    
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
    
    
    
    
    
    echo("A reserva numero $numero foi criada com sucesso");

            
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
    