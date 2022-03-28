<?php
session_start();
if(isset($_SESSION['login'])){ 
    $idUser = $_SESSION['login'];
    //require_once('layout.php');
    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');

    if(!has_Droit($idUser, "Enregistrer une participation")){
        header('Location:index.php');
    }else{
        

        $idsaintescene = 0;

        $date_jour = date('Y-m-d');
        $annee = $_SESSION['annee'];
        $contribution = array();
        $somme_fidele = array();
        $grandTotal  = array();
        $typeContribution = array();
        $nbre_HM = 0;
        $nbre_FM = 0;
        $nbre_EG = 0;
        $nbre_EF = 0;
        $montant_HM = array();
        $montant_FM = array();
        $montant_EG = array();
        $montant_EF = array();
        $sTotal = array();
        $stat_total = 0;
      
        $selectC = $db->prepare("SELECT * from contribution where lisible = 1");
        $selectC->execute();
        $depPerPeriod= $db->prepare("SELECT *
                                            
                                        FROM
                                            depenses
                                        WHERE lisible = 1 
                                        AND date='$date_jour'
                                        ORDER BY id DESC");
            $depPerPeriod->execute();

        //fonction qui recupere le montant de chaque fidele
        function getMontant($date_jour, $idcontribution, $idfidele){

            global $db;
            $montant = 0;

            


            $select= $db->prepare("SELECT montant
                                            FROM contributionfidele, fidele
                                            WHERE idfidele = fidele_idfidele 
                                            AND date='$date_jour'
                                            AND contributionfidele.lisible=1
                                            AND fidele.lisible = 1
                                            AND contribution_idcontribution = $idcontribution
                                            AND fidele_idfidele = $idfidele");
            $select->execute();

            while ($x=$select->fetch(PDO::FETCH_OBJ)) {
                
                $montant = $x->montant;
            }

            return $montant;
        }

         $select_contribution= $db->prepare("SELECT heure, saintescene_idsaintescene, fidele_idfidele
                                            FROM contributionfidele, fidele
                                            WHERE idfidele = fidele_idfidele 
                                            AND date='$date_jour'
                                            AND contributionfidele.lisible=1
                                            AND fidele.lisible = 1
                                           GROUP BY fidele_idfidele");
        $select_contribution->execute();
        

     
    }

}else{
    header('Location:login.php');
}
?>

<section class="wrapper">
    <!--<div class="loader"></div>     -->

    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <ol class="breadcrumb">
                <li class="col-blue"><i class="material-icons">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                <li class="col-blue"><i class="material-icons">assistant</i><a href="sainteCene.php" class="afficher col-blue"> Sainte Cène</a></li>
                <li class="col-blue"><i class="material-icons">assistant</i> Bilan journalier</li>
               
            </ol>
        </div>
    </div>

    <div class="row card">
        <div class="col-lg-12">
            <section class="panel">

                <div class="row">
                    <div class="col-lg-12">
                        <header class="panel-heading text-center h4">
                            Contributions enregistrées le <?php echo $date_jour; ?> 
                        </header>
                        <div id="result"> </div>
                        <div id="old_table" class="table-responsive">
                            <table class="table table-bordered table-striped table-hover tableau_dynamique">
                                <thead>
                                    <tr>
                                        <th>Numéro</th>
                                        <th>Code</th>
                                        <th>Noms et prenoms</th>
                                        <th>Statut paroissial</th>
                                        <th>Sainte Cène</th>
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

                                    if($liste->saintescene_idsaintescene==1){
                                        $sainteCene='Janvier';
                                    }elseif($liste->saintescene_idsaintescene==2){
                                        $sainteCene='Février';
                                    }elseif($liste->saintescene_idsaintescene==3){
                                        $sainteCene='Mars';
                                    }elseif($liste->saintescene_idsaintescene==4){
                                        $sainteCene='Avril';
                                    }elseif($liste->saintescene_idsaintescene==5){
                                        $sainteCene='Mai';
                                    }elseif($liste->saintescene_idsaintescene==6){
                                        $sainteCene='Juin';
                                    }elseif($liste->saintescene_idsaintescene==7){
                                        $sainteCene='Juillet';
                                    }elseif($liste->saintescene_idsaintescene==8){
                                        $sainteCene='Aout';
                                    }elseif($liste->saintescene_idsaintescene==9){
                                        $sainteCene='Septembre';
                                    }elseif($liste->saintescene_idsaintescene==10){
                                        $sainteCene='Octobre';
                                    }elseif($liste->saintescene_idsaintescene==11){
                                        $sainteCene='Novembre';
                                    }elseif($liste->saintescene_idsaintescene==12){
                                        $sainteCene='Decembre';
                                    }

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
                                            <td><?php echo $sainteCene.' '.$annee; ?></td>
                                            <td><?php echo $liste->heure; ?></td>
                                            <?php 
                                                for($i=0;$i<count($contribution);$i++){

                                                    $temp = getMontant($date_jour, $contribution[$i], $liste1->idfidele);
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
                                    <td colspan="6" align="center"><h3>Total</h3></td>
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



                            <table class="table table-bordered table-striped table-hover tableau_dynamique">
                                <caption style="font-size: 1.5em; font-weight: bold;text-align: center">Dépense Journlière</caption>
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











                        

                             <table class="table table-bordered table-striped table-hover statistique">
                             <caption style="font-size: 1.5em; font-weight: bold; text-align: center">Statistiques de la journée du <?php echo $date_jour; ?></caption>
                                      <thead>
                                      <tr>
                                          <th>CATEGORIE</th>
                                          <th>EFFECTIFS</th>
                                          <?php 
                                            for($i=0;$i<count($typeContribution);$i++){

                                                echo '<th>'.strtoupper($typeContribution[$i]).'</th>';
                                            }
                                          ?>
                                          <th>TOTAL</th>
                                     </tr>
                                     </thead>
                                     <tbody>
                                        <tr>
                                           <td>Hommes</td>
                                            <td><?php echo $nbre_HM; ?></td>
                                            <?php 
                                                for($i=0;$i<count($montant_HM);$i++){

                                                    echo'<td>'.$montant_HM[$i].'</td>';
                                                }
                                             ?>
                                            <td><?php 
                                                $val =  array_sum($montant_HM); $stat_total+=$val; echo $val; 
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>                                            
                                            <td class="gauche">Femmes</td>
                                            <td><?php echo $nbre_FM; ?></td>
                                            <?php 
                                                for($i=0;$i<count($montant_FM);$i++){

                                                    echo'<td>'.$montant_FM[$i].'</td>';
                                                }
                                             ?>
                                           <td><?php 
                                                $val =  array_sum($montant_FM); $stat_total+=$val; echo $val; 
                                                ?>
                                            </td>
                                        </tr> 
                                        <tr>
                                            <td>Garçons</td>
                                            <td><?php echo $nbre_EG; ?></td>
                                           <?php 
                                                for($i=0;$i<count($montant_EG);$i++){

                                                    echo'<td>'.$montant_EG[$i].'</td>';
                                                }
                                             ?>
                                            <td><?php 
                                                $val =  array_sum($montant_EG); $stat_total+=$val; echo $val; 
                                                ?>
                                            </td>
                                        </tr> 
                                        <tr>
                                            <td>Filles</td>
                                            <td><?php echo $nbre_EF; ?></td>
                                            <?php 
                                                for($i=0;$i<count($montant_EF);$i++){

                                                    echo'<td>'.$montant_EF[$i].'</td>';
                                                }
                                             ?>
                                            <td><?php 
                                                $val =  array_sum($montant_EF); $stat_total+=$val; echo $val; 
                                                ?>
                                            </td>
                                        </tr> 
                                        <tr class="gras">
                                            <td>Total</td>
                                            <td><?php echo $nbre_HM+$nbre_FM+$nbre_EG+$nbre_EF; ?></td>
                                            <?php 
                                                for($i=0;$i<count($typeContribution);$i++){

                                                    $s = $montant_HM[$i]+$montant_FM[$i]+$montant_EG[$i]+$montant_EF[$i];
                                                    
                                                    echo'<td>'.$s.'</td>';
                                                }
                                             ?>
                                            <td><?php echo $stat_total; ?></td>
                                        </tr> 
                                     </tbody>
                                     <tfoot>
                                    <tr>
                                        <td colspan="2"><h3>SOLDE</h3></td>
                                        <td  colspan="2" style="font-weight: bold; font-size: 1.5em; text-align: center;">DEPENCE: <?php echo $t; ?>                                        
                                        </td>
                                        <td  colspan="2" style="font-weight: bold; font-size: 1.5em; text-align: center;">ENTREE: <?php echo $stat_total; ?>                                        
                                        </td><td  colspan="2" style="font-weight: bold; font-size: 1.5em; text-align: center;">RESTE: <?php echo $stat_total - $t; ?>                                        
                                        </td>
                                    </tr>    
                                    </tfoot>
                            </table>

                           <div align="center">   
                                <a class="btn btn-success h3" href="report/imprimer_param.php?file=bilanjounalier&amp;param=<?php echo $date_jour; ?>" title="Imprimer le bilan journalier" target="_blank"><i class="material-icons">print</i> Imprimer</a>                              
                          </div> 
                        </div>                         
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
    .gras{

        background-color: #E1E8EB;
        font-size: 1.2em;
        font-weight: bold;
        text-align: center;
    }
    .statistique td, th{

        text-align: center;
    }

</style>