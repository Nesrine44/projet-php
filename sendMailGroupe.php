<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Envoyer newsletter")){

            header('Location:index.php');

        }else{

             function isAjax(){
                return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
            }

            if(isAjax()){

                if(!isset($_POST['sujet']) || !isset($_POST['message']) || !isset($_POST['choix'])){

                    header('500 Internal Server Error', true, 500);
                    die('Veuillez remplir tous les champs!');

                }else{

                    $sujet = addslashes($_POST['sujet']);
                    $msg = $_POST['message'];

                    $header = "From: EEC-Biyem-assi <info@eec-biyem-assi.net >\r\n";
                    $header .= "Content-Type: text/html; charset=\"UTF-8\"\r\n";

                    try{
                        $sms = "<html><body>";           

                            foreach ($_POST['choix'] as $check) {

                                $selectMail = $db->prepare("SELECT email FROM personne, fidele, groupe, fidelegroupe 
                                    WHERE fidele.personne_idpersonne = personne.idpersonne
                                    AND  groupe.idgroupe = fidelegroupe.groupe_idgroupe
                                    AND fidele.idfidele = fidelegroupe.fidele_idfidele
                                    AND groupe.idgroupe = $check
                                    AND personne.lisible = 1
                                    AND fidele.lisible = 1
                                    AND groupe.lisible = 1
                                    AND fidelegroupe.lisible = 1");

                                $selectMail->execute();

                                while($liste = $selectMail->fetch(PDO::FETCH_OBJ)){

                                    $email = $liste->email;

                                    $sms .= $msg;

                                    $sms .= "</body></html>";

                                    mail($email, $sujet, $sms, $header);

                                }

                                    

                                }
                                            
                    }catch(Exception $ex){

                        header('500 Internal Server Error', true, 500);
                        die('Erreur '.$ex.': Veuillez contacter l\'administrateur');
                    }

                }
            }else{
                    header('Location:index.php');
            }
        }
}else{

    header('Location:index.php');
}

?>