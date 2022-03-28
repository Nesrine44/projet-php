<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Editer parametres")){

            header('Location:index.php');

        }else{

        		$data['text'] = $_POST;

	             $annee = $_SESSION['annee'];
		 		 $json  = '';
		 		 $nomlogo = "";
		 		
		 		  

	             function getNom(){
	             	 $characts    = 'abcdefghijklmnopqrstuvwxyz';
				     $characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				    $characts   .= '1234567890';
				    $code_aleatoire      = '';
				 
				    for($i=0;$i < 4;$i++)
				    {
				        $code_aleatoire .=substr($characts,rand()%(strlen($characts)),1);
				    }

				    return $code_aleatoire;
	             }

		 		/* function isAjax(){
	        		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
	      		}
	      		if(isAjax()){*/

			 		 if(!isset($_POST['sigle']) || empty($_POST['sigle']) ||
			 		 	   !isset($_POST['nom_paroisse']) || empty($_POST['nom_paroisse']) ||		 		 	   
			 		 	   !isset($_POST['ville']) || empty($_POST['ville']) ||
			 		 	   !isset($_POST['email_paroisse']) || empty($_POST['email_paroisse']) ||
			 		 	   !isset($_POST['telephone']) || empty($_POST['telephone']) ||
			 		 	   !isset($_POST['date_paroisse']) || empty($_POST['date_paroisse'])
			 		 	){
			 		 			
							$json = 'veuillez remplir tous les champs';

			 		 	}else{

			 		 		//if($_GET['file'] != ''){
			 		 			
			 		 				$nomlogo = $_FILES['logo']['name'];
			 		 				if($nomlogo == ""){

			 		 					//$nomlogo = "avatar1_small.jpg";
			 		 				}else{
			 		 								 		 					
						                $file_tmp_name=$_FILES['logo']['tmp_name'];
                                        $extension = pathinfo($nomlogo, PATHINFO_EXTENSION);
                                        $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                                        if (in_array($extension, $extensions_autorisees))
                                        {
                                            $nomlogo = getNom().'.'.$extension;
                                            move_uploaded_file($file_tmp_name, realpath(".")."/report/".$nomlogo );

                                            $nomlogo =  addslashes(realpath(".")."/report/".$nomlogo);

                                        }else{

                                            $json = "Format de logo incorrect. Le format doit être jpg, png, gif ou jpeg";
                                        }	               
					               }

				 		 	$nom = addslashes(strtoupper($_POST['nom_paroisse']));
				 		 	$sigle = addslashes(strtoupper($_POST['sigle']));
			                $bp = (isset($_POST['bp'])? $_POST['bp'] : 0);
							$ville = addslashes(strtoupper($_POST['ville']));
							$email = addslashes($_POST['email_paroisse']);
			                $site = (isset($_POST['site_web'])? addslashes($_POST['site_web']):"");
			                $siege = addslashes(strtoupper($_POST['siege']));
			                $telephone = addslashes($_POST['telephone']);
			                $date_paroisse = $_POST['date_paroisse'];

					        try{
					          
					          	

					          	$requete = "UPDATE `parametre` SET `sigle`='$sigle', `nom`='$nom',`siege`='$siege',`bp`=$bp,`ville`='$ville',`email_paroisse`='$email',`site_web`='$site',`telephone`='$telephone',`date_paroisse`='$date_paroisse' ";

					          	if($nomlogo){$requete .=", logo = '".$nomlogo."'";}

					          	$requete .= " WHERE idparametre = 1";

					          	

					          	$update = $db->prepare($requete);
					          	if($update->execute()){

					          		 $json = '1';

					          		
					          	}else{

					          	$json = '0';
					          }
						    
						    }catch(Exception $ex){

						            	
		    							$json = 'Erreur '.$ex->getMessage().': lors de l\'enregistrement, veuillez contacter l\'administrateur';
						    }
						            
						}

						  echo json_encode($json);
						    
		}
}else{

	header('Location:login.php');
}
     

?>