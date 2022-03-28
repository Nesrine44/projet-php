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

        $d = date('Y-m-d');
        $m = explode('-', $d);
        $in = intval($m[1]);
        $annee_encours = $_SESSION['annee'];

        $selectSainteC = $db->prepare("SELECT idsaintescene, mois, annee from saintescene where valide = 0 AND lisible = 1");
                $selectSainteC->execute();

                $grandTotal = 0;
                $sous_grand_total = array(0, 0, 0, 0, 0);
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

        $collectes= $db->prepare("SELECT * FROM collectes");
        $collectes->execute();

        $coltotale= 0;
        while($col=$collectes->fetch(PDO::FETCH_OBJ)){
                                            
        $coltotale = $coltotale + $col->montant;
        }
        $donss= $db->prepare("SELECT * FROM dons");
        $donss->execute();

        $ttc= 0;
        while($do=$donss->fetch(PDO::FETCH_OBJ)){
                                            
        $ttc = $coltotale + $do->montant;
        }


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
              $dat = date('Y-m-d');

             function getMount($code, $idsaintescene, $idcontribution){

                global $db;
                global $dat; 
                global $in;

                $selects=$db->prepare("SELECT sum(montant) as montant, date FROM contributionfidele, fidele WHERE idfidele=fidele_idfidele AND fidele.lisible = 1 AND contributionfidele.lisible = 1 AND saintescene_idsaintescene=$idsaintescene AND codefidele LIKE '%$code' AND contribution_idcontribution = $idcontribution");
                $selects->execute();

                $vals = 0;


                if($x=$selects->fetch(PDO::FETCH_OBJ)){

                    
                    $mn = $x->date;
                    if($mn != null){
                        $moun =  explode('-', $mn);
                        $va = intval($moun[1]);
                        if($va == $in){
                            $vals = $x->montant;
                        }
                    }
                }

                return $vals;
             }



             $contributions = $db->prepare("SELECT idcontribution, type from contribution where lisible = 1");
             $contributions->execute();

             $contributions1 = $db->prepare("SELECT idcontribution, type from contribution where lisible = 1");
             $contributions1->execute();

             $allContributions = array();
             $allSaintescenes = array();
             $mnt = array(0, 0, 0, 0,0);

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

                <li style="float: right;"> 
                    <a class="" href="#" title="Imprimer la lla liste des projets" target="_blank"><i class="material-icons">print</i> Imprimer</a>
                    <a class="" href="#" title="Imprimer la lla liste des projets" target="_blank"><i class="material-icons">print</i> CSV</a>
                </li>
            </ol>
        </div>
    </div>

    <div class="row card">
        <div class="col-lg-12">
            <section class="panel">
                <div class="row">
                    <div class="col-lg-12">
                        <header class="panel-heading h4" style="text-align: center;">
                            Finance Générale
                        </header>
                        <div class="table-responsive">  
                            <table class="table table-bordered table-striped table-hover table-advance">
                                <thead>
                                   <tr>
                                        <th>Contributions</th> 
                                        <th>Mois en cours</th>
                                        <th>Bilan Annuel</th>
                                        <th>Type</th>
                                        <?php 
                                        $mt=0;
                                            while ($cont = $contributions->fetch(PDO::FETCH_OBJ)) {
                                         
                                                array_push($allContributions, $cont->idcontribution);
                                            }
                                        ?>
                                    </tr>   
                                </thead>  


                                <tbody>


                                             <tr>
                                                 <td align=center><a class="afficher" href="bilanGeneral.php">Sainte scène</a></td>
                                    <?php $k =0;
                                            while ($cont = $contributions1->fetch(PDO::FETCH_OBJ)) {
                                        ?>
                                        

                                <?php 
                                    $n = 0;
                                    while($liste = $selectSainteC->fetch(PDO::FETCH_OBJ)){

                                        $total_temp = 0;
                                        $total_pers_temp = 0;
                                        $sous_totaux_contribution = array();
                                        $vecteur = array();
                                        $sumount = array();
                                ?>
                                            <?php $q = getTotalPersonne('HM', $liste->idsaintescene); 
                                                  $total_pers_temp +=$q; 
                                            ?>
                                        <?php
                                            for($i=0;$i<count($allContributions);$i++){
                                                $val = getData('HM', $liste->idsaintescene, $allContributions[$i]);
                                                $totalHM += $val;   
                                                array_push($sous_totaux_contribution, $val);
                                                array_push($sumount, getMount('HM', $liste->idsaintescene, $allContributions[$i]));
                                                          array_push($vecteur, $val);
                                            }
                                        ?>
                                        <?php  array_sum($vecteur); $vecteur = array();  ?>
                                            <?php 
                                                $q = getTotalPersonne('FM', $liste->idsaintescene); 
                                                $total_pers_temp += $q; 
                                            ?> 
                                        <?php
                                            for($i=0;$i<count($allContributions);$i++){
                                                $val = getData('FM', $liste->idsaintescene, $allContributions[$i]);
                                                $totalFM    += $val;
                                                $sous_totaux_contribution[$i] += $val; 
                                                array_push($vecteur, $val);

                                                $m = getMount('FM', $liste->idsaintescene, $allContributions[$i]);

                                                $sumount[$i] += $m;
                                            }
                                        ?>
                                            <?php 
                                                 array_sum($vecteur); 
                                                $vecteur = array(); 
                                             ?>        
                                            <?php 
                                                $q = getTotalPersonne('EG', $liste->idsaintescene); 
                                                $total_pers_temp += $q; 
                                            ?>  
                                        <?php
                                            for($i=0;$i<count($allContributions);$i++){     
                                                $val = getData('EG', $liste->idsaintescene, $allContributions[$i]);
                                                $totalEG += $val;   
                                                $sous_totaux_contribution[$i] += $val; 
                                                array_push($vecteur, $val);

                                                $m = getMount('EG', $liste->idsaintescene, $allContributions[$i]);

                                                $sumount[$i] += $m;
                                            }
                                                    ?><?php  array_sum($vecteur); $vecteur = array();// $total_temp += $totalEG; echo $totalEG; ?>
                                            <?php 
                                                $q = getTotalPersonne('EF', $liste->idsaintescene); 
                                                $total_pers_temp += $q; 
                                            ?>                                              
                                        
                                        <?php
                                            for($i=0;$i<count($allContributions);$i++){
                                                $val = getData('EF', $liste->idsaintescene, $allContributions[$i]);
                                                $totalEF += $val;   
                                                $sous_totaux_contribution[$i] += $val; 
                                                array_push($vecteur, $val);

                                                $m = getMount('EF', $liste->idsaintescene, $allContributions[$i]);

                                                $sumount[$i] += $m;
                                            }
                                        ?><?php  array_sum($vecteur); $vecteur = array();?><?php  $total_pers_temp; ?>
                                        <?php   
                                            $p = 0;
                                            for($j=0; $j<count($sous_totaux_contribution); $j++){
                                               
                                                $sous_grand_total[$j] += $sous_totaux_contribution[$j];
                                                $mnt[$j] += $sumount[$j];
                                            }
                                        ?><?php  array_sum($sous_totaux_contribution); ?> 
                                    <?php
                                        $grandTotalPers += $total_pers_temp;

                                }
                                ?>




                                        <?php
                                        $k++;  
                                            }
                                            echo '<td align=center>'.array_sum($mnt).'</td>';
                                            echo '<td align=center>'.array_sum($sous_grand_total).'</td>';
                                             echo '<td align=center>Sainte scène </td>';
                                        ?>
                                         </tr>
                                         <tr>
                                             <td align="center"><a class="afficher" href="listedons.php">Collectes Domminicales</a></td>
                                             <td align="center">------------</td>
                                             <td align="center"><?php echo $coltotale; ?></td>
                                             <td align="center">Collectes Domminicales</td>
                                         </tr> 
                                          <tr>
                                             <td align="center"><a class="afficher" href="listecollectes.php">Collectes Spéciales</a></td>
                                             <td align="center">------------</td>
                                             <td align="center"><?php echo $ttc; ?></td>
                                             <td align="center">Collectes Spéciales</td>
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
        var $b = $(this);
        url = $b.attr('href');

        $('#main-content').load(url);
    });

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