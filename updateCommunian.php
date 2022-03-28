
<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Modifier bapteme")){

            header('Location:index.php');

        }else{

       function isAjax(){
                return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
            }

        if(isAjax()){

            if(!isset($_GET['id'])){

                header('Location:inex.php');

            }else{

                if( 
                    !isset($_POST['lieu_confirmation']) || empty($_POST['lieu_confirmation']) ||
                    !isset($_POST['date_confirmation']) || empty($_POST['date_confirmation'])
                 ){

                    header('500 Internal Server Error', true, 500);
                        die('Veuillez remplir tous les champs!');

                }else{
                    try{

    		            $idconfirmation = $_GET['id'];
    		            $lieu_confirmation = addslashes($_POST['lieu_confirmation']);
    		            $date_confirmation = $_POST['date_confirmation'];

                        $update1 = $db->prepare("UPDATE confirmation SET date_confirmation = '$date_confirmation', lieu_confirmation = '$lieu_confirmation' WHERE idconfirmation = $idconfirmation AND lisible = 1");
                        $update1->execute(); 

                      
                    }catch(Exception $ex){
                        
                        header('500 Internal Server Error', true, 500);
                        die('Erreur '.$ex->getline().': Veuillez contacter l\'administrateur');
                    }
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