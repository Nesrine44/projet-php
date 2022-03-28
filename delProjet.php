<?php
session_start();

if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login'];
    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');

    if(!has_Droit($idUser, "Liste des depenses")){

        header('Location:index.php');

    }else{ 
			$id = $_GET['id'];
         
			$query = "DELETE FROM traveaux WHERE id=$id";
			//$update = $db->prepare("UPDATE depenses SET lisible = 0 where id= $id");
            $update = $db->prepare($query);
            $update->execute();

            echo json_encode($id);


    }
}