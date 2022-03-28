<?php 

    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Modifier un groupe")){

            header('Location:index.php');

        }else{

            function isAjax(){
                return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
              }

                if(isAjax()){
                            if(!isset($_POST['dateEnregistrement'])|| empty($_POST['dateEnregistrement']) ||
                               !isset($_POST['dateDebutMaladie']) || empty($_POST['dateDebutMaladie']) ||
                               !isset($_POST['residence']) || empty($_POST['residence']) ||
                               !isset($_POST['guide']) || empty($_POST['guide'])
                            ){
                                header('500 Internal Server Error', true, 500);
                                die('Veuillez remplir tous les champs');
                            }else{
                                
                                $idmalade = $_GET['id'];
                                $dateEnregistrement = $_POST['dateEnregistrement'];
                                $dateDebutMaladie = $_POST['dateDebutMaladie'];
                                $residence = addslashes(strtoupper($_POST['residence']));
                                $guide = addslashes(strtoupper($_POST['guide']));

                                try{
                                    $insert2 = $db->prepare("UPDATE malade SET dateEnregistrementMaladie='$dateEnregistrement', dateDebutMaladie='$dateDebutMaladie', guide='$guide', residence = '$residence' WHERE malade.idmalade = $idmalade");
                                    $insert2->execute();
                                    unset($db);                            
                                }catch(Exception $ex){
                                    header('500 Internal Server Error', true, 500);
                                    die('Erreur '.$ex->getLine().': Veuillez contacter l\'administrateur');
                                }
                            }
                }else{

                    header('Location:index.php');
                }
        }
        
    }else{

        header('Location:login.php');
    }
?>