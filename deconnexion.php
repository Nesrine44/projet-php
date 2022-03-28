<?php


    session_start();

	if(isset($_SESSION['login']) && isset($_SESSION['idconnect'])){

		require_once('includes/connexionbd.php');

		$idUser = $_SESSION['login'];
		$idconnect = $_SESSION['idconnect'];
		$update = $db->prepare("UPDATE connexion set heurefin = CURTIME() where idconnexion = $idconnect AND lisible = 1");
		$update->execute();

		session_destroy();
		header('Location:index.php');
		exit;
	}else{
	session_destroy();
	header('Location:Login.php');
	}
?>