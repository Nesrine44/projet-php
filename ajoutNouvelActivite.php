<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Creer activite")){
            header('Location:index.php');
        }else{
            $json = "";
            
            if(!isset($_POST['nomActivite']) || empty($_POST['nomActivite']) ||
                !isset($_POST['description']) || empty($_POST['description']) ||
                !isset($_POST['dateDebut'])   || empty($_POST['dateDebut'])   ||
                !isset($_POST['dateFin'])     || empty($_POST['dateFin']) ||
                !isset($_POST['choixA'])       || empty($_POST['choixA'])
            ){
                $json = 'Veuillez remplir tous les champs!';
            }else{
                $nomActivite1 = strtoupper(addslashes($_POST['nomActivite']));
                $description1 = addslashes($_POST['description']);
                $dateDebut1 = $_POST['dateDebut'];
                $dateFin1 = $_POST['dateFin'];

                $dateDebut1ToCheck = explode('-', $dateDebut1);
                $dateDebut1ToCheck = (int)$dateDebut1ToCheck[0].$dateDebut1ToCheck[1].$dateDebut1ToCheck[2];

                $dateFin1ToCheck = explode('-', $dateFin1);
                $dateFin1ToCheck = (int)$dateFin1ToCheck[0].$dateFin1ToCheck[1].$dateFin1ToCheck[2];

                if($dateFin1ToCheck > $dateDebut1ToCheck) {
                    try{
                        $insert1 = "INSERT INTO activite values('', '$nomActivite1', '$description1', '$dateDebut1','$dateFin1', true);";
                        $db->exec($insert1);
                        $insert2 = "SELECT max( idactivite ) AS idactivite FROM activite";
                        $res=$db->query($insert2);
                        while($id=$res->fetch(PDO::FETCH_ASSOC)) {
                            $identifiant=$id['idactivite'];
                        }
                        foreach($_POST['choixA'] as $idgroupe) {
                            $insert3 = "INSERT INTO groupeactivite values('', $idgroupe, $identifiant, true);";
                            $db->exec($insert3);
                        }
                        unset($db);
                    }catch(Exception $ex) {
                        // header('500 Internal Server Error', true, 500);
                        // echo 'Erreur: '.$ex;
                        $json = 'Erreur: '.$ex->getMessage()."\n lors de l'enregistrement de l'activité!";
                    } 
                } else {
                    $json = 'Erreur: La date de fin doit être supérieure ou égale à la date de début';
                }  
            }
            echo json_encode($json);
        }
    }else{
        header('Location:login.php');
    }
?>
