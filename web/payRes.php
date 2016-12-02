<!DOCTYPE html>
<html>
<body>

    <center><h1>Pagar Reserva</h1></center>


   <h3> <?=$_REQUEST['resNum']?></h3>
 <form action="paidR.php" method="post">
 <p><input type="hidden" name="numero"
value="<?=$_REQUEST['numero']?>"/></p>
 <p>Número da Reserva: <input type="text" name="numero"/></p>
 <p><input type="submit" value="Submit"/></p>
 </form>
    
   
    
    
<?php
    
    $numero=$_REQUEST['numero'];
    
try
 {
     $host = "db.ist.utl.pt";
     $user ="ist178742";
     $password = "hnum2031";
     $dbname = $user;

     $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $sql="insert into paga (numero) values('$numero');";
    
    
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
    