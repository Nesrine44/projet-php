

<?php


  $annee = date('Y');

  $base = "paroisse".$annee;

    $db = new PDO('mysql:host=localhost;dbname='.$base, 'paroisse', 'paroisse#2016');
 
?>
