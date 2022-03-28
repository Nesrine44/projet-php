<?php
session_start();

if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login']; 
        $annee = $_SESSION['annee'];
    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');

    if(!has_Droit($idUser, "Afficher fidele")){

        header('Location:index.php');

    }else{

        $personne = null;
        //$bap=null;
        //$conf=null;
        //$mal=null;
        if(!isset($_GET['idpersonne']) || empty($_GET['idpersonne'])){

            header('Location:index.php');

        }else{
            $idpersonne1=$_GET['idpersonne'];

            $selectInfo = $db->prepare("SELECT nom, prenom, datenaiss, nombre_enfant, idpersonne, pere_vivant, mere_vivant, village, pere, mere, lieunaiss, idpasteur, email, statut_pro, situation_matri, etablissement, domaine, diplome, employeur, niveau, serie, profession, sexe, photo, nomzone, idzone, telephone, conjoint, religion_conjoint, grade, personne.arrondissement as idarron, iddepartement, idregion, arrondissement.arrondissement as nom_arro, departement.departement as nom_dept, region.region AS nom_region
                                            FROM personne, zone, pasteur, arrondissement, departement, region
                                            WHERE idpersonne = $idpersonne1
                                            AND zone.idzone = personne.zone_idzone
                                            AND personne.idpersonne = pasteur.personne_idpersonne
                                            AND departement_iddepartement = iddepartement
                                            AND region_idregion = idregion
                                            AND idarrondissement = personne.arrondissement
                                            AND arrondissement.lisible = 1
                                            AND departement.lisible = 1
                                            AND region.lisible = 1");
            $selectInfo->execute();

            while($s = $selectInfo->fetch(PDO::FETCH_OBJ)){

                $personne = $s;
            }

            $ps=$db->prepare("SELECT nomgrade
                              FROM grade
                              WHERE idgrade=$personne->grade");
            $ps->execute();
            while($grades = $ps->fetch(PDO::FETCH_OBJ)){

                $grade = $grades;
            }


        }
    }

}else{
    header('Location:login.php');
}
?>

<section class="wrapper">


     <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb col-blue">
                    <li><i class="material-icons">home</i><a href="index.php" class="col-blue">Accueil</a></li>
                    <li><i class="material-icons">people</i>   <a href="#" class="col-blue"> Fidèles</li></a>
                    <li><i class="material-icons">list</i><a class="col-blue listeF" href="listePasteurs.php">Liste Pasteurs</a></li>
                    <li><i class="material-icons">person_pin</i>Profile</li>
                </ol>
            </div>
        </div>


     <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                

                    <div class="header text-center h4 card " style="line-height: 50px;">
                          
                            Informations personnelles
                         
                            
                    </div>




   
        <div class="profile-widget-info">
            <div class="panel-body bg-blue-grey" >
                <div class="col-lg-2 col-sm-2">
                    <div class="">
                        <img src="images/<?php echo $personne->photo; ?>" width="150" height="150" alt="">
                    </div>
                </div>
                <div class="profile-widget">
                    <div class="col-lg-6 col-sm-4 follow-info">
                        <p><h4>Noms et prénoms:<strong> <?php echo $personne->nom.' '.$personne->prenom; ?></strong></h4></p>
                        <p><h4>Date de naissance: <strong><?php echo $personne->datenaiss; ?></strong></h4></p>
                        <p><h4>Lieu de naissance: <strong><?php echo $personne->lieunaiss; ?></strong></h4></p>
                        <p><h4>Sexe: <strong> <?php echo $personne->sexe ; ?></strong></h4></p>
                    </div>

                    <div class="col-lg-4 col-sm-3 follow-info">
                        <p><h4>Téléphone: <strong><?php echo $personne->telephone; ?></strong></h4></p>
                        <p><h4>Email: <strong><?php echo $personne->email; ?></strong></h4></p>
                        <p><h4>Zone d'habitation: <strong><?php echo $personne->nomzone; ?></strong></h4></p>
                        <p><h4>Grade: <strong><?php echo $grade->nomgrade; ?></strong></h4></p>
                    </div>
                </div>
            </div>
            <br>

                <ul class="nav nav-tabs  col-sm-12 col-lg-10" >
                   <center>
                    <button data-toggle="tab" href="#etatCivil" class="btn btn-info waves-effect">Etat Civil</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button data-toggle="tab" href="#statutProfessionnel" class="btn btn-info waves-effect">Statut Professionnel</button>

                     <span style="float:right">     
                    <button class="btn bg-green listeF" href="modifierPasteur.php?idpersonne=<?php echo $personne->idpersonne; ?>" title="Modifier" <?php if(!has_Droit($idUser, "Modifier un pasteur")|| (date('Y') != $annee)){echo 'disabled';}else{echo "";} ?>><i class="material-icons">border_color</i> Modifier</button>
                    <a class="btn bg-blue waves-effect" href="report/imprimer_param.php?file=profil_pasteur&param=<?php echo $personne->idpersonne; ?>" title="Imprimer la fiche du pasteur" target="_blank"><i class="material-icons">print</i> Imprimer</a>
                    <button class="btn bg-green waves-effect" href="#" title="Envoyer un mail au pasteur" target="_blank"><i class="material-icons">message</i> Ecrire</button>
                  </span>
                </center>
            </ul>
        </div>
    </div>
</div><br>


<div class="panel-body card">
    <div class="tab-content">

        <!-- Etat Civil -->
        <div id="etatCivil" class="tab-pane active">
            <table class="table table-bordered table-striped table-hover table-advance">
                <tbody>
                <tr>
                    <th>Nom du père</th>
                    <th>Nom de la mère</th>
                    <th>Situation matrimoniale</th>
                    <th>Nom conjoint</th>
                    <th>Religion conjoint</th>
                </tr>
                <tr>
                    <td><?php echo $personne->pere; ?>(<?php if($personne->pere_vivant == 1){ echo "Vivant";}else{ echo "Décédé";} ?>)</td>
                    <td><?php echo $personne->mere; ?>(<?php if($personne->mere_vivant == 1){ echo "Vivante";}else{ echo "Décédée";} ?>)</td>
                    <td><?php echo $personne->situation_matri; ?></td>
                    <td><?php echo $personne->conjoint; ?></td>
                    <td><?php echo $personne->religion_conjoint; ?></td>
                </tr>
                <tr>
                    <th>Nombre d'enfants</th>
                    <th>Région d'origine</th>
                    <th>Département</th>
                    <th>Arrondissement</th>
                    <th>Village</th>
                </tr>
                <tr>
                    <td><?php echo $personne->	nombre_enfant; ?></td>
                    <td><?php echo $personne->	nom_region; ?></td>
                    <td><?php echo $personne->	nom_dept; ?></td>
                    <td><?php echo $personne->	nom_arro; ?></td>
                    <td><?php echo $personne->	village; ?></td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Statut Professionnel -->
        <div id="statutProfessionnel" class="tab-pane">
            <table class="table table-bordered table-striped table-hover table-advance">
                <tbody>
                    <tr>
                        <th>Statut</th>
                        <th>Dernier diplome</th>
                        <th>Domaine</th>
                        <th>Profession</th>
                        <th>Employeur</th>
                    </tr>
                    <tr>
                        <td><?php echo $personne->statut_pro; ?></td>
                        <td><?php echo $personne->diplome; ?></td>
                        <td><?php echo $personne->domaine; ?></td>
                        <td><?php echo $personne->profession; ?></td>
                        <td><?php echo $personne->employeur; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</section>

<Script>

    $('#chargement').hide();
    $('.listeF').on('click', function(af){

        af.preventDefault();
        var $b = $(this);
        url = $b.attr('href');

        $('#main-content').load(url);
    });
</Script>