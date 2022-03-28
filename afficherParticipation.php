<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Consulter participation")){

            header('Location:index.php');

        }else{

        	if(!isset($_GET['code'])){

        		header('Location:index.php');

        	}else{

        		$idfidele = $_GET['code'];
        		$fidele = null;

        		$selectF = $db->prepare("SELECT fidele_idfidele as idfidele, idsaintescene, nom, prenom, sexe, idpersonne, codefidele FROM personne, fidele, saintescene, fidelesaintescene   WHERE idpersonne=personne_idpersonne AND saintescene_idsaintescene = idsaintescene AND fidele.idfidele = fidelesaintescene.fidele_idfidele AND personne.lisible=1 AND fidele.lisible=1 AND saintescene.lisible = 1 AND fidele.idfidele = $idfidele");
        		$selectF->execute();

        		while ($s = $selectF->fetch(PDO::FETCH_OBJ)) {

        			$fidele = $s;
        		}

        	$selectSainteC = $db->prepare("SELECT saintescene_idsaintescene as idsaintescene, mois, annee from fidelesaintescene, saintescene where idsaintescene = saintescene_idsaintescene AND saintescene.lisible = 1 AND fidele_idfidele = $idfidele GROUP BY idsaintescene");
            $selectSainteC->execute();


        	}

        }
    }
?>
<section class="wrapper">    
       
        
	<div class="row">
	    <div class="col-lg-12">
	        <ol class="breadcrumb">
	            <li><i class="fa fa-home"></i><a href="index.php">Accueil</a></li>
	            <li><i class="fa fa-files-o"></i><a href="saintecene.php" class="afficher">Sainte Cène</a></li>
	           <li><i class="icon_document_alt"></i>Fiche de participation</li>
	        </ol>
	    </div>
	</div>

	<div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <div class=class="row">
                    <div class="col-lg-12">                        
                           <p style="font-size: 1.4em;"> Fiche de contribution de <span style="font-weight: bold; font-size: 1.4em;"><?php echo $fidele->nom.' '.$fidele->prenom; ?></span><br>
                           </p>
    <?php 		
    					while ($sainteC = $selectSainteC->fetch(PDO::FETCH_OBJ)) {
   	?>
    						<div><b>Sainte Cène de <?php echo $sainteC->mois.$sainteC->annee; ?></b></div>
                            <table class="table table-striped table-advance table-hover">
                                <tbody>
                                <tr>
                                    <th><i class=""></i>#</th>
                                    <th><i class="icon_calendar"></i>Date</th>
                                    <th><i class="icon_pin_alt"></i>Montant(Fcfa)</th>
                                    <th>Note</th>
                                </tr>

     <?php
     							$selectInfo = $db->prepare("SELECT date_contribution, contribution, remarque FROM fidelesaintescene where fidele_idfidele = $fidele->idfidele AND saintescene_idsaintescene = $sainteC->idsaintescene");
     							$selectInfo->execute();
     							$n = 0;
     							$total = 0;
     							while($info=$selectInfo->fetch(PDO::FETCH_OBJ))
     							{
     ?>
	                                <tr>
	                                    <td><?php echo ++$n; ?></td>
	                                    <td><?php echo $info->date_contribution; ?></td>
	                                    <td><?php echo $info->contribution; ?></td>
	                                    <td><?php echo $info->remarque; ?></td>
	                                </tr>
	<?php
	                                $total += $info->contribution;
	                            }
	?>
                                <tr>
                                    <td colspan="2" style="text-align: center; font-size: 1.3em;">Total</td>
                                    <td style="font-size: 1.3em;"><b><?php echo $total; ?></b></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table><br>
    <?php					
    					}


    ?>                           

                    </div>
                </div>
            </section>
        </div>
    </div>



</section>

<script type="text/javascript">

	$('#chargement').hide();

	$('.afficher').on('click', function(af){

	    af.preventDefault();
	    var $b = $(this);
	    url = $b.attr('href');
       $('#main-content').load(url);
    });
	$('.loader').hide();
</script>