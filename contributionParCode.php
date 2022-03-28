<?php
session_start();
if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login'];
    //require_once('layout.php');
    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');

    if(!has_Droit($idUser, "consulter participation")){
        header('Location:index.php');
    }else{

        $contributions = $db->prepare("SELECT type from contribution where lisible = 1");
        $contributions->execute();

        //nombre de fidele a afficher par page
        $nbeParPage=50;
        //total de fideles enregistrés
        $total = 0;

        //selection du nombre de fideles enregistrés
        $selectNombreFidele = $db->prepare("SELECT COUNT(idfidele) AS nbretotalfidele FROM fidele WHERE lisible=1");
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


        //selection des information sur les fideles
        $selectAllFidele= $db->prepare("SELECT *
                                        FROM fidele, personne
                                        WHERE fidele.personne_idpersonne = personne.idpersonne
                                        AND fidele.lisible=1
                                        AND personne.lisible = 1
                                        ORDER BY codefidele ASC LIMIT $premierElementDeLaPage, $nbeParPage");
        $selectAllFidele->execute();

    }

}else{
    header('Location:login.php');
}
?>

<section class="wrapper">

<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="index.php">Accueil</a></li>
            <li><i class="icon_document_alt"></i>Finances</li>
            <li><i class="fa fa-files-o"></i>Fiche Contribution</li>
            <li><i class="fa fa-files-o"></i>Fiche Contribution Par Statut</li>
        </ol>
    </div>
</div>

<div class="row">
<div class="col-lg-12">
    <section class="panel">

        <div class=class="row">
            <div class="col-lg-12">
                <header class="panel-heading">
                    Bilan des contributions par statut

                </header>

                <table class="table table-striped table-advance table-hover">
                    <tbody>
                    <tr>
                        <th>#</th>
                        <th><i class=""></i><a class="ordre_rangement"  href="contributionParCode.php" title="Réorganiser les contributions par code de fidèle croissant" class="afficher">Code</a></th>
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

                    while($fidele=$selectAllFidele->fetch(PDO::FETCH_OBJ)){


                        $selectMontantOffrande = "SELECT SUM(montant) as totaloffrande FROM contributionfidele WHERE fidele_idfidele=$fidele->idfidele AND typecontribution='offrandes' AND lisible=1";
                        $resMontantOffrande=$db->query("$selectMontantOffrande");
                        while($idMontantOffrande=$resMontantOffrande->fetch(PDO::FETCH_ASSOC)){
                            $sommeoffrande=$idMontantOffrande['totaloffrande'];
                        }

                        $selectMontantTravaux = "SELECT SUM(montant) as totalotravaux FROM contributionfidele WHERE fidele_idfidele=$fidele->idfidele AND typecontribution='travaux' AND lisible=1;";
                        $resMontantTravaux=$db->query("$selectMontantTravaux");
                        while($idMontantTravaux=$resMontantTravaux->fetch(PDO::FETCH_ASSOC)){
                            $sommetravaux=$idMontantTravaux['totalotravaux'];
                        }

                        $selectMontantConsiegerie = "SELECT SUM(montant) as totaloconsiegerie FROM contributionfidele WHERE fidele_idfidele=$fidele->idfidele AND typecontribution='consiegerie' AND lisible=1;";
                        $resMontantConsiegerie=$db->query("$selectMontantConsiegerie");
                        while($idMontantConsiegerie=$resMontantConsiegerie->fetch(PDO::FETCH_ASSOC)){
                            $sommeconsiegerie=$idMontantConsiegerie['totaloconsiegerie'];
                        }

                        $selectMontantDon = "SELECT SUM(montant) as totaldon FROM contributionfidele WHERE fidele_idfidele=$fidele->idfidele AND typecontribution='don' AND lisible=1;";
                        $resMontantDon=$db->query("$selectMontantDon");
                        while($idMontantDon=$resMontantDon->fetch(PDO::FETCH_ASSOC)){
                            $sommedon=$idMontantDon['totaldon'];
                        }

                        $total = $sommeoffrande+$sommetravaux+$sommeconsiegerie+$sommedon;
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
                                    class="afficher" title="Afficher le fidèle"><?php echo $fidele->nom.' '.$fidele->prenom ; ?></a></td>
                            <td>
                                <a href="traitementContributionParStatut.php?statut=<?php echo $fidele->statut; ?>" title="Afficher les contributions par statut <?php echo $fidele->statut; ?>" class="afficher"><?php echo $fidele->statut; ?></a>
                            </td>
                            <td><?php echo $sommeconsiegerie ; ?></td>
                            <td><?php echo $sommedon ; ?></td>
                            <td><?php echo $sommeoffrande; ?></td>
                            <td><?php echo $sommetravaux ; ?></td>
                            <td><?php echo $total ; ?></td>

                            <td>
                                <div class="btn-group">
                                    <a class="btn btn-primary afficher" href="afficherContributionFidele.php?idfidele=<?php echo $fidele->idfidele; ?>&idpersonne=<?php echo $fidele->personne_idpersonne; ?>" title="Voir" class="afficher"><i class="icon_plus_alt2"></i></a>
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
                        <td></td>
                        <td></td>
                        <td>Total</td>
                        <td><b><?php echo $totalconsiegerie; ?></b></td>
                        <td><b><?php echo $totaldon; ?></b></td>
                        <td><b><?php echo $totaloffrande; ?></b></td>
                        <td><b><?php echo $totaltravaux; ?></b></td>
                        <td><b><?php echo $totalTtout; ?></b></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="text-center">
                <ul class="pagination">
                    <li <?php if($pageCourante == 1){ echo "hidden";}?>><a class="item" href="contributionParCode.php?p=<?php if($pageCourante != 1) { echo $pageCourante - 1; }else{echo $pageCourante; } ?>">&laquo</a> </li>

                    <?php
                    for($i=1; $i<=$nbDePage; $i++){
                        if( $i == $pageCourante){
                            ?>
                            <li class="active"><a class="item" href="contributionParCode.php?p=<?php echo $i ?>"><?php echo $i ?></a></li>
                        <?php
                        }else{
                            ?>
                            <li><a class="item" href="contributionParCode.php?p=<?php echo $i ?>"><?php echo $i ?></a></li>
                        <?php
                        }
                    }
                    ?>

                    <li <?php if($pageCourante == $nbDePage){ echo "disabled";}?>><a class="item" href="contributionParCode.php?p=<?php if($pageCourante != $nbDePage) { echo $pageCourante + 1; }else{echo $pageCourante; } ?>">&raquo</a> <br/>
                        <label id="modifiertext" style="font-style:italic;"></label>
                    </li>
                </ul>
            </div>

            <div align="center">
                <a class="btn btn-success" href="report/imprimer.php?file=..." title="Imprimer la liste des ancien" target="_blank"><i class="fa fa-print"></i> Imprimer</a>
            </div>

        </div>
</div>

<script>
    $('#chargement').hide();

    $('.afficher').on('click', function(af){

        af.preventDefault();
        var $b = $(this);
        url = $b.attr('href');

        $('#main-content').load(url);
    });

    $('.item').on('click', function(i){

        i.preventDefault();
        $('#modifiertext').html('Chargement...');
        var $i = $(this);
        url = $i.attr('href');

        $('#main-content').load(url);

        $('#modifiertext').html('');
    });


    $('.updateMalade').on('click', function(e){

        e.preventDefault();
        var $link = $(this);
        target = $link.attr('href');

        $('#main-content').load(target);
    });


    $('#form-find').on('submit', function(e){

        e.preventDefault();

        var $a = $(this);
        var url = $a.attr('action');

        $.ajax(url, {

            success: function(){
                $('main-content').load(url);

            },

            error: function(){

                alert("Une erreur est survenue lors de la suppresion du malade");
            }
        });

    });

    $('.form_liste_fidele_zone').on('click', function(e){

        e.preventDefault();

        var z = $(this);
        target = z.attr('href');

        $('#main-content').load(target);


    });
    $('.ordre_rangement').on('click', function(e){

        e.preventDefault();

        var z = $(this);
        target = z.attr('href');

        $('#main-content').load(target);


    });

    $('.listeSexe').on('click', function(e){

        e.preventDefault();

        var z = $(this);
        target = z.attr('href');

        $('#main-content').load(target);

    });
	
	$('.loader').hide();


</script>

</section>
</div>
</div>
</section>
