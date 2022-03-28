<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Enregistrer bapteme")){
            header('Location:index.php');
        }else{
           function isAjax(){
                return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
            }
            if(isAjax()){
                if( !isset($_POST['idfidele']) || empty($_POST['idfidele']) ||
                    !isset($_POST['lieu_confirmation']) || empty($_POST['lieu_confirmation']) ||
                    !isset($_POST['date_confirmation']) || empty($_POST['date_confirmation'])
                 ){
                    header('500 Internal Server Error', true, 500);
                    die('Veuillez remplir tous les champs!');
                }else{
                    $date_day = date('Y-m-d');
                    $lieu_confirmation = addslashes($_POST['lieu_confirmation']);
                    $date_confirmation = $_POST['date_confirmation'];
                    $idfidele = $_POST['idfidele'];
                    $datenaiss = $_POST['dateNaissFidele'];

                    $dateNaissanceToCheck = explode('-', $datenaiss);
                    $dateNaissanceToCheck = (int)$dateNaissanceToCheck[0].$dateNaissanceToCheck[1].$dateNaissanceToCheck[2];

                    $dateConfirmationToCheck = explode('-', $date_confirmation);
                    $dateConfirmationToCheck = (int)$dateConfirmationToCheck[0].$dateConfirmationToCheck[1].$dateConfirmationToCheck[2];

                    if($dateNaissanceToCheck < $dateConfirmationToCheck) {
                        try{
                            $insert = $db->prepare("INSERT INTO confirmation VALUES ('', '$date_confirmation', '$lieu_confirmation' , $idfidele, 1, '$date_day')");
                            $insert->execute(); 
                            $db = null;
                        }catch(Exception $ex){
                            header('500 Internal Server Error', true, 500);
                            die('Erreur '.$ex->getline().': Veuillez contacter l\'administrateur');
                        }
                    }else {
                        header('500 Internal Server Error', true, 500);
                        die('Erreur : La date de Communion doit être supérieure à la date de naissance');
                    }
                }
            }else{
                header('Location:index.php');
            }
        }
    }
?>