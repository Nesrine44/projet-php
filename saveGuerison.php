
<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Enregistrer guerison")){
            header('Location:index.php');
        }else{
           function isAjax(){
                return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
            }
            if(isAjax()){
                if( !isset($_POST['idmalade']) || empty($_POST['idmalade']) ||
                    !isset($_POST['dateEnregistrement']) || empty($_POST['dateEnregistrement']) ||
                    !isset($_POST['dateGuerison']) || empty($_POST['dateGuerison'])
                 ){
                    header('500 Internal Server Error', true, 500);
                    die('Veuillez remplir tous les champs!');
                }else{
                    $dateEnregistrement = $_POST['dateEnregistrement'];
                    $dateGuerison = $_POST['dateGuerison'];
                    $idmalade = $_POST['idmalade'];  

                    $saveDate = explode('-', $dateEnregistrement);
                    $saveDate = (int)$saveDate[0].$saveDate[1].$saveDate[2];

                    $gueriDate = explode('-', $dateGuerison);
                    $gueriDate = (int)$gueriDate[0].$gueriDate[1].$gueriDate[2];

                    $dateDebutMaladie = $db->prepare("SELECT dateDebutMaladie FROM malade WHERE idmalade = $idmalade AND lisible = 1 AND est_decede = 0 AND est_retabli = 0");
                    $dateDebutMaladie->execute();

                    foreach($dateDebutMaladie as $row) {
                        $dateDebutMaladie = explode('-', $row[0]);
                        $dateDebutMaladie = (int)$dateDebutMaladie[0].$dateDebutMaladie[1].$dateDebutMaladie[2];
                    }

                    if($gueriDate <= $saveDate) {
                        if($gueriDate > $dateDebutMaladie) {
                            try{
                                $insert = $db->prepare("UPDATE malade set dateEnregistrementGuerison='$dateEnregistrement', dateGuerison='$dateGuerison', est_retabli = 1, lisible=0 WHERE idmalade = $idmalade");
                                $insert->execute();                        
                            }catch(Exception $ex){
                                header('500 Internal Server Error', true, 500);
                                die('Erreur '.$ex->getline().': Veuillez contacter l\'administrateur');
                            }
                        }else {
                            header('500 Internal Server Error', true, 500);
                            die('Erreur : La date de guérison du malade doit être supérieure à la date de début de la maladie');
                        }
                    }else {
                        header('500 Internal Server Error', true, 500);
                        die('Erreur : La date de guérison du malade doit être inférieure ou égale à la date d\'enregistrement de la guérison');
                    }
                }
            }else{
                header('Location:index.php');
            }

        }
    }
?>