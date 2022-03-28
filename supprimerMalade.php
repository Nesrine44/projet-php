<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Supprimer un malade")){

            header('Location:index.php');

        }else{
            
            $idmalade=$_GET['code'];

            $update = $db->prepare("UPDATE malade SET est_decede = 1, lisible = 0 WHERE idmalade = $idmalade");
            $update->execute();

        }

    }else{
        header('Location:login.php');
    }
?>