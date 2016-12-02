<!DOCTYPE html>
<html>
<body>

    <center><h1>Adicionar Posto de Trabalho</h1></center>


   
    
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
    
    
    
    echo("O posto com a morada $morada e com o código $codigo foi adicionado com sucesso.");

            
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
    