
<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Creer un groupe")){

            header('Location:index.php');

        }else{

           function isAjax(){
                    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
                }

            if(isAjax()){
                if( !isset($_POST['nomGroupe']) || empty($_POST['nomGroupe']) ||
                    !isset($_POST['dateCreation']) || empty($_POST['dateCreation']) ||
                    !isset($_POST['typeGroupe']) || empty($_POST['typeGroupe']) 
                 ){

                    header('500 Internal Server Error', true, 500);
                        die('Veuillez remplir tous les champs!');

                }else{
                    try{
    		            $nomGroupe1 = strtoupper(addslashes($_POST['nomGroupe']));
							
						$selectG = $db->prepare("SELECT idgroupe from groupe where lisible = 1 and nomgroupe = '$nomGroupe1'");
						$selectG->execute();
						
						if($x=$selectG->fetch(PDO::FETCH_OBJ)){
							
							header('500 Internal Server Error', true, 500);
							die('Ce groupe existe deja!');
						}else{
						
							$dateCreation1 = $_POST['dateCreation'];
							$typeGroupe1 = strtoupper($_POST['typeGroupe']);
    		                   
							$insert = $db->prepare("INSERT INTO groupe VALUES ('', '$nomGroupe1', '$typeGroupe1', '$dateCreation1', true)");

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