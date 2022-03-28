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
		if (!isset($_POST['intitule']) || empty($_POST['intitule']) || !isset($_POST['duree']) || empty($_POST['duree'])) {
			$json = "Veuillez remplir tous les champs";
		} else {
			$intitule = addslashes($_POST['intitule']);
			$dureecollecte = $_POST['duree'];

			if (isset($_POST['montant'])){
				$montant = $_POST['montant'];
			}

			try {
				$insertion = "INSERT INTO `collectes`(`intitule`, `duree`, `montant`) VALUES ('$intitule', '$dureecollecte', '$montant')";
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