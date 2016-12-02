<!DOCTYPE html>
<html>
<body>

    <center><h1>Remover Edifício</h1></center>


   <h3> <?=$_REQUEST['account_number']?></h3>
 <form action="removedB.php" method="post">
 <p><input type="hidden" name="morada"
value="<?=$_REQUEST['morada']?>"/></p>
 <p>Morada: <input type="text" name="morada"/></p>
 <p><input type="submit" value="Submit"/></p>
 </form>
    
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
    