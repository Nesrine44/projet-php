<?php
	require_once('includes/connexionbd.php');

	if(isset($_GET['iddept'])){

		$json = array();

		$iddepartement = htmlentities(intval($_GET['iddept']));

		$requete = "SELECT * from arrondissement WHERE lisible=1 AND departement_iddepartement = $iddepartement";
		
		$resultat = $db->query($requete) or die(print_r($db->errorInfo()));

		while ($donnees=$resultat->fetch(PDO::FETCH_ASSOC)) {
			$json[$donnees['idarrondissement']][] = utf8_encode($donnees['arrondissement']);
		}

		echo json_encode($json);
	}
?>
