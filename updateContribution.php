
<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Afficher contribution")){

            header('Location:index.php');

        }else{

           function isAjax(){
                    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
                }

            if(isAjax()){

                $idcontribution = 0;

                if(!isset($_GET['id'])){

                    header('Location:index.php');
                    
                }else{

                    if( !isset($_POST['date_contribution']) || empty($_POST['date_contribution']) ||
                        !isset($_POST['montant']) || empty($_POST['montant']) 
                     ){

                        header('500 Internal Server Error', true, 500);
                            die('Veuillez remplir tous les champs!');

                    }else{
                        try{

                            $idcontribution = $_GET['id'];
        		            
                            $date_contribution = $_POST['date_contribution'];
                            $montant= $_POST['montant'];
        		                   
                            $update = $db->prepare("UPDATE  contributionfidele c  SET c.date = '$date_contribution', c.montant = $montant WHERE c.idcontributionfidele = $idcontribution AND lisible = 1");

                            $update->execute(); 


                        }catch(Exception $ex){
                            
                            header('500 Internal Server Error', true, 500);
                            die('Erreur '.$ex.': Veuillez contacter l\'administrateur');
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