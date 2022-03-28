

<?php 
    session_start();

    if(isset($_SESSION['login'])){      

        $idLogin = $_SESSION['login'];
        require_once('layout.php'); 
        require_once('includes/function_role.php');


        $nbreMensuel = 0;
        $nbreExtraordinaire=0;
        $nbreElargi = 0;
        $nbreConsistoire=0;
        $nbreCinode=0;
        $decembre = 0;


            $selectNombreFidele = $db->prepare("SELECT COUNT(idfidele) AS nbretotalfidele FROM fidele WHERE lisible=1 AND est_decede=0");
                $selectNombreFidele->execute();

             
            while($idselectNombreFidele=$selectNombreFidele->fetch(PDO::FETCH_OBJ)){
                 $tota =  $idselectNombreFidele->nbretotalfidele;
            }


        $selectNombreFidele = $db->prepare("SELECT COUNT(idmalade) AS nbretotalfidele FROM malade WHERE lisible=1 AND est_retabli = 0");
            $selectNombreFidele->execute(); 
            
            while($idselectNombreFidele=$selectNombreFidele->fetch(PDO::FETCH_OBJ)){
                $total = $idselectNombreFidele->nbretotalfidele;
            }   

        $groupe = $db->prepare("SELECT COUNT(idgroupe) as nbre FROM groupe where lisible = 1");
        $groupe->execute();
        
        $pasteur = $db->prepare("SELECT COUNT(idpasteur) as nbre FROM pasteur where lisible = 1");
        $pasteur->execute();


        
        $nbreConseilMensuel = "SELECT mensuel FROM occurrence WHERE idoccurrence=1;";
        $resconseil=$db->query("$nbreConseilMensuel");
        while($idconseil=$resconseil->fetch(PDO::FETCH_ASSOC)){
            $nbreMensuel=$idconseil['mensuel'];
        }

        $nbreConseilExtraordinaire = "SELECT extraordinaire FROM occurrence WHERE idoccurrence=1;";
        $resextraordinaire=$db->query("$nbreConseilExtraordinaire");
        while($idextraordinaire=$resextraordinaire->fetch(PDO::FETCH_ASSOC)){
            $nbreExtraordinaire=$idextraordinaire['extraordinaire'];
        }

        $nbreConseilElargi = "SELECT elargi FROM occurrence WHERE idoccurrence=1;";
        $reselargi=$db->query("$nbreConseilElargi");
        while($idelargi=$reselargi->fetch(PDO::FETCH_ASSOC)){
            $nbreElargi=$idelargi['elargi'];
        }

        $nbreConseilConsistoire = "SELECT consistoire FROM occurrence WHERE idoccurrence=1;";
        $resconsistoire=$db->query("$nbreConseilConsistoire");
        while($idconsistoire=$resconsistoire->fetch(PDO::FETCH_ASSOC)){
            $nbreConsistoire=$idconsistoire['consistoire'];
        }

        $nbreConseilCinode = "SELECT cinode FROM occurrence WHERE idoccurrence=1;";
        $rescinode=$db->query("$nbreConseilCinode");
        while($idcinode=$rescinode->fetch(PDO::FETCH_ASSOC)){
            $nbreCinode=$idcinode['cinode'];
        }

        $breAncien = "SELECT  COUNT(idfidele) AS nombreanciens FROM fidele as fil WHERE statut= 'ancien' AND fil.lisible=1";
        $resnombreAnciens=$db->query("$breAncien");
        while($idnombreAnciens=$resnombreAnciens->fetch(PDO::FETCH_ASSOC)){
            $nbreAnciens=$idnombreAnciens['nombreanciens'];
        }

        $selectAnciens = "SELECT idfidele FROM fidele fil WHERE statut= 'ancien' AND fil.lisible=1";
        $resanciens=$db->query("$selectAnciens");

        $tauxMensuel=0;
        $tauxEtraordinaire=0;
        $tauxElargi=0;
        $tauxConsistoire=0;
        $tauxCinode=0;

        while($idAnciens=$resanciens->fetch(PDO::FETCH_ASSOC)){
            $identifiantancien=$idAnciens['idfidele'];

            $nbrePresenceMensuel = "SELECT COUNT(fidele_idfidele)as nombremensuel FROM  fideleconseil, fidele WHERE idfidele = fidele_idfidele AND type = 'conseil mensuel' AND fideleconseil.lisible=1 AND fidele_idfidele=$identifiantancien AND fidele.lisible = 1";
            $resnbrePresenceMensuel=$db->query("$nbrePresenceMensuel");
            while($idnbrePresenceMensuel=$resnbrePresenceMensuel->fetch(PDO::FETCH_ASSOC)){
                $mensuel1=$idnbrePresenceMensuel['nombremensuel'];
            }

            $nbrePresenceExtraordinaire = "SELECT COUNT(fidele_idfidele)as nombreextra FROM  fideleconseil, fidele WHERE idfidele = fidele_idfidele AND type = 'conseil extraordinaire' AND fideleconseil.lisible=1 AND fidele_idfidele=$identifiantancien AND fidele.lisible = 1";
            $resnbrePresenceExtraordianire=$db->query("$nbrePresenceExtraordinaire");
            while($idnbrePresenceExtraordinaire=$resnbrePresenceExtraordianire->fetch(PDO::FETCH_ASSOC)){
                $extraordinaire1=$idnbrePresenceExtraordinaire['nombreextra'];
            }

            $nbrePresenceElargi = "SELECT COUNT(fidele_idfidele)as nombreelargi FROM  fideleconseil , fidele WHERE idfidele = fidele_idfidele AND type = 'conseil elargi' AND fideleconseil.lisible=1 AND fidele_idfidele=$identifiantancien AND fidele.lisible = 1";
            $resnbrePresenceElargi=$db->query("$nbrePresenceElargi");
            while($idnbrePresenceElargi=$resnbrePresenceElargi->fetch(PDO::FETCH_ASSOC)){
                $elargi1=$idnbrePresenceElargi['nombreelargi'];
            }

            $nbrePresenceConsistoire = "SELECT COUNT(fidele_idfidele)as nombreconsis FROM  fideleconseil , fidele WHERE idfidele = fidele_idfidele AND type = 'consistoire' AND fideleconseil.lisible=1 AND fidele_idfidele=$identifiantancien AND fidele.lisible = 1";
            $resnbrePresenceConsistoire=$db->query("$nbrePresenceConsistoire");
            while($idnbrePresenceConsistoire=$resnbrePresenceConsistoire->fetch(PDO::FETCH_ASSOC)){
                $consistoire1=$idnbrePresenceConsistoire['nombreconsis'];
            }

            $nbrePresenceCinode = "SELECT COUNT(fidele_idfidele)as nombreconode FROM  fideleconseil , fidele WHERE idfidele = fidele_idfidele AND type = 'cinode regional' AND fideleconseil.lisible=1 AND fidele_idfidele=$identifiantancien AND fidele.lisible = 1";
            $resnbrePresenceCinode=$db->query("$nbrePresenceCinode");
            while($idnbrePresenceCinode=$resnbrePresenceCinode->fetch(PDO::FETCH_ASSOC)){
                $cinode1=$idnbrePresenceCinode['nombreconode'];
            }

            if($nbreMensuel==0){
                $tauxMensuel=0;
            }else{
                $tauxMensuel = $tauxMensuel + $mensuel1 * 100/$nbreMensuel;
            }

            if($nbreExtraordinaire==0){
                $tauxEtraordinaire=0;
            }else{
                $tauxEtraordinaire = $tauxEtraordinaire + $extraordinaire1 * 100/$nbreExtraordinaire;
            }

            if($nbreElargi==0){
                $tauxElargi=0;
            }else{
                $tauxElargi = $tauxElargi + $elargi1 * 100/$nbreElargi;
            }

            if($nbreConsistoire==0){
                $tauxConsistoire=0;
            }else{
                $tauxConsistoire = $tauxConsistoire + $consistoire1 * 100/$nbreConsistoire;
            }

            if($nbreCinode==0){
                $tauxCinode=0;
            }else{
                $tauxCinode = $tauxCinode + $cinode1 * 100/$nbreCinode;
            }

        }
        $tauxMensuel = ($nbreAnciens == 0 ? 0 : number_format($tauxMensuel/$nbreAnciens, 2));
        $tauxEtraordinaire = ($nbreAnciens == 0 ? 0 : number_format($tauxEtraordinaire/$nbreAnciens, 2));
        $tauxElargi = ($nbreAnciens == 0 ? 0 : number_format($tauxElargi/$nbreAnciens, 2));
        $tauxConsistoire = ($nbreAnciens == 0 ? 0 : number_format($tauxConsistoire/$nbreAnciens, 2));
        $tauxCinode = ($nbreAnciens == 0 ? 0 : number_format($tauxCinode/$nbreAnciens, 2));

        //gestion Sainte Scene

        $ps_Sainte_Scene=$db->prepare("SELECT * FROM saintescene WHERE lisible=1");
        $ps_Sainte_Scene->execute();

        $saintescess='';
        $montantsaintesceness=0;

        while($saintesce=$ps_Sainte_Scene->fetch(PDO::FETCH_OBJ)){
            $noms=$saintesce->mois;
            $saintescess=$saintescess.'"'.$noms.'"'.', ';

            $ps_montant_sainte_scene=$db->prepare("SELECT SUM(montant) AS montantsaintescene
                                                    FROM contributionfidele
                                                    WHERE   saintescene_idsaintescene=$saintesce->idsaintescene
                                                    AND lisible=1");
            $ps_montant_sainte_scene->execute();

            $montantsaintesce=$ps_montant_sainte_scene->fetch(PDO::FETCH_OBJ);
            if(!isset($montantsaintesce->montantsaintescene)){
                $valeur_montants=0;
            }else{
                $valeur_montants=$montantsaintesce->montantsaintescene;
            }
            $montantsaintesceness=$montantsaintesceness.$valeur_montants.", ";
        }

        $zones="";
        $nombreFideleParzone=0;
        $selectNomZone ="SELECT nomzone FROM zone WHERE lisible=1";
        $resselectNomZone=$db->query("$selectNomZone");
       

        while($idselectNomZone=$resselectNomZone->fetch(PDO::FETCH_ASSOC)){
            $nomZone=$idselectNomZone['nomzone'];
            $zones=$zones.$nomZone.',';
            //$zones=$zones.'"'.$nomZone.'"'.', ';

            $nombreFi = "SELECT COUNT(idpersonne) AS nombre FROM personne, zone, fidele  WHERE idzone=zone_idzone AND personne_idpersonne=idpersonne AND fidele.lisible=1 AND personne.lisible=1 AND zone.lisible = 1 AND zone.idzone = personne.zone_idzone AND zone.nomzone ='$nomZone'";
            $resnombreFi=$db->query("$nombreFi");
            while($idnombreFi=$resnombreFi->fetch(PDO::FETCH_ASSOC)){
                $nombrefid=$idnombreFi['nombre'];
                $nombreFideleParzone=$nombreFideleParzone.$nombrefid.",";
            }
        }


    }else{

        
        header('Location:login.php'); 
    }
?>

<body class="theme-brown" >
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
   
    <!-- #Top Bar -->
    

    <section class="content section scrollspy" id="main-content"  >
      <script type="text/javascript">$('.loader').hide();</script>
            <div class="block-header">
                <h2>DASHBOARD</h2> 
            </div>
         

            <!-- Widgets -->
        <div class="row clearfix bi">
            <a <?php if(has_Droit($idUser, "lister fidele")){echo 'href="listeFideles.php"';}else{echo "disabled";} ?>>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-teal hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">people</i>
                        </div>
                        <div class="content">
                            <div class="text">FIDELES</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $tota; ?>" data-speed="15" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
              </a>
              <a <?php if(has_Droit($idUser, "Lister malade")){echo 'href="listeMalades.php"';}else{echo "disabled";} ?>>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 bi">
                    <div class="info-box bg-cyan hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">local_hospital</i>
                        </div>
                        <div class="content">
                            <div class="text">MALADES</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $total; ?>" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
             </a>
             <a <?php if(has_Droit($idUser, "Lister groupe")){echo 'href="listeGroupe.php"';}else{echo "disabled";} ?>>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 bi">
                    <div class="info-box bg-green hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">group_work</i>
                        </div>
                        <div class="content">
                            <div class="text">GROUPES</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $groupe->fetch(PDO::FETCH_OBJ)->nbre; ?>" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
             </a>
             <a <?php if(has_Droit($idUser, "Envoyer newsletter")){echo 'href="newsLetter.php"';}else{echo "disabled";} ?>>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-brown hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">message</i>
                        </div>
                        <div class="content">
                            <div class="text">NEWSLETTER</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo date('Y'); ?>" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
             </a>
            </div>
            <!-- #END# Widgets -->
            <!-- CPU Usage script apres me,-->
            <div class="row clearfix">
                <div >
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="header">
                                <div class="row clearfix">
                                    
                                        <?php
                                            $zonechart = explode(',', $zones);
                                            $nbfidele = explode(',', $nombreFideleParzone);
                                            $dataFideleParZone = array();

                                            for($i = 0; $i < sizeof($nbfidele); $i++) {
                                                array_push($dataFideleParZone, array("y" => (float)($nbfidele[$i].'000'), "label" => implode(explode(',', $zonechart[$i]))));
                                            }
                                        ?>
                                        <script>
                                            window.onload = function() {
                                                var chart = new CanvasJS.Chart("chartContainerFideleParZone", {
                                                    animationEnabled: true,
                                                    exportEnabled: true,
                                                    theme: "light1",
                                                    title:{
                                                        text: "Nombre fidèles par zone"
                                                    },
                                                    data: [{
                                                        type: "column",
                                                        //indexLabel: "{y}", //Shows y value on all Data Points
                                                        indexLabelFontColor: "#5A5757",
                                                        indexLabelPlacement: "outside",   
                                                        yValueFormatString: "#,##0.## fidèles",
                                                        dataPoints: <?php echo json_encode($dataFideleParZone, JSON_NUMERIC_CHECK); ?>
                                                    }]
                                                });
                                                chart.render();
                                            }
                                        </script>
                                     <div id="chartContainerFideleParZone" style="height: 450px; width: 100%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Ici se trouve le taux de contribution de fideles par zone -->

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 card" style="overflow-x: auto;">
                        <div class="">
                            <div class="header">
                                <div class="row clearfix">
                                    <div class="col-xs-12 col-sm- col-lg-12">
                                            <h4 style="text-align:center;">Contributions cumulées mensuelles à la Sainte Cène</h4>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                         <canvas id="canvas3" height="403" width="770" style="overflow-x: auto;"></canvas>
                                    </div>
                                    
                                    <script>
                                        var barChartData = {
                                            labels : [ <?php echo $saintescess; ?>],
                                            datasets : [
                                                {
                                                    fillColor : "rgba(151,187,205,0.5)",
                                                    strokeColor : "rgba(151,187,205,1)",
                                                    data : [<?php echo $montantsaintesceness; ?>]
                                                }
                                            ]
                                        }
                                        var myLine = new Chart(document.getElementById("canvas3").getContext("2d")).Bar(barChartData);
                                     </script>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card" style="visibility: hidden; height:">
                        <div class="body" >
                            <div id="real_time_chart" class="dashboard-flot-chart"></div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- #END# CPU Usage -->
            <div class="row clearfix">
                <!-- Visitors -->
                 <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="header">
                                <div class="row clearfix">
                                    <div class="col-xs-12 col-sm- col-lg-12">
                                            <h4 style="text-align:center;">Activités</h4>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                         <div class="panel-body">
                                            <div id="calendar" ></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- #END# Visitors -->
                <!-- Latest Social Trends -->
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="card">
                     
                        <table class="table bootstrap-datatable countries">
                          
                                <thead class="col-white bg-cyan">
                                    <tr>
                                        <th>Conseil</th>
                                        <th>Pourcentage d'assiduité</th>
                                        <th><i class="icon_cogs"></i> Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="col-black">Mensuel</td>
                                        <td>
                                            <div class="progress thin">
                                                <div class="progress-bar progress-bar-warning <?php if($tauxMensuel<=0.00){echo 'col-black';} ?>" role="progressbar" aria-valuenow="<?php echo $tauxMensuel; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $tauxMensuel.'%'; ?>">
                                                    <?php echo $tauxMensuel.'%'; ?>
                                                </div>
                                            </div>
                                            <span class="sr-only"><?php echo $tauxMensuel.'%'; ?></span>
                                        </td>
                                        <td>
                                            <div class="">
                                                <a class="col-blue"  title="Voir"  <?php if(has_Droit($idUser, "Assiduite a un conseil")){echo 'href="affichePourcentageMensuel.php"';}else{echo "";} ?>><i class="material-icons">loupe</i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-black">Extraordianaire</td>
                                        <td>
                                            <div class="progress thin">
                                                <div class="progress-bar progress-bar-warning <?php if($tauxEtraordinaire<=0.00){echo 'col-black';} ?>" role="progressbar" aria-valuenow="<?php echo $tauxEtraordinaire; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $tauxEtraordinaire.'%'; ?>">
                                                    <?php echo $tauxEtraordinaire.'%'; ?>
                                                </div>
                                            </div>
                                            <span class="sr-only"><?php echo $tauxEtraordinaire.'%'; ?></span>
                                        </td>
                                        <td>
                                            <div class="">
                                                <a class="col-blue"  title="Voir" <?php if(has_Droit($idUser, "Assiduite a un conseil")){echo 'href="affichePourcentageExtraordinaire.php"';}else{echo "";} ?>><i class="material-icons">loupe</i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-black">Elargi</td>
                                        <td>
                                            <div class="progress thin">
                                                <div class="progress-bar progress-bar-warning <?php if($tauxElargi<=0.00){echo 'col-black';} ?>" role="progressbar" aria-valuenow="<?php echo $tauxElargi; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $tauxElargi.'%'; ?>">
                                                    <?php echo $tauxElargi.'%'; ?>
                                                </div>
                                            </div>
                                            <span class="sr-only"><?php echo $tauxElargi.'%'; ?></span>
                                        </td>
                                        <td>
                                            <div class="">
                                                <a class="col-blue"  title="Voir" <?php if(has_Droit($idUser, "Assiduite a un conseil")){echo 'href="affichePourcentageElargi.php"';}else{echo "";} ?>><i class="material-icons">loupe</i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-black">Consistoire</td>
                                        <td>
                                            <div class="progress thin">
                                                <div class="progress-bar progress-bar-warning <?php if($tauxConsistoire<=0.00){echo 'col-black';} ?>" role="progressbar" aria-valuenow="<?php echo $tauxConsistoire; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $tauxConsistoire.'%'; ?>">
                                                    <?php echo $tauxConsistoire.'%'; ?>
                                                </div>
                                            </div>
                                            <span class="sr-only"><?php echo $tauxConsistoire.'%'; ?></span>
                                        </td>
                                        <td>
                                            <div class="">
                                                <a class="col-blue"  title="Voir" <?php if(has_Droit($idUser, "Assiduite a un conseil")){echo 'href="affichePourcentageConsistoire.php"';}else{echo "";} ?>><i class="material-icons">loupe</i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-black">Cinode regionale</td>
                                        <td>
                                            <div class="progress thin">
                                                <div class="progress-bar progress-bar-warning <?php if($tauxCinode<=0.00){echo 'col-black';} ?>" role="progressbar" aria-valuenow="<?php echo $tauxCinode; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $tauxCinode.'%'; ?>">
                                                    <?php echo $tauxCinode.'%'; ?>
                                                </div>
                                            </div>
                                            <span class="sr-only"><?php echo $tauxCinode.'%'; ?></span>
                                        </td>
                                        <td>
                                            <div class="">
                                                <a class="col-blue"  title="Voir" <?php if(has_Droit($idUser, "Assiduite a un conseil")){echo 'href="affichePourcentageCinode.php"';}else{echo "";} ?>><i class="material-icons">loupe</i></a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                    </div> 
                    <div class="card" style="visibility: hidden; width: 0px; height: 0px;">
                        <div class="body" >
                             <div id="donut_chart" class="dashboard-donut-chart"></div>
                        </div>
                    </div>
                </div>       
       
    </section>
         <!-- Footer -->
        <footer class="text-center themes_church_sidbar" style="height:50px; position:absolute; bottom: 0px; position: fixed; left: 0px; right: 0px; background-color: rgba(13,13,13,1);" >
             <div class="col-lg-3 "></div>
             <div class="col-lg-9">
                <div class="copyright col-white" align="center">
                    &copy; 2016 - 2017 <a href="javascript:void(0);" class="col-grey"> Copyright All rigths reserved, powered by KTC-CENTER</a>.
                </div>
                <div class="version col-white" align="center">
                    <b>Version: </b> 1.0.5
                </div>
            </div>
        </footer>
                     <!-- #Menu -->
            <!-- #Footer -->

    <!-- Jquery Core Js -->
    <!-- <script src="plugins/jquery/jquery.min.js"></script> -->

    <!-- Bootstrap Core Js -->
    <!-- <script src="plugins/bootstrap/js/bootstrap.js"></script> -->

    <!-- Select Plugin Js -->
    <!-- <script src="plugins/bootstrap-select/js/bootstrap-select.js"></script> -->


    <!-- <script src="plugins/flot-charts/jquery.flot.resize.js"></script> -->
    <!-- <script src="plugins/flot-charts/jquery.flot.pie.js"></script> -->
    <!-- <script src="plugins/flot-charts/jquery.flot.categories.js"></script> -->
    <!-- <script src="plugins/flot-charts/jquery.flot.time.js"></script> -->

    <!-- Sparkline Chart Plugin Js -->
    <!-- <script src="plugins/jquery-sparkline/jquery.sparkline.js"></script> -->

    <!-- Custom Js -->
   

<script type="text/javascript">
    $('.sub a').on('click', function(e){
        $('.loader').show();
        e.preventDefault();
        //$('#chargement').slideToggle();
        var $cible = $(this);
        target = $cible.attr('href');
        $('#main-content').load(target);

    });

    $('.bi a').on('click', function(e){
        $('.loader').show();
        e.preventDefault();
        var $cible = $(this);
        target = $cible.attr('href');
        $('#main-content').load(target);
    });

    $('.col-blue').on('click', function(e){
        //$('.loader').show();
        e.preventDefault();
        var $cible = $(this);
        target = $cible.attr('href');
        $('#main-content').load(target);
    });
</script>
    
<style type="text/css">
    .loader {
        position : fixed;
        z-index: 9999;
        background : url('img/icon-loader.gif') 50% 50% no-repeat;
        top : 0px;
        left : 0px;
        height : 100%;
        width : 100%;
        cursor: wait;
    }
    .wrapper{
        overflow: hidden;
        margin-bottom: 1.5%;
    }
    .panel-headings{
        background-color: #acdef6; 
        color: #106894; 
        border-color: #67c2ef;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {  
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                    center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events:'evenement.php',
            eventClick: function(calEvent, jsEvent, view) {
                $(this).css('border-color', 'red');
                $('#main-content').load('afficherActivite.php?id='+calEvent.id);
            },
            loading: function(json){}
        });
    });
</script>
</body>

</html>