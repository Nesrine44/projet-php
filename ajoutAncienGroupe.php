<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
       require_once('includes/function_role.php');
    //$codefidele=$_POST['choixcode'];
    $nomGroupe=$_POST['choixgroupe'];
    $selectIdGroupe = "SELECT idgroupe FROM groupe WHERE nomgroupe='$nomGroupe' and lisible=1;";
    $resselectIdGroup=$db->query($selectIdGroupe);
    while($idselectIdGroup=$resselectIdGroup->fetch(PDO::FETCH_ASSOC)){
        $idgroupe=$idselectIdGroup['idgroupe'];
    }

    foreach($_POST['choix'] as $idpersonne){

        $selectIdfidele = "SELECT idfidele FROM fidele WHERE personne_idpersonne='$idpersonne' and lisible=1;";
        $resselectIdfidele=$db->query($selectIdfidele);
        while($idselectIdFidele=$resselectIdfidele->fetch(PDO::FETCH_ASSOC)){
            $idfidele=$idselectIdFidele['idfidele'];
        }

        $insert3 = "INSERT INTO fidelegroupe values('',true, $idfidele, $idpersonne, $idgroupe);";
        $db->exec($insert3);

        $updateEstGroupe = "UPDATE fidele set estgroupe=1 WHERE idfidele=$idfidele;";
        $db->exec($updateEstGroupe);

    }
    $db=NULL;
    header("location:enregistrerAncienAgroupe.php");

}
else{
    header('Location:login.php');
}

?>
	