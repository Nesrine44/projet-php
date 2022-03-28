<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Supprimer contribution")){

            header('Location:index.php');

        }else{

            function isAjax(){
                    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
            }
        	
            if(isAjax()){

                if(!isset($_GET['id']))
                {
                    header('Location:index.php');
                }else{
                    
        			$ident=$_GET['id'];
        			$req2="update contributionfidele set lisible=0  where  idcontributionfidele = $ident";
        			$db->exec($req2);
        			$db=NULL;
                }
            }else{
                header('Location:index.php');
            }

		}

	}else{

		header('location:login.php');
	}
?>