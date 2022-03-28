
<?php
    session_start();

    if(isset($_SESSION['login']) && isset($_SESSION['annee'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');
        require_once('includes/connexionDefault.php');

        $json = '';

        if(!has_Droit($idUser, "Editer parametres")){

            header('Location:index.php');

        }else{
                try{

                    $annee_encours = $_SESSION['annee'];
                                       
                    $new_annee = $annee_encours + 1;

                    $base_new = "paroisse".$new_annee;
                    $base_old = "paroisse".$annee_encours;

                    function copy_table($table){

                        global $db;
                        global $base_old;
                        global $base_new;

                        $query1 = "delete from ".$base_new.".".$table;
                        $delete = $db->prepare($query1);
                        $delete->execute();

                        $query2 = "INSERT INTO ".$base_new.".".$table." SELECT * from  ".$base_old.".".$table;  
                        $copy = $db->prepare($query2);
                        $copy->execute();

                    }

                    

                    $link = mysql_connect('localhost', 'root', '') or die('Connection impossible au sgbd');
                     
                    $checkthisdb= $base_new;
                     
                    $showdb=mysql_query("SHOW DATABASES LIKE '$checkthisdb'");
                     

                   if ($resultsd2=mysql_fetch_array($showdb)){

                        try{
                            $transfert = $root->prepare("SELECT etat FROM base where annee = $new_annee");
                            $transfert->execute();
                            $etat = 0;

                            while($x=$transfert->fetch(PDO::FETCH_OBJ)){

                                $etat = $x->etat;
                            }

                            if($etat){

                                $json = "Les données ont déjà été transférées!";

                            }else{
                                // la sainte scene
                                $saintescene = $db->prepare("UPDATE saintescene set annee = $annee_encours, valide = 1 where lisible = 1");
                                $saintescene->execute();

                                //les regions
                                copy_table("region");
                                //departement
                                copy_table("departement");
                                //arrondissement
                                copy_table("arrondissement");
                                //les zones
                                copy_table("zone");
                                //les personnes
                                copy_table("personne");
                                //modules
                                copy_table("modules");
                                //droit
                                copy_table("droit");
                                //role
                                copy_table("role");
                                //roledroit
                                copy_table("roledroit");                            
                                //les utilisateurs
                                copy_table("utilisateur");
                                //userrole
                                copy_table("userrole");
                                //contribution
                                copy_table("contribution");
                                //les fideles
                                copy_table("fidele");
                                //bapteme
                                copy_table("bapteme");
                                //confirmation
                                copy_table("confirmation");
                                //malade
                                copy_table("malade");
                                //groupes
                                copy_table("groupe");
                                //fidelegroupe
                                copy_table("fidelegroupe");
                                //grade
                                copy_table("grade");
                                //pasteurs
                                copy_table("pasteur");
                                //parametres
                                copy_table("parametre");

                                $query3 = "UPDATE ".$base_new.".fidele set etat = 0";
                                $fidele = $db->prepare($query3);
                                $fidele->execute();

                                $updateBase = $root->prepare("UPDATE base set etat=1 where annee = $new_annee");
                                $updateBase->execute();
                            }

                        }catch(Exception $e){

                            $json = $e->getMessage();
                        }

                    }else{
                        
                        $json = "Veuillez d'abord créér une nouvelle année!";
                    }

                }catch(Exception $ex){

                    $json = $ex->getMessage();
                        
                }


                echo json_encode($json);
        }
            
        
    }else{

        header('Location:login.php');
    }
?>