<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Inscrire a un groupe")){

            header('Location:index.php');

        }else{
        
    		$idfidele=$_GET['fidele'];
    		$idgroupe=$_GET['groupe'];

            $update1 = $db->prepare("UPDATE fidelegroupe SET lisible=0 WHERE fidele_idfidele=$idfidele AND groupe_idgroupe=$idgroupe");
    		$update1->execute();

            $update2 = $db->prepare("UPDATE fidele SET estgroupe=0 WHERE idfidele=$idfidele ");
            $update2->execute();

    		unset($db);

        }

	}else{

		header('Location:login.php');
	}
?>