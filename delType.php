<?php
session_start();

if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login'];
    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');

    if(!has_Droit($idUser, "Enregistrer contribution")){

        header('Location:index.php');

    }else{
			$id = $_GET['id'];
         
			//$query = "DELETE FROM contribution WHERE idcontribution = $id";
			$update = $db->prepare("UPDATE contribution SET lisible = 0 where idcontribution= $id");
            $update->execute();
            echo json_encode($id);


    }
}
