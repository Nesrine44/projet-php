<?php
	
	require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');

    
/*
    	$selectD = $db->prepare("SELECT iddroit from droit where lisible = 1");
    	$selectD->execute();

    	while($y = $selectD->fetch(PDO::FETCH_OBJ)){*/

    		//$insert = $db->prepare("INSERT INTO roledroit(hasDroit, droit_iddroit, role_idrole) VALUES(0, 59, 4), (0, 60, 4)");
    		//$insert->execute();
    	//}

    		$selectD = $db->prepare("SELECT codefidele, idfidele from fidele where lisible = 1 and codefidele LIKE '2017%'");

    		$selectD->execute();

    		while($y = $selectD->fetch(PDO::FETCH_OBJ)){


    			$val = str_replace('2017', '17', $y->codefidele);
    			$id = $y->idfidele;

    			$update = $db->prepare("UPDATE fidele set codefidele = '$val' where lisible = 1 AND idfidele = $id");
    			$update->execute();

    		}

?>