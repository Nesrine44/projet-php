<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Supprimer un groupe")){

            header('Location:index.php');

        }else{
        	
			$ident=$_GET['id'];
			$req2="update groupe set lisible=0 where idgroupe=$ident";
			$db->exec($req2);
			$db=NULL;

		}

	}else{

		header('location:login.php');
	}
?>