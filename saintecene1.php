<?php
session_start();

if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login'];
    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');

    if(!has_Droit($idUser, "Consulter participation")){

        header('Location:index.php');

    }else{

        function isAjax(){
            return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
        }

        if(isAjax()){

            if(isset($_POST['occurrence'])){
                $occurrence=$_POST['occurrence'];
                $contribution=$_POST['contribution'];


                $ps=$db->prepare("SELECT *
                                    FROM personne, fidele, fidelesaintescene, saintescene, zone
                                    WHERE personne.idpersonne=fidele.personne_idpersonne
                                    AND fidele.idfidele=fidelesaintescene.fidele_idfidele
                                    AND fidelesaintescene.saintescene_idsaintescene=saintescene.idsaintescene
                                    AND saintescene.idsaintescene=fidelesaintescene.saintescene_idsaintescene
                                    AND personne.zone_idzone=zone.idzone
                                    AND personne.lisible=1
                                    AND fidele.lisible=1
                                    AND zone.lisible=1
                                    AND fidelesaintescene.lisible=1
                                    AND saintescene.lisible=1
                                    AND mois='$contribution' ORDER BY contribution DESC LIMIT 0, $occurrence");
                $ps->execute();

                $zone=$db->prepare("SELECT nomzone
                                    FROM zone
                                    WHERE lisible=1
                                    ORDER BY nomzone");
                $zone->execute();

                $statut=$db->prepare("SELECT DISTINCT statut
                                        FROM fidele
                                        ORDER BY statut");
                $statut->execute();

                $nombre_homme=0;
                $nombre_femme=0;
                $montant_homme=0;
                $montant_femme=0;
                $pourcentage_homme=0;
                $pourcentage_femme=0;
                $total=0;
                $montant_total=0;
                while($p=$ps->fetch(PDO::FETCH_OBJ)){
                    if($p->sexe == 'Masculin'){
                        $nombre_homme++;
                        $montant_homme=$montant_homme + $p->contribution;
                    }else{
                        $nombre_femme++;
                        $montant_femme=$montant_femme + $p->contribution;
                    }
                    $total++;
                    $montant_total=$montant_total + $p->contribution;
                }

                //$total=($total==0?1:$total);
                if($total!=0){
                    $pourcentage_homme=number_format($montant_homme*100/$montant_total, 2);
                    $pourcentage_femme=number_format($montant_femme*100/$montant_total, 2);
                }

            }




            ?>

            <br><div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2><i class="fa fa-flag-o red"></i><strong>Statistiques sur le sexe</strong></h2>
                        </div>
                        <div class="panel-body">
                            <table class="table bootstrap-datatable countries">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Nombre</th>
                                    <th>Pourcentage contributions cumulées</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Homme</td>
                                    <td><?php echo $nombre_homme; ?></td>
                                    <td>
                                        <div class="progress thin">
                                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo $pourcentage_homme; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $pourcentage_homme.'%'; ?>; color: blue;">
                                                <?php echo $pourcentage_homme.'%'; ?>
                                            </div>
                                        </div>
                                        <span class="sr-only"><?php echo $pourcentage_homme.'%'; ?></span>
                                    </td>

                                </tr>
                                <tr>
                                    <td>Femme</td>
                                    <td><?php echo $nombre_femme; ?></td>
                                    <td>
                                        <div class="progress thin">
                                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo $pourcentage_femme; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $pourcentage_femme.'%'; ?>; color: blue">
                                                <?php echo $pourcentage_femme.'%'; ?>
                                            </div>
                                        </div>
                                        <span class="sr-only"><?php echo $pourcentage_femme.'%'; ?></span>
                                    </td>

                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2><i class="fa fa-flag-o red"></i><strong>Statistiques sur les zones</strong></h2>
                        </div>
                        <div class="panel-body">
                            <table class="table bootstrap-datatable countries">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Nombre</th>
                                    <th>Pourcentage contributions cumulées</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php while($listezone=$zone->fetch(PDO::FETCH_OBJ)){
                                    $nombre=0;
                                    $montant=0;
                                    $pourcentage=0;
                                    $ps->execute();

                                    while($p1=$ps->fetch(PDO::FETCH_OBJ)){
                                        if($p1->nomzone == $listezone->nomzone){
                                            $nombre++;
                                            $montant=$montant + $p1->contribution;
                                        }
                                    }

                                    if($total!=0){
                                        $pourcentage=number_format($montant*100/$montant_total, 2);
                                    }
                                    ?>

                                    <tr>
                                        <td><?php echo $listezone->nomzone; ?></td>
                                        <td><?php echo $nombre; ?></td>
                                        <td>
                                            <div class="progress thin">
                                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo $pourcentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $pourcentage.'%'; ?>; color: blue">
                                                    <?php echo $pourcentage.'%'; ?>
                                                </div>
                                            </div>
                                            <span class="sr-only"><?php echo $pourcentage.'%'; ?></span>
                                        </td>

                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2><i class="fa fa-flag-o red"></i><strong>Statistiques sur les statuts</strong></h2>
                        </div>
                        <div class="panel-body">
                            <table class="table bootstrap-datatable countries">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Nombre</th>
                                    <th>Pourcentage contributions cumulées</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php while($listestatut=$statut->fetch(PDO::FETCH_OBJ)){
                                    $nombre1=0;
                                    $montant1=0;
                                    $pourcentage1=0;
                                    $ps->execute();

                                    while($p2=$ps->fetch(PDO::FETCH_OBJ)){
                                        if($p2->statut == $listestatut->statut){
                                            $nombre1++;
                                            $montant1=$montant1 + $p2->contribution;
                                        }
                                    }

                                    if($total!=0){
                                        $pourcentage1=number_format($montant1*100/$montant_total, 2);
                                    }
                                    ?>

                                    <tr>
                                        <td><?php echo $listestatut->statut; ?></td>
                                        <td><?php echo $nombre1; ?></td>
                                        <td>
                                            <div class="progress thin">
                                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo $pourcentage1; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $pourcentage1.'%'; ?>; color: blue">
                                                    <?php echo $pourcentage1.'%'; ?>
                                                </div>
                                            </div>
                                            <span class="sr-only"><?php echo $pourcentage1.'%'; ?></span>
                                        </td>

                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">

                        <header class="panel-heading">
                            <span style="font-size: 1.5em;"> Liste des <?php if($occurrence>$total){echo $total;}else{ echo $occurrence;}?> meilleurs contributions à la Sainte Cène de <?php echo $contribution.' '.date('Y'); ?> </span>
                        </header>

                        <div id="old_table" class="table-responsive">
                            <table class="table table-striped table-hover">
                                <tbody>
                                <tr>
                                    <th>#</th>
                                    <th><i class="icon_pin_alt"></i> Code</th>
                                    <th><i class="icon_profile"></i>Noms et prenoms</th>
                                    <th><i class="icon_profile"></i>Statut paroissial</th>
                                    <th><i class="icon_profile"></i>Sexe</th>
                                    <th><i class="icon_pin_alt"></i>Zone</th>
                                    <th><i class="icon_pin_alt"></i>Montant</th>
                                </tr>
                                <?php
                                $n=0;
                                $ps->execute();
                                while($liste=$ps->fetch(PDO::FETCH_OBJ)){
                                    $statut = str_replace(" ", "+", $liste->statut);
                                    ?>
                                    <tr>
                                        <td><?php echo ++$n; ?></td>
                                        <td><?php echo $liste->codefidele; ?></td>
                                        <td><?php echo $liste->nom.' '.$liste->prenom; ?></td>
                                        <td><?php  echo $liste->statut; ?></td>
                                        <td><?php  echo $liste->sexe; ?></td>
                                        <td><?php  echo $liste->nomzone; ?></td>
                                        <td><?php  echo $liste->contribution; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            <div>


                    </section>
                </div>
            </div>



            <script>

            </script>

        <?php
        }else{
            header('Location:index.php');


        }

    }
}
?>

