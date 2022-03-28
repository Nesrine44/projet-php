<?php
	require_once('includes/connexionbd.php');

	if(isset($_GET['idpersonne'])){


		$idpersonne = htmlentities(intval($_GET['idpersonne']));

		$requete = $db->prepare("SELECT dateDebutMaladie as date_maladie, guide  FROM fidele, malade where personne_idpersonne = $idpersonne and idfidele = malade.fidele_idfidele and fidele.lisible = 1 and malade.lisible = 1");

		$requete->execute();

		$personne = null;

		while($donnees = $requete->fetch(PDO::FETCH_OBJ)){

			$personne = $donnees;

		}

		$json = $personne;

		echo json_encode($json);
	}
?>



