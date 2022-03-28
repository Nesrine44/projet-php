<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Editer role")){

            header('Location:index.php');

        }else{
		  
			foreach($_POST['choix'] as $idDroit)
			{
				$val = false;
				if($idDroit){

					$val = true;
				}else{

					$val = false;
				}					
			  $insert = $db->prepare("UPDATE  roledroit SET hasdroit = $val WHERE droit_iddroit = $isDroit");
                $insert->execute();
			  
			}
			$db=NULL;

			header('location:editRole.php');
		}
			
	}else{
		header('Location:login.php');
    }
	
?>