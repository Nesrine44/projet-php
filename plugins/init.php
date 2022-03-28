<?php 
    session_start();

	if(isset($_SESSION['login'])){		

		$idLogin = $_SESSION['login'];
		require_once('layout.php');	
		require_once('includes/function_role.php');

        $nbreMensuel = 0;
        $nbreExtraordinaire=0;
        $nbreElargi = 0;
        $nbreConsistoire=0;
        $nbreCinode=0;
        $decembre = 0;


		$fidel = $db->prepare("SELECT count(idfidele) as nbre FROM fidele where lisible = 1");
		$fidel->execute();

		$malade = $db->prepare("SELECT count(idmalade) as nbre FROM malade, fidele where idfidele = fidele_idfidele AND malade.lisible = 1 AND fidele.lisible = 1 ");
		$malade->execute();   

		$groupe = $db->prepare("SELECT COUNT(idgroupe) as nbre FROM groupe where lisible = 1");
		$groupe->execute();
		
		$pasteur = $db->prepare("SELECT COUNT(idpasteur) as nbre FROM pasteur where lisible = 1");
		$pasteur->execute();


        
        $nbreConseilMensuel = "SELECT mensuel FROM occurrence WHERE idoccurrence=1;";
        $resconseil=$db->query("$nbreConseilMensuel");
        while($idconseil=$resconseil->fetch(PDO::FETCH_ASSOC)){
            $nbreMensuel=$idconseil['mensuel'];
        }

        $nbreConseilExtraordinaire = "SELECT extraordinaire FROM occurrence WHERE idoccurrence=1;";
        $resextraordinaire=$db->query("$nbreConseilExtraordinaire");
        while($idextraordinaire=$resextraordinaire->fetch(PDO::FETCH_ASSOC)){
            $nbreExtraordinaire=$idextraordinaire['extraordinaire'];
        }

        $nbreConseilElargi = "SELECT elargi FROM occurrence WHERE idoccurrence=1;";
        $reselargi=$db->query("$nbreConseilElargi");
        while($idelargi=$reselargi->fetch(PDO::FETCH_ASSOC)){
            $nbreElargi=$idelargi['elargi'];
        }

        $nbreConseilConsistoire = "SELECT consistoire FROM occurrence WHERE idoccurrence=1;";
        $resconsistoire=$db->query("$nbreConseilConsistoire");
        while($idconsistoire=$resconsistoire->fetch(PDO::FETCH_ASSOC)){
            $nbreConsistoire=$idconsistoire['consistoire'];
        }

        $nbreConseilCinode = "SELECT cinode FROM occurrence WHERE idoccurrence=1;";
        $rescinode=$db->query("$nbreConseilCinode");
        while($idcinode=$rescinode->fetch(PDO::FETCH_ASSOC)){
            $nbreCinode=$idcinode['cinode'];
        }

        $breAncien = "SELECT  COUNT(idfidele) AS nombreanciens FROM fidele as fil WHERE statut= 'ancien' AND fil.lisible=1";
        $resnombreAnciens=$db->query("$breAncien");
        while($idnombreAnciens=$resnombreAnciens->fetch(PDO::FETCH_ASSOC)){
            $nbreAnciens=$idnombreAnciens['nombreanciens'];
        }

        $selectAnciens = "SELECT idfidele FROM fidele fil WHERE statut= 'ancien' AND fil.lisible=1";
        $resanciens=$db->query("$selectAnciens");

        $tauxMensuel=0;
        $tauxEtraordinaire=0;
        $tauxElargi=0;
        $tauxConsistoire=0;
        $tauxCinode=0;

        while($idAnciens=$resanciens->fetch(PDO::FETCH_ASSOC)){
            $identifiantancien=$idAnciens['idfidele'];

            $nbrePresenceMensuel = "SELECT COUNT(fidele_idfidele)as nombremensuel FROM  fideleconseil, fidele WHERE idfidele = fidele_idfidele AND type = 'conseil mensuel' AND fideleconseil.lisible=1 AND fidele_idfidele=$identifiantancien AND fidele.lisible = 1";
            $resnbrePresenceMensuel=$db->query("$nbrePresenceMensuel");
            while($idnbrePresenceMensuel=$resnbrePresenceMensuel->fetch(PDO::FETCH_ASSOC)){
                $mensuel1=$idnbrePresenceMensuel['nombremensuel'];
            }

            $nbrePresenceExtraordinaire = "SELECT COUNT(fidele_idfidele)as nombreextra FROM  fideleconseil, fidele WHERE idfidele = fidele_idfidele AND type = 'conseil extraordinaire' AND fideleconseil.lisible=1 AND fidele_idfidele=$identifiantancien AND fidele.lisible = 1";
            $resnbrePresenceExtraordianire=$db->query("$nbrePresenceExtraordinaire");
            while($idnbrePresenceExtraordinaire=$resnbrePresenceExtraordianire->fetch(PDO::FETCH_ASSOC)){
                $extraordinaire1=$idnbrePresenceExtraordinaire['nombreextra'];
            }

            $nbrePresenceElargi = "SELECT COUNT(fidele_idfidele)as nombreelargi FROM  fideleconseil , fidele WHERE idfidele = fidele_idfidele AND type = 'conseil elargi' AND fideleconseil.lisible=1 AND fidele_idfidele=$identifiantancien AND fidele.lisible = 1";
            $resnbrePresenceElargi=$db->query("$nbrePresenceElargi");
            while($idnbrePresenceElargi=$resnbrePresenceElargi->fetch(PDO::FETCH_ASSOC)){
                $elargi1=$idnbrePresenceElargi['nombreelargi'];
            }

            $nbrePresenceConsistoire = "SELECT COUNT(fidele_idfidele)as nombreconsis FROM  fideleconseil , fidele WHERE idfidele = fidele_idfidele AND type = 'consistoire' AND fideleconseil.lisible=1 AND fidele_idfidele=$identifiantancien AND fidele.lisible = 1";
            $resnbrePresenceConsistoire=$db->query("$nbrePresenceConsistoire");
            while($idnbrePresenceConsistoire=$resnbrePresenceConsistoire->fetch(PDO::FETCH_ASSOC)){
                $consistoire1=$idnbrePresenceConsistoire['nombreconsis'];
            }

            $nbrePresenceCinode = "SELECT COUNT(fidele_idfidele)as nombreconode FROM  fideleconseil , fidele WHERE idfidele = fidele_idfidele AND type = 'cinode regional' AND fideleconseil.lisible=1 AND fidele_idfidele=$identifiantancien AND fidele.lisible = 1";
            $resnbrePresenceCinode=$db->query("$nbrePresenceCinode");
            while($idnbrePresenceCinode=$resnbrePresenceCinode->fetch(PDO::FETCH_ASSOC)){
                $cinode1=$idnbrePresenceCinode['nombreconode'];
            }

            if($nbreMensuel==0){
                $tauxMensuel=0;
            }else{
                $tauxMensuel = $tauxMensuel + $mensuel1 * 100/$nbreMensuel;
            }

            if($nbreExtraordinaire==0){
                $tauxEtraordinaire=0;
            }else{
                $tauxEtraordinaire = $tauxEtraordinaire + $extraordinaire1 * 100/$nbreExtraordinaire;
            }

            if($nbreElargi==0){
                $tauxElargi=0;
            }else{
                $tauxElargi = $tauxElargi + $elargi1 * 100/$nbreElargi;
            }

            if($nbreConsistoire==0){
                $tauxConsistoire=0;
            }else{
                $tauxConsistoire = $tauxConsistoire + $consistoire1 * 100/$nbreConsistoire;
            }

            if($nbreCinode==0){
                $tauxCinode=0;
            }else{
                $tauxCinode = $tauxCinode + $cinode1 * 100/$nbreCinode;
            }

        }
        $tauxMensuel = ($nbreAnciens == 0 ? 0 : number_format($tauxMensuel/$nbreAnciens, 2));
        $tauxEtraordinaire = ($nbreAnciens == 0 ? 0 : number_format($tauxEtraordinaire/$nbreAnciens, 2));
        $tauxElargi = ($nbreAnciens == 0 ? 0 : number_format($tauxElargi/$nbreAnciens, 2));
        $tauxConsistoire = ($nbreAnciens == 0 ? 0 : number_format($tauxConsistoire/$nbreAnciens, 2));
        $tauxCinode = ($nbreAnciens == 0 ? 0 : number_format($tauxCinode/$nbreAnciens, 2));

        //gestion Sainte Scene

        $ps_Sainte_Scene=$db->prepare("SELECT * FROM saintescene WHERE lisible=1");
        $ps_Sainte_Scene->execute();

        $saintescess='';
        $montantsaintesceness=0;

        while($saintesce=$ps_Sainte_Scene->fetch(PDO::FETCH_OBJ)){
            $noms=$saintesce->mois;
            $saintescess=$saintescess.'"'.$noms.'"'.', ';

            $ps_montant_sainte_scene=$db->prepare("SELECT SUM(montant) AS montantsaintescene
                                                    FROM contributionfidele
                                                    WHERE 	saintescene_idsaintescene=$saintesce->idsaintescene
                                                    AND lisible=1");
            $ps_montant_sainte_scene->execute();

            $montantsaintesce=$ps_montant_sainte_scene->fetch(PDO::FETCH_OBJ);
            if(!isset($montantsaintesce->montantsaintescene)){
                $valeur_montants=0;
            }else{
                $valeur_montants=$montantsaintesce->montantsaintescene;
            }
            $montantsaintesceness=$montantsaintesceness.$valeur_montants.", ";
        }

        $zones="";
        $nombreFideleParzone=0;
        $selectNomZone ="SELECT nomzone FROM zone WHERE lisible=1;";
        $resselectNomZone=$db->query("$selectNomZone");
        while($idselectNomZone=$resselectNomZone->fetch(PDO::FETCH_ASSOC)){
            $nomZone=$idselectNomZone['nomzone'];
            $zones=$zones.$nomZone.',';
            //$zones=$zones.'"'.$nomZone.'"'.', ';

            $nombreFi = "SELECT COUNT(idpersonne) AS nombre FROM personne, zone, fidele  WHERE idzone=zone_idzone AND personne_idpersonne=idpersonne AND fidele.lisible=1 AND personne.lisible=1 AND zone.lisible = 1 AND zone.idzone = personne.zone_idzone AND zone.nomzone ='$nomZone'";
            $resnombreFi=$db->query("$nombreFi");
            while($idnombreFi=$resnombreFi->fetch(PDO::FETCH_ASSOC)){
                $nombrefid=$idnombreFi['nombre'];
                $nombreFideleParzone=$nombreFideleParzone.$nombrefid.",";
            }
        }

    }else{

		header('Location:login.php');
	}
