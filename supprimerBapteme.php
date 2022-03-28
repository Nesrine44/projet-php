<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
       require_once('includes/function_role.php');

	    if(!has_Droit($idUser, "Supprimer bapteme")){

            header('Location:index.php');

        }else{

        	if(!isset($_GET['code'])){

        		header('Location:index.php');

        	}else{
			    $ident=$_GET['code'];
			    $req2="delete FROM bapteme where idbapteme = $ident";
			    $db->exec($req2);
			    $db=NULL;
			}		   
	 }

	}else{

		header('Location:login.php');
	}
?>