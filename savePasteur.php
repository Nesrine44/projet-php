<?php
session_start();

if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login'];
    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');

    if(!has_Droit($idUser, "Creer un pasteur")){ 

        header('Location:index.php');

    }else{

        $annee = $_SESSION['annee'];
        $nbre = 0;
        $idpersonne = 0;
        $prefixe = "";
        //$codeFidele = "";
        $grade="";
        $json  = '';
        $nomphoto = "";
        $date_day=date('Y-m-d');

        /*function getCode($annee, $nbre, $prefixe){

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

        }*/
      
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
            !isset($_POST['lieu']) || empty($_POST['lieu']) ||
            !isset($_POST['tel']) || empty($_POST['tel'])||
            !isset($_POST['grade']) || empty($_POST['grade'])
        ){

            $json = 'veuillez remplir tous les champs';

        }

        else{

            // $etat = 0;
            // if(isset($_POST['choix'])){


            //     foreach ($_POST['choix'] as $val) {
            //         $etat = $val;
            //     }
            // }
            




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

            $nom1 = addslashes($_POST['nom']);
           
            $prenom1 = (isset($_POST['prenom'])? addslashes($_POST['prenom']):"");
            $dateNaiss1 = $_POST['dateNaiss'];
            $lieunaiss = addslashes($_POST['lieunaiss']);
            $sexe1 = $_POST['sexe'];
            $email1 = addslashes($_POST['email']);
            $statut_pro = strtoupper($_POST['statut_pro']);
            $zone = $_POST['lieu'];
            $tel1 = addslashes($_POST['tel']);
          
            $pere = (isset($_POST['pere'])? $_POST['pere']:"");
            $pere_vivant = (isset($_POST['pere_vivant'])? $_POST['pere_vivant']:1);
            $mere = (isset($_POST['mere'])? $_POST['mere']:"");
            $mere_vivante = (isset($_POST['mere_vivante'])? $_POST['mere_vivante']:1);
            $situation = (isset($_POST['situation'])? utf8_encode($_POST['situation']):"");
            $conjoint = (isset($_POST['conjoint'])? addslashes($_POST['conjoint']):"");
            $religion = (isset($_POST['religion'])? addslashes($_POST['religion']):"");
            $nbre_enfant = (isset($_POST['nbre_enfant'])? $_POST['nbre_enfant']:0);
            $village = (isset($_POST['village'])? addslashes($_POST['village']):"");
            $arrondissement = (isset($_POST['arrondissement'])? $_POST['arrondissement']:1);
            $profession = (isset($_POST['profession'])? addslashes($_POST['profession']):"");
            $employeur = (isset($_POST['employeur'])? addslashes($_POST['employeur']):"");
            $diplome = (isset($_POST['diplome'])? addslashes(strtoupper($_POST['diplome'])):"");
            $domaine = (isset($_POST['domaine'])? addslashes(strtoupper($_POST['domaine'])):"");


            //vérifions que'adresse email n'existe pas
            $exist = $db->prepare("SELECT email from personne where lisible  = 1 and email = '".$email1."'");
            $exist->execute();

            if($x=$exist->fetch(PDO::FETCH_OBJ)){

                $json  = "Cette addresse email existe déja!";

            }else{


                try{
                    //enregistrement de la personne
                    $insert1 = $db->prepare("INSERT INTO personne(`idpersonne`, `nom`, `prenom`, `datenaiss`, `lieunaiss`, `sexe`, `email`, `profession`, `zone_idzone`, `telephone`, `pere`, `pere_vivant`, `mere`, `mere_vivant`, `photo`, `lisible`, `domaine`, `diplome`, `annee_enregistrement`, `statut_pro`, `employeur`, `village`, `arrondissement`, `etablissement`, `niveau`, `serie`, `situation_matri`, `conjoint`, `nombre_enfant`, `religion_conjoint`, `date_enregistrement`) values('', '$nom1', '$prenom1', '$dateNaiss1', '$lieunaiss', '$sexe1', '$email1', '$profession', $zone, '$tel1', '$pere', $pere_vivant, '$mere', $mere_vivante, '$nomphoto', true, '$domaine', '$diplome', '', '$statut_pro', '$employeur', '$village', $arrondissement, '', '', '', '$situation', '$conjoint', $nbre_enfant, '$religion', '$date_day')");
                    $insert1->execute();




                    $id=$db->lastInsertId();

                    // $idgrad = query('SELECT idgrad FROM grade WHERE grade = $grade');

                    //enregistrement des information sur le fidele
                    $insertp = $db->prepare("INSERT INTO pasteur(`idpasteur`, `grade`, `adresse`, `lisible`, `personne_idpersonne`)  values(?,?,?, ?,?)");
                    $insertp->execute(array('',1,$zone,1,$id));


                    //liberation du grade pris
                    $ps=$db->prepare("UPDATE grade SET estPris=1 WHERE idgrade='$grade'");
                    $ps->execute();

                  
                }catch(Exception $ex){
                    $json = 'Erreur '.$ex->getLine().': lors de l\'insertion, veuillez contacter l\'administrateur';
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
