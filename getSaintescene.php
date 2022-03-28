<?php
	require_once('includes/connexionbd.php');

	if(isset($_GET['id'])){

		$json = array();

		$idfidele = $_GET['idfidele'];
		$id = htmlentities(intval($_GET['id']));

		$requete = $db->prepare("SELECT type from contribution, contributionfidele WHERE idcontribution=contribution_idcontribution AND saintescene_idsaintescene = $id and fidele_idfidele = $idfidele and  contributionfidele.lisible = 1 AND contribution.lisible = 1");
		
		$requete->execute();
		$n=0;
		$data=$requete->fetch(PDO::FETCH_OBJ);
			$json= $data;
		echo json_encode($json);
	}
?>
