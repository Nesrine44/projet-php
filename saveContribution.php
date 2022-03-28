<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Enregistrer contribution")){
            header('Location:index.php');
        }else{
             function isAjax(){
                return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
            }
            if(isAjax()){
                if( !isset($_POST['typecontribution']) || empty($_POST['typecontribution']) ||
                    !isset($_POST['choix']) || 
                    !isset($_POST['montant']) || empty($_POST['montant'])
                 ){
                    header('500 Internal Server Error', true, 500);
                    die('Veuillez remplir tous les champs!');
                }else{
                    $idcontribution = $_POST['typecontribution'];
                    $montant = $_POST['montant'];
                    $type = "";

                    $select = $db->prepare("SELECT type FROM contribution where lisible = 1 and idcontribution = $idcontribution");
                    $select->execute();

                    while ($s = $select->fetch(PDO::FETCH_OBJ)) {
                        $type = $s->type;
                    }

                    foreach ($_POST['choix'] as $check) {
                        $insert = $db->prepare("INSERT INTO contributionfidele VALUES('', $check, $idcontribution, '$type', $montant, now(), true)");
                        $insert->execute();
                    }
                }
            }else{
            header('Location:index.php');
        }
    }
}else{
    header('Location:login.php');
}