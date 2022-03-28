
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

                header('Location:index.php');

            }else{

                    if( 
                        !isset($_POST['lieu_bapteme']) || empty($_POST['lieu_bapteme']) ||
                        !isset($_POST['date_bapteme']) || empty($_POST['date_bapteme'])
                     ){

                        header('500 Internal Server Error', true, 500);
                            die('Veuillez remplir tous les champs!');

                    }else{
                        try{

                            $idbapteme = $_GET['id'];
                            $date_day = date('Y-m-d');
                            
                            $lieu_bapteme = addslashes($_POST['lieu_bapteme']);
                            $date_bapteme = $_POST['date_bapteme'];

                           

                                $update = $db->prepare("UPDATE bapteme set datebaptise =  '$date_bapteme',  lieu_baptise = '$lieu_bapteme' where idbapteme = $idbapteme");
                                $update->execute(); 

                               

                           $db = null;


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