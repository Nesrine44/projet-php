<?php 

    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Modifier zone")){

            header('Location:index.php');

        }else{
    
        function isAjax(){
            return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
          }

            if(isAjax()){
                        if(!isset($_POST['nomzone'])|| empty($_POST['nomzone']) ||
                           !isset($_POST['description'])  
                        ){
                            header('500 Internal Server Error', true, 500);
                            die('Veuillez remplir tous les champs');
                        }else{

                            $idzone= $_GET['param'];
                            $nomzone = addslashes($_POST['nomzone']);
                            $description = addslashes($_POST['description']);

                            try{

                                $exist = $db->prepare("SELECT nomzone from zone where lisible = 1 AND nomzone = '$nomzone' and idzone != $idzone");
                                $exist->execute();

                                if($x=$exist->fetch(PDO::FETCH_OBJ)){

                                    header('500 Internal Server Error', true, 500);
                                    die('Cette zone existe déja!');

                                }else{

                                    $insert2 = $db->prepare("UPDATE zone SET nomzone='$nomzone', description='$description' WHERE idzone = $idzone AND lisible = true");
                                    $insert2->execute();
                                    unset($db);         

                                }

                                                   
                            }catch(Exception $ex){
                                
                                header('500 Internal Server Error', true, 500);
                                die('Erreur '.$ex->getLine().': Veuillez contacter l\'administrateur');
                            }
                        }
            }else{


                header('500 Internal Server Error', true, 500);
                die('Une erreur est survenue lors de la mise à jour de la zone!');
         }

        }

    }else{

        header('Location:login.php');
    }
?>