<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Editer role")){

            header('Location:index.php');

        }else{

				$idDroit = $_GET['id'];
				$val = $_GET['hasDroit'];
				$idRole = $_SESSION['role'];

			  $update = $db->prepare("
                LOCK TABLES roledroit WRITE;
                UPDATE  roledroit SET hasdroit = $val WHERE droit_iddroit = $idDroit and role_idrole = $idRole;
                UNLOCK TABLES;
                ");
              $update->execute();
			
		}  

		}else{
			header('Location:login.php');
    	}
	
?>