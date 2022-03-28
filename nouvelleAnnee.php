
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

                    $filesql = "includes/paroisse.sql";
                    
                    $annee_encours = $_SESSION['annee'];
                                       
                    $new_annee = $annee_encours + 1;

                    $base_new = "paroisse".$new_annee;
                    $base_old = "paroisse".$annee_encours;

                    $link = mysql_connect('localhost', 'root', '') or die('Connection impossible au sgbd');
                     
                    $checkthisdb= $base_new;
                     
                    $showdb=mysql_query("SHOW DATABASES LIKE '$checkthisdb'");
                     

                   if ($resultsd2=mysql_fetch_array($showdb)){

                        $json = " Cette année a deja été créée!";

                    }else{
                         //creation de la future bd  
                         $create = $db->prepare("CREATE DATABASE ".$base_new);
                          $create->execute();

                          //Creation d'un user sur la bd
                            $q1 = "GRANT ALL PRIVILEGES ON ".$base_new.".* TO 'paroisse'@'localhost' IDENTIFIED BY  'paroisse#2016'";
                            $user = $db->prepare($q1);
                            $user->execute();
                          
                            //enregistrement de la nouvelle base
                            $updateBase = $root->prepare("INSERT INTO base VALUE('', $new_annee, 0)");
                            $updateBase->execute();

                            //connexion à cette base
                         $db2 = new PDO('mysql:host=localhost;dbname='.$base_new, 'paroisse', 'paroisse#2016');

                         $db2->query(file_get_contents($filesql));

                         function copy_table($table){

	                        global $db2;
	                        global $base_old;
	                        global $base_new;

	                        $query1 = "delete from ".$base_new.".".$table;
	                        $delete = $db2->prepare($query1);
	                        $delete->execute();

	                        $query2 = "INSERT INTO ".$base_new.".".$table." SELECT * from  ".$base_old.".".$table;  
	                        $copy = $db2->prepare($query2);
	                        $copy->execute();

                    	}

                    	// la sainte scene
                                
                                copy_table("saintescene");
                                $saintescene = $db2->prepare("UPDATE saintescene set annee = $annee_encours, valide = 1 where lisible = 1");
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
                                //userrole                                
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
                                //les utilisateurs
                                copy_table("utilisateur");
                                copy_table("userrole");
                                //contribution
                                //parametres
                                copy_table("parametre");

                                //les occurences

                                $query4 = "INSERT INTO `occurrence` (`idoccurrence`, `mensuel`, `extraordinaire`, `elargi`, `consistoire`, `cinode`, `retraite`, `nombrehomme`, `nombrefemme`, `nombregarcon`, `nombrefille`) VALUES(1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);";
                                 $oc = $db2->prepare($query4);
                                $oc->execute();

                                $query3 = "UPDATE ".$base_new.".fidele set lisible=0";
                                $fidele = $db2->prepare($query3);
                                $fidele->execute();


                                $updateBase = $root->prepare("UPDATE base set etat=1 where annee = $new_annee");
                                $updateBase->execute();
                         
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