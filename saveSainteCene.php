
<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Creer sainte cene")){
            header('Location:index.php');
        }else{
           function isAjax(){
                return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
            }
            if(isAjax()){
                if(                   
                    !isset($_POST['mois']) || empty($_POST['mois'])
                 ){
                    header('500 Internal Server Error', true, 500);
                        die('Veuillez remplir tous les champs!');
                }else{
                    try{
    		            $annee = $_SESSION['annee'];
    		            $mois = $_POST['mois'];
                        $Update = $db->prepare("UPDATE saintescene set valide = 0, annee='$annee' WHERE idsaintescene = $mois and lisible = true");
                        $Update->execute();
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