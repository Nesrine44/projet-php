<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Enregistrer un conseil")){

            header('Location:index.php');

        }else{

            function isAjax(){
                return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
            }

            if(isAjax()){
                    if(!isset($_POST['typeconseil']) || empty($_POST['typeconseil']) ||
                       !isset($_POST['date']) || empty($_POST['date']) ||
                       !isset($_POST['rapport']) || empty($_POST['rapport'])
                    ){
                        header('500 Internal Server Error', true, 500);
                        die('Veuillez remplir tous les champs!');

                    }else{
                        $typeconseil1 = $_POST['typeconseil'];
                        $date1 = $_POST['date'];
                        $rapport1 = addslashes($_POST['rapport']);

                        $date_inscription = date('Y-m-d');

                        $insert1 = $db->prepare("INSERT INTO conseil values('',true, '$date_inscription')");
                        $insert1->execute();

                        $insert2 = "SELECT last_insert_id() AS idconseil FROM conseil";
                        $res=$db->query($insert2);

                        while($id=$res->fetch(PDO::FETCH_ASSOC)){
                                $identifiant=$id['idconseil'];
                        }


                        foreach($_POST['choix'] as $idfidele)
                        {                        
                            $insert3 = "INSERT INTO fideleconseil values('', $idfidele, $identifiant, '$typeconseil1', '$date1', '$rapport1', true)";
                            $db->exec($insert3);
                        }

                        //Mise à jour de la table des occurrences
                        if($typeconseil1 == 'conseil mensuel'){
                            $insert4 = "UPDATE occurrence SET mensuel = mensuel + 1";
                            $db->exec($insert4);
                        }
                        if($typeconseil1 == 'conseil extraordinaire'){
                            $insert4 = "UPDATE occurrence SET extraordinaire = extraordinaire + 1";
                            $db->exec($insert4);
                        }
                        if($typeconseil1 == 'conseil elargi'){
                            $insert4 = "UPDATE occurrence SET elargi = elargi + 1";
                            $db->exec($insert4);
                        }
                        if($typeconseil1 == 'consistoire'){
                            $insert4 = "UPDATE occurrence SET consistoire = consistoire + 1";
                            $db->exec($insert4);
                        }
                        if($typeconseil1 == 'cinode regional'){
                            $insert4 = "UPDATE occurrence SET cinode = cinode + 1";
                            $db->exec($insert4);
                        }
                        if($typeconseil1 == 'retraite spirituel'){
                            $insert4 = "UPDATE occurrence SET retraite = retraite + 1";
                            $db->exec($insert4);
                        }
                        $db=NULL;
                    }
                }else{

                    header('Location:index.php');
                }

            }
    }else{
        header('Location:login.php');
    }
?>
