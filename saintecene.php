<?php
    session_start();
    if(isset($_SESSION['login'])){
       $idUser = $_SESSION['login'];
        //require_once('layout.php');
       require_once('includes/connexionbd.php');
       require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Consulter participation")){
            header('Location:index.php');
        }else{

        	 $selectSainteC = $db->prepare("SELECT idsaintescene, mois, annee from saintescene where valide = 0 AND lisible = 1");
                $selectSainteC->execute();

                $total = 0;

             function getMontant($idsaintescene){

                $montant = 0;

                global $db; //= new PDO('mysql:host=localhost;dbname=paroisse', 'paroisse', 'paroisse#2016');

                $selectMontant = $db->prepare("SELECT sum(montant) as sommes from contributionfidele, fidele where saintescene_idsaintescene = $idsaintescene AND idfidele = fidele_idfidele 
                    AND fidele.lisible = 1 
                    AND contributionfidele.lisible = 1");
                $selectMontant->execute();

                while($s=$selectMontant->fetch(PDO::FETCH_OBJ)){

                    $montant = ($s->sommes ? $s->sommes : 0);
                }

                return $montant;
             }

              function getTotalMontantPartype($idcontribution){

                $montant = 0;

                global $db; 

                $selectMontant = $db->prepare("SELECT sum(montant) as sommes from contributionfidele, fidele where  contribution_idcontribution = $idcontribution AND idfidele = fidele_idfidele 
                    AND fidele.lisible = 1 
                    AND contributionfidele.lisible = 1");
                $selectMontant->execute();

                while($s=$selectMontant->fetch(PDO::FETCH_OBJ)){

                    $montant = ($s->sommes ? $s->sommes : 0);
                }

                return $montant;
             }

            
             function getMontantParticipationParType($idsaintescene, $idcontribution){

             	$participation = 0;
             	global $db; //= new PDO('mysql:host=localhost;dbname=paroisse', 'paroisse', 'paroisse#2016');

                //selection des participations
                $selectParticipation = $db->prepare("SELECT sum(montant) as nbre_participation from contributionfidele, fidele where  saintescene_idsaintescene = $idsaintescene AND idfidele = fidele_idfidele 
                    AND fidele.lisible = 1 
                    AND contributionfidele.lisible = 1 
                    AND contribution_idcontribution = $idcontribution");
                $selectParticipation->execute();


                while($s=$selectParticipation->fetch(PDO::FETCH_OBJ)){

                    $participation = ($s->nbre_participation ? $s->nbre_participation : 0);
                }

                return $participation;

             }

             $contributions = $db->prepare("SELECT idcontribution, type from contribution where lisible = 1");
             $contributions->execute(); 

             $allContributions = array();
             $allSaintescenes = array();
             $contributionsfoot = $db->prepare("SELECT idcontribution, type from contribution where lisible = 1");
             $contributionsfoot->execute();
              $allContributionsfoot = array();
             $allSaintescenesfoot = array();



       	}
    }else{

    	header('Location:login.php');
    }

?>


<section class="wrapper">    
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li class="col-blue"><i class="material-icons">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                <li class="col-blue"><i class="material-icons">assistant</i> Sainte Cène</li>
                
                <li style="float: right;"> 
                        <a class="afficher col-blue" href="nouvelleContributionSainteCene.php" title="nouvelle Contribution"><i class="material-icons">plus_one</i> Nouvelle Contribution</a>
                </li>
                <li style="float: right;"> 
                        <a class="afficher col-blue" href="nouvelleSaintecene.php" title="nouvelle Saintecène"><i class="material-icons">plus_one</i> Nouvelle Sainte Cène</a>
                </li>
            </ol>
        </div>
    </div>

     <div class="row card">
        <div class="col-lg-12">
            <section class="panel">

                <div class="row">
                    <div class="col-lg-12">
                        <header class="panel-heading h4 text-center">
                            <span style="font-size: 1.5em;">Liste des saintes Cènes enrégistrées pour l'année <?php echo $_SESSION['annee']; ?></span>
                        </header>  

                        <div id="old_table" class="table-responsive">
                        	<table class="table table-bordered table-striped table-hover tableau_dynamique">

                        		<thead>                        			
                        			<th> <i class="material-icons iconposition">confirmation_number</i> Numéro</th>
                        			<th> <i class="material-icons iconposition">assistant</i> Sainte Cène</th>
                                    <?php 
                                        while ($cont = $contributions->fetch(PDO::FETCH_OBJ)) {
                                            
                                    ?>
                                        <th style="text-align: center;"> <i class="material-icons iconposition">monetization_on</i> <?php echo $cont->type; ?></th>

                                    <?php   
                                        array_push($allContributions, $cont->idcontribution);
                                        }
                                     ?>
                                    
                        			<th style="text-align: center;"> <i class="material-icons iconposition">monetization_on</i> Montant</th>
                        		</thead>
                                <tfoot>                                 
                                    <th> <i class="material-icons iconposition">confirmation_number</i> Numéro</th>
                                    <th> <i class="material-icons iconposition">assistant</i> Sainte Cène</th>
                                    <?php 

                                        while ($conts = $contributionsfoot->fetch(PDO::FETCH_OBJ)) {
                                            
                                    ?>
                                        <th style="text-align: center;"> <i class="material-icons iconposition">monetization_on</i> <?php echo $conts->type; ?></th>

                                    <?php   
                                        array_push($allContributionsfoot, $conts->idcontribution);
                                        }
                                     ?>
                                    
                                    <th style="text-align: center;"> <i class="material-icons iconposition">monetization_on</i> Montant</th>
                                </tfoot>

                        		<tbody>
                        			<?php 
                        				$n = 0;

                        				while($liste = $selectSainteC->fetch(PDO::FETCH_OBJ)){

                        			?>
                        			
                        					<tr>
                        						<td><?php echo ++$n; ?></td>
                        						<td><a href="ficheContributionParType.php?id=<?php echo $liste->idsaintescene;?>" title="Afficher les détails" class="afficher"><?php echo $liste->mois." ".$liste->annee;  ?></a></td>
                                                <?php 
                                                    for($i=0; $i<count($allContributions); $i++){

                                                ?>
                                                
                                                        <td style="text-align: center;"><?php echo getMontantParticipationParType($liste->idsaintescene, $allContributions[$i]);?></td>
                                                <?php        
                                                    }
                                                 ?>
                        						
                        						<td style="text-align: center;"><h4><?php echo getMontant($liste->idsaintescene); ?></h4></td>
                        					</tr>
                        			<?php		

                        				$total += getMontant($liste->idsaintescene);
                                        
                        				}
                        			?>
                                    </tbody>
                                    <tfoot>
                        					<tr>
                        						<td colspan="2" style="text-align: center; font-size: 1.5em; ">Total</td>
                                                <?php

                                                    for ($i=0; $i < count($allContributions); $i++) { 
                                                ?>

                                                        <td style="text-align: center;"><b><?php echo getTotalMontantPartype($allContributions[$i]); ?></b></td>

                                                <?php
                                                    }
                                                ?>
                        						<td style="text-align: center; font-size: 1.5em; "><?php echo $total; ?></td>
                        					</tr>
                        		</tfoot>

                        	</table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>

<script>
    
  $(".tableau_dynamique").DataTable();

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