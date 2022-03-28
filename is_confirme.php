<?php
	require_once('includes/connexionbd.php');

	if(isset($_GET['idpersonne'])){


		$idpersonne = htmlentities(intval($_GET['idpersonne']));

		$requete = $db->prepare("SELECT  date_confirmation, lieu_confirmation FROM fidele, confirmation where personne_idpersonne = $idpersonne and idfidele = confirmation.fidele_idfidele and fidele.lisible = 1 and confirmation.lisible = 1");

		$requete->execute();

		$personne = null;

		while($donnees = $requete->fetch(PDO::FETCH_OBJ)){

			$personne = $donnees;

		}

		$json = $personne;

		echo json_encode($json);
	}
?>



