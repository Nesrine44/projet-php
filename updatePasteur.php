<?php
session_start();

if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login'];
    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');

    if(!has_Droit($idUser, "Modifier un pasteur")){

        header('Location:index.php');

    }else{

        $json='';

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

        if(!isset($_GET['idpersonne'])|| !isset($_GET['grade'])){

            header('Location:index.php');

        }else{

            $annee = $_SESSION['annee'];
            $nbre = 0;

            $nomphoto = "";
            $idzone = 0;

            $idpersonne = $_GET['idpersonne'];
            $old_grade=$_GET['grade'];

            if(!isset($_POST['nom']) || empty($_POST['nom'])||
                !isset($_POST['dateNaiss']) || empty($_POST['dateNaiss']) ||
                !isset($_POST['lieunaiss']) || empty($_POST['lieunaiss']) ||
                !isset($_POST['sexe']) || empty($_POST['sexe']) ||
                !isset($_POST['email']) || empty($_POST['email']) ||
                !isset($_POST['lieu']) || empty($_POST['lieu']) ||
                !isset($_POST['tel']) || empty($_POST['tel'])||
                !isset($_POST['grade']) || empty($_POST['grade'])
            ){

                $json = 'veuillez remplir tous les champs';

            }else{


                //if($_GET['file'] != ''){

                $nomphoto = $_FILES['photo']['name'];
                if($nomphoto != ""){

                    $file_tmp_name=$_FILES['photo']['tmp_name'];
                    $extension = pathinfo($nomphoto, PATHINFO_EXTENSION);
                    $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                    if (in_array($extension, $extensions_autorisees))
                    {
                        $nomphoto = getNom().'.'.$extension;
                        move_uploaded_file($file_tmp_name,"./images/$nomphoto");

                    }
                }

                $nom1 = addslashes($_POST['nom']);
                $prenom1 = (isset($_POST['prenom'])? addslashes($_POST['prenom']):"");
                $dateNaiss1 = $_POST['dateNaiss'];
                $lieunaiss = addslashes($_POST['lieunaiss']);
                $sexe1 = $_POST['sexe'];
                $email1 = addslashes($_POST['email']);
                $statut_pro = $_POST['statut_pro'];
                $zone = $_POST['lieu'];
                $tel1 = addslashes($_POST['tel']);
                $grade = addslashes($_POST['grade']);
                $pere = (isset($_POST['pere'])? $_POST['pere']:"");
                $pere_vivant = (isset($_POST['pere_vivant'])? $_POST['pere_vivant']:1);
                $mere = (isset($_POST['mere'])? $_POST['mere']:"");
                $mere_vivante = (isset($_POST['mere_vivante'])? $_POST['mere_vivante']:1);
                $situation = (isset($_POST['situation'])? utf8_encode($_POST['situation']):"");
                $conjoint = (isset($_POST['conjoint'])? addslashes($_POST['conjoint']):"");
                $religion = (isset($_POST['religion'])? addslashes($_POST['religion']):"");
                $religion_autre = (isset($_POST['religion_autre'])? addslashes($_POST['religion_autre']):"");
                $nbre_enfant = (isset($_POST['nbre_enfant'])? $_POST['nbre_enfant']:0);
                $village = (isset($_POST['village'])? addslashes($_POST['village']):"");
                $arrondissement = (isset($_POST['arrondissement'])? $_POST['arrondissement']:1);
                $profession = (isset($_POST['profession'])? addslashes($_POST['profession']):"");
                $domaine = (isset($_POST['domaine'])? addslashes($_POST['domaine']):"");
                $diplome = (isset($_POST['diplome'])? addslashes($_POST['diplome']):"");
                $employeur = (isset($_POST['employeur'])? addslashes($_POST['employeur']):"");


                if($religion == 'AUTRE'){$religion = $religion_autre;}



                //vérifions que'adresse email n'existe pas
                $exist = $db->prepare("SELECT email from personne where lisible  = 1 and email = '$email1' AND personne.idpersonne != $idpersonne");
                $exist->execute();

                if($x=$exist->fetch(PDO::FETCH_OBJ)){

                    $json  = "Cette addresse email existe déja!";

                }else{


                    try{
                        //enregistrement de la personne
                       // $insert1 = $db->prepare("INSERT INTO personne values('', '$nom1', '$prenom1', '$dateNaiss1', '$lieunaiss', '$sexe1', '$email1', '$profession', $zone, '$tel1', '$pere', $pere_vivant, '$mere', $mere_vivante, '$nomphoto', true, '$domaine', '$diplome', '', '$statut_pro', '$employeur', '$village', $arrondissement, '', '', '', '$situation', '$conjoint', $nbre_enfant, '$religion', '$date_day')");
                        //$insert1->execute();
                        $requete=" UPDATE personne SET nom='$nom1', prenom='$prenom1', datenaiss='$dateNaiss1',
                                                                  lieunaiss='$lieunaiss', sexe='$sexe1', email='$email1',
                                                                  zone_idzone='$zone', telephone='$tel1', pere='$pere',
                                                                  pere_vivant='$pere_vivant',
                                                                  mere='$mere', mere_vivant='$mere_vivante',
                                                                  domaine='$domaine', diplome='$diplome',
                                                                  statut_pro='$statut_pro',
                                                                  village='$village', arrondissement='$arrondissement',
                                                                  situation_matri='$situation',
                                                                  nombre_enfant='$nbre_enfant'";
                        if($nomphoto!=""){
                            $requete .=", photo='$nomphoto' ";
                        }

                        if($situation=='CELIBATAIRE'){
                            $requete .=", conjoint='', religion_conjoint=''";
                        }else{
                            $requete .=", conjoint='$conjoint', religion_conjoint='$religion'";
                        }

                        if($statut_pro=='COMMERCANT' || $statut_pro=='SANS EMPLOI'){
                            $requete .=", employeur='', profession=''";
                        }else{
                            $requete .=", employeur='$employeur', profession='$profession'";
                        }



                        $requete .=" WHERE idpersonne=$idpersonne ";

                        $update=$db->prepare($requete);
                        $update->execute();




                        //enregistrement des information sur le fidele
                        //$insertp = $db->prepare("INSERT INTO pasteur values('', '$grade', '$zone', 1, LAST_INSERT_ID())");
                        //$insertp->execute();

                        $update1=$db->prepare("UPDATE pasteur SET grade='$grade', adresse='$zone'
                                               WHERE personne_idpersonne='$idpersonne'");
                        $update1->execute();

                        if($old_grade!=$grade){
                            $ps=$db->prepare("UPDATE grade SET estpris=0 WHERE idgrade='$old_grade'");
                            $ps->execute();

                            $psps=$db->prepare("UPDATE grade SET estpris=1 WHERE idgrade='$grade'");
                            $psps->execute();
                        }

                        //liberation du grade pris


                    }catch(Exception $ex){


                        $json = 'Erreur '.$ex->getLine().': lors de l\'insertion, veuillez contacter l\'administrateur';
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