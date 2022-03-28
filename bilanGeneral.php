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

    	$annee_encours = $_SESSION['annee'];

    	$selectSainteC = $db->prepare("SELECT idsaintescene, mois, annee from saintescene where valide = 0 AND lisible = 1");
                $selectSainteC->execute();

                $grandTotal = 0;
                $sous_grand_total = array(0, 0, 0, 0);
                $grandTotalPers = 0;
                $totalSC = 0;
                $totalHM = 0;
                $totalFM = 0;
                $totalEG = 0;
                $totalEF = 0;

        $depense= $db->prepare("SELECT *
                                            
                                        FROM
                                            depenses
                                        WHERE lisible = 1
                                        ORDER BY id ASC");
        $depense->execute();
        $total = 0;
        while($liste=$depense->fetch(PDO::FETCH_OBJ)){
                                            
        $total = $total + $liste->montant;
        }
                                      




             function getTotalPersonne($code, $idsaintescene){


                global $db; 
                $n = 0;
                $select = $db->prepare("SELECT DISTINCT fidele_idfidele from fidele, contributionfidele where  idfidele=fidele_idfidele AND fidele.lisible = 1 AND contributionfidele.lisible = 1 AND saintescene_idsaintescene=$idsaintescene AND codefidele LIKE '%$code'");
                $select->execute();

                while($s=$select->fetch(PDO::FETCH_OBJ)){

                    ++$n;
                }

                return $n;
             }

             function getData($code, $idsaintescene, $idcontribution){

             	global $db;
             	

             	$select=$db->prepare("SELECT sum(montant) as montant FROM contributionfidele, fidele WHERE idfidele=fidele_idfidele AND fidele.lisible = 1 AND contributionfidele.lisible = 1 AND saintescene_idsaintescene=$idsaintescene AND codefidele LIKE '%$code' AND contribution_idcontribution = $idcontribution");
             	$select->execute();

             	$val = 0;


             	if($x=$select->fetch(PDO::FETCH_OBJ)){

             		$val = $x->montant;
             	}

             	return $val;
             }

             $contributions = $db->prepare("SELECT idcontribution, type from contribution where lisible = 1");
             $contributions->execute();

             $allContributions = array();
             $allSaintescenes = array();

    }
}else{

	header('Location:index.php');
}
?>


<section class="wrapper">
   
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <ol class="breadcrumb">
                 <li class="col-blue"><i class="material-icons">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                <li class="col-blue"><i class="material-icons">assistant</i><a href="sainteCene.php" class="afficher col-blue"> Sainte Cène</a></li>
                <li class="col-blue"><i class="material-icons">assistant</i> Bilan Général</li>
            </ol>
        </div>
    </div>

    <div class="row card">
        <div class="col-lg-12">
            <section class="panel">
                <div class="row">
                    <div class="col-lg-12">
                        <header class="panel-heading h4" style="text-align: center;">
                            Bilan général des contributions aux différentes préparations  à la Sainte Cène Année <?php echo $annee_encours; ?>
                        </header>
                        <div class="table-responsive">	
			                <table class="table table-bordered table-striped table-hover table-advance">
			                    <thead>
				                   <tr>
					                    <th>Numéro</th>
					            		<th>SAINTE CENE</th>
					                    <th>CATEGORIE</th>
					                    <th>EFFECTIFS</th>
					                    <?php 
	                                        while ($cont = $contributions->fetch(PDO::FETCH_OBJ)) {
	                                    ?>
					                           <th><?php echo strtoupper($cont->type); ?></th>
					                    <?php   
					                            array_push($allContributions, $cont->idcontribution);
					                        }
					                    ?>
					                                    
					                    <th style="text-align: center;">TOTAL</th>
				                    </tr>	
			                    </thead>			                              
			                    <tbody>
			                    <?php 
	                        		$n = 0;
                    				while($liste = $selectSainteC->fetch(PDO::FETCH_OBJ)){

	                        			$total_temp = 0;
	                        			$total_pers_temp = 0;
	                        			$sous_totaux_contribution = array();
	                        			$vecteur = array();
	                        	?>
			                        <tr>		                        			
			                        	<td rowspan="5" style="vertical-align: middle; text-align: center;"><?php echo ++$n; ?>			                        		
			                        	</td>
			                        	<td rowspan="5" style="vertical-align: middle; text-align: center;">
			                        		<a class ="afficher" <?php if(has_Droit($idUser, "Consulter participation")) echo 'href="bilanGeneralMensuel.php?id='.$liste->idsaintescene.'"'; ?> title="Afficher les details de ce mois">
			                        			<?php echo $liste->mois." ".$liste->annee; ?>			                        			
			                        		</a>
			                        	</td>
			                        	<td>Hommes</td>
			                        	<td align="center">
			                        		<?php $q = getTotalPersonne('HM', $liste->idsaintescene); 
			                        			  $total_pers_temp +=$q;      				 
			                        			  echo $q; 
			                        		?>			                        				 	
			                        	</td>
			                        	<?php
			                        		for($i=0;$i<count($allContributions);$i++){
			                        			$val = getData('HM', $liste->idsaintescene, $allContributions[$i]);
			                        			$totalHM += $val;	
			                        			array_push($sous_totaux_contribution, $val);
			                        			echo '<td align=center>'.$val.'</td>';
			                        					  array_push($vecteur, $val);
                        					}
                        				?>
                        				<td class="ss_total"><?php echo array_sum($vecteur); $vecteur = array();  ?></td>
			                        </tr>
			                        <tr>		                        				
			                        	<td>Femmes</td>
			                        	<td align="center">
			                        		<?php 
			                        			$q = getTotalPersonne('FM', $liste->idsaintescene); 
			                        			$total_pers_temp += $q; 
			                        			echo $q; 
			                        		?>			                        			
			                        	</td>
			                        	<?php
			                        		for($i=0;$i<count($allContributions);$i++){
			                        			$val = getData('FM', $liste->idsaintescene, $allContributions[$i]);
			                        			$totalFM	+= $val;
			                        			$sous_totaux_contribution[$i] += $val; 
			                        			array_push($vecteur, $val);
			                        			echo '<td align=center>'.$val.'</td>';
											}
										?>
			                        	<td class="ss_total">
			                        		<?php 
			                        			echo array_sum($vecteur); 
			                        			$vecteur = array(); 
			                        		 ?>		                        		 	
			                        	</td>                     				
			                        </tr>
			                        <tr>
			                        	<td>Garçons</td>
			                        	<td align="center">
			                        		<?php 
			                        			$q = getTotalPersonne('EG', $liste->idsaintescene); 
			                        			$total_pers_temp += $q; 
			                        			echo $q; 
			                        		?>			                        			
			                        	</td>
			                        	<?php
                        					for($i=0;$i<count($allContributions);$i++){		
    	                    					$val = getData('EG', $liste->idsaintescene, $allContributions[$i]);
				                        		$totalEG += $val;	
			                        			$sous_totaux_contribution[$i] += $val; 
			                        			array_push($vecteur, $val);
			                        			echo '<td align=center>'.$val.'</td>';
			                        		}
			                        				?>
			                        				<td class="ss_total"><?php echo array_sum($vecteur); $vecteur = array();// $total_temp += $totalEG; echo $totalEG; ?></td> 
			                        </tr>
			                        <tr>		                        				
			                        	<td>Filles</td>
			                            <td align="center">
			                            	<?php 
			                            		$q = getTotalPersonne('EF', $liste->idsaintescene); 
			                            		$total_pers_temp += $q; 
			                            		echo $q; 
			                            	?>			                            		
			                            </td>
			                        	<?php
                        					for($i=0;$i<count($allContributions);$i++){
                         					    $val = getData('EF', $liste->idsaintescene, $allContributions[$i]);
			                        			$totalEF += $val;	
			                        			$sous_totaux_contribution[$i] += $val; 
			                        			array_push($vecteur, $val);
			                        			echo '<td align=center>'.$val.'</td>';
                        					}
                        				?>
                        				<td class="ss_total"><?php echo array_sum($vecteur); $vecteur = array();?></td> 
			                        </tr>
			                        <tr class="gras">
			                        	<td>TOTAL</td>
			                        	<td align="center"><?php echo $total_pers_temp; ?></td>
			                        	<?php	
			                        		$p = 0;
			                        		for($j=0; $j<count($sous_totaux_contribution); $j++){
			                        			echo '<td align=center>'.$sous_totaux_contribution[$j].'</td>';
			                        			$sous_grand_total[$j] += $sous_totaux_contribution[$j];
			                        		}
			                        	?>
			                        	<td class="ss_total"><?php echo array_sum($sous_totaux_contribution); ?></td>  
			                        </tr>
	                        		<?php
			                        	$grandTotalPers += $total_pers_temp;
                        		}
	                        	?>
			                        	  	
			                        <tr class="summary">
			                        	<td colspan="3">TOTAL SAINTE CENE</td>
			                        	<td align="center"><?php echo $grandTotalPers;  ?></td>
			                        	<?php 	
			                        		for($k=0; $k<count($sous_grand_total); $k++){
	                        					echo '<td align=center>'.$sous_grand_total[$k].'</td>';
	                        				}	 
	                        			?>                       				
                        				<td class="gt"><?php echo array_sum($sous_grand_total) ?></td>  
                        			</tr>
                        			<tr class="summary">
                        				<td colspan="3">DEPENSES</td>
                        				<td colspan="<?php echo((count($allContributions))+2) ?>">
                        					<?php echo $total; ?>
                        				</td>
                        			</tr>
                        			<tr class="summary">
                        				<td colspan="3">SOLDE</td>
                        				<td style="color: red" colspan="<?php echo((count($allContributions))+2) ?>">
                        					<?php  echo (array_sum($sous_grand_total)  - $total); ?>
                        				</td> 
                        			</tr>
                              </tbody>
                          </table> 
                                     
                	</div>
                </div>
            </div>    	
        </section>
       </div>
    </div>
</section>

<script>
   // $(".tableau_dynamique").DataTable();

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

<style type="text/css">
	thead th{

		text-align: center;
	}

	.summary td{

		font-size: 1.5em;
		font-weight: bold;
		text-align: center;
		background-color: #F5F8FA;
	}
	.gras{

		background-color: #E1E8EB;
		font-size: 1.2em;
		font-weight: bold;
		text-align: center;
	}
	.ss_total{

		font-size: 1.2em;
		font-weight: bold;
		text-align: center;
	}

	.gt{

		font-size: 2em;
		font-weight: bold;
		text-align: center;
		background-color: green;
	}
</style>