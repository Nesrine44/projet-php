<?php
    session_start();

    if(isset($_SESSION['login']) && isset($_SESSION['annee'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');
        require_once('includes/connexionDefault.php');

        $json = '';

        if(!has_Droit($idUser, "Editer parametres")){

            header('Location:index.php');

        }else{
        		if(!isset($_GET['id'])){

        			header('Location:index.php');

        		}else{

        			$idfidele = htmlentities(intval($_GET['id']));

	                try{

	                	$annee_encours = $_SESSION['annee'];
	                	$base_old = "paroisse".$annee_encours;
	                                       
	                    $query = "UPDATE ".$base_old.".fidele SET lisible = 1  WHERE idfidele = $idfidele AND etat = 1";
		                $active = $db->prepare($query);
		                $active->execute(); 

		                                   

	                }catch(Exception $ex){

	                	$json = $ex->getMessage();
	                }
	            }

	            echo json_encode($json);
		}
	}else{

		header('Location:login.php');
	}
?>
