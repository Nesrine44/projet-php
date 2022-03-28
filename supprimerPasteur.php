<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Supprimer pasteur")){

            header('Location:index.php');

        }else{

            $idpersonne=$_GET['idpersonne'];
            $idgrade=$_GET['idgrade'];

            $insert2 = $db->prepare("UPDATE pasteur SET lisible=0 WHERE personne_idpersonne=$idpersonne");
            $insert2->execute();

             $insert3 = $db->prepare("UPDATE personne SET lisible=0 WHERE idpersonne=$idpersonne");
             $insert3->execute();

             $freeGrade = $db->prepare("UPDATE grade SET estpris=0 WHERE idgrade= $idgrade AND lisible=1");
             $freeGrade->execute();

             header('location:listePasteurs.php');
         
            
	    }

	}else{
        header('Location:login.php');
    }
?>