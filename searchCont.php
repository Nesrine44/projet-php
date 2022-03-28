<?php
session_start();

if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login'];

    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');

    if(!has_Droit($idUser, "Consulter participation")){

        header('Location:index.php');

    }else{

        $contributions = $db->prepare("SELECT type from contribution where lisible = 1");
        $contributions->execute();

        $output = "";
        $sql = null;
        $idsaintescene = $_GET['id'];

        if(!isset($_GET['search'])){

            echo 'Aucun resultat';

        }else{

            $txt = addslashes($_GET['search']);

            $sql = $db->prepare("SELECT idfidele, idpersonne, codefidele, nom, prenom, statut
                                    FROM fidele, personne
                                    WHERE fidele.personne_idpersonne = personne.idpersonne
                                    AND fidele.lisible=1
                                    AND personne.lisible = 1
                                    AND (nom LIKE '%".$txt."%' OR prenom LIKE '%".$txt."%' OR CONCAT(nom, ' ', prenom) LIKE '%".$txt."%' OR codefidele LIKE '%".$txt."%'OR statut LIKE '%".$txt."%') ORDER BY nom ASC LIMIT 0, 20");

            $sql->execute();

            if($sql){

                $output.='<h4 align="center"><Search></Search> Result</h4>';
                $output.='<table class="table table-advance table-hover table-advance">';

                $output.='<tr>
	                                        <th>#</th>
                                    <th>Code</th>
                                    <th><i class="icon_profile"></i>Noms et prenom</th>
                                    <th><i class=""></i>Statut</th>';


                            while ($contribution=$contributions->fetch(PDO::FETCH_OBJ)) {
                                    $output.='
                                        <th>' .$contribution->type. '</th>';
                            }
                           $output.='
                            <th><i class=""></i>Total</th>
                                    <th><i class="icon_cogs"></i> Action</th>;
                            </tr>';

                $n = 0;
                $totaloffrande=0;
                $totaltravaux=0;
                $totalconsiegerie=0;
                $totaldon=0;
                $totalTtout=0;

                while($row=$sql->fetch(PDO::FETCH_OBJ)){

                    $selectMontantOffrande = "SELECT SUM(montant) as totaloffrande FROM contributionfidele WHERE fidele_idfidele=$row->idfidele AND typecontribution='offrandes' AND lisible=1 AND saintescene_idsaintescene = $idsaintescene";
                    $resMontantOffrande=$db->query("$selectMontantOffrande");
                    while($idMontantOffrande=$resMontantOffrande->fetch(PDO::FETCH_ASSOC)){
                        $sommeoffrande=$idMontantOffrande['totaloffrande'];
                    }

                    $selectMontantTravaux = "SELECT SUM(montant) as totalotravaux FROM contributionfidele WHERE fidele_idfidele=$row->idfidele AND typecontribution='travaux' AND lisible=1 AND saintescene_idsaintescene = $idsaintescene";
                    $resMontantTravaux=$db->query("$selectMontantTravaux");
                    while($idMontantTravaux=$resMontantTravaux->fetch(PDO::FETCH_ASSOC)){
                        $sommetravaux=$idMontantTravaux['totalotravaux'];
                    }

                    $selectMontantConsiegerie = "SELECT SUM(montant) as totaloconsiegerie FROM contributionfidele WHERE fidele_idfidele=$row->idfidele AND typecontribution='conciergerie' AND lisible=1 AND saintescene_idsaintescene = $idsaintescene";
                    $resMontantConsiegerie=$db->query("$selectMontantConsiegerie");
                    while($idMontantConsiegerie=$resMontantConsiegerie->fetch(PDO::FETCH_ASSOC)){
                        $sommeconsiegerie=$idMontantConsiegerie['totaloconsiegerie'];
                    }

                    $selectMontantDon = "SELECT SUM(montant) as totaldon FROM contributionfidele WHERE fidele_idfidele=$row->idfidele AND typecontribution='don' AND lisible=1 AND saintescene_idsaintescene = $idsaintescene";
                    $resMontantDon=$db->query("$selectMontantDon");
                    while($idMontantDon=$resMontantDon->fetch(PDO::FETCH_ASSOC)){
                        $sommedon=$idMontantDon['totaldon'];
                    }

                    $total = $sommeoffrande+$sommetravaux+$sommeconsiegerie+$sommedon;

                    $output.='

	    					<tr>
	    						<td>'.++$n.'</td>
	    						<td>'.$row->codefidele.'</td>
	    						<td>'.$row->nom.' '.$row->prenom.'</td>
	    						<td>'.$row->statut.'</td>
	    						<td>' .$sommeconsiegerie.'</td>
	    						<td>' .$sommedon.'</td>
	    						<td>' .$sommeoffrande.'</td>
	    						<td>' .$sommetravaux.'</td>
	    						<td>' .$total.'</td>
	    						<td>
	    							
                                                    <a class="btn btn-primary afficher" href="afficherContributionFidele.php?idfidele='.$row->idfidele.'&idpersonne='.$row->idpersonne.'&page=ficheContributionParType.php?code='.$row->idpersonne.'" title="Voir"><i class="icon_plus_alt2"></i></a>
                                                    <a class="btn btn-success" href="report/imprimer_param2.php?file=ticket&param='.$row->idfidele.'&param2='.$idsaintescene.'" title="Imprimer le reçu" target="_blank"><i class="fa fa-print"></i>
                                                    </a>
                                   
                                </td>
	    			        </tr>';
                }

                $output.='</table>';


                echo $output;



            }else{

                echo 'Data  Not Found';
            }

        }

    }
    //return json data
    //   echo json_encode($data);

}else{

    header('Location:login.php');
}

?>

<script>

   $('.afficher').on('click', function(af){

                            af.preventDefault();
                            $('.loader').show();
                           var $b = $(this);
                            url = $b.attr('href');
                           $('#main-content').load(url, function(){
                                $('.loader').hide();
                           });
                        });
	$('.loader').hide();
</script>

