<!DOCTYPE html>
<html>
<body>

    <center><h1>Adicionar Edifício</h1></center>


<?php
try
 {
     $host = "db.ist.utl.pt";
     $user ="ist178742";
     $password = "hnum2031";
     $dbname = $user;

     $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    
    
     $db = null;
     }
     catch (PDOException $e)
     {
     echo("<p>ERROR: {$e->getMessage()}</p>");
     }


    
    
    
    $link_address1 = 'addB.php';
    echo "<a href='$link_address1'>Adicionar Edifício</a>";
?>
    
    
  

</body>
</html>
    