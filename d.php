<?php
	require_once('includes/connexionbd.php');

	if(isset($_GET['idregion'])){

		$json = array();

		$idregion = htmlentities(intval($_GET['idregion']));

		$requete = "SELECT * from departement WHERE lisible=1 AND region_idregion = $idregion";
		
		$resultat = $db->query($requete) or die(print_r($db->errorInfo()));

		while ($donnees=$resultat->fetch(PDO::FETCH_ASSOC)) {
			$json[$donnees['iddepartement']][] = utf8_encode($donnees['departement']);
		}

		echo json_encode($json);
	}
?>
