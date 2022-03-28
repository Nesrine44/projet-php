<?php
	require_once('includes/connexionbd.php');

	if(isset($_GET['idpersonne'])){


		$idpersonne = htmlentities(intval($_GET['idpersonne']));

		$requete = $db->prepare("SELECT  datebaptise as date_bapteme, lieu_baptise FROM fidele, bapteme where personne_idpersonne = $idpersonne and idfidele = bapteme.fidele_idfidele and fidele.lisible = 1 and bapteme.lisible = 1");

		$requete->execute();

		$personne = null;

		while($donnees = $requete->fetch(PDO::FETCH_OBJ)){

			$personne = $donnees;

		}

		$json = $personne;

		echo json_encode($json);
	}
?>



