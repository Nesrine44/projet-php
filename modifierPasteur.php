<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Modifier un pasteur")){

            header('Location:index.php');

        }else{

            $n = 1;
            $annee = date('Y');
            $anneeMin = $annee - 30;
            $annee_creation = 0;


            $selectzone = $db->prepare("SELECT nomzone, idzone, description
                                        FROM zone
                                        WHERE lisible=1;");
            $selectzone->execute();
            $personne = null;

            $idpersonne=$_GET['idpersonne'];

            $selectInfo = $db->prepare("SELECT nom, prenom, datenaiss, lieunaiss, email, statut_pro, pere, mere, pere_vivant, domaine, diplome, employeur, village, niveau, serie, conjoint, situation_matri, religion_conjoint, nombre_enfant, mere_vivant, profession, sexe, photo, nomzone, idzone, telephone, grade, nomgrade, personne.arrondissement as idarron, iddepartement, idregion, arrondissement.arrondissement as nom_arro, departement.departement as nom_dept, region.region AS nom_region
                                        FROM personne, zone, pasteur, arrondissement, departement, region, grade
                                        WHERE idpersonne = $idpersonne
                                        AND zone.idzone = personne.zone_idzone
                                        AND personne.idpersonne = pasteur.personne_idpersonne
                                        AND departement_iddepartement = iddepartement
                                        AND region_idregion = idregion
                                        AND idarrondissement = personne.arrondissement
                                        AND pasteur.grade=grade.idgrade
                                        AND arrondissement.lisible = 1
                                        AND departement.lisible = 1
                                        AND region.lisible = 1");
            $selectInfo->execute();
            while($s = $selectInfo->fetch(PDO::FETCH_OBJ)){

                $personne = $s;
            }


            $regions = $db->prepare("SELECT * FROM region where  region.idregion != $personne->idregion AND lisible=1 ORDER BY region");
            $regions->execute();


            $departements = $db->prepare("SELECT iddepartement, departement from departement where region_idregion = $personne->idregion and departement.iddepartement != $personne->iddepartement AND lisible = 1");
            $departements->execute();

            $arrondissements = $db->prepare("SELECT idarrondissement, arrondissement FROM arrondissement where departement_iddepartement = $personne->iddepartement and arrondissement.idarrondissement != $personne->idarron AND  lisible = 1");
            $arrondissements->execute();

            $selectGrade=$db->prepare("SELECT nomgrade, idgrade
                                        FROM grade
                                        WHERE lisible=1
                                        AND estpris=0");
            $selectGrade->execute();

           }

    }else{
        header('Location:login.php');
    }

    unset($db);
?>

<section class="wrapper">

    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="material-icons">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                <li><i class="material-icons">people</i> Pateurs</li>
                <li><i class="material-icons">people</i><a class="afficher col-blue" href="listePasteurs.php"> Liste Pasteurs</a></li>
                <li><i class="material-icons">border_color</i> Modifier Pasteur</li>
            </ol>
        </div>
    </div>

    <div class="row card">
        <div class="col-lg-12">
            <section class="pannel">

                <header class="panel-heading tab-bg-primary ">
                    <ul class="nav nav-tabs">
                        <li class="active h4">
                            <a data-toggle="tab" href="#personnel">Informations personelles</a>
                        </li>
                        <li class="etat_civil h4">
                            <a data-toggle="tab" href="#etat_civile">Etat Civil</a>
                        </li>
                        <li class="statut_prof h4">
                            <a data-toggle="tab" href="#statut_professionnel">Statut Professionnel</a>
                        </li>
                    </ul>
                </header>

                <div class="form">
                    <form class="form-validate form-horizontal" id="form_updatePasteur" method="POST" enctype="multipart/form-data" action="updatePasteur.php?idpersonne=<?php echo $idpersonne; ?>&grade=<?php echo $personne->grade; ?>">
                        <div class="panel-body">
                            <div class="tab-content">

                                <!-- informations personnelles-->
                                <div id="personnel" class="tab-pane active">

                                    <div class="row clearfix inputTopSpace">
                                       <div class="col-md-6">
                                        <label for="Nom">Nom(s): <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">edite</i>
                                                </span>
                                                <div class="form-line">
                                                    <input class="form-control" id="cnom" name="nom" required  type="text" value="<?php echo $personne->nom; ?>"  />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                        <label for="Nom">Prenom(s): <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">edite</i>
                                                </span>
                                                <div class="form-line">
                                                    <input class="form-control" id="cnom" name="nom" required  type="text" value="<?php echo $personne->nom; ?>"  />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>

                                    <div class="row clearfix inputTopSpace">
                                       <div class="col-md-6">
                                        <label for="Nom">Date de naissance: <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">perm_contact_calendar</i>
                                                </span>
                                                <div class="form-line">
                                                     <input class="form-control datepicker" id="datenaiss" name="dateNaiss" type="text" value="<?php echo $personne->datenaiss; ?>" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="YYYY-MM-DD" required  />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                        <label for="Nom">Lieu de naissance: <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">location_on</i>
                                                </span>
                                                <div class="form-line">
                                                    <input class="form-control" id="lieunaiss" name="lieunaiss" type="text" value="<?php echo $personne->lieunaiss; ?>" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>

                                    <div class="row clearfix inputTopSpace">
                                      <div class="col-md-6">
                                        <label for="ctel">Téléphone: <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">call</i>
                                                </span>
                                                <div class="form-line">
                                                    <input class="form-control " id="ctel" type="text" name="tel" required minlength="9" value="<?php echo $personne->telephone; ?>" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                        <label for="cemail">E-Mail: <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">email</i>
                                                </span>
                                                <div class="form-line">
                                                    <input class="form-control " id="cemail" type="email" name="email" value="<?php echo $personne->email; ?>" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br> 

                                    <div class="row clearfix inputTopSpace">
                                        <div class="col-md-6">
                                        <label for="csexe">Sexe: <span class="required">*</span></label>
                                            <select class="form-control " name="sexe" required>
                                                <option value="Feminin" id="Feminin" <?php if($personne->sexe == 'Feminin') echo 'selected'; ?>>Feminin</option>
                                                <option value="Masculin" id="masculin" <?php if($personne->sexe == 'Masculin') echo 'selected'; ?>>Masculin</option>
                                            </select>
                                        </div>
                                       <div class="col-md-6">
                                        <label for="cgrade">Grade: <span class="required">*</span></label>
                                            <select class="form-control" name="grade" required>
                                                <option value="<?php echo $personne->grade;?>" selected><?php echo $personne->nomgrade;?></option>
                                                <?php

                                                while($grade=$selectGrade->fetch(PDO::FETCH_OBJ)){
                                                    ?>

                                                    <option value="<?php echo $grade->idgrade;?>" ><?php echo $grade->nomgrade;?></option>
                                                <?php
                                                }
                                                $db=NULL;
                                                ?>

                                            </select>
                                        </div>
                                    </div>
                                    <br>

                                    <div class="row clearfix inputTopSpace">
                                        <div class="col-md-6">
                                        <label for="csexe">Zone d'habitation: <span class="required">*</span></label>
                                            <select class="form-control zone" name="lieu" required>
                                                <?php
                                                while($zones=$selectzone->fetch(PDO::FETCH_OBJ)){

                                                         $texte = str_replace(',', ';', $zones->description); 
                                                         $texte = str_replace(';', '|', $texte);
                                                         $texte = explode('|', $texte);
                                                         

                                                         for($i=0; $i<count($texte); $i++){
                                                    ?>  
                                                            <option value="<?php echo $zones->idzone;?>" class="test" <?php if($zones->idzone == $personne->idzone) echo "selected";?>>
                                                    
                                                                <?php echo $zones->nomzone.': '.$texte[$i]; ?>

                                                            </option>    
                                                    <?php
                                                         }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                          <div class="col-md-6">
                                            <label for="Nom">Photo: <span class="required">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="material-icons">insert_photo</i>
                                                    </span>
                                                    <div class="form-line col-green">
                                                         <img src="" />
                                                        <input  id="cphoto" type="file" name="photo" value="<?php echo $personne->photo; ?>" accept="images/*" />
                                                    </div>
                                                </div>
                                            </div> 
                                    </div>
                                    <br>

                                </div>
                                <!-- FIN informations personnelles-->


                                <!-- Etat civil-->
                                <div id="etat_civile" class="tab-pane">
                                    <div class="row clearfix inputTopSpace">
                                       <div class="col-md-6">
                                        <label for="Nom">Nom du père: <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">edite</i>
                                                </span>
                                                <div class="form-line">
                                                 <input class="form-control" id="cnom" name="pere" value="<?php echo $personne->pere; ?>" type="text"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                        <label for="Nom">Vivant?: <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">hotel</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="radio" name="pere_vivant" value="1" id="radio_34" class="with-gap radio-col-green" <?php if($personne->pere_vivant==1){echo 'checked="checked"';}; ?> /><label for="radio_34" class="col-black">&nbsp;Oui</label>&nbsp;&nbsp;&nbsp;
                                                    <input type="radio" name="pere_vivant" value="0" id="radio_35" class="with-gap radio-col-red" <?php if($personne->pere_vivant==0){echo 'checked="checked"';}; ?> /><label for="radio_35" class="col-black">&nbsp;Non</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br> 

                                    <div class="row clearfix inputTopSpace">
                                       <div class="col-md-6">
                                        <label for="Nom">Nom de  la mère: <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">edite</i>
                                                </span>
                                                 <div class="form-line">
                                                 <input class="form-control" id="cnom" name="mere" value="<?php echo $personne->mere; ?>" type="text" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                        <label for="Nom">Vivante?: <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">hotel</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="radio" name="mere_vivante" value="1" id="radio_36" class="with-gap radio-col-green" <?php if($personne->mere_vivant==1){echo 'checked="checked"';}; ?> /><label for="radio_36" class="col-black">&nbsp;Oui</label>&nbsp;&nbsp;&nbsp;
                                                    <input type="radio" name="mere_vivante" value="0" id="radio_37" class="with-gap radio-col-red" <?php if($personne->mere_vivant==0){echo 'checked="checked"';}; ?>/><label for="radio_37" class="col-black">&nbsp;Non</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br> 

                                    <div class="row clearfix inputTopSpace">
                                       <div class="col-md-2">
                                        <label for="Nom">Situation matrimoniale: <span class="required">*</span></label>
                                        </div>
                                            <div class="col-md-2">
                                                    <div class="form-line">
                                                        <input type="radio" name="situation" value="CELIBATAIRE" id="radio_38" class="with-gap radio-col-green choix_situation" <?php if($personne->situation_matri=="CELIBATAIRE"){echo 'checked';} ?> /><label for="radio_38" class="col-black">&nbsp;Célibataire</label>
                                                    </div>
                                            </div>

                                            <div class="col-md-2">
                                                    <div class="form-line">
                                                        <input type="radio" name="situation" value="DIVORCE" id="radio_39" class="with-gap radio-col-red choix_situation" <?php if($personne->situation_matri=="DIVORCE"){echo 'checked';} ?> /><label for="radio_39" class="col-black">&nbsp;Divorcé(e)</label>
                                                         </div>
                                            </div>


                                            <div class="col-md-2">
                                                    <div class="form-line">
                                                        <input type="radio" name="situation" value="MARIE" id="radio_40" class="with-gap radio-col-red choix_situation" <?php if($personne->situation_matri=="MARIE"){echo 'checked';} ?> /><label for="radio_40" class="col-black">&nbsp;Marié(e) </label>
                                                                </div>
                                            </div>
                                            

                                             <div class="col-md-2">
                                                    <div class="form-line">
                                                        <input type="radio" name="situation" value="FIANCE" id="radio_41" class="with-gap radio-col-red choix_situation" <?php if($personne->situation_matri=="FIANCE"){echo 'checked';} ?> /><label for="radio_41" class="col-black">&nbsp;Fiancé(e) </label>
                                                    </div>
                                            </div>
                                            <div class="col-md-2">
                                                    <div class="form-line">
                                                        <input type="radio" name="situation" value="VEUF" id="radio_42" class="with-gap radio-col-red choix_situation" <?php if($personne->situation_matri=="VEUF"){echo 'checked';} ?> /><label for="radio_42" class="col-black">&nbsp;Veuf/veuve  </label>
                                                    </div>
                                            </div>
                                       
                                    </div>
                                    <br> 

                                    <div class="row clearfix inputTopSpace conjoint" style="display: none;">
                                        <div class="col-md-6">
                                            <label for="Nom">Conjoint: <span class="required">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="material-icons">edite</i>
                                                    </span>
                                                    <div class="form-line">
                                                       <input class="form-control" id="cConjoint" name="conjoint" value="<?php echo $personne->conjoint; ?>" type="text"/>
                                                    </div>
                                                </div>
                                        </div>   
                                       <div class="col-md-6">
                                          <label for="Nom">Réligion: <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">edite</i>
                                                </span>
                                                    <div class="form-line">
                                                        <input type="radio" name="religion" value="EEC" id="radio_43" class="with-gap radio-col-green religion" checked//><label for="radio_43" class="col-black">&nbsp;EEC</label>&nbsp;&nbsp;&nbsp;
                                                        <input type="radio" name="religion" value="CATHOLIQUE" id="radio_44" class="with-gap radio-col-red religion" /><label for="radio_44" class="col-black">&nbsp;Catholique</label>&nbsp;&nbsp;&nbsp;
                                                        <input type="radio" name="religion" value="AUTRE" id="radio_45" class="with-gap radio-col-red religion" /><label for="radio_45" class="col-black">&nbsp;Autre réligion </label>&nbsp;&nbsp;&nbsp;
                                                    </div>
                                                       
                                            </div>
                                        </div>
                                     </div>
                                    <br> 

                                    <div class="row clearfix inputTopSpace " style="margin-top: 5px; display: none;" id="cAutre">
                                          <div class="col-md-4">
                                            <label for="Nom">Préciser la réligion: <span class="required">*</span></label>
                                          </div> 
                                          <div class="col-md-8">
                                                <div class="form-line">
                                                   <input class="form-control"  type="text" name="religion_autre" placeholder="Préciser" />
                                               </div> 
                                           </div> 
                                    </div> 
                                    <br>

                                    <div class="row clearfix inputTopSpace">
                                       <div class="col-md-6">
                                        <label for="cNbre">Nombre d'enfants: <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">edite</i>
                                                </span>
                                                 <div class="form-line">
                                                  <input class="form-control " id="cnbre" type="number" name="nbre_enfant"  value="<?php echo $personne->nombre_enfant; ?>" value="0" min="0" max="100" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                        <label for="cVillage">Village: <span class="col-yellow">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">edite</i>
                                                </span>
                                                <div class="form-line">
                                                    <input class="form-control " id="cVllage" type="text" name="village" value="<?php echo $personne->village; ?>" required/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br> 

                                    <div class="row clearfix inputTopSpace">
                                       <div class="col-md-6">
                                        <label for="cOrigine">originaire de: <span class="required">*</span></label>
                                            <select class="form-control " name="region" id="region">
                                                <option value="<?php echo $personne->idregion; ?>" selected><?php echo $personne->nom_region; ?></option>

                                                <?php
                                                while($region=$regions->fetch(PDO::FETCH_OBJ)){
                                                    ?>
                                                    <option value="<?php echo $region->idregion;?>" class="region"><a href="d.php?idregion=<?php echo $region->idregion;?>"><?php echo $region->region;?></a></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                        <label for="cVillage">Département: <span class="col-yellow">*</span></label>
                                            <select class="form-control " name="departement" required id="departement">
                                                <option value="<?php echo $personne->iddepartement; ?>" selected><?php echo $personne->nom_dept; ?></option>
                                                <?php

                                                while($dept = $departements->fetch(PDO::FETCH_OBJ)){
                                                    ?>

                                                    <option value="<?php echo $dept->iddepartement; ?>" ><?php echo $dept->departement; ?></option>

                                                <?php

                                                }
                                                ?>
                                            </select>
                                        </div> 

                                        <div class="col-md-3">
                                        <label for="cVillage">Arrondissement: <span class="col-brown">*</span></label>
                                            <select class="form-control" name="arrondissement" id="arrondissement" required>

                                                <option value="<?php echo $personne->idarron; ?>" selected><?php echo $personne->nom_arro; ?></option>
                                                <?php
                                                while ($arron = $arrondissements->fetch(PDO::FETCH_OBJ)) {

                                                    ?>

                                                    <option value="<?php echo $arron->idarrondissement; ?>" ><?php echo $arron->arrondissement; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <br> 
                                </div>
                                <!-- FIN Etat civil-->


                                <!-- Statut professionnel-->
                                <div id="statut_professionnel" class="tab-pane">

                                <div class="row clearfix inputTopSpace">
                                   <div class="col-md-3">
                                      <label for="inputSuccess">Activité ménée<span class="col-brown">*</span></label>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="form-line col-lg-2">
                                          <input type="radio" name="statut_pro" value="FONCTIONNAIRE" id="radio_47" class="with-gap radio-col-green statut_pro" /><label for="radio_47" class="col-black">&nbsp;Fonctionnaire </label>
                                        </div>
                                        <div class="form-line col-lg-2">
                                          <input type="radio" name="statut_pro" value="COMMERCANT" id="radio_48" class="with-gap radio-col-green statut_pro" /><label for="radio_48" class="col-black">&nbsp;Commerçant </label>
                                        </div>  
                                        <div class="form-line col-lg-2">
                                          <input type="radio" name="statut_pro" value="SANS EMPLOI" id="radio_49" class="with-gap radio-col-green statut_pro" /><label for="radio_49" class="col-black">&nbsp;Sans Emploi </label>
                                        </div> 
                                        <div class="form-line col-lg-2">
                                          <input type="radio" name="statut_pro" value="ACTIVITE LIBERALE" id="radio_50" class="with-gap radio-col-green statut_pro" /><label for="radio_50" class="col-black">&nbsp;Activité libérale</label>
                                        </div>       
                                        <div class="form-line col-lg-2">
                                          <input type="radio" name="statut_pro" value="RETRAITE" id="radio_51" class="with-gap radio-col-green statut_pro" /><label for="radio_51" class="col-black">&nbsp;Retraité</label>
                                        </div>
                                       </div> 

                                </div>
                                <br> 

                                <div class="row clearfix inputTopSpace" id="employeur" style="display: none;">
                                   <div class="col-md-6">
                                    <label for="cNbre">Profession : <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">edite</i>
                                            </span>
                                             <div class="form-line">
                                              <input class="form-control profession" id="cprofession" name="profession"  type="text"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    <label for="cVillage">Employeur: <span class="col-yellow">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">edite</i>
                                            </span>
                                            <div class="form-line">
                                                <input class="form-control profession" id="cemployeur" name="employeur"  type="text"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br> 

                                <div class="row clearfix inputTopSpace" id="sans_emploi" style="display: none;">
                                   <div class="col-md-6">
                                    <label for="cNbre">Dernier diplôme : <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">edite</i>
                                            </span>
                                             <div class="form-line">
                                              <input class="form-control" id="cdiplome" name="diplome"  type="text"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    <label for="cVillage">Domaine: <span class="col-yellow">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">edite</i>
                                            </span>
                                            <div class="form-line">
                                                 <input class="form-control" id="cdomaine" name="domaine"  type="text"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br> 

                                    <div class="form-group">
                                        <div class="col-lg-offset-5 col-lg-10">
                                            <a class="btn btn-warning afficher" id="annuler">Annuler</a>
                                            <button class="btn btn-primary" name="submit" type="submit">Mettre à jour
                                            </button><span class="envoi_en_cours" style="margin-left: 10px; display: none;">Enregistrement en cours...</span>
                                        </div>
                                    </div>

                                </div><br>
                                <!-- FIN Statut professionnel-->
                            </div>
                        </div>
                    </form>
                </div>

            </section>
        </div>
    </div>
</section>
































<Script>

    var compteur = false;

    $('#chargement').hide();

    $('.afficher').on('click', function(af){

        $('.loader').show();

        af.preventDefault();
        var $b = $(this);
        url = $b.attr('href');

        $('#main-content').load(url);
    });

    $('#form_updatePasteur').on('submit', function (e) {
        // On empêche le navigateur de soumettre le formulaire
        e.preventDefault();
        $('.envoi_en_cours').show();
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();

        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'json', // selon le retour attendu
            data: data,
            success: function (reponse) {

                if(reponse != ''){

                    $('.envoi_en_cours').hide();
                    alert(reponse);

                }else{

                    alert('Pasteur modifié avec succès!');
                    $('.loader').show();
                    $('#main-content').load('listePasteurs.php');
                    $('.envoi_en_cours').hide();
                }

            }
        });
    });

    $('#form_updatePasteur').find('input[name="photo"]').on('change', function (e) {
        var files = $(this)[0].files;

        if (files.length > 0) {
            // On part du principe qu'il n'y qu'un seul fichier
            // étant donné que l'on a pas renseigné l'attribut "multiple"
            var file = files[0],
                $image_preview = $('#image_preview');

            // Ici on injecte les informations recoltées sur le fichier pour l'utilisateur
            // $image_preview.find('.thumbnail').removeClass('hidden');
            $image_preview.find('img').attr('src', window.URL.createObjectURL(file));
            //$image_preview.find('h4').html(file.name);
            $image_preview.find('h4').html(file.size +' bytes');
        }
    });

    $('#annuler').on('click', function(en){

        en.preventDefault();
        var $link = $(this);
        target = $link.attr('href');
        if(window.confirm("Voulez-vous vraiment annuler?")){
            $('#main-content').load('annuler.php?grade=<?php echo $personne->grade;?>');
        }
        $('.loader').hide();
    });

    $('.statut_pro').on('click', function(){

        $x = $(this);
        if($x.val() == 'Commerçant' || $x.val() == 'Sans Emploi'){

            $('.profession').hide();

        }else{

            $('.profession').show();
        }
    });

    $('.choix_situation').on('click', function(){

        $x = $(this).val();
        if($x == 'CELIBATAIRE'){

            $('.conjoint').hide();
        }else{
            $('.conjoint').show();
        }
    });

    $('.religion').on('click', function(){

        $x = $(this).val();

        if($x != 'AUTRE'){

            $('#religion_autre').removeAttr('checked');

            $('#cAutre').hide();


        }else{
            $('#cAutre').show();

        }
    });

    $('#region').on('change', function(){

        var x = $(this).val();
        if(x != ''){
            $('#departement').empty();
            $.ajax({

                url:'d.php',
                data:'idregion='+x,
                dataType:'json',
                success:function(json){

                    $.each(json, function(index, value){
                        $('#departement').append('<option value="'+index+'">'+value+'</option>')
                    });
                }
            });
        }
    });

    $('#departement').on('change', function(){

        var x = $(this).val();
        if(x != ''){
            $('#arrondissement').empty();
            $.ajax({

                url:'a.php',
                data:'iddept='+x,
                dataType:'json',
                success:function(json){

                    $.each(json, function(index, val){
                        $('#arrondissement').append('<option value="'+index+'">'+val+'</option>')
                    });
                }
            });
        }
    });

    $(".datepicker" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });

    $('.active').on('click', function(){

        // alert('Informations professionnelles');
    });

    //     $('.etat_civil').on('click', function(){

    //alert('Etat civil');

    var id = <?php echo $idpersonne; ?>

        $.ajax({

            url:'etat_civil.php',
            data:'idpersonne='+id,
            dataType: 'json',

            success: function(json){
                $('#cpere').val(json.pere);
                $('#cmere').val(json.mere);

                if(json.pere_vivant == 1){
                    $('#pere_vivant_oui').attr({'checked':'true'});
                }else if(json.pere_vivant == 0){
                    // $('#pere_vivant_oui').removeAttr('checked');
                    $('#pere_vivant_non').attr({'checked':'true'});
                }


                if(json.mere_vivant == 1){
                    $('#mere_vivante_oui').attr({'checked':'true'});
                }else if(json.mere_vivant == 0){
                    // $('#pere_vivant_oui').removeAttr('checked');
                    $('#mere_vivante_non').attr({'checked':'true'});
                }

                $('.choix_situation').each(function(cs){

                    if($(this).val() == json.situation_matri){

                        $(this).attr({'checked':'true'});
                        //alert(json.situation_matri);

                        if(json.situation_matri != 'CELIBATAIRE'){

                            $('.conjoint').show();
                            $('#conjoint').val(json.conjoint);

                            $('.religion').each(function(r){

                                if($(this).val() == json.religion){

                                    $(this).attr({'checked':'true'});
                                    $('#cAutre').hide();
                                }
                            });

                            if(json.religion != 'EEC' && json.religion != 'Catholique'){

                                $('#religion_autre').attr({'checked':'true'});
                                $('#cAutre').show();
                                $('#r_autre').val(json.religion);

                            }

                        }else{

                            $('.conjoint').hide();
                        }
                    }

                });



                $('#nbre').val(json.nombre_enfant);
                $('#village').val(json.village);

            }

        });
    //});

    //   $('.statut_prof').on('click', function(){

    var id = <?php echo $idpersonne; ?>

        $.ajax({

            url:'statut_prof.php',
            data:'idpersonne='+id,
            dataType:'json',
            success: function(infoProf){

                $('.statut_pro').each(function(st){

                    x = $(this).val();

                    if(x == infoProf.statut_pro){

                        $(this).attr({'checked':'true'});

                        if(x == 'ETUDIANT'){

                            $('#etablissement').show();
                            $('#employeur').hide();
                            $('#sans_emploi').show();

                            $('#cetablissement').val(infoProf.etablissement);
                            $('#cniveau').val(infoProf.niveau);
                            $('#cfiliere').val(infoProf.serie);
                            $('#cdiplome').val(infoProf.diplome);
                            $('#cdomaine').val(infoProf.domaine);

                        }else if(x=='COMMERCANT' || x=='SANS EMPLOI'){

                            $('#etablissement').hide();
                            $('#employeur').hide();
                            $('#sans_emploi').show();
                            $('#cdiplome').val(infoProf.diplome);
                            $('#cdomaine').val(infoProf.domaine);

                        }else{

                            $('#etablissement').hide();
                            $('#employeur').show();
                            $('#sans_emploi').show();
                            $('#cprofession').val(infoProf.profession);
                            $('#cemployeur').val(infoProf.employeur);
                            $('#cdiplome').val(infoProf.diplome);
                            $('#cdomaine').val(infoProf.domaine);
                        }
                    }
                });
            }
        });

    $('.statut_pro').on('click', function(){

        var x = $(this).val();

        if(x == 'ETUDIANT'){

            $('#etablissement').show();
            $('#employeur').hide();
            $('#sans_emploi').show();

        }else if(x=='COMMERCANT' || x=='SANS EMPLOI'){

            $('#etablissement').hide();
            $('#employeur').hide();
            $('#sans_emploi').show();

        }else{

            $('#etablissement').hide();
            $('#employeur').show();
            $('#sans_emploi').show();
        }
    });
    // });

    $('.statut_parois').on('click', function(){

        var id = <?php echo $idpersonne; ?>

            $.ajax({

                url:'statut_paroisse.php',
                data:'idpersonne='+id,
                dataType:'json',

                success: function(json){

                    $('.statut').each(function(){

                        if($(this).val() == json.statut){

                            $(this).attr({'selected':'true'});
                        }
                    });

                    $('.date_inscript').each(function(){

                        if($(this).val() == json.date_inscription){

                            $(this).attr({'selected':'true'});
                        }
                    });

                }
            });

        $.ajax({

            url:'is_malade.php',
            data:'idpersonne='+id,
            dataType:'json',
            success: function(malade){

                if(malade != null){

                    $('#is_malade_oui').attr({'checked':'true'});
                    $('#est_malade').show();
                    $('#cdate_maladie').val(malade.date_maladie);
                    $('#cguide').val(malade.guide);

                }else{

                    $('#is_malade_non').attr({'checked':'true'});
                    $('#est_malade').hide();
                    $('#cdate_maladie').val("");
                    $('#cguide').val("");
                }
            }
        });

        $.ajax({

            url:'is_baptise.php',
            data:'idpersonne='+id,
            dataType:'json',
            success: function(bapteme){

                if(bapteme != null){

                    $('#is_baptise_oui').attr({'checked':'true'});
                    $('#baptise').show();
                    $('#cdate_bapteme').val(bapteme.date_bapteme);
                    $('#clieu_bapteme').val(bapteme.lieu_baptise);

                }else{

                    $('#is_baptise_non').attr({'checked':'true'});
                    $('#baptise').hide();
                    $('#cdate_bapteme').val("");
                    $('#clieu_bapteme').val("");
                }
            }
        });

        $.ajax({

            url:'is_confirme.php',
            data:'idpersonne='+id,
            dataType:'json',
            success: function(confirme){

                if(confirme != null){

                    $('#is_confirme_oui').attr({'checked':'true'});
                    $('#confirme').show();
                    $('#cdate_confirme').val(confirme.date_confirmation);
                    $('#clieu_confirme').val(confirme.lieu_confirmation);

                }else{

                    $('#is_confirme_non').attr({'checked':'true'});
                    $('#confirme').hide();
                    $('#cdate_confirme').val("");
                    $('#clieu_confirme').val("");
                }
            }
        });

        $.ajax({

            url:'is_groupe.php',
            data:'idpersonne='+id,
            dataType:'json',
            success: function(groupe){

                if(groupe != null){

                    $('#is_groupe_oui').attr({'checked':'true'});
                    $('#dans_groupe').show();

                    $('.checkbox').each(function(ch){

                        if($(this).val() == groupe.idgroupe){

                            $(this).attr({'checked':'true'});
                        }
                    });

                    $('.cdate_ins').each(function(ch){

                        if($(this).val() == groupe.date_inscription){

                            $(this).attr({'selected':'true'});
                        }
                    });


                }else{

                    $('#is_groupe_non').attr({'checked':'true'});
                    $('#dans_groupe').hide();
                }
            }
        });
    });

    $('.baptise').on('click', function(){

        var x = $(this).val();

        if(x == '1'){

            $('#baptise').show();

        }else{

            $('#baptise').hide();
        }
    });

    $('.confirme').on('click', function(){

        var x = $(this).val();

        if(x == '1'){

            $('#confirme').show();

        }else{

            $('#confirme').hide();
        }
    });

    $('.malade').on('click', function(){

        var x = $(this).val();

        if(x == '1'){

            $('#est_malade').show();

        }else{

            $('#est_malade').hide();
        }
    });

    $('#checkAll').click(function() {
        // on cherche les checkbox à l'intérieur de l'id  'magazine'
        //var magazines = $("#magazine").find(':checkbox');
        var test='Cocher Tous';
        var test1='Decocher Tous';
        if(this.checked){ // si 'checkAll' est coché
            $(":checkbox").attr('checked', true);
            $('#modifiertext').html(test1);
        }else{ // si on décoche 'checkAll'
            $(":checkbox").attr('checked', false);
            $('#modifiertext').html(test);
        }
    });

    $('.membre_groupe').on('click', function(){

        var x = $(this).val();

        if(x == '1'){

            $('#dans_groupe').show();

        }else{

            $('#dans_groupe').hide();

        }

    });

    $('.loader').hide();

</Script>