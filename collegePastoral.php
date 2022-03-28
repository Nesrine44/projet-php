<?php
    session_start();

    if(isset($_SESSION['login'])){

        $annee = $_SESSION['annee'];
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Afficher le college")){

            header('Location:index.php');

        }else{

            $selectAllPasteur = $db->prepare("SELECT nom, prenom, datenaiss, lieunaiss, sexe, email, profession, idpersonne, nomzone, telephone, photo, nomgrade
                                                FROM personne, pasteur, zone, grade
                                                WHERE pasteur.personne_idpersonne = personne.idpersonne
                                                AND pasteur.grade = grade.idgrade
                                                AND zone.idzone = personne.zone_idzone
                                                AND pasteur.lisible = 1
                                                AND grade.lisible = 1
                                                AND zone.lisible = 1
                                                AND personne.lisible = 1
                                                ORDER BY grade.nomgrade ASC");
            $selectAllPasteur->execute();
        }


}else{
    header('Location:login.php');
}
?>


    <section class="wrapper">
       
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="material-icons">home</i><a href="index.php" class="col-blue" > Accueil</a></li>
                    <li><i class="material-icons">school</i> Collège Pastoral</li>
                    <li><i class="material-icons">people</i><a id="listeP" href="listePasteurs.php" class="col-blue"> Liste Pasteurs</a></li>
                     <li style="float: right;">
                        
                            <a class="col-blue"href="report/imprimer.php?file=liste_pasteurs" title="Imprimer la liste des fidèles" target="_blank"><i class="fa material-icons">print</i> Imprimer</a>
                    </li>
                </ol>
            </div>
            </div>
      <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="">

                        <div class="header text-center h4 card" style="line-height: 50px;">
                              
                                Collège pastoral 
                                
                             
                                
                        </div>

        <header class="panel-heading">
           <strong align='center'></strong>
            
            <!--<a class="btn btn-warning " href="#" title="Exporter la liste des pasteurs" target="_blank"><i class="icon_cloud-upload_alt"></i> Exporter</a>-->
        </header>
        <br>


        <?php

       while($listePasteur = $selectAllPasteur->fetch(PDO::FETCH_OBJ)){

        ?>  
             <div class="col-lg-12">
                <div class="profile-widget-info">
                    <div class="panel-body card  bg-blue-grey" >
                       
                            <div class="col-lg-2">
                                
                                    <img src="images/<?php echo $listePasteur->photo; ?>" width="150" height="150" alt="">
                               
                            </div>
                  
                        <div class="profile-widget panel-body">
           
                            
                            <div class="profile-widget">
                                <div class="col-lg-6 col-sm-4 follow-info">
                                    <p><h4>Noms et prénoms:<strong> <?php echo $listePasteur->nom.' '.$listePasteur->prenom; ?></strong></h4></p>
                                    <p><h4>Date de naissance: <strong><?php echo $listePasteur->datenaiss; ?></strong></h4></p>
                                    <p><h4>Lieu de naissance: <strong><?php echo $listePasteur->lieunaiss; ?></strong></h4></p>
                                    <p><h4>Sexe: <strong> <?php echo $listePasteur->sexe ; ?></strong></h4></p>
                                </div>

                                <div class="col-lg-4 col-sm-3 follow-info">
                                    <p><h4>Téléphone: <strong><?php echo $listePasteur->telephone; ?></strong></h4></p>
                                    <p><h4>Email: <strong><?php echo $listePasteur->email; ?></strong></h4></p>
                                    <p><h4>Zone d'habitation: <strong><?php echo $listePasteur->nomzone; ?></strong></h4></p>
                                    <p><h4>Grade: <strong><?php echo $listePasteur->nomgrade; ?></strong></h4></p>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="col-lg-12">

                            <ul class="nav nav-tabs  col-sm-12 ">
                                <center>
                                
                                    <button class="btn bg-blue waves-effect listeP afficher" href="afficherPasteur.php?idpersonne=<?php echo $listePasteur->idpersonne; ?>" title="Information détaillées du pasteur" <?php if(!has_Droit($idUser, "afficher le college")){echo 'disabled';}else{echo "";} ?>><i class="material-icons">loupe</i> Détails</button>
                                
                                    <button class="btn bg-green waves-effect listeP afficher" href="modifierPasteur.php?idpersonne=<?php echo $listePasteur->idpersonne; ?>" title="Modifier" <?php if(!has_Droit($idUser, "Modifier un pasteur")|| (date('Y') != $annee)){echo 'disabled';}else{echo "";} ?>><i class="material-icons">border_color</i> Modifier</button>
                                
                                 <!-- <a class="bg-blue" href="report/imprimer_param.php?file=profil_pasteur&param=<?php echo $listePasteur->idpersonne; ?>" title="Imprimer la fiche du pasteur" target="_blank"><i class="material-icons">print</i> Imprimer</a> --> 
                                </center>
                            </ul>
                        </div>

                    </div>
                </div>


            </div>


       <?php
          }
        ?>
           
    </section>

    <script type="text/javascript">

        $('#chargement').hide();

         $('#listeP').on('click', function(e){

                                e.preventDefault();

                                var z = $(this);
                                target = z.attr('href');

                                $('#main-content').load(target);                                
                            
                        });

        $('.afficher').on('click', function(af){

            $('.loader').show();

            af.preventDefault();
            var $b = $(this);
            url = $b.attr('href');

            $('#main-content').load(url);

            $('.loader').hide();
        });

        $('.loader').hide();

    </script>

