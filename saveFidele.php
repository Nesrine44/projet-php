<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Creer un fidele")){

            header('Location:index.php');

        }else{

        		$data['text'] = $_POST;

	             $annee = date('y');             
		 		 $nbre = 0;  
		 		 $idpersonne = 0;       
		 		 $prefixe = "";
		 		 $codeFidele = "";
		 		 $json  = '';
		 		 $nomphoto = "";
		 		 $idzone = 0;

		 		  function getCode($annee, $nbre, $prefixe){

	                $code = ""; 
	                if($nbre<10){

	                $code = $annee."000".$nbre."-".$prefixe;

	                }elseif ($nbre<100 and $nbre>=10) {

	                    $code = $annee."00".$nbre."-".$prefixe;

	                }elseif ($nbre<1000 and $nbre>=100) {

	                    $code = $annee."0".$nbre."-".$prefixe;

	                }elseif ($nbre>=1000) {

	                    $code = $annee.$nbre."-".$prefixe;
	                }

	                return $code;
	             }

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

			 		 if(!isset($_POST['nom']) || empty($_POST['nom']) ||
			 		 	   !isset($_POST['dateNaiss']) || empty($_POST['dateNaiss']) ||
			 		 	   !isset($_POST['lieunaiss']) || empty($_POST['lieunaiss']) ||
			 		 	   !isset($_POST['sexe']) || empty($_POST['sexe']) ||
			 		 	   !isset($_POST['email']) || empty($_POST['email']) ||
			 		 	   !isset($_POST['statut_pro']) || empty($_POST['statut_pro']) ||
			 		 	   !isset($_POST['statut']) || empty($_POST['statut']) ||
			 		 	   !isset($_POST['lieu']) || empty($_POST['lieu']) ||
			 		 	   !isset($_POST['tel']) || empty($_POST['tel']) 
			 		 	){
							$json = 'veuillez remplir tous les champs';
			 		 	}else{
			 		 		$etat = 0;
			 		 		if(isset($_POST['choix'])){
			 		 			foreach ($_POST['choix'] as $val) {
			 		 				$etat = $val;
			 		 			}
			 		 		}

			 		 		$idzone = $_POST['lieu'];
							//if($_GET['file'] != ''){
							
							$nomphoto = $_FILES['photo']['name'];
							if($nomphoto == ""){
								$nomphoto = "avatar1_small.jpg";
							}else{									
								$file_tmp_name=$_FILES['photo']['tmp_name'];
								$extension = pathinfo($nomphoto, PATHINFO_EXTENSION);
								$extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
								if (in_array($extension, $extensions_autorisees))
								{
									$nomphoto = getNom().'.'.$extension;
									move_uploaded_file($file_tmp_name,"./images/$nomphoto");
								}else{
									$nomphoto = "avatar1_small.jpg";
								}
							}

				 		 	$nom1 = addslashes(strtoupper($_POST['nom']));
			                $prenom1 = (isset($_POST['prenom'])? addslashes(strtoupper($_POST['prenom'])):"");
			                $dateNaiss1 = $_POST['dateNaiss'];
			                $lieunaiss = addslashes(strtoupper($_POST['lieunaiss']));
			                $sexe1 = $_POST['sexe'];
			                $email1 = addslashes($_POST['email']);
			                $statut_pro = strtoupper($_POST['statut_pro']);
			                $statut = addslashes(strtoupper($_POST['statut']));			                
			                $tel1 = $_POST['tel'];
			                $pere = (isset($_POST['pere'])? addslashes(strtoupper($_POST['pere'])):""); 
			                $pere_vivant = (isset($_POST['pere_vivant'])? $_POST['pere_vivant']:1); 
			                $mere = (isset($_POST['mere'])? addslashes(strtoupper($_POST['mere'])):""); 
			                $mere_vivante = (isset($_POST['mere_vivante'])? $_POST['mere_vivante']:1); 
			                $situation = (isset($_POST['situation'])? strtoupper(($_POST['situation'])):"");  
			                $conjoint = (isset($_POST['conjoint'])? addslashes(strtoupper($_POST['conjoint'])):""); 
			                $religion = (isset($_POST['religion'])? addslashes(strtoupper($_POST['religion'])):""); 
			                $religion_autre = (isset($_POST['religion_autre'])? addslashes(strtoupper($_POST['religion_autre'])):""); 
			                $nbre_enfant = (isset($_POST['nbre_enfant'])? $_POST['nbre_enfant']:0); 
			                $village = (isset($_POST['village'])? addslashes(strtoupper($_POST['village'])):""); 
			                $arrondissement = (isset($_POST['arrondissement'])? $_POST['arrondissement']:1); 
			                $etablissement = (isset($_POST['etablissement'])? addslashes(strtoupper($_POST['etablissement'])):""); 
			                $niveau = (isset($_POST['niveau'])? addslashes(strtoupper($_POST['niveau'])):""); 
			                $serie = (isset($_POST['filiere'])? addslashes(strtoupper($_POST['filiere'])):"");  
			                $profession = (isset($_POST['profession'])? addslashes(strtoupper($_POST['profession'])):""); 
			                $employeur = (isset($_POST['employeur'])? addslashes(strtoupper($_POST['employeur'])):""); 
			                $diplome = (isset($_POST['diplome'])? addslashes(strtoupper($_POST['diplome'])):""); 
							$domaine = (isset($_POST['domaine'])? addslashes(strtoupper($_POST['domaine'])):""); 
							$annee_inscription = (isset($_POST['anneeMin_inscription'])? $_POST['anneeMin_inscription']:$annee); 
							$bapteme = (isset($_POST['baptise'])? $_POST['baptise']:0); 
							$date_bapteme = (isset($_POST['date_bapteme'])? $_POST['date_bapteme']:$date_day);
							$lieu_bapteme = (isset($_POST['lieu_bapteme'])? addslashes(strtoupper($_POST['lieu_bapteme'])):"");  
							$malade = (isset($_POST['malade'])? $_POST['malade']:0);
							$date_maladie = (isset($_POST['date_maladie'])? $_POST['date_maladie']:$date_day);  
							$guide = (isset($_POST['guide'])? addslashes(strtoupper($_POST['guide'])):""); 
							$membre_groupe = (isset($_POST['membre_groupe'])? $_POST['membre_groupe']:0);
							$confirme = (isset($_POST['confirme'])? $_POST['confirme']:0); 
							$date_confirme = (isset($_POST['date_confirme'])? $_POST['date_confirme']:$date_day);
							$lieu_confirme = (isset($_POST['lieu_confirme'])? addslashes(strtoupper($_POST['lieu_confirme'])):""); 
			                
							if($religion == 'AUTRE'){$religion = $religion_autre;}
							
							//Vérification différence entre date enregistrement du fidèle et sa date de naissance
							$annee_naiss = substr($dateNaiss1, 0, 4);

			               	//vérifions que'adresse email n'existe pas
			 		 		$exist = $db->prepare("SELECT email from personne where lisible  = 1 and email = '$email1'");
			 		 		$exist->execute();

			 		 		if($x=$exist->fetch(PDO::FETCH_OBJ)){
			 		 			$json  = "Cette addresse email existe déja!";
							}elseif($annee_naiss > $annee_inscription) {
								$json = "La date de naissance doit être inférieure à l'année d'inscription";
							}else{
			 		 			$nbre = 0;
			 		 			$count = $db->prepare("SELECT count(idfidele) as q FROM fidele");
								$count->execute();
								while($s = $count->fetch(PDO::FETCH_OBJ)){
								    $nbre = $s->q+1;
								}

								if($sexe1=='Masculin'){
									if($statut_pro=='ETUDIANT'){
										$prefixe ="EG";
									}else{
										$prefixe ="HM";
									}
								}else{
									if($statut_pro=='ETUDIANT'){
										$prefixe="EF";
									}else{
										$prefixe="FM";
									}
								}

								//generation du code
								$codeFidele = getCode($annee, $nbre, $prefixe);
								$date_day = date('Y-m-d');
								
								try{
								//enregistrement de la personne
									//echo $zone;
									$insert1 = $db->prepare("INSERT INTO personne values('', '$nom1', '$prenom1', '$dateNaiss1', '$lieunaiss', '$sexe1', '$email1', '$profession',$idzone, '$tel1', '$pere', $pere_vivant, '$mere', $mere_vivante, '$nomphoto', true, '$domaine', '$diplome', '$annee_inscription', '$statut_pro', '$employeur', '$village', $arrondissement, '$etablissement', '$niveau', '$serie', '$situation', '$conjoint', $nbre_enfant, '$religion', '$date_day')");
									$insert1->execute();

									 $id=$db->lastInsertId();

									//enregistrement des information sur le fidele
									$insertF = $db->prepare("INSERT INTO fidele values('', '$codeFidele', '$statut', 1, 1, false, '$id', '$date_day',0)");
									$insertF->execute();

									//recuperation du dernier id enregistré
									$selectId = $db->prepare("SELECT last_insert_id() AS id FROM fidele");
									$selectId->execute();
									$idfidele = 0;

									while($x = $selectId->fetch(PDO::FETCH_OBJ)){
										$idfidele = $x->id;
									}

									if($bapteme == 1){
										$insertB = $db->prepare("INSERT into bapteme VALUES('', '$date_bapteme', '$lieu_bapteme', true, $idfidele, '$date_day')");
										$insertB->execute();
									}

									if($confirme == 1){
										$insertC = $db->prepare("INSERT into confirmation VALUES('', '$date_confirme', '$lieu_confirme', $idfidele,  true, '$date_day')");
										$insertC->execute();
									}

									if($malade == 1){
										$insertM = $db->prepare("INSERT INTO malade(guide, dateEnregistrementMaladie, dateDebutMaladie, est_retabli, est_decede, lisible, fidele_idfidele ) VALUES('$guide', '$date_day', '$date_maladie', 0, 0, 1, $idfidele)");
										$insertM->execute();
									}

									if($membre_groupe == 1){
										if(isset($_POST['choix'])){
											if(isset($_POST['annee_inscription_groupe'])){
												$i = 0;
												foreach ($_POST['choix'] as $idgroupe) {
													$an = $_POST['annee_inscription_groupe'][$i];
													$insertG = $db->prepare("INSERT INTO fidelegroupe VALUES('', 1, $idfidele, $idgroupe, '$an')");
													$insertG->execute();
												}
											}
										}
									}
								}catch(Exception $ex){
									$json = 'Erreur '.$ex->getMessage().'\n lors de l\'insertion, veuillez contacter l\'administrateur';
								}
						            
							}
						echo json_encode($json);
						    
		}
	}

}
else{

	header('Location:login.php');
}
     

?>