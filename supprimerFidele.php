<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Supprimer fidele")){

            header('Location:index.php');

        }else{
            
            $idpersonne=$_GET['code'];

            $update = $db->prepare("UPDATE fidele, personne SET fidele.lisible = 0, fidele.etat = 0, personne.lisible = 0
                                    WHERE personne.idpersonne = fidele.personne_idpersonne
                                    AND personne.idpersonne = $idpersonne");
            $update->execute();

        }

    }else{
        
        header('Location:login.php');
    }
?>