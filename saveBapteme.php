
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
                    !isset($_POST['lieu_bapteme']) || empty($_POST['lieu_bapteme']) ||
                    !isset($_POST['date_bapteme']) || empty($_POST['date_bapteme'])
                 ){
                    header('500 Internal Server Error', true, 500);
                    die('Veuillez remplir tous les champs!');
                }else{
                    $date_day = date('Y-m-d');
                    $idfidele = $_POST['idfidele'];
                    $lieu_bapteme = addslashes($_POST['lieu_bapteme']);
                    $date_bapteme = $_POST['date_bapteme'];
                    $date_naiss_fidele = $_POST['dateNaissFidele'];
                    $date_inscription_fidele = $_POST['dateInscriptionFidele'];

                    $dateBaptemeToCheck = explode('-', $date_bapteme);
                    $dateBaptemeToCheck = (int)$dateBaptemeToCheck[0].$dateBaptemeToCheck[1].$dateBaptemeToCheck[2];

                    $dateNaissToCheck = explode('-', $date_naiss_fidele);
                    $dateNaissToCheck = (int)$dateNaissToCheck[0].$dateNaissToCheck[1].$dateNaissToCheck[2];

                    $dateInscriptionToCheck = explode('-', $date_inscription_fidele);
                    $dateInscriptionToCheck = (int)$dateInscriptionToCheck[0];

                    try{
                        if($dateBaptemeToCheck > $dateNaissToCheck) {
                            if($dateBaptemeToCheck >= $dateInscriptionToCheck) {
                                try{        
                                    $insert = $db->prepare("INSERT INTO bapteme VALUES ('', '$date_bapteme', '$lieu_bapteme' , 1, $idfidele, '$date_day')");
                                    $insert->execute(); 
                                    $db = null;                     
                                }catch(Exception $ex){
                                    header('500 Internal Server Error', true, 500);
                                    die('Erreur '.$ex->getline().': Veuillez contacter l\'administrateur');
                                }
                            }else {
                                header('500 Internal Server Error', true, 500);
                                die('Erreur : La date de bapteme doit être supérieure à la date d\'inscription à la paroisse');
                            }
                        }else {
                            header('500 Internal Server Error', true, 500);
                            die('Erreur : La date de bapteme doit être supérieure à la date de naissance');
                        }
                    }catch(Exception $ex){
                        header('500 Internal Server Error', true, 500);
                        die('Erreur '.$ex->getline().': Veuillez contacter l\'administrateur');
                    }
                }
            }else{
                header('Location:index.php');
            }
        }
    }
?>