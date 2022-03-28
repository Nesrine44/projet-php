<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');
        
        if(!has_Droit($idUser, "Modifier activite")){
            header('Location:index.php');
        }else{
            function isAjax(){
                return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
            }
            if(!isset($_GET['id'])){
                header('Location:index.php');
            }else{
                if(isAjax()){
                    if(!isset($_POST['nomActivite']) || empty($_POST['nomActivite']) ||
                        !isset($_POST['description']) || empty($_POST['description']) ||
                        !isset($_POST['dateDebut'])   || empty($_POST['dateDebut'])   ||
                        !isset($_POST['dateFin'])     || empty($_POST['dateFin'])                                
                    ){
                        header('500 Internal Server Error', true, 500);
                        die('Veuillez remplir tous les champs!');
                    }else{
                        $code = $_GET['id'];
                        $nom = strtoupper(addslashes($_POST['nomActivite']));
                        $description = addslashes($_POST['description']);
                        $dateDebut1 = $_POST['dateDebut'];
                        $dateFin1 = $_POST['dateFin'];
                        try{
                            $insert1=$db->prepare("UPDATE activite set nomactivite='$nom', description='$description', datedebut='$dateDebut1', datefin='$dateFin1' where idactivite=$code");
                            $insert1->execute();                               
                            unset($db);
                        }catch(Exception $ex){
                            header('500 Internal Server Error', true, 500);
                            die('Erreur: '.$ex.'\n lors de la mise à jour de l\'activité!');
                        }
                    }
                }else{
                    header('Location:index.php');
                }                
            }
        }
    }else{
        header('Location:login.php');
    }
?>
