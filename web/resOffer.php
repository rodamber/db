<!DOCTYPE html>
<html>
<body>

    <center><h1>Criar Reserva sobre Oferta</h1></center>


   <h3> <?=$_REQUEST['morada']?>  <?=$_REQUEST['codigo']?> </h3>
 <form action="reservedOffer.php" method="post">
 <p><input type="hidden" name="oferta"
value="<?=$_REQUEST['oferta']?>"/><input type="hidden" name="morada"
value="<?=$_REQUEST['morada']?>"/><input type="hidden" name="codigo"
value="<?=$_REQUEST['codigo']?>"/></p>
<p>Oferta: <input type="text" name="oferta"/></p>
<p>Morada: <input type="text" name="morada"/></p>
<p>Codigo: <input type="text" name="codigo"/></p>     
 <p><input type="submit" value="Submit"/></p>
 </form>
   
    
    
<?php
    
    $oferta=$_REQUEST['oferta'];
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

    $sql="from ofertas select not in reservas ;";
    
    
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
    