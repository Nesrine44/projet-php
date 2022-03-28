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


        $fidel = $db->prepare("SELECT count(idfidele) as nbre FROM fidele where lisible = 1");
        $fidel->execute();

        $malade = $db->prepare("SELECT count(idmalade) as nbre FROM malade, fidele where idfidele = fidele_idfidele AND malade.lisible = 1 AND fidele.lisible = 1 ");
        $malade->execute();   

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
        $selectNomZone ="SELECT nomzone FROM zone WHERE lisible=1;";
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

<body class="theme-red">
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
    

   
    <section class="content" id="main-content"  >
      <script type="text/javascript">$('.loader').hide();</script>
        <div class="container-fluid">
            <div class="block-header">
                <h2>DASHBOARD</h2>
            </div>

            <!-- Widgets -->
            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-pink hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">playlist_add_check</i>
                        </div>
                        <div class="content">
                            <div class="text">FIDELES</div>
                            <div class="number count-to" data-from="0" data-to="125" data-speed="15" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-cyan hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">help</i>
                        </div>
                        <div class="content">
                            <div class="text">MALADES</div>
                            <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-light-green hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">forum</i>
                        </div>
                        <div class="content">
                            <div class="text">GROUPES</div>
                            <div class="number count-to" data-from="0" data-to="243" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-orange hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">person_add</i>
                        </div>
                        <div class="content">
                            <div class="text">NEWSLETTER</div>
                            <div class="number count-to" data-from="0" data-to="1225" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Widgets -->
            <!-- CPU Usage script apres me,-->
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-6">
                                    <h2>CPU USAGE (%)</h2>
                                </div>
                                <div class="col-xs-12 col-sm-6 align-right">
                                    <div class="switch panel-switch-btn">
                                        <span class="m-r-10 font-12">REAL TIME</span>
                                        <label>OFF<input type="checkbox" id="realtime" checked><span class="lever switch-col-cyan"></span>ON</label>
                                    </div>
                                </div>
                            </div>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div id="real_time_chart" class="dashboard-flot-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# CPU Usage -->
            <div class="row clearfix">
                <!-- Visitors -->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="card">
                        <div class="body bg-pink">
                            <div class="sparkline" data-type="line" data-spot-Radius="4" data-highlight-Spot-Color="rgb(233, 30, 99)" data-highlight-Line-Color="#fff"
                                 data-min-Spot-Color="rgb(255,255,255)" data-max-Spot-Color="rgb(255,255,255)" data-spot-Color="rgb(255,255,255)"
                                 data-offset="90" data-width="100%" data-height="92px" data-line-Width="2" data-line-Color="rgba(255,255,255,0.7)"
                                 data-fill-Color="rgba(0, 188, 212, 0)">
                                12,10,9,6,5,6,10,5,7,5,12,13,7,12,11
                            </div>
                            <ul class="dashboard-stat-list">
                                <li>
                                    TODAY
                                    <span class="pull-right"><b>1 200</b> <small>USERS</small></span>
                                </li>
                                <li>
                                    YESTERDAY
                                    <span class="pull-right"><b>3 872</b> <small>USERS</small></span>
                                </li>
                                <li>
                                    LAST WEEK
                                    <span class="pull-right"><b>26 582</b> <small>USERS</small></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- #END# Visitors -->
                <!-- Latest Social Trends -->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="card">
                        <div class="body bg-cyan">
                            <div class="m-b--35 font-bold">LATEST SOCIAL TRENDS</div>
                            <ul class="dashboard-stat-list">
                                <li>
                                    #socialtrends
                                    <span class="pull-right">
                                        <i class="material-icons">trending_up</i>
                                    </span>
                                </li>
                                <li>
                                    #materialdesign
                                    <span class="pull-right">
                                        <i class="material-icons">trending_up</i>
                                    </span>
                                </li>
                                <li>#adminbsb</li>
                                <li>#freeadmintemplate</li>
                                <li>#bootstraptemplate</li>
                                <li>
                                    #freehtmltemplate
                                    <span class="pull-right">
                                        <i class="material-icons">trending_up</i>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- #END# Latest Social Trends -->
                <!-- Answered Tickets -->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="card">
                        <div class="body bg-teal">
                            <div class="font-bold m-b--35">ANSWERED TICKETS</div>
                            <ul class="dashboard-stat-list">
                                <li>
                                    TODAY
                                    <span class="pull-right"><b>12</b> <small>TICKETS</small></span>
                                </li>
                                <li>
                                    YESTERDAY
                                    <span class="pull-right"><b>15</b> <small>TICKETS</small></span>
                                </li>
                                <li>
                                    LAST WEEK
                                    <span class="pull-right"><b>90</b> <small>TICKETS</small></span>
                                </li>
                                <li>
                                    LAST MONTH
                                    <span class="pull-right"><b>342</b> <small>TICKETS</small></span>
                                </li>
                                <li>
                                    LAST YEAR
                                    <span class="pull-right"><b>4 225</b> <small>TICKETS</small></span>
                                </li>
                                <li>
                                    ALL
                                    <span class="pull-right"><b>8 752</b> <small>TICKETS</small></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- #END# Answered Tickets -->
            </div>

            <div class="row clearfix">
                <!-- Task Info -->
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <div class="card">
                        <div class="header">
                            <h2>TASK INFOS</h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover dashboard-task-infos">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Task</th>
                                            <th>Status</th>
                                            <th>Manager</th>
                                            <th>Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Task A</td>
                                            <td><span class="label bg-green">Doing</span></td>
                                            <td>John Doe</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-green" role="progressbar" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100" style="width: 62%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Task B</td>
                                            <td><span class="label bg-blue">To Do</span></td>
                                            <td>John Doe</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-blue" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Task C</td>
                                            <td><span class="label bg-light-blue">On Hold</span></td>
                                            <td>John Doe</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-light-blue" role="progressbar" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100" style="width: 72%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Task D</td>
                                            <td><span class="label bg-orange">Wait Approvel</span></td>
                                            <td>John Doe</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-orange" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" style="width: 95%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Task E</td>
                                            <td>
                                                <span class="label bg-red">Suspended</span>
                                            </td>
                                            <td>John Doe</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-red" role="progressbar" aria-valuenow="87" aria-valuemin="0" aria-valuemax="100" style="width: 87%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- #END# Task Info -->
                <!-- Browser Usage -->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="card">
                        <div class="header">
                            <h2>BROWSER USAGE</h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div id="donut_chart" class="dashboard-donut-chart"></div>
                        </div>
                    </div>
                </div>
                <!-- #END# Browser Usage -->
            </div>
        </div>
   
    </section>
                     <!-- #Menu -->
            <!-- Footer -->
            <div class="legal fixed-bottom col-lg-12 text-center bg-white fluid" style="hight:200px">
                <div class="copyright">
                    &copy; 2016 - 2017 <a href="javascript:void(0);">AdminBSB - Material Design</a>.
                </div>
                <div class="version">
                    <b>Version: </b> 1.0.5
                </div>
            </div>
       
   
    
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

    $('.btn-primary').on('click', function(e){
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
