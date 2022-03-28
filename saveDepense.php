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

        $raison = $_POST['motif'];
        $traveau = $_POST['traveau'];
        $montant = $_POST['montant'];
        $executant = $_POST['executant'];
        $date = $_POST['date'];



$query = "INSERT INTO `depenses`( `montant`, `motif`, `lisible`, `utilisateur_idutilisateur`, `date`, `heure`, `traveau`, `date_d`) 
VALUES ('$montant','".$raison."',1,'".$executant."', '".$date."', now(), '".$traveau."', '".$date_day."')";

            $insert = $db->prepare($query);
           // echo($query."\n");
            $insert->execute();
            echo json_encode('ok');
        }
                
}else{

    header('Location:index.php');

}

    