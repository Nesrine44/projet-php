<?php

    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Modifier user")){

            header('Location:index.php');

        }else{

         $id = 0;
		 function isAjax(){
	        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
	      }

	      if(isAjax()){

			if(
			   (!isset($_POST['username']) || empty($_POST['username'])) ||
			   (!isset($_POST['password']) || empty($_POST['password'])) ||
			   (!isset($_POST['confirmation']) || empty($_POST['confirmation'])) ||
			   (!isset($_POST['role']) || empty($_POST['role']))
			 ){

				header('500 Internal Server Error', true, 500);
				die('Veuillez remplir tous les champs');
			}else{

				$id = $_GET['id'];

				$selectOldPass = $db->prepare("Select password from utilisateur where idutilisateur = $id and lisible = true");
				$selectOldPass->execute();

				$idPersonne = $_POST['personne'];
			    $username = addslashes($_POST['username']);
			   
			    $password = addslashes($_POST['password']);
			    $confirmation =$_POST['confirmation'];
			    $role = $_POST['role'];
			    $p = $selectOldPass->fetch(PDO::FETCH_OBJ)->password;

			    if(!$p){

			    	header('500 Internal Server Error', true, 500);
					die('Ancien mot de passe incorrect!');

			    }else{

			    	if(strlen($password) < 6){

			    		header('500 Internal Server Error', true, 500);
							die('Mot de passe trop court: Au moins 6 caractères!');

			    	}else{

				    	if($password !== $confirmation){

				    		header('500 Internal Server Error', true, 500);
							die('Les mots de passe sont différents!');
				    	}else{

				    		$pass = md5($password);
					    	$update = $db->prepare("update utilisateur set login = '$username', password = '$pass', personne_idpersonne = $idPersonne where idutilisateur = $id and lisible = true");
			    		    $update->execute();

			    		    $update2 = $db->prepare("update userrole set role_idrole = $role  where utilisateur_idutilisateur=$id");
			    		    $update2->execute();    

		    		   }
		    		}
	    		}
			}
		 }else{


			  	header('Location:index.php');
		 }
	}

}else{

	header('Location:login.php');
}

?>