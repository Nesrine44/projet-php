<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Consulter participation")){

            header('Location:index.php');

        }else{

            
             if(!isset($_GET['id'])){

                    $idsaintescene = $_SESSION['id'];

                }else{

                    $idsaintescene=$_GET['id'];
                    $_SESSION['id'] = $idsaintescene;
                }

                $_SESION['id'] = $idsaintescene;

                if(!isset($_GET['statut'])){

                 $statut = $_SESSION['statut'];

                }else{

                    $statut=addslashes($_GET['statut']);
                    $_SESSION['statut'] = $statut;
                }
               

            $contributions = $db->prepare("SELECT type from contribution where lisible = 1");
            $contributions->execute();


            $off=0;
            $cons=0;
            $dons=0;
            $tra=0;
            $tot=0;


                        //nombre de fidele a afficher par page
            $nbeParPage=50;
            //total de fideles enregistrés
            $total = 0;

            //selection du nombre de fideles enregistrés
            $selectNombreFidele = $db->prepare("SELECT COUNT(idfidele) AS nbretotalfidele FROM fidele WHERE lisible=1 AND statut='$statut'");
            $selectNombreFidele->execute();
            
            while($idselectNombreFidele=$selectNombreFidele->fetch(PDO::FETCH_OBJ)){
                $total = $idselectNombreFidele->nbretotalfidele;
            }

            //calcul du nombre de pages
            $nbDePage = ceil($total/$nbeParPage);

            //navigation dans le paginator
            if(isset($_GET['p']) && !empty($_GET['p']) && ctype_digit($_GET['p'])==1){
                if($_GET['p'] > $nbDePage){
                    $pageCourante = $nbDePage;
                }else{
                    $pageCourante = $_GET['p'];
                }

            }else{
                $pageCourante = 1;
            }

            $premierElementDeLaPage = ($pageCourante - 1) * $nbeParPage;

            $fideles = $db->prepare("SELECT * FROM fidele, personne where fidele.personne_idpersonne = personne.idpersonne and fidele.lisible=1 and personne.lisible = 1 AND statut='$statut' ORDER BY nom ASC LIMIT $premierElementDeLaPage, $nbeParPage");
            $fideles->execute();

            $sommeContribution = $db->prepare("SELECT  idfidele,
                
                sum(case when idfidele IN(select fidele_idfidele from  contributionfidele where typecontribution = 'offrandes' and lisible = 1 and saintescene_idsaintescene=$idsaintescene) then (select DISTINCT montant from  contributionfidele where  fidele_idfidele = idfidele AND saintescene_idsaintescene = $idsaintescene AND typecontribution = 'offrandes' and contributionfidele.lisible = 1) else 0 end )as offrandes,

                sum(case when idfidele IN(select fidele_idfidele from  contributionfidele where typecontribution = 'don' and lisible = 1 and saintescene_idsaintescene=$idsaintescene) then (select DISTINCT montant from  contributionfidele where fidele_idfidele = idfidele AND saintescene_idsaintescene = $idsaintescene AND typecontribution = 'don' and contributionfidele.lisible = 1) else 0 end) as don,

                sum(case when idfidele IN(select fidele_idfidele from  contributionfidele where typecontribution = 'consiegerie' and lisible = 1 and saintescene_idsaintescene=$idsaintescene) then (select DISTINCT montant from  contributionfidele where fidele_idfidele = idfidele AND saintescene_idsaintescene = $idsaintescene AND typecontribution = 'consiegerie' and contributionfidele.lisible = 1) else 0 end) as consiegerie,

                sum(case when idfidele IN(select fidele_idfidele from  contributionfidele where typecontribution = 'travaux' and lisible = 1 and saintescene_idsaintescene=$idsaintescene) then (select DISTINCT montant from  contributionfidele where fidele_idfidele = idfidele AND saintescene_idsaintescene = $idsaintescene AND typecontribution = 'travaux' and contributionfidele.lisible = 1) else 0 end) as travaux
                from fidele where  fidele.lisible = true AND statut = '$statut'");
            $sommeContribution->execute();

             while ($x=$sommeContribution->fetch(PDO::FETCH_OBJ)) {

                $cons = $x->consiegerie;
                $don = $x->don;
                $off = $x->offrandes;
                $tra = $x->travaux;

            }


             $selectSainteC = $db->prepare("SELECT  idsaintescene, mois, annee from saintescene where idsaintescene = $idsaintescene AND saintescene.lisible = 1");
                $selectSainteC->execute();

                while ($x=$selectSainteC->fetch(PDO::FETCH_OBJ)) {
                    
                    $saintecene = $x;
                }
        }
        
    }else{
        header('Location:login.php');
    }
?>

    <section class="wrapper">
    <div style="background-color:orange; text-align:center; font-style: italic; font-style: bold; font-size: 1.5em; width:15%; margin-left:40%;" id="chargement">Chargement...</div>
        
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="index.php">Accueil</a></li>
                    <li><i class="fa fa-files-o"></i><a href="saintecene.php" class="afficher">Sainte Cène</a></li>
                    <li><i class="fa fa-files-o"></i>Liste Participation du mois de <?php echo $saintecene->mois.$saintecene->annee; ?></li>
                    <li style="float: right;"> 
                        <a class="" href="report/imprimer_param.php?file=fichecontributionMensuelle&amp;param=<?php echo $idsaintescene; ?>" title="Imprimer la liste des contributions de <?php echo $saintecene->mois.$saintecene->annee; ?>" target="_blank"><i class="fa fa-print"></i> Imprimer</a>
                    </li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">

                    <div class=class="row">
                        <div class="col-lg-12">
                            <header class="panel-heading">
                                Bilan de toutes les contributions

                                <!-- Module de recherche -->
                                <div class="form-group">
                                    <input class="form-control" id="recherche" type="text" name="search" placeholder="Rechercher un fidèle">
                                </div>
                            </header>

                            <!-- Balise du resultat de la recherche-->
                            <div id="result">
                            </div>
                            <!-- fin de la balise-->

                            <div id="old_table">
                                <table class="table table-striped table-advance table-hover">
                                    <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th><i class="icon_profile"></i>Noms et prenom</th>
                                        <th><i class=""></i><a class="ordre_rangement"  href="contributionParStatut.php" title="Réorganiser les contributions par statut" class="afficher">Statut</a></th>

                                        <?php

                                            while ($contribution=$contributions->fetch(PDO::FETCH_OBJ)) {
                                        ?>
                                                <th><?php echo $contribution->type; ?></th>
                                        <?php
                                            }

                                         ?>
                                        <th><i class=""></i>Total</th>
                                        <th><i class="icon_cogs"></i> Action</th>

                                    </tr>
                                    <?php
                                    $n=0;
                                    $totaloffrande=0;
                                    $totaltravaux=0;
                                    $totalconsiegerie=0;
                                    $totaldon=0;
                                    $totalTtout=0;

                                    while($fidele=$fideles->fetch(PDO::FETCH_OBJ)){


                                        $selectMontantOffrande = "SELECT SUM(montant) as totaloffrande FROM contributionfidele WHERE fidele_idfidele=$fidele->idfidele AND typecontribution='offrandes' AND lisible=1 AND saintescene_idsaintescene = $idsaintescene";
                                        $resMontantOffrande=$db->query("$selectMontantOffrande");
                                        while($idMontantOffrande=$resMontantOffrande->fetch(PDO::FETCH_ASSOC)){
                                            $sommeoffrande=$idMontantOffrande['totaloffrande'];
                                        }

                                        $selectMontantTravaux = "SELECT SUM(montant) as totalotravaux FROM contributionfidele WHERE fidele_idfidele=$fidele->idfidele AND typecontribution='travaux' AND lisible=1 AND saintescene_idsaintescene = $idsaintescene";
                                        $resMontantTravaux=$db->query("$selectMontantTravaux");
                                        while($idMontantTravaux=$resMontantTravaux->fetch(PDO::FETCH_ASSOC)){
                                            $sommetravaux=$idMontantTravaux['totalotravaux'];
                                        }

                                        $selectMontantConsiegerie = "SELECT SUM(montant) as totaloconsiegerie FROM contributionfidele WHERE fidele_idfidele=$fidele->idfidele AND typecontribution='consiegerie' AND lisible=1 AND saintescene_idsaintescene = $idsaintescene";
                                        $resMontantConsiegerie=$db->query("$selectMontantConsiegerie");
                                        while($idMontantConsiegerie=$resMontantConsiegerie->fetch(PDO::FETCH_ASSOC)){
                                            $sommeconsiegerie=$idMontantConsiegerie['totaloconsiegerie'];
                                        }

                                        $selectMontantDon = "SELECT SUM(montant) as totaldon FROM contributionfidele WHERE fidele_idfidele=$fidele->idfidele AND typecontribution='don' AND lisible=1 AND saintescene_idsaintescene = $idsaintescene";
                                        $resMontantDon=$db->query("$selectMontantDon");
                                        while($idMontantDon=$resMontantDon->fetch(PDO::FETCH_ASSOC)){
                                            $sommedon=$idMontantDon['totaldon'];
                                        }

                                        $total = $sommeoffrande+$sommetravaux+$sommeconsiegerie+$sommedon;

                                        $statut = str_replace(' ', '+',$fidele->statut );
                                        $statut = addslashes($statut);
                                        ?>



                                        <tr>
                                            <td><?php echo ++$n ; ?></td>
                                            <td><?php echo $fidele->codefidele; ?></td>
                                            <td><a
                                                    <?php
                                                                if(has_Droit($idUser, "Afficher fidele")){
                                                                    echo 'href="afficherFidele.php?code='.$fidele->idpersonne.'"';
                                                                }else{echo "";}
                                                         ?>
                                             class="afficher" title="Afficher le fidèle"><?php echo $fidele->nom.' '.$fidele->prenom ; ?></a>
                                            </td>
                                            <td>
                                                <a href="traitementContributionParStatut.php?statut=<?php echo $statut; ?>&amp;id=<?php echo $idsaintescene; ?>" title="Afficher les contributions par statut <?php echo $fidele->statut; ?>" class="afficher"><?php echo $fidele->statut; ?></a>
                                            </td>
                                            <td style="text-align: center;"><?php echo $sommeconsiegerie ; ?></td>
                                            <td style="text-align: center;"><?php echo $sommedon ; ?></td>
                                            <td style="text-align: center;"><?php echo $sommeoffrande; ?></td>
                                            <td style="text-align: center;"><?php echo $sommetravaux ; ?></td>
                                            <td style="text-align: center;"><?php echo $total ; ?></td>

                                            <td>
                                                <div class="btn-group">
                                                    <a class="btn btn-primary afficher" href="afficherContributionFidele.php?idfidele=<?php echo $fidele->idfidele; ?>&idpersonne=<?php echo $fidele->personne_idpersonne; ?>&page=ficheContributionParType.php?code=<?php echo $fidele->idpersonne; ?>" title="Voir"><i class="icon_plus_alt2"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                        $totaloffrande=$totaloffrande+$sommeoffrande;
                                        $totaltravaux=$totaltravaux+$sommetravaux;
                                        $totalconsiegerie=$totalconsiegerie+$sommeconsiegerie;
                                        $totaldon=$totaldon+$sommedon;
                                        $totalTtout=$totalTtout+$total;
                                    }
                                    ?>
                                        <tr>                                            
                                            <td colspan="4">Total</td>
                                            <td style="text-align: center;"><b><?php echo $totalconsiegerie; ?></b></td>
                                            <td style="text-align: center;"><b><?php echo $totaldon; ?></b></td>
                                            <td style="text-align: center;"><b><?php echo $totaloffrande; ?></b></td>
                                            <td style="text-align: center;"><b><?php echo $totaltravaux; ?></b></td>
                                            <td style="text-align: center;"><b><?php echo $totalTtout; ?></b></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                        <tr class="centrer">                     
                                        <td colspan="4"><h3><b>Grand Total</b></h3></td>
                                        <td><?php echo $cons; ?></td>
                                        <td><?php echo $don; ?></td>
                                        <td><?php echo $off; ?></td>
                                        <td><?php echo $tra; ?></td>
                                        <td><h3><b><?php echo $cons+$don+$off+$tra; ?></b></h3></td>
                                        <td></td>
                                    </tr>
                                    
                                      
                                </table><br>

                                 <?php pagination($pageCourante, $nbDePage, "traitementContributionParStatut"); ?>
                            </div>

                                        
                                        
                            </div> 
                            <div align="center">
                                <a class="btn btn-success" href="report/imprimer_param.php?file=fichecontributionMensuelle&amp;param=<?php echo $idsaintescene; ?>" title="Imprimer la liste des contributions de <?php echo $saintecene->mois.$saintecene->annee; ?>" target="_blank"><i class="fa fa-print"></i> Imprimer</a>
                            </div>
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

    $('.item').on('click', function(i){
        $('.loader').show();

        i.preventDefault();
        $('#modifiertext').html('Chargement...');
        var $i = $(this);                            
        url = $i.attr('href');

        $('#main-content').load(url);

        $('#modifiertext').html('');
    });

    $('.listeStatut').on('click', function(e){
        $('.loader').show();
        e.preventDefault();

        var z = $(this);
        target = z.attr('href');

        $('#main-content').load(target);

    });

    $('.ordre_rangement').on('click', function(e){
        $('.loader').show();
        e.preventDefault();
        
        var z = $(this);
        target = z.attr('href');

        $('#main-content').load(target);


    });

    $('#recherche').keyup(function(){

        var txt = $(this).val();

        // alert(txt);
        if(txt != ''){
            $.ajax({

                url:"searchCont.php?id=<?php echo $idsaintescene; ?>",
                method:"get",
                data:{search:txt},
                dataType:"text",
                success:function(data)
                {
                    $('#old_table').hide();
                    $('#result').html(data);
                    //alert(txt);
                }
            });

        }else{
            // alert(txt);
            $('#result').html(txt);
            $('#old_table').show();
        }

    });
    $('.loader').hide();
</script>

<style type="text/css">
    .centrer td {

        text-align: center;
    }
</style>