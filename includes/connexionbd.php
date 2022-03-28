
<?php
$annee = null;
  //session_start();
  if(isset($_SESSION['annee'])){

    $annee = $_SESSION['annee']; 
  }else{

    $annee = date('Y');
  }
  

  $base = "paroisse".$annee;

  try{
    $db = new PDO('mysql:host=localhost;dbname=paroise', 'root', '');
    $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
  }catch(Exception $ex){
    header('Location:404.php?error=1');
    die();
  } 
    ini_set('display_errors', '1');
    ini_set('error_reporting', E_ALL);
?>

