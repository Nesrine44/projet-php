<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
       require_once('includes/function_role.php');

	    if(!has_Droit($idUser, "Supprimer activite")){

            header('Location:index.php');

        }else{

        	if(!isset($_GET['id'])){

        		header('Location:index.php');
        	}else{

			    $ident=$_GET['id'];
			    $req2="update activite set lisible=0 where idactivite=$ident";
			    $db->exec($req2);
			    $db=NULL;	
		    }	   
	 }

	}else{

		header('Location:login.php');
	}
?>