<?php
session_start();
if (isset($_SESSION['login'])){
	$iduser = $_SESSION['login'];
	require_once('includes/connexionbd.php');
	require_once('includes/function_role.php');

	if (!has_droit($iduser, 'Droit de creer un nouveau groupe')){
		header('location:index.pĥp');
	} else {
		$json = "";
		if (!isset($_POST['idfidele']) || empty($_POST['idfidele']) || !isset($_POST['montant']) || empty(['montant']) || !isset($_POST['datedons']) || empty(['datedons'])) {
			$json = "Veuillez remplir tous les champs";
		} else {
			$montant = $_POST['montant'];
			$datedons = $_POST['datedons'];
			$idfidele = $_POST['idfidele'];

			try {
				$insertion = "INSERT INTO `dons`(`montant`, `datedons`, `idfidele`) VALUES ('$montant', '$datedons', '$idfidele')";
				$db->exec($insertion);
			} catch (exception $ex){
				$json = "Error :". $ex->getMessage();
			}
		}
	}

} else {
	header('location:index.php');
}
?>