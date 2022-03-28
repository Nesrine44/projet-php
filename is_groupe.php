<?php
	require_once('includes/connexionbd.php');

	if(isset($_GET['idpersonne'])){


		$idpersonne = htmlentities(intval($_GET['idpersonne']));

		$requete = $db->prepare("SELECT idgroupe, fidelegroupe.date_inscription  from personne, fidele, groupe, fidelegroupe where fidele.personne_idpersonne = personne.idpersonne AND fidele.idfidele = fidelegroupe.fidele_idfidele AND groupe.idgroupe = fidelegroupe.groupe_idgroupe AND personne.lisible = 1 AND fidele.lisible = 1 AND groupe.lisible = 1 AND fidelegroupe.lisible = 1 AND typegroupe != 'Anciens' AND idpersonne = $idpersonne ORDER BY nomgroupe ASC");

		$requete->execute();

		$personne = null;

		while($donnees = $requete->fetch(PDO::FETCH_OBJ)){

			$personne = $donnees;

		}

		$json = $personne;

		echo json_encode($json);
	}
?>



