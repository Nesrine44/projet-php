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

        $saintescene = "";
        $idsaintescene = 0;
        $jour = date('Y-m-d');
        $heure = '14:00:00';

        //info sur la sainte scene en cours        
        $select_saintescene = $db->prepare("
                SELECT distinct mois, annee, idsaintescene FROM `contributionfidele` 
                     INNER JOIN saintescene ON `saintescene_idsaintescene` = idsaintescene
                    AND month(`date`) = month('$jour')
                    AND `utilisateur_idutilisateur` = $idUser
                    AND saintescene.lisible = 1
                    AND contributionfidele.`lisible` = 1
                    AND valide = 0;
            ");

        $select_saintescene->execute();        

        while ($s=$select_saintescene->fetch(PDO::FETCH_OBJ)) {
            
            $idsaintescene = $s->idsaintescene;            
            $saintescene = $saintescene = $s->mois.' '.$s->annee;
        }

        $depPerPeriod= $db->prepare("SELECT *
                                            
                                        FROM depenses
                                        WHERE utilisateur_idutilisateur = $idUser
                                        ORDER BY id DESC");
        $depPerPeriod->execute();


        function getMontant($jour, $idcontribution, $idfidele){

            global $db;
            global $idUser;
            $montant = 0;

            $select= $db->prepare("SELECT montant
                                            FROM contributionfidele, fidele
                                            WHERE idfidele = fidele_idfidele 
                                            AND date='$jour'
                                            AND contributionfidele.lisible=1
                                            AND fidele.lisible = 1
                                            AND contribution_idcontribution = $idcontribution
                                            AND fidele_idfidele = $idfidele
                                            AND utilisateur_idutilisateur = $idUser");
            $select->execute();

            while ($x=$select->fetch(PDO::FETCH_OBJ)) {
                
                $montant = $x->montant;
            }

            return $montant;
        }


               $contribution = array();
               $typeContribution = array();
               $grandTotal  = array();
               $somme_fidele = array();

                $nbre_HM = 0;
                $nbre_FM = 0;
                $nbre_EG = 0;
                $nbre_EF = 0;

                $grandTotal1 = 0;
                $sous_grand_total1 = array(0, 0, 0, 0);
                $grandTotal1Pers1 = 0;
                $totalSC1 = 0;
                $totalHM1 = 0;
                $totalFM1 = 0;
                $totalEG1= 0;
                $totalEF1 = 0;

                $grandTotal1é = 0;
                $sous_grand_total2 = array(0, 0, 0, 0);
                $grandTotal1Pers12 = 0;
                $totalSC12 = 0;
                $totalHM12 = 0;
                $totalFM12 = 0;
                $totalEG12 = 0;
                $totalEF12 = 0;

            $selectC = $db->prepare("SELECT * from contribution where lisible = 1");
            $selectC->execute();

            $select_contribution= $db->prepare("SELECT heure, saintescene_idsaintescene, fidele_idfidele
                                            FROM contributionfidele, fidele
                                            WHERE idfidele = fidele_idfidele 
                                            AND date='$jour'
                                            AND contributionfidele.lisible=1
                                            AND fidele.lisible = 1
                                            AND utilisateur_idutilisateur = $idUser
                                           GROUP BY fidele_idfidele");
            $select_contribution->execute();

             function getTotalPersonne($code, $idsaintescene, $date, $periode){

                global $db; 
                global $heure;
                global $idUser;
                $query = "SELECT DISTINCT fidele_idfidele from fidele, contributionfidele where  idfidele=fidele_idfidele AND fidele.lisible = 1 AND contributionfidele.lisible = 1 AND saintescene_idsaintescene=$idsaintescene AND codefidele LIKE '%$code' AND contributionfidele.date = '$date' AND utilisateur_idutilisateur = $idUser AND ";
                $suite = $periode == 1 ? " heure < '$heure'" : " heure >= '$heure'";
                $query = $query.$suite;
                $n = 0;
                $select = $db->prepare($query);
                $select->execute();

                while($s=$select->fetch(PDO::FETCH_OBJ)){

                    ++$n;
                }

                return $n;
             }
            

             function getData($code, $idsaintescene, $idcontribution, $date, $periode){

                global $db;
                global $heure;
                global $idUser;
                $query = "SELECT sum(montant) as montant FROM contributionfidele, fidele WHERE idfidele=fidele_idfidele AND fidele.lisible = 1 AND contributionfidele.lisible = 1 AND saintescene_idsaintescene=$idsaintescene AND codefidele LIKE '%$code' AND contribution_idcontribution = $idcontribution AND contributionfidele.date = '$date' AND utilisateur_idutilisateur = $idUser  AND ";

                 $suite = $periode == 1 ? " heure < '$heure'" : " heure >= '$heure'";
                $query = $query.$suite;

                $select=$db->prepare($query);
                $select->execute();

                $val = 0;


                if($x=$select->fetch(PDO::FETCH_OBJ)){

                    $val = $x->montant;
                }

                return $val;
             }

            
             $utilisateur = "";
            $select_urilisateur=$db->prepare("SELECT *
                                                FROM personne, utilisateur
                                                WHERE utilisateur.lisible=1
                                                AND personne.lisible=1
                                                AND personne.idpersonne=utilisateur.personne_idpersonne
                                                AND idutilisateur=$idUser");
            $select_urilisateur->execute();


            while ($x = $select_urilisateur->fetch(PDO::FETCH_OBJ)) {
                
                $utilisateur = $x->nom.' '.$x->prenom;
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

    header('Location:index.php');
}
?>


<section class="wrapper">
   
    <div class="row no_print">
        <div class="col-lg-12 col-sm-12">
            <ol class="breadcrumb">
                <li class="col-blue"><i class="material-icons">home</i><a class="col-blue" href="index.php"> Accueil</a></li>
                <li class="col-blue"><i class="material-icons">assistant</i><a href="saintecene.php" class="afficher col-blue"> Sainte Cène</a></li>
                <li class="col-blue"><i class="material-icons">assistant</i> Bilan du <?php echo $jour; ?> enrégistré par "<?php echo $utilisateur; ?>"</li>
            </ol>
        </div>
    </div>

    <div class="row card">
        <div class="col-lg-12">
            <section class="panel">
                <div class="row">
                    <div class="col-lg-12">

                    <!-- mes contributions -->

                    <div id="old_table" class="table-responsive">
                            <h3 class="text-center" >MON BILAN</h3>
                            <table class="table table-bordered table-striped table-hover tableau_dynamique">
                                <thead>
                                    <tr>
                                        <th>Numéro</th>
                                        <th>Code</th>
                                        <th>Noms et prenoms</th>
                                        <th>Statut paroissial</th>
                                        <th>Heure</th>
                                        <?php  
                                            
                                            while($cont=$selectC->fetch(PDO::FETCH_OBJ)){


                                                array_push($contribution, $cont->idcontribution);
                                                array_push($typeContribution, $cont->type);                                                                                                                   
                                                $sTotal[] = 0; 
                                                $montant_HM[] = 0;
                                                $montant_FM[] = 0;
                                                $montant_EG[] = 0;
                                                $montant_EF[] = 0;                                           
                                        ?>
                                            <th align="center"><?php echo $cont->type; ?></th>

                                        <?php

                                            }
                                        ?>
                                      
                                        <th style="text-align: center; font-weight: bold;">Total</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>    
                                    <?php
                                    $n=0;
                                    //$sTotal = array();
                                    while($liste=$select_contribution->fetch(PDO::FETCH_OBJ)){

                                        
                                        $idsaintescene = $liste->saintescene_idsaintescene;

                                        $fidele=$db->prepare("SELECT nom, prenom, codefidele, idfidele, statut
                                                                FROM fidele, personne
                                                                WHERE personne.lisible=1
                                                                AND fidele.lisible=1
                                                                AND personne.idpersonne=fidele.personne_idpersonne
                                                                AND fidele.idfidele=$liste->fidele_idfidele                
                                                                ");
                                        $fidele->execute();

                                        while($liste1=$fidele->fetch(PDO::FETCH_OBJ)){
                                            $code = substr($liste1->codefidele, 7, 9);
                                            switch ($code) {
                                                case 'HM':
                                                    $nbre_HM++;
                                                    break;
                                                case 'FM':
                                                    $nbre_FM++;
                                                    break;
                                                case 'EG':
                                                    $nbre_EG++;
                                                    break;
                                                case 'EF':
                                                    $nbre_EF++;
                                                    break;
                                              
                                            }
                                            $statut = str_replace(" ", "+", $liste1->statut);

                                            ?>
                                            <tr>
                                                <td><?php echo ++$n; ?></td>
                                                <td><?php echo $liste1->codefidele; ?></td>
                                                <td><?php echo $liste1->nom.' '.$liste1->prenom; ?></td>
                                                <td><?php echo $statut; ?></td>                                               
                                                <td><?php echo $liste->heure; ?></td>
                                                <?php 
                                                    for($i=0;$i<count($contribution);$i++){

                                                        $temp = getMontant($jour, $contribution[$i], $liste1->idfidele);
                                                        array_push($somme_fidele, $temp);
                                                        switch ($code) {
                                                            case 'HM':
                                                                $montant_HM[$i] += $temp;
                                                                break;
                                                            case 'FM':
                                                                $montant_FM[$i] += $temp;
                                                                break;
                                                            case 'EG':
                                                                $montant_EG[$i] += $temp;
                                                                break;
                                                            case 'EF':
                                                                $montant_EF[$i] += $temp;
                                                                break;
                                                          
                                                        }
                                                        echo'<td align="center">'.$temp.'</td>';
                                                        $sTotal[$i]+=$temp;
                                                    }


                                                 ?>
                                              
                                                <td style="text-align: center; font-weight: bold;">
                                                    <?php 
                                                        $temp2 = array_sum($somme_fidele);
                                                        array_push($grandTotal, $temp2);
                                                        echo  $temp2;
                                                        $somme_fidele = array();
                                                    ?>                                                 
                                                </td>
                                                <td>
                                                    <a class="btn btn-success" href="report/imprimer_param2.php?file=ticket&param=<?php echo $liste1->idfidele; ?>&param2=<?php echo $idsaintescene ?>" title="Imprimer le reçu" target="_blank"><i class="fa fa-print"></i></a>
                                                </td>
                                                
                                            </tr>

                                        <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot>    
                                    <tr>
                                        <td colspan="5"><h3>Total</h3></td>
                                        <?php 
                                           for ($j=0;$j<count($sTotal);$j++) {

                                                echo '<td style=" font-size: 1.5em; text-align: center;">'.$sTotal[$j].'</td>';
                                            
                                            }  
                                        ?>
                                        <td style="font-weight: bold; font-size: 1.5em; text-align: center;"><?php echo array_sum($grandTotal); ?>                                        
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>

                    <!--  --><br><br>
                    <table class="table table-bordered table-striped table-hover tableau_dynamique">
                                <caption style="font-size: 1.5em; font-weight: bold;text-align: center">MES DEPENSES</caption>
                                <thead>
                                    <tr>
                                        <th><i class="material-icons iconposition"></i>Numéro</th>
                                        <th><i class="material-icons iconposition"></i>Motif</th>
                                        <th><i class="material-icons iconposition"></i>Montant</th>
                                        <th><i class="material-icons iconposition"></i>Heure</th>
                                    </tr>
                               </thead> 
                                <tbody>
                                <?php
                                $n=0;
                                $t = 0;
                                

                                    while($dep=$depPerPeriod->fetch(PDO::FETCH_OBJ)){

                                        ?>
                                        <tr>
                                            <td><?php echo ++$n; ?></td>
                                            <td><?php echo $dep->motif; ?></td>
                                            <td><?php echo $dep->montant; $t = $t + $dep->montant;?></td>
                                            <td><?php echo $dep->heure; ?></td>
                                            
                                        </tr>

                                    <?php
                                    }
                                
                                ?>
                                </tbody> 
                                <tfoot>
                                    <tr>
                                    <td colspan="2"><h3>Total</h3></td>
                                    <td  colspan="2" style="font-weight: bold; font-size: 1.5em; text-align: center;"><?php echo $t; ?>                                        
                                    </td>
                                    <td></td>
                                </tr>    
                                </tfoot>                                                            
                            </table>

                            <br><br>

                        
                        <div class="table_responsive">
                            <table class="table table-bordered table-striped table-hover" id="bilanMensuel">
                                      <thead>
                                      <tr>
                                          <th>Numéro</th>
                                          <th>PERIODE</th>
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
                                            $n = 1;

                                            while($n <= 2){

                                                $total_temp = 0;
                                                $total_pers_temp = 0;
                                                $sous_totaux_contribution = array();
                                                $vecteur = array();
                                        ?>
                                            <tr>                                            
                                                <td rowspan="5" style="vertical-align: middle; text-align: center;"><?php echo $n; ?></td>
                                                <td rowspan="5" style="vertical-align: middle; text-align: center;">Période <?php echo $n; ?></td>
                                                <td>Hommes</td>
                                                <td align="center">
                                                    <?php $q = getTotalPersonne('HM', $idsaintescene, $jour, $n); $total_pers_temp += $q;
                                                 echo $q; 
                                                    ?>                                                  
                                                 </td>
                                                <?php

                                                    for($i=0;$i<count($allContributions);$i++){

                                                      $val = getData('HM',$idsaintescene, $allContributions[$i], $jour, $n);
                                                      $totalHM1 += $val;    
                                                      array_push($sous_totaux_contribution, $val);
                                                      echo '<td align=center>'.$val.'</td>';

                                                      array_push($vecteur, $val);
                                                    }
                                                ?>
                                                <td class="ss_total"><?php echo array_sum($vecteur); $vecteur = array(); //$total_temp += $totalHM1; echo $totalHM1; ?></td>
                                            </tr>
                                            <tr>                                                
                                                <td>Femmes</td>
                                                <td align="center"><?php $q = getTotalPersonne('FM', $idsaintescene, $jour, $n); $total_pers_temp += $q; echo $q; ?></td>
                                                <?php

                                                    for($i=0;$i<count($allContributions);$i++){

                                                      $val = getData('FM', $idsaintescene, $allContributions[$i], $jour, $n);
                                                      $totalFM1 += $val;
                                                      $sous_totaux_contribution[$i] += $val; 
                                                      array_push($vecteur, $val);
                                                      echo '<td align=center>'.$val.'</td>';

                                                    }
                                                ?>
                                                <td class="ss_total"><?php echo array_sum($vecteur); $vecteur = array(); //$total_temp += $totalFM1;  echo $totalFM1; ?></td>                                   
                                            </tr>
                                            <tr>
                                                <td>Garçons</td>
                                                <td align="center"><?php $q = getTotalPersonne('EG', $idsaintescene, $jour, $n); $total_pers_temp += $q; echo $q; ?></td>
                                                <?php

                                                    for($i=0;$i<count($allContributions);$i++){     
                                                      $val = getData('EG', $idsaintescene, $allContributions[$i], $jour, $n);
                                                      $totalEG1+= $val; 
                                                      $sous_totaux_contribution[$i] += $val; 
                                                      array_push($vecteur, $val);
                                                      echo '<td align=center>'.$val.'</td>';

                                                    }
                                                ?>
                                                <td class="ss_total"><?php echo array_sum($vecteur); $vecteur = array();// $total_temp += $totalEG1; echo $totalEG1; ?></td> 
                                            </tr>
                                            <tr>                                                
                                                <td>Filles</td>
                                                <td align="center"><?php $q = getTotalPersonne('EF', $idsaintescene, $jour, $n); $total_pers_temp += $q; echo $q; ?></td>
                                                <?php

                                                    for($i=0;$i<count($allContributions);$i++){

                                                      $val = getData('EF', $idsaintescene, $allContributions[$i], $jour, $n);
                                                      $totalEF1 += $val;    
                                                      $sous_totaux_contribution[$i] += $val; 
                                                      array_push($vecteur, $val);
                                                      echo '<td align=center>'.$val.'</td>';

                                                    }
                                                ?>
                                                <td class="ss_total"><?php echo array_sum($vecteur); $vecteur = array();//$total_temp += $totalEF1; echo $totalEF1; ?></td> 
                                            </tr>
                                            <tr class="gras">
                                                <td>TOTAL</td>
                                                <td align="center"><?php echo $total_pers_temp; ?></td>
                                            <?php   
                                                $p = 0;
                                                for($j=0; $j<count($sous_totaux_contribution); $j++){

                                                    

                                                    echo '<td align=center>'.$sous_totaux_contribution[$j].'</td>';
                                                    

                                                        $sous_grand_total1[$j] += $sous_totaux_contribution[$j];
                                                        
                                                    
                                                    
                                                }
                                            ?>
                                                                                            
                                                <td class="ss_total"><?php echo array_sum($sous_totaux_contribution); ?></td>  
                                            </tr>

                                        <?php
                                            
                                            $grandTotal1Pers1 += $total_pers_temp;
                                            $n++;

                                            }

                                        ?>
                                            <tr class="summary">
                                                <td colspan="3">GRAND TOTAL</td>
                                                <td align="center"><?php echo $grandTotal1Pers1;  ?></td>
                                            <?php   
                                                for($k=0; $k<count($sous_grand_total1); $k++){

                                                    echo '<td align=center>'.$sous_grand_total1[$k].'</td>';
                                                }    
                                            ?>                                      
                                                <td class="gt"><?php echo array_sum($sous_grand_total1) ?></td>  
                                            </tr>
                                      </tbody>
                                      <tfoot>
                                    <tr>
                                        <td colspan="2"><h3>SOLDE</h3></td>
                                        <td  colspan="2" style="font-weight: bold; font-size: 1.5em; text-align: center;">DEPENCE: <?php echo $t; ?>                                        
                                        </td>
                                        <td  colspan="2" style="font-weight: bold; font-size: 1.5em; text-align: center;">ENTREE: <?php echo array_sum($sous_grand_total1); ?>                                        
                                        </td><td  colspan="2" style="font-weight: bold; font-size: 1.5em; text-align: center;">RESTE: <?php  echo array_sum($sous_grand_total1) - $t; ?>                                        
                                        </td>
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
<script> 
function impression() { 
    no_print.style.visibility = 'hidden';    
    window.print();             
    no_print.style.visibility = 'visible';    
    } 
 
</script>