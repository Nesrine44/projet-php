
<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Enregistrer une participation")){

            header('Location:index.php');

        }else{

           function isAjax(){
                    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
                }

            if(isAjax()){
                if( !isset($_POST['choix']) ||
                    !isset($_POST['saintescene']) || empty($_POST['saintescene']) ||
                    !isset($_POST['contribution']) || empty($_POST['contribution'])
                 ){

                    header('500 Internal Server Error', true, 500);
                        die('Veuillez remplir tous les champs!');

                }else{
                    try{
    		            
    		            $idsaintescene = $_POST['saintescene'];
                        $contribution  = $_POST['contribution'];
                        $note = addslashes($_POST['note']);
                        $date_contribution = date('Y-m-d');

                        foreach ($_POST['choix'] as $idfidele) {                           
                        
                            $insert = $db->prepare("INSERT INTO fidelesaintescene values('', true, $idfidele,  $idsaintescene, $contribution, '$note', '$date_contribution', 0)");
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