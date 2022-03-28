<?php
	require_once('includes/connexionbd.php');

	if(isset($_GET['id1'])){

		$json = 0;

		$idfidele = htmlentities(intval($_GET['id1']));
		$idsaintesecene = htmlentities(intval($_GET['id2']));
		$requete = $db->prepare("UPDATE contributionfidele SET recu = 1 where fidele_idfidele=$idfidele AND saintescene_idsaintescene = $idsaintesecene AND lisible = 1");
		
		if($requete->execute()){

			$json = 1; 
		}

		echo json_encode($json);
	}
?>
