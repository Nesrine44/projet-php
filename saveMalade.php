<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Creer un malade")){
            header('Location:index.php');
        }else{
            function isAjax(){
                return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
            }

            if(isAjax()){
                if( 
                    !isset($_POST['dateEnregistrement']) || empty($_POST['dateEnregistrement']) ||
                    !isset($_POST['dateDebutMaladie']) || empty($_POST['dateDebutMaladie']) ||
                    !isset($_POST['residence']) || empty($_POST['residence']) ||
                    !isset($_POST['idfidele']) || empty($_POST['idfidele'])
                 ){
                    header('500 Internal Server Error', true, 500);
                    die('Veuillez remplir tous les champs!');
                }else{
                    $dateEnregistrement = $_POST['dateEnregistrement'];
                    $dateDebutMaladie = $_POST['dateDebutMaladie'];
                    $residence = addslashes(strtoupper($_POST['residence']));
                    $guide = addslashes(strtoupper($_POST['choixguide']));    
                    $idfidele =  $_POST['idfidele'];     

                    $saveDate = explode('-', $dateEnregistrement);
                    $saveDate = (int)$saveDate[0].$saveDate[1].$saveDate[2];

                    $startDate = explode('-', $dateDebutMaladie);
                    $startDate = (int)$startDate[0].$startDate[1].$startDate[2];

                    if($startDate <= $saveDate) {
                        try{                                 
                            $insert = $db->prepare("INSERT INTO malade values('', '$guide', '$dateEnregistrement', '$dateDebutMaladie', '', '', '', '', false, false, true, $idfidele, '$residence')");
                            $insert->execute(); 
        
                        }catch(Exception $ex){
                            header('500 Internal Server Error', true, 500);
                            die('Erreur '.$ex.': Veuillez contacter l\'administrateur');
                        }
                    }else {
                        header('500 Internal Server Error', true, 500);
                        die('Erreur : La date de début de la maladie doit être inférieure à la date d\'enregistrement de la maladie du fidèle');
                    }
                }
            }else{
                header('Location:index.php');
            }
        }
    }
?>