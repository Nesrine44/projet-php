<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Creer utilisateur")){

            header('Location:index.php');

        }else{

	       	function isAjax(){
	        	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
	      	}
	      	if(isAjax()){

				if(!isset($_POST['personne']) || empty($_POST['personne']) ||
				   !isset($_POST['username']) || empty($_POST['username']) ||
				   !isset($_POST['password']) || empty($_POST['password']) ||
				   !isset($_POST['confirmation']) || empty($_POST['confirmation']) ||
				   !isset($_POST['role']) || empty($_POST['role']) 
				 ){

					header('500 Internal Server Error', true, 500);
						die('Veuillez remplir tous les champs!!!');

				}else{

					$personne = $_POST['personne'];
					$username = addslashes($_POST['username']);
					$password = addslashes($_POST['password']);
					$confirmation =addslashes($_POST['confirmation']);
					$role = $_POST['role'];

					if(strlen($password) < 6){
			    		
							header('500 Internal Server Error', true, 500);
							die('Mot de passe trop court: Au moins 6 caractères!');

			    	}else{
						if($password !== $confirmation){

							header('500 Internal Server Error', true, 500);
							die('Les mots de passe sont differentes!');

						}else{
							
							try{
							//chiffrement du mot de passe
								$pass = md5($password);
								//insertion de l'utilisateur
								$insertUser = $db->prepare("INSERT into utilisateur values('', '$username', '$pass', true, $personne)");
								$insertUser->execute();
								//recuperation de l'Id du user
								$selectIdUser = $db->prepare("SELECT last_insert_id() as id from utilisateur where lisible = true");
								$selectIdUser->execute();
								$idUser =  $selectIdUser->fetch(PDO::FETCH_OBJ)->id;

								//insertion du userRole
								$insertUserRole = $db->prepare("INSERT into userrole values('', true, true, $idUser, $role)");
								$insertUserRole->execute();		
							}catch(Exception $e){
								header('500 Internal Server Error', true, 500);
	                    		die('Erreur '.$ex->getline().': Veuillez contacter l\'administrateur');
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