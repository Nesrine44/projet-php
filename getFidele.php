<?php
	require_once('includes/connexionbd.php');

	if(isset($_GET['id'])){

		$json = array();
		$idfidele = htmlentities(intval($_GET['id']));

		$requete = $db->prepare("SELECT nom, prenom, codefidele, personne_idpersonne, datenaiss, date_inscription from fidele, personne where idpersonne=personne_idpersonne AND fidele.lisible = 1 and personne.lisible = 1 and idfidele = $idfidele");
		$requete->execute();

		while ($data=$requete->fetch(PDO::FETCH_OBJ)) {
			$json = array('codefidele' => $data->codefidele, 'nom' => $data->nom, 'prenom' => $data->prenom, 'idpersonne' => $data->personne_idpersonne, 'datenaiss' => $data->datenaiss, 'dateInscription' => $data->date_inscription);
		}

		echo json_encode($json);
	}
?>
