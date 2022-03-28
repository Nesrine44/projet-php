<?php
session_start();

if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login']; 
    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php'); 

    if(!has_Droit($idUser, "Enregistrer Depense")){
 
        header('Location:index.php');

    }else{ 

        $date_day = date('Y-m-d');
        $d = date('Y');

        $intitule = $_POST['intitule'];
        $budget = $_POST['budget'];
        $responsable = $_POST['responsable'];
        $duree = $_POST['duree'];
        $type = $_POST['type'];
        $idp = substr($d, 2).''.genererChaineAleatoire().''.rand(100,190);



$query = "INSERT INTO `traveaux`( `intitule`, `budget`, `lisible`, `utilisateur_idutilisateur`, `responsable`, `duree`, `type`, `idp`) 
VALUES ('".$intitule."','".$budget."',1,$idUser, '".$responsable."','".$duree."', '".$type."', '".$idp."')";

            $insert = $db->prepare($query);
           // echo($query."\n");
            $insert->execute();
            echo json_encode('ok');
        }
                
}else{

    header('Location:index.php');

}

function genererChaineAleatoire($longueur = 10)
{
 $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
 $longueurMax = strlen($caracteres);
 $chaineAleatoire = '';
 for ($i = 0; $i < 2; $i++)
 {
 $chaineAleatoire .= $caracteres[rand(0, $longueurMax - 1)];
 }
 return $chaineAleatoire;
}
//Utilisation de la fonction
 
    