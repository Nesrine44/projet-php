<?php
	require_once('includes/connexionbd.php');

	if(isset($_GET['idpersonne'])){


		$idpersonne = htmlentities(intval($_GET['idpersonne']));

		$requete = $db->prepare("SELECT statut_pro, etablissement, niveau, serie, diplome, domaine, profession, employeur FROM personne where idpersonne = $idpersonne and lisible = 1");

		$requete->execute();

		$personne = null;

		while($donnees = $requete->fetch(PDO::FETCH_OBJ)){

			$personne = $donnees;

		}

		$json = $personne;

		echo json_encode($json);
	}
?>