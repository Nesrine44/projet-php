<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Supprimer zone")){

            header('Location:index.php');

        }else{
            
            $idzone=$_GET['code'];

            $update = $db->prepare("UPDATE Zone SET lisible = 0 WHERE idzone = $idzone");
            $update->execute();

        }

    }else{
        header('Location:login.php');
    }
?>