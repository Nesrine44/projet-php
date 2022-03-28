<?php
session_start();

if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login'];
    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');

    if(!has_Droit($idUser, "Enregistrer contribution")){

        header('Location:index.php');

    }else{
			$name = $_POST['nameContribu'];

			$query = "INSERT into contribution(type, lisible) VALUES('$name', 1)";
			$insert = $db->prepare($query);
            $state = $insert->execute();
            if($state){

            echo json_encode('true');
            }
            else {
            	echo json_encode(false);
            }



    }
}


