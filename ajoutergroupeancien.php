<?php
    session_start();

    if (isset($_SESSION['login'])){
        $iduser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if (!has_droit($iduser, 'Droit de creer un nouveau groupe')){
            exit();
        } else {
            $json = "";
            if (!isset($_POST['nomgroupe']) || empty($_POST['nomgroupe'])){
                $json = "Veuillez remplir tous les champs";
            } else {
                $nomgroupe = addslashes($_POST['nomgroupe']);
                $type = addslashes($_POST['typegroupe']);

                $date = date('Y-m-d');

                try{
                    
                     $sertion = "INSERT INTO `groupe`(`nomGroupe`, `typeGroupe`, `dateCreation`, `lisible`) VALUES ('$nomgroupe', '$type', '$date', 1)";
                    $db->exec($sertion);
                    $json="ok";
                    echo json_encode($json);
                    // header('location:index.php');
                    // exit();
                } catch(Exception $ex){
                    $json = 'Erreur: '.$ex->getMessage()."\n lors de l'enregistrement";
                }
                $db = null;
            }
        }

    } else {
    }
?>