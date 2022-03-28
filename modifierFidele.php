<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Modifier un fidele")){

            header('Location:index.php');

        }else{        

            $n = 1;
            $annee = $_SESSION['annee'];
            $anneeMin = $annee - 30;
            $annee_creation = 0;


            $selectzone = $db->prepare("SELECT nomzone, idzone, description FROM zone where lisible=1;");
            $selectzone->execute();
            $personne = null;

            $idpersonne=$_GET['id'];

            $selectInfo = $db->prepare("SELECT nom, prenom, datenaiss, lieunaiss, email, statut_pro, profession, sexe, photo, nomzone, idzone, telephone, statut, codefidele, personne.arrondissement as idarron, iddepartement, idregion, arrondissement.arrondissement as nom_arro, departement.departement as nom_dept, region.region AS nom_region FROM personne, zone, fidele, arrondissement, departement, region WHERE idpersonne = $idpersonne AND zone.idzone = personne.zone_idzone AND personne.idpersonne = fidele.personne_idpersonne AND departement_iddepartement = iddepartement AND region_idregion = idregion AND idarrondissement = personne.arrondissement AND arrondissement.lisible = 1 AND departement.lisible = 1 AND region.lisible = 1");
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

            $selectGroupe = $db->prepare("SELECT idgroupe, nomgroupe, typegroupe, YEAR(datecreation) as annee_creation FROM groupe  where typegroupe != 'Anciens' AND lisible = 1 ORDER BY nomgroupe ASC");
            $selectGroupe->execute();
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
                    <li><i class="material-icons">people</i> Fidèles</li>
                    <li><i class="material-icons"></i><a class="afficher col-blue" href="listeFideles.php"> Liste Fidèles</a></li>
                    <li><i class="material-icons">people</i> Modifier Fidèle</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <section class="panel">

                   <header class="panel-heading tab-bg-primary ">
                              <ul class="nav nav-tabs">
                                  <li class="active h4">
                                      <a data-toggle="tab" href="#home">Informations personelles</a>
                                  </li>
                                  <li class="etat_civil h4">
                                      <a data-toggle="tab" href="#about">Etat Civil</a>
                                  </li>
                                  <li class="statut_prof h4">
                                      <a data-toggle="tab" href="#profile">Statut Professionnel</a>
                                  </li>
                                  <li class="statut_parois h4">
                                      <a data-toggle="tab" href="#contact">Statut Paroissial</a>
                                  </li>
                              </ul>
                    </header>
                        <div class="form">
                            <form class="form-validate form-horizontal" id="form_updateFidele" method="POST" enctype="multipart/form-data" action="updateFidele.php?idpersonne=<?php echo $idpersonne; ?>&amp;sexe=<?php echo $personne->sexe; ?>&amp;statut_pro=<?php echo $personne->statut_pro; ?>&amp;code=<?php echo $personne->codefidele; ?>">

                                <div class="panel-body">
                                    <div class="tab-content">

                                    <!-- partie 1 -->
                                        <div id="home" class="tab-pane active">
                                            <h4>Informations personnelles</h4><br>
                                        <div class="row clearfix inputTopSpace">
                                               <div class="col-md-6">
                                                <label for="Nom">Nom(s): <span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="material-icons">edite</i>
                                                        </span>
                                                        <div class="form-line">
                                                            <input class="form-control" id="cnom" name="nom"  type="text" value="<?php echo $personne->nom; ?>" required />
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
                                                            <input class="form-control" id="cprenom" name="prenom" type="text" value="<?php echo $personne->prenom; ?>"/>
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
                                                <label for="Nom">Téléphone: <span class="required">*</span></label>
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
                                                <label for="Nom">E-Mail: <span class="required">*</span></label>
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
                                                <label for="Nom">Sexe: <span class="required">*</span></label>
                                                     <select class="form-control " name="sexe" required>
                                                        <option disabled selected>Sélectionner le sexe</option>
                                                        <option value="Feminin" id="Feminin" <?php if($personne->sexe == 'Feminin') echo 'selected'; ?>>Feminin</option>
                                                        <option value="Masculin" id="masculin" <?php if($personne->sexe == 'Masculin') echo 'selected'; ?>>Masculin</option>   
                                                    </select>
                                                </div>
                                               <div class="col-md-6">
                                                <label for="Nom">Zone d'habitation: <span class="required">*</span></label>
                                                    <select class="form-control show-tick" name="quartier" required>
                                                    
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
                                            </div>
                                            <br> 
                                            
                                            <div class="form-group ">
                                              <div class="col-md-3"></div>
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
                                                <div class="col-md-3"></div>                                        
                                        </div>

                                </div> <!-- fin partie 1 -->

                                <!-- partie 2 -->
                                <div id="about" class="tab-pane">
                                   <h4>Etat Civil</h4> <br>
                                    

                                    <div class="row clearfix inputTopSpace">
                                       <div class="col-md-6">
                                        <label for="Nom">Nom du père: <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">edite</i>
                                                </span>
                                                <div class="form-line">
                                                 <input class="form-control" id="cpere" name="pere"  type="text" value="" />
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
                                                    <input type="radio" name="pere_vivant" value="1" id="pere_vivant_oui" class="with-gap radio-col-green" /><label for="pere_vivant_oui" class="col-black">&nbsp;Oui</label>&nbsp;&nbsp;&nbsp;
                                                    <input type="radio" name="pere_vivant" value="0" id="pere_vivant_non" class="with-gap radio-col-red" /><label for="pere_vivant_non" class="col-black">&nbsp;Non</label>
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
                                                 <input class="form-control" id="cmere" name="nom_mere"  type="text"/>
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
                                                    <input type="radio" name="mere_vivante" value="1" id="mere_vivante_oui" class="with-gap radio-col-green" /><label for="mere_vivante_oui" class="col-black">&nbsp;Oui</label>&nbsp;&nbsp;&nbsp;
                                                    <input type="radio" name="mere_vivante" value="0" id="mere_vivante_non" class="with-gap radio-col-red" /><label for="mere_vivante_non" class="col-black">&nbsp;Non</label>
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
                                                        <input type="radio" name="situation" value="CELIBATAIRE" id="celibataire" class="with-gap radio-col-green choix_situation" checked//><label for="celibataire" class="col-black">&nbsp;Célibataire</label>
                                                    </div>
                                            </div>   
                                            <div class="col-md-2">
                                                    <div class="form-line">
                                                        <input type="radio" name="situation" value="DIVORCE" id="divorce" class="with-gap radio-col-red choix_situation" /><label for="divorce" class="col-black">&nbsp;Divorcé(e)</label>
                                                         </div>
                                            </div>
                                            <div class="col-md-2">
                                                    <div class="form-line">
                                                        <input type="radio" name="situation" value="MARIE" id="marie" class="with-gap radio-col-red choix_situation" /><label for="marie" class="col-black">&nbsp;Marié(e) </label>
                                                                </div>
                                            </div>
                                             <div class="col-md-2">
                                                    <div class="form-line">
                                                        <input type="radio" name="situation" value="FIANCE" id="fiance" class="with-gap radio-col-red choix_situation" /><label for="fiance" class="col-black">&nbsp;Fiancé(e) </label>
                                                    </div>
                                            </div>
                                            <div class="col-md-2">
                                                    <div class="form-line">
                                                        <input type="radio" name="situation" value="VEUF" id="veuf" class="with-gap radio-col-red choix_situation" /><label for="veuf" class="col-black">&nbsp;Veuf/veuve  </label>
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
                                                      <input class="form-control" id="conjoint" name="conjoint" type="text"/>
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
                                                        <input type="radio" name="religion" value="EEC" id="r_eec" class="with-gap radio-col-green religion" checked//><label for="r_eec" class="col-black">&nbsp;EEC</label>&nbsp;&nbsp;&nbsp;
                                                        <input type="radio" name="religion" value="CATHOLIQUE" id="r_catholique" class="with-gap radio-col-red religion" /><label for="r_catholique" class="col-black">&nbsp;Catholique</label>&nbsp;&nbsp;&nbsp;
                                                        <input type="radio" name="religion" value="Autre" id="religion_autre" class="with-gap radio-col-red religion" /><label for="religion_autre" class="col-black">&nbsp;Autre réligion </label>&nbsp;&nbsp;&nbsp;
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
                                               <input class="form-control"  id="r_autre" type="text" name="religion_autre" placeholder="Préciser" />
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
                                                  <input class="form-control " id="nbre" type="number" name="nbre_enfant" min="0" max="100" value="" />
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
                                                    <input class="form-control " id="village" type="text" name="village" value="" required/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>  


                                    <div class="row clearfix inputTopSpace">
                                       <div class="col-md-6">
                                        <label for="coriginal">originaire de: <span class="required">*</span></label>
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

                                <!-- partie 3 -->
                                <div id="profile" class="tab-pane">
                                    <h4>Statut Professionnel</h4><br>

                                    <div class="row clearfix inputTopSpace">
                                               <div class="col-md-3">
                                                  <label for="inputSuccess">Activité ménée<span class="col-brown">*</span></label>
                                                </div>

                                                <div class="col-md-9">
                                                   <div class="form-line col-lg-2">
                                                      <input type="radio" name="statut_pro" value="ETUDIANT" id="cEtudiant" class="with-gap radio-col-green statut_pro" /><label for="cEtudiant" class="col-black">&nbsp;Etudiant </label>
                                                    </div>
                                                    <div class="form-line col-lg-2">
                                                      <input type="radio" name="statut_pro" value="FONCTIONNAIRE" id="cFonctionnaire" class="with-gap radio-col-green statut_pro" /><label for="cFonctionnaire" class="col-black">&nbsp;Fonctionnaire </label>
                                                    </div>
                                                    <div class="form-line col-lg-2">
                                                      <input type="radio" name="statut_pro" value="COMMERCANT" id="cCommercant" class="with-gap radio-col-green statut_pro" /><label for="cCommercant" class="col-black">&nbsp;Commerçant </label>
                                                    </div>  
                                                    <div class="form-line col-lg-2">
                                                      <input type="radio" name="statut_pro" value="SANS EMPLOI" id="cSansEmploi" class="with-gap radio-col-green statut_pro" /><label for="cSansEmploi" class="col-black">&nbsp;Sans Emploi </label>
                                                    </div> 
                                                    <div class="form-line col-lg-2">
                                                      <input type="radio" name="statut_pro" value="ACTIVITE LIBERALE" id="radio_50" class="with-gap radio-col-green statut_pro" /><label for="radio_50" class="col-black">&nbsp;Activité libérale</label>
                                                    </div>       
                                                    <div class="form-line col-lg-2">
                                                      <input type="radio" name="statut_pro" value="RETRAITE" id="cRetraite" class="with-gap radio-col-green statut_pro" /><label for="cRetraite" class="col-black">&nbsp;Retraité</label>
                                                    </div>
                                                   </div> 

                                            </div>
                                            <br>
                                             <div class="row clearfix inputTopSpace" id="etablissement" style="display: none;">
                                               <div class="col-md-4">
                                                <label for="cNbre">Etablissement : <span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="material-icons">edite</i>
                                                        </span>
                                                         <div class="form-line">
                                                          <input class="form-control" id="cetablissement" name="etablissement"  type="text"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                <label for="cVillage">Classe ou niveau: <span class="col-yellow">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="material-icons">edite</i>
                                                        </span>
                                                        <div class="form-line">
                                                            <input class="form-control" id="cniveau" name="niveau"  type="text"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                <label for="cVillage">serie/Filière: <span class="col-yellow">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="material-icons">edite</i>
                                                        </span>
                                                        <div class="form-line">
                                                            <input class="form-control" id="cfiliere" name="filiere"  type="text"/>
                                                        </div>
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
                                                          <input class="form-control" id="cprofession" name="profession"  type="text"/>
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
                                                            <input class="form-control" id="cemployeur" name="employeur"  type="text"/>
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

                                </div>

                                <!-- partie 4 -->
                                <div id="contact" class="tab-pane">
                                    <h4>Statut Paroissial</h4><br>

                                 <div class="row clearfix inputTopSpace" >
                                           <div class="col-md-6">
                                            <label for="cstaut">Statut : <span class="required">*</span></label>
                                            <select class="form-control" name="statut" required>           
                                                <option value="ANCIEN" class="statut" id="ancien">Ancien</option>
                                                <option value="CATECHUMENE" class="statut" id="catéchumène">Catéchumène</option>
                                                <option value="CONSEILLER" class="statut" id="conseille">Conseiller</option>
                                                <option value="CULTE D'ENFANT" class="statut" id="conseille">Culte d'Enfant</option>
                                                <option value="DIACRE" class="statut" id="diacre">Diacre</option>
                                                <option value="DIASPORA" class="statut" id="Diaspora">Diaspora</option>
                                                <option value="FIDELE" class="statut" id="Fidele">Fidèle</option>
                                                <option value="MONITEUR" class="statut" id="Moniteur">MONITEUR</option>
                                                <option value="PERSONNEL EMPLOYE" class="statut" id="perosnnel">PERSONNEL EMPLOYE</option>
                                            </select>
                                            </div>
                                            <div class="col-md-6">
                                            <label for="cVillage">Année d'inscription : <span class="col-yellow">*</span></label>
                                                <select class="form-control " id="cdate_ins" name="anneeMin_inscription">
                                                            
                                                    <?php 

                                                            while ($anneeMin <= $annee) {                       
                                                    ?>
                                                            <option value="<?php echo $anneeMin; ?>" class="date_inscript"><?php echo $anneeMin; ?>
                                                            </option>
                                                    <?php            
                                                                $anneeMin++;
                                                            }
                                                     ?>

                                                </select>
                                            </div>
                                    </div>
                                    <br> 

                                    <div class="row clearfix inputTopSpace">
                                       <div class="col-md-6">
                                            <div class="col-md-4">
                                                <label for="cNbre">Baptisé(e)? : <span class="required">*</span></label>
                                            </div>
                                            <div class="col-md-4">
                                                 <div class="form-line">
                                                  <input type="radio" name="baptise" value="1" id="is_baptise_oui" class="with-gap radio-col-green baptise" /><label for="is_baptise_oui" class="col-black">&nbsp;Oui</label>
                                                </div>
                                            </div> 
                                            <div class="col-md-4">
                                                 <div class="form-line">
                                                  <input type="radio" name="baptise" value="0" id="is_baptise_non" class="with-gap radio-col-green baptise" checked /><label for="is_baptise_non" class="col-black">&nbsp;Nom</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6"  id="baptise" style="display: none;">
                                            <div class="col-md-6">
                                                <label for="cdate_bapteme">Date :</label>
                                                 <div class="form-line">
                                                   <input class="form-control datepicker" id="cdate_bapteme" name="date_bapteme"  type="text" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="YYYY-MM-DD"/>
                                                </div>
                                            </div>                                                    
                                            <div class="col-md-6">
                                                <label for="clieu_bapteme">Lieu :</label>
                                                 <div class="form-line">
                                                   <input class="form-control" id="clieu_bapteme" name="lieu_bapteme"  type="text"/>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <br>

                                    <div class="row clearfix inputTopSpace">
                                       <div class="col-md-6">
                                            <div class="col-md-4">
                                                <label for="cNbre">Confirmé(e)? : <span class="required">*</span></label>
                                            </div>
                                            <div class="col-md-4">
                                                 <div class="form-line">
                                                  <input type="radio" name="confirme" value="1" id="is_confirme_oui" class="with-gap radio-col-green confirme" /><label for="is_confirme_oui" class="col-black">&nbsp;Oui</label>
                                                </div>
                                            </div> 
                                            <div class="col-md-4">
                                                 <div class="form-line">
                                                  <input type="radio" name="confirme" value="0" id="is_confirme_non" class="with-gap radio-col-green confirme" checked/><label for="is_confirme_non" class="col-black" >&nbsp;Nom</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6"  id="confirme" style="display: none;">
                                            <div class="col-md-6">
                                                <label for="cdate_confirme">Date :</label>
                                                 <div class="form-line">
                                                   <input class="form-control datepicker" id="cdate_confirme" name="date_confirme"  type="text" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="YYYY-MM-DD"/>
                                                </div>
                                            </div>                                                    
                                            <div class="col-md-6">
                                                <label for="clieu_confirme">Lieu :</label>
                                                 <div class="form-line">
                                                   <input class="form-control" id="clieu_confirme" name="lieu_confirme"  type="text"/>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <br>

                                   <div class="row clearfix inputTopSpace">
                                       <div class="col-md-6">
                                            <div class="col-md-4">
                                                <label for="cNbre">Fidèle Malade? : <span class="required">*</span></label>
                                            </div>
                                            <div class="col-md-4">
                                                 <div class="form-line">
                                                  <input type="radio" name="malade" value="1" id="is_malade_oui" class="with-gap radio-col-green malade" /><label for="is_malade_oui" class="col-black">&nbsp;Oui</label>
                                                </div>
                                            </div> 
                                            <div class="col-md-4">
                                                 <div class="form-line">
                                                  <input type="radio" name="malade" value="0" id="is_malade_non" class="with-gap radio-col-green malade" checked /><label for="is_malade_non" class="col-black">&nbsp;Nom</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6"  id="est_malade" style="display: none;">
                                            <div class="col-md-6">
                                                <label for="cdate_malade">Date :</label>
                                                 <div class="form-line">
                                                   <input class="form-control datepicker" id="cdate_maladie" name="date_maladie"  type="text" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="YYYY-MM-DD"/>
                                                </div>
                                            </div>                                                    
                                            <div class="col-md-6">
                                                <label for="cGuide_malade">Guide :</label>
                                                 <div class="form-line">
                                                   <input class="form-control" id="cguide" name="guide"  type="text"/>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <br>

                                    <div class="form-group">
                                        <div class="col-lg-offset-5 col-lg-10">
                                            <a class="btn btn-warning" id="annuler" href="listeFideles.php">Annuler</a>
                                            <button class="btn btn-primary" name="submit" type="submit">Mettre à jour
                                            </button><span class="envoi_en_cours" style="margin-left: 10px; display: none;">Enregistrement en cours...</span>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div> <!-- fin panel body-->
                      </form>
                    </div>
                </section>
            </div>
        </div>
    </section>



<Script>

            
                $('#chargement').hide();

               
                $('.afficher').on('click', function(af){

                        $('.loader').show();

                            af.preventDefault();
                           var $b = $(this);
                            url = $b.attr('href');

                           $('#main-content').load(url);
                        });

   
                $('#form_updateFidele').on('submit', function (e) {
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

                                alert('Fidèle modifié avec succès!');
                                $('.loader').show();
                                $('#main-content').load('listeFideles.php', function(){
                                    $('.envoi_en_cours').hide();
                                    $('.loader').hide();
                                });
                                
                            }
                            
                        }
                    });
                });

                 $('#form_updateFidele').find('input[name="photo"]').on('change', function (e) {
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
                        $('.loader').show();
                        $('#main-content').load('listeFideles.php', function(){
                            $('.loader').hide();
                        });
                    }
                    
                });         

            $('.statut_pro').on('click', function(){

                $x = $(this);
                if($x.val() == 'ETUDIANT' || $x.val() == 'SANS EMPLOI'){

                    $('#profession').hide();
                  
                }else{

                    $('#profession').show();
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

        if($x == 'Autre'){
           // $('#religion_autre').removeAttr('checked');

           
            $('#cAutre').show();

        }else{

             $('#cAutre').hide();
            
         //   $('#religion_autre').attr({'checked':'true'});

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

                var id = <?php echo $idpersonne; ?>;

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

                                    if(json.religion != 'EEC' && json.religion != 'CATHOLIQUE'){

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

           // $('.statut_parois').on('click', function(){

               

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

                            if($(this).val() == json.annee_enregistrement){

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
         //   });


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
    $(".select2").select2();

    $('.loader').hide();

</Script>