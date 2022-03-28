<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');


        if(!has_Droit($idUser, "Modifier un fidele")){

            header('Location:index.php');

        }else{ 
              
              $data['text'] = $_POST;
               $json  = '';

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

            if(!isset($_GET['idpersonne']) || !isset($_GET['sexe']) || !isset($_GET['statut_pro']) || !isset($_GET['code']) ){

                header('Location:index.php');

            }else{

                $annee = date('y');             
                 $nbre = 0;  
                 $idpersonne = 0;       
                 $prefixe = "";
                 $codeFidele = "";
                $conf='';
                $bap='';
                $mal='';
                 $nomphoto = "";
                 $idzone = 0;
                $date_day=date('Y-m-d');
                $idpersonne = $_GET['idpersonne'];
                $old_sexe = $_GET['sexe'];
                $old_statut_pro = $_GET['statut_pro'];
                $old_code = $_GET['code'];

                if(!isset($_POST['nom']) || empty($_POST['nom']) ||
                           !isset($_POST['dateNaiss']) || empty($_POST['dateNaiss']) ||
                           !isset($_POST['lieunaiss']) || empty($_POST['lieunaiss']) ||
                           !isset($_POST['sexe']) || empty($_POST['sexe']) ||
                           !isset($_POST['email']) || empty($_POST['email']) ||
                           !isset($_POST['statut_pro']) || empty($_POST['statut_pro']) ||
                           !isset($_POST['statut']) || empty($_POST['statut']) ||
                           !isset($_POST['quartier']) ||  
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

                                
                                
                                    $nomphoto = $_FILES['photo']['name'];

                                    if($nomphoto == ""){

                                        //$nomphoto = "avatar1_small.jpg";
                                    }else{
                                                                                
                                        $file_tmp_name=$_FILES['photo']['tmp_name'];
                                        $extension = pathinfo($nomphoto, PATHINFO_EXTENSION);
                                        $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                                        if (in_array($extension, $extensions_autorisees))
                                        {
                                            $nomphoto = getNom().'.'.$extension;
                                            move_uploaded_file($file_tmp_name,"./images/$nomphoto");

                                        }else{

                                            $json = "Format de photo incorrect. Le format doit être jpg, png, gif ou jpeg";
                                        }
                                       
                                    }

                                        $idzone = $_POST['quartier'];

                                        $nom1 = addslashes(strtoupper($_POST['nom']));
                                        $prenom1 = (isset($_POST['prenom'])? addslashes(strtoupper($_POST['prenom'])):"");
                                        $dateNaiss1 = $_POST['dateNaiss'];
                                        $lieunaiss = addslashes(strtoupper($_POST['lieunaiss']));
                                        $sexe = $_POST['sexe'];
                                        $email1 = addslashes($_POST['email']);
                                        $statut_pro = strtoupper($_POST['statut_pro']);
                                        $statut = addslashes(strtoupper($_POST['statut']));                         
                                        $tel1 = addslashes($_POST['tel']);
                                        $pere = (isset($_POST['pere'])? addslashes(strtoupper($_POST['pere'])):""); 
                                        $pere_vivant = (isset($_POST['pere_vivant'])? $_POST['pere_vivant']:1); 
                                        /*$nom_mere = (isset($_POST['nom_mere'])? addslashes(strtoupper($_POST['nom_mere'])):""); */
                                        $nom_mere = addslashes(strtoupper($data['text']['nom_mere']));
                                        $mere_vivante = (isset($_POST['mere_vivante'])? $_POST['mere_vivante']:1); 
                                        $situation = (isset($_POST['situation'])? strtoupper($_POST['situation']):"");  
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
                                       
                                        $exist = $db->prepare("SELECT email from personne WHERE lisible = true and email = '$email1' and personne.idpersonne != $idpersonne");
                                        $exist->execute();

                                        if($e = $exist->fetch(PDO::FETCH_OBJ)){

                                            $json = 'Cette adresse email existe déjà!!!';

                                        }else{
                                                
                                              

                                                   if($sexe=='Masculin'){
                                                        if($statut_pro =='ETUDIANT'){
                                                            
                                                             $prefixe ="EG";

                                                        }else{

                                                             $prefixe ="HM";
                                                        }
                                                    }else{

                                                        if($statut_pro =='ETUDIANT'){
                                                            
                                                           $prefixe="EF";

                                                        }else{
                                                            
                                                            $prefixe="FM";
                                                        }
                                                    }
                                                        //generation du code

                                                    $codeFidele = substr($old_code, 0, 7).$prefixe;

                                             
                                                    

                                                       // $codeFidele = substr($old_code, 0, 7).$prefixe;
                                                       //$json = $codeFidele;

                                                        $requete = "UPDATE personne set nom='$nom1', prenom='$prenom1',
                                                                            datenaiss='$dateNaiss1', lieunaiss='$lieunaiss',
                                                                            sexe='$sexe', email='$email1',
                                                                            zone_idzone=$idzone,
                                                                            telephone='$tel1', pere='$pere',
                                                                            pere_vivant=$pere_vivant, mere='$nom_mere',
                                                                            mere_vivant=$mere_vivante, ";

                                                            if($nomphoto){$requete .="photo = '".$nomphoto."', ";}

                                                            if($situation=='CELIBATAIRE'){
                                                                $requete .=" conjoint='', religion_conjoint=''";
                                                            }else{
                                                                $requete .=" conjoint='$conjoint', religion_conjoint='$religion'";
                                                            }

                                                            if($statut_pro!='ETUDIANT'){
                                                                $requete .=", etablissement='', niveau='', serie=''";
                                                            }else{
                                                                $requete .=", etablissement='$etablissement', niveau='$niveau', serie='$serie'";
                                                            }


                                                            if($statut_pro=='ETUDIANT' || $statut_pro=='COMMERCANT' || $statut_pro=='SANS EMPLOI'){
                                                                $requete .=", profession='', employeur=''";
                                                            }else{
                                                                $requete .=", profession='$profession', employeur='$employeur'";
                                                            }

                                                          $requete .= ", domaine='$domaine', diplome='$diplome',
                                                                        annee_enregistrement = '$annee_inscription',
                                                                        statut_pro='$statut_pro',
                                                                        village='$village', arrondissement=$arrondissement,
                                                                        situation_matri='$situation',
                                                                        nombre_enfant=$nbre_enfant
                                                                        WHERE idpersonne=$idpersonne AND lisible = 1";

                                                          $insert1 = $db->prepare($requete);

                                                          $insert1->execute();
                                                       
                                                            //mise à jour du fidele
                                                            $insert3=$db->prepare("UPDATE fidele set codefidele = '$codeFidele', statut='$statut' where personne_idpersonne=$idpersonne");
                                                            $insert3->execute();

                                                            //mise à jour de la situation du bapteme
                                                            $selectId = $db->prepare("SELECT idfidele AS id FROM fidele where lisible = true AND personne_idpersonne = $idpersonne");
                                                                $selectId->execute();

                                                                $idfidele = 0;

                                                                while($x = $selectId->fetch(PDO::FETCH_OBJ)){

                                                                    $idfidele = $x->id;
                                                                }

                                                                 if($bapteme == 1){

                                                                    $ps=$db->prepare("SELECT idbapteme
                                                                                      FROM bapteme
                                                                                      WHERE fidele_idfidele = $idfidele");
                                                                     $ps->execute();

                                                                     if($b = $ps->fetch(PDO::FETCH_OBJ)){
                                                                         $insertB = $db->prepare("UPDATE  bapteme SET  datebaptise='$date_bapteme', lieu_baptise='$lieu_bapteme', lisible = 1 WHERE fidele_idfidele = $idfidele");
                                                                         $insertB->execute();
                                                                     }else{
                                                                         $insertBap = $db->prepare("INSERT into bapteme VALUES('', '$date_bapteme', '$lieu_bapteme', true, $idfidele, '$date_day')");
                                                                         $insertBap->execute();
                                                                     }
                                                                 }



                                                                 if($confirme == 1){

                                                                     $ps1=$db->prepare("SELECT idconfirmation
                                                                                      FROM confirmation
                                                                                      WHERE fidele_idfidele = $idfidele");
                                                                     $ps1->execute();

                                                                     if($c = $ps1->fetch(PDO::FETCH_OBJ)){
                                                                         $insertC = $db->prepare("UPDATE confirmation SET date_confirmation = '$date_confirme', lieu_confirmation = '$lieu_confirme', lisible = 1 WHERE fidele_idfidele = $idfidele");
                                                                         $insertC->execute();
                                                                     }else{
                                                                         $insertConf = $db->prepare("INSERT into confirmation VALUES('', '$date_confirme', '$lieu_confirme', $idfidele, true, '$date_day')");
                                                                         $insertConf->execute();
                                                                     }

                                                                 }



                                                                 if($malade == 1){

                                                                     $ps2=$db->prepare("SELECT idmalade
                                                                                      FROM malade
                                                                                      WHERE fidele_idfidele = $idfidele");
                                                                     $ps2->execute();

                                                                     if($m = $ps2->fetch(PDO::FETCH_OBJ)){
                                                                         $insertM = $db->prepare("UPDATE  malade SET lisible = 0 WHERE fidele_idfidele = $idfidele");
                                                                         $insertM->execute();
                                                                     }else{
                                                                         $insertMal = $db->prepare("INSERT into malade VALUES('', '$guide', '$date_day', '', '', '', '', '', 0, 0, true, $idfidele)");
                                                                         $insertMal->execute();
                                                                     }

                                                                 }
                                                                    
                                                                  }
                                                                }
                                                              }

                                                             echo json_encode($json);

                                                            }
        
    }else{

        header('Location:login.php');
    }
?>
