
<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Enregistrer deces")){

            header('Location:index.php');

        }else{

           function isAjax(){
                    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
                }

            if(isAjax()){
                if( !isset($_POST['idfidele']) || empty($_POST['idfidele']) ||
                    !isset($_POST['dateEnregistrement']) || empty($_POST['dateEnregistrement']) ||
                    !isset($_POST['dateDeces']) || empty($_POST['dateDeces'])
                 ){

                    header('500 Internal Server Error', true, 500);
                        die('Veuillez remplir tous les champs!');

                }else{
                    $dateEnregistrement = $_POST['dateEnregistrement'];
                    $dateEnregistrement1 = $_POST['dateEnregistrement'];
                    $dateDeces = $_POST['dateDeces'];                          
                    $dateDeces1 = $_POST['dateDeces'];  		                   
                    $idfidele = $_POST['idfidele'];
                    
                    $saveDate = explode('-', $dateEnregistrement);
                    $saveDate = (int)$saveDate[0].$saveDate[1].$saveDate[2];

                    $decesDate = explode('-', $dateDeces);
                    $decesDate = (int)$decesDate[0].$decesDate[1].$decesDate[2];

                    if($decesDate <= $saveDate) {
                        try{ 
                           $insert1 = $db->query("SELECT idmalade FROM  malade WHERE fidele_idfidele = $idfidele");
                            $id = $insert1->fetch(PDO::FETCH_OBJ);

                            if($id){
                                $value=$id->idmalade;

                                $insert = $db->prepare("UPDATE malade set dateEnregistrementDeces='$dateEnregistrement', dateDeces='$dateDeces', est_decede = 1, lisible=0 WHERE idmalade = $value");
                                $insert->execute();
                                
                            }
                            else {


                                 $insert3 = $db->prepare("INSERT INTO malade (`idmalade`, `guide`, `dateEnregistrementMaladie`, `dateDebutMaladie`, `dateEnregistrementGuerison`, `dateGuerison`, `dateEnregistrementDeces`, `dateDeces`, `est_retabli`, `est_decede`, `lisible`, `fidele_idfidele`, `residence`) values('', 'pas de guide', date('Y-m-d'), '0000-00-00', '0000-00-00', '0000-00-00', '$dateEnregistrement1', '$dateDeces1', 0, 1, 0, $idfidele, 'inconnu')");
                                 $insert3->execute(); 
                                
                               
                              
                            }
                            
                                             
                            $insert2 = $db->prepare("UPDATE fidele set est_decede=1 WHERE idfidele = $idfidele");
                            $insert2->execute();
                            
                            

                        }catch(Exception $ex){
                            header('500 Internal Server Error', true, 500);
                            die('Erreur '.$ex->getline().': Veuillez contacter l\'administrateur');
                        }
                    }else {
                        header('500 Internal Server Error', true, 500);
                        die('Erreur : La date de décès doit être inférieure à la date d\'enregistrement');
                    }
                }
            }else{
                    header('Location:index.php');
            }

        }
    }
?>