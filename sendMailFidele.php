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

                if(!isset($_POST['sujet']) || !isset($_POST['message'])){

                    header('500 Internal Server Error', true, 500);
                    die('Veuillez remplir tous les champs!');

                }else{

                    $email = $_GET['email'];
                    $sujet = addslashes($_POST['sujet']);
                    $msg = addslashes($_POST['message']);

                    $header = "From: EEC-Biyem-assi <info@eec-biyem-assi.net >\r\n";
                    $header .= "Content-Type: text/html; charset=\"UTF-8\"\r\n";

                    try{
                        $sms = "<html><body>";           

                        $sms .= $msg;

                        $sms .= "</body></html>";

                        mail($email, $sujet, $sms, $header);

                            
                                            
                    }catch(Exception $ex){

                        header('500 Internal Server Error', true, 500);
                        die('Erreur '.$ex->getline().': Veuillez contacter l\'administrateur');
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