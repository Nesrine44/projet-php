<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        
            if(!has_Droit($idUser, "supprimer user")){

            	header('Location:index.php');
                
            }else{
    		    $id=$_GET['id'];

    		    $insert1 = $db->prepare("update utilisateur set lisible=0 where idutilisateur=$id;");
    		    $insert1->execute();

    		    $insert2 = $db->prepare("update userrole set lisible=0 where utilisateur_idutilisateur=$id;");
    		    $insert2->execute();
    		}

        

	}else{
        header('Location:login.php');
    }
?>