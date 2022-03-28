
<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Creer un zone")){

            header('Location:index.php');

        }else{

           function isAjax(){
                    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
                }

            if(isAjax()){
                if( !isset($_POST['nomzone']) || empty($_POST['nomzone']) ||
                    !isset($_POST['description'])  
                 ){

                    header('500 Internal Server Error', true, 500);
                        die('Veuillez remplir tous les champs!');

                }else{
                    try{
    		            $nomzone = addslashes($_POST['nomzone']);
    		            $description = addslashes($_POST['description']);

                        $exist = $db->prepare("SELECT nomzone from zone where lisible = 1 AND nomzone = '$nomzone'");
                        $exist->execute();

                        if($x=$exist->fetch(PDO::FETCH_OBJ)){

                            header('500 Internal Server Error', true, 500);
                            die('Cette zone existe déja!');

                        }else{


                            $insert = $db->prepare("INSERT INTO zone VALUES ('', '$nomzone', '$description', true)");

                            $insert->execute(); 


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