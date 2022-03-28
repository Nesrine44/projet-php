<?php
session_start();

if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login'];
    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');

    if(!has_Droit($idUser, "Enregistrer contribution")){

        header('Location:index.php');

    }else{
            $data['text'] = $_POST;
            $json = "";

        function getMontant($idcontribution, $idfidele, $idsaintecene){

                    global $db;

                    $exist = false;

                    $select = $db->prepare("SELECT montant from contributionfidele where fidele_idfidele = $idfidele AND saintescene_idsaintescene = $idsaintecene AND contribution_idcontribution = $idcontribution AND lisible = 1");
                    $select->execute();

                    if($x=$select->fetch(PDO::FETCH_OBJ)) {
                        
                        $exist = true;
                    }else{

                        $exist = false;
                    }

                    return $exist;
                }

            if( 
                !isset($_POST['typesaintecene']) || empty($_POST['typesaintecene']) ||
                !isset($_POST['idfidele']) || empty($_POST['idfidele'])
                
            ){

                $json = 'Veuillez remplir tous les champs!';

            }else{

                 $allContribution = array();
                 $allIdContribution = array();
                 $montant = array();
                 $contributions = $db->prepare("SELECT * from contribution where lisible = 1");
                 $contributions->execute();

                 while ($contribution=$contributions->fetch(PDO::FETCH_OBJ)) {
                     
                     $valeur = (isset($_POST[$contribution->type])?$_POST[$contribution->type]:0);

                     if($valeur != 0){

                        array_push($allContribution, $contribution->type);
                        array_push($allIdContribution, $contribution->idcontribution);
                        array_push($montant, $valeur);
                     }
                     
                 }

                 if(count($montant)==0){

                     $json = "Au moins un montant doit être renseigné!!!";

                 }else{

                    $idsaintecene = $_POST['typesaintecene'];               
                $idfidele = $_POST['idfidele'];

                if($idsaintecene==1){
                    $mois='01';
                }elseif($idsaintecene==2){
                    $mois='02';
                }elseif($idsaintecene==3){
                    $mois='03';
                }elseif($idsaintecene==4){
                    $mois='04';
                }elseif($idsaintecene==5){
                    $mois='05';
                }elseif($idsaintecene==6){
                    $mois='07';
                }elseif($idsaintecene==8){
                    $mois='08';
                }elseif($idsaintecene==9){
                    $mois='09';
                }else{
                    $mois='$idsaintecene';
                }
               // $date_now=date('Y').'-'.$mois.'-'.date('d');

                $date_now=date('Y-m-d');
                $heure_now = date('H:m');
                $test = false;

                for($i=0; $i<count($allContribution); $i++){
                    

                    $query = "INSERT into contributionfidele(fidele_idfidele, heure, saintescene_idsaintescene, date, lisible, utilisateur_idutilisateur, recu, contribution_idcontribution, typecontribution, montant) VALUES($idfidele, now() ,$idsaintecene, '$date_now', 1, $idUser, 0,  ";

                    if(!getMontant($allIdContribution[$i], $idfidele, $idsaintecene)){

                        $query .= $allIdContribution[$i].", '".$allContribution[$i]."', ".$montant[$i].")";

                        $insert = $db->prepare($query);
                       //echo($query."\n");
                        $insert->execute();

                        $test = true;
                    }
                }

                    if(!$test){

                       $json = "Toutes les contributions de cette Sainte Cèneont déja été enrégistrées pour ce fidèle";
                    }
                 }

                
               
                echo json_encode($json);
            }       

    }
}else{

    header('Location:login.php');
}