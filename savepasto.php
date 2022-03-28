<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Creer un fidele")){

            header('Location:index.php');

        }else{

            $selectzone = $db->prepare("SELECT * FROM zone where lisible = 1 ORDER BY nomzone");
            $selectzone->execute();

            $regions = $db->prepare("SELECT * FROM region where lisible=1 ORDER BY region");
            $regions->execute();

            $selectGroupe = $db->prepare("SELECT idgroupe, nomgroupe, typegroupe, YEAR(datecreation) as annee_creation FROM groupe where lisible = 1 AND typegroupe != 'Anciens' ORDER BY nomgroupe ASC");
            $selectGroupe->execute();

            $n = 1;
            $annee = $_SESSION['annee'];
            $anneeMin = $annee - 30;
            $annee_creation = 0;

            function tri($temps){

                for($i=1;$i<$n;$i++){
            
                    for($j=0;$j<$i;$j++){
                        if(  $temps[$j] <  $temps[$i]){
                            $tamp = $temps[$j];
                            $temps[$j] = $temps[$i];
                            $temps[$i] = $tamp;
                        }
                    }
                }

                return $temps;
            }

        }

    }else{
        header('Location:login.php');
    }
?>
    <section class="wrapper">
        
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li class="col-blue"><i class="material-icons">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                    <li class="col-blue"><i class="material-icons">people</i><a href="#" class="col-blue"> Fidèles</a></li>
                    <li class="col-blue"><i class="material-icons">people</i><a href="#" class="col-blue"> Nouveau Fidèle</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
               
                    <div class="form">
                        <form class="form-validate form-horizontal"  id="wizard_with_validatio" method="POST" enctype="multipart/form-data" action="savePasteur.php">
                            <h3>Informations personelles</h3>
                                    <fieldset>
                                <div class="row clearfix inputTopSpace">
                                        <div class="col-md-6">
                                        <label for="Nom">Nom(s): <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">edite</i>
                                                </span>
                                                <div class="form-line">
                                                    <input class="form-control" id="cnom" name="nom"  type="text" required placeholder="Nom" />
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
                                                <input class="form-control" id="cprenom" name="prenom" type="text" placeholder="Prenom(s)" />
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
                                                <input class="form-control datepicker" id="datenaiss" name="dateNaiss" type="text" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])"
                                                    placeholder="YYYY-MM-DD"  required  />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                        <label for="Nom">lieu de naissance: <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">location_on</i>
                                                </span>
                                                <div class="form-line">
                                                <input class="form-control" id="lieunaiss" name="lieunaiss" type="text" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>   

                                    <div class="row clearfix inputTopSpace">
                                        <div class="col-md-6">
                                        <label for="ctel">Téléphone<span class="required">*</span>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">call</i>
                                                </span>
                                                <div class="form-line">
                                                        <input class="form-control " id="ctel" type="text" name="tel" required minlength="9" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                        <label for="cemail">E-Mail<span class="required">*</span>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">email</i>
                                                </span>
                                                <div class="form-line">
                                                <input class="form-control " id="cemail" type="email" name="email" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br> 

                                    <div class="row clearfix inputTopSpace">
                                        <div class="col-md-4">
                                            <label for="Nom">Sexe: <span class="required">*</span></label>
                                            <select class="form-control " name="sexe" required>
                                                <option disabled selected>Sélectionner le sexe</option>
                                                <option value="Feminin" id="Feminin">Feminin</option>
                                                <option value="Masculin" id="masculin">Masculin</option>   
                                            </select>
                                        </div>
                                       
                                        <div class="col-md-4">
                                            <label for="cquatier" class="control-label col-lg-4">Zone d'habitation<span class="required">*</span></label>
                                            <div class="input-group">
                                        
                                                <div class="form-line">
                                                    <select class="form-control zone" name="lieu" required>
                                                        <option disabled selected>Sélectionner la zone d'habitation</option>
                                                        <?php
                                                        while($zones=$selectzone->fetch(PDO::FETCH_OBJ)){

                                                                $texte = str_replace(',', ';', $zones->description); 
                                                                $texte = str_replace(';', '|', $texte);
                                                                $texte = explode('|', $texte);
                                                                

                                                                for($i=0; $i<count($texte); $i++){
                                                            ?>  
                                                                    <option value="<?php echo $zones->idzone;?>">
                                                            
                                                                        <?php echo $zones->nomzone.': '.$texte[$i]; ?>

                                                                    </option>    
                                                            <?php
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 ">
                                            <label for="cgrade" class="control-label col-lg-4">Grade<span class="required">*</span></label>
                                            <div class="col-lg-10">
                                                <select class="form-control" name="grade" >
                                                    <option disabled selected>Choisir un grade</option>
                                                    <?php
                                                    while($grade=$selectGrade->fetch(PDO::FETCH_OBJ)){
                                                    ?>
                                                        <option value="<?php echo $grade->idgrade;?>"><?php echo $grade->nomgrade;?></option>
                                                    <?php
                                                    }
                                                    $db=NULL;
                                                    ?>

                                                </select>
                                            </div>
                                        </div>
                                  
                                    

                                   
                                    <br>  
                                    <div class="form-group ">
                                    
                                        <div class="col-md-6">
                                            <label for="Nom">Photo: <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">insert_photo</i>
                                                </span>
                                                <div class="form-line col-green"  id="image_preview">
                                                    <img src="" />
                                                    <input  id="cphoto" type="file" name="photo" />
                                                </div>
                                            </div>
                                        </div>  
                                                                                
                                    </div>                                         
                                </div>
                                            
                                        

                            </fieldset>

                                    <!-- parrtie 2 -->
                                    <h3>Etat Civil</h3>                                  
                                    <fieldset>
                                    <div class="row clearfix inputTopSpace">
                                               <div class="col-md-6">
                                                <label for="Nom">Nom du père: <span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="material-icons">edite</i>
                                                        </span>
                                                        <div class="form-line">
                                                         <input class="form-control" id="cnom" name="pere"  type="text"/>
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
                                                            <input type="radio" name="pere_vivant" value="1" id="radio_34" class="with-gap radio-col-green" /><label for="radio_34" class="col-black">&nbsp;Oui</label>&nbsp;&nbsp;&nbsp;
                                                            <input type="radio" name="pere_vivant" value="0" id="radio_35" class="with-gap radio-col-red" /><label for="radio_35" class="col-black">&nbsp;Non</label>
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
                                                         <input class="form-control" id="cnom" name="mere"  type="text"/>
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
                                                            <input type="radio" name="mere_vivante" value="1" id="radio_36" class="with-gap radio-col-green" /><label for="radio_36" class="col-black">&nbsp;Oui</label>&nbsp;&nbsp;&nbsp;
                                                            <input type="radio" name="mere_vivante" value="0" id="radio_37" class="with-gap radio-col-red" /><label for="radio_37" class="col-black">&nbsp;Non</label>
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
                                                                <input type="radio" name="situation" value="CELIBATAIRE" id="radio_38" class="with-gap radio-col-green choix_situation" checked//><label for="radio_38" class="col-black">&nbsp;Célibataire</label>
                                                            </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                            <div class="form-line">
                                                                <input type="radio" name="situation" value="DIVORCE" id="radio_39" class="with-gap radio-col-red choix_situation" /><label for="radio_39" class="col-black">&nbsp;Divorcé(e)</label>
                                                                 </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                            <div class="form-line">
                                                                <input type="radio" name="situation" value="MARIE" id="radio_40" class="with-gap radio-col-red choix_situation" /><label for="radio_40" class="col-black">&nbsp;Marié(e) </label>
                                                                        </div>
                                                    </div>
                                                     <div class="col-md-2">
                                                            <div class="form-line">
                                                                <input type="radio" name="situation" value="FIANCE" id="radio_41" class="with-gap radio-col-red choix_situation" /><label for="radio_41" class="col-black">&nbsp;Fiancé(e) </label>
                                                            </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                            <div class="form-line">
                                                                <input type="radio" name="situation" value="VEUF" id="radio_42" class="with-gap radio-col-red choix_situation" /><label for="radio_42" class="col-black">&nbsp;Veuf/veuve  </label>
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
                                                              <input class="form-control" id="cConjoint" name="conjoint" type="text"/>
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
                                                                <input type="radio" name="religion" value="Autre" id="radio_45" class="with-gap radio-col-red religion" /><label for="radio_45" class="col-black">&nbsp;Autre réligion </label>&nbsp;&nbsp;&nbsp;
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
                                                          <input class="form-control " id="cnbre" type="number" name="nbre_enfant" value="0" min="0" max="100" />
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
                                                            <input class="form-control " id="cVllage" type="text" name="village" required/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br> 


                                            <div class="row clearfix inputTopSpace">
                                               <div class="col-md-6">
                                                <label for="coriginal">originaire de: <span class="required">*</span></label>
                                                    <select class="form-control " name="region" id="region">
                                                        <option disabled selected>--Région--</option>
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
                                                        <option disabled selected>--Département--</option>
                                                        
                                                       </select>
                                                </div> 

                                                <div class="col-md-3">
                                                <label for="cVillage">Arrondissement: <span class="col-brown">*</span></label>
                                                        <select class="form-control" name="arrondissement" id="arrondissement"  required>
                                                                       
                                                       </select>
                                                </div>
                                            </div>
                                            <br> 
                                    </fieldset>

                                    <!-- parrtie3 -->
                                    <h3>Satut professionnel</h3>
                                    <fieldset>
                                    <div class="row clearfix inputTopSpace">
                                               <div class="col-md-3">
                                                  <label for="inputSuccess">Activité ménée<span class="col-brown">*</span></label>
                                                </div>

                                                <div class="col-md-9">
                                                   
                                                    <div class="form-line col-lg-2">
                                                      <input type="radio" onclick ="add()" name="statut_pro" value="FONCTIONNAIRE" id="radio_47" class="with-gap radio-col-green statut_pro" /><label for="radio_47" class="col-black">&nbsp;Fonctionnaire </label>
                                                    </div>
                                                    <div class="form-line col-lg-2">
                                                      <input type="radio"onclick ="add()" name="statut_pro" value="COMMERCANT" id="radio_48" class="with-gap radio-col-green statut_pro" /><label for="radio_48" class="col-black">&nbsp;Commerçant </label>
                                                    </div>  
                                                    <div class="form-line col-lg-2">
                                                      <input type="radio" onclick ="add()" name="statut_pro" value="SANS EMPLOI" id="radio_49" class="with-gap radio-col-green statut_pro" /><label for="radio_49" class="col-black">&nbsp;Sans Emploi </label>
                                                    </div> 
                                                    <div class="form-line col-lg-2">
                                                      <input type="radio" onclick ="add()" name="statut_pro" value="ACTIVITE LIBERALE" id="radio_50" class="with-gap radio-col-green statut_pro" /><label for="radio_50" class="col-black">&nbsp;Activité libérale</label>
                                                    </div>       
                                                    <div class="form-line col-lg-2">
                                                      <input type="radio" onclick ="add()" name="statut_pro" value="RETRAITE" id="radio_51" class="with-gap radio-col-green statut_pro" /><label for="radio_51" class="col-black">&nbsp;Retraité</label>
                                                    </div>
                                                   </div> 

                                            </div>
                                            <br> 

                                            <div class="row clearfix inputTopSpace" id="etablissements" style="display: none;">
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
                                    </fieldset>


                                    <!-- partie 4444444 -->



                                    <!-- partie4 -->
                                    

                                        <div class="form-group">
                                            <div class="col-lg-offset-5 col-lg-10">
                                                <a class="btn btn-warning annuler" id="jquer" href="listeFideles.php">Annuler</a>
                                               
                                            </div>
                                        </div>

                         

                                    <!-- fin des parties du formulaire-->
                                
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </section>

    <script>

    //var catrine1 = $('#choixselect').val();
    //var catrine2 = $('#anneeInscription').val();

     var radio = $('input[name="statut_pro"]:checked').val();
    function add()
        {
            if($('#catrine').length == 0) {

            $('#jquer').after(
            $(' <button class="btn btn-primary" id = "catrine" name="submit" type="submit">Enregistrer</button><span class="envoi_en_cours" style="margin-left: 10px; display: none;">Enregistrement en cours...</span>'
            ));
        }
        }


</script>





    <script>
                                                $(function () {
                                                                    //Horizontal form basic
                                                                 

                                                                    //Advanced form with validation
                                                                    var form = $('#wizard_with_validatio').show();
                                                                    form.steps({
                                                                        headerTag: 'h3',
                                                                        bodyTag: 'fieldset',
                                                                        transitionEffect: 'slideLeft',
                                                                        onInit: function (event, currentIndex) {
                                                                            $.AdminBSB.input.activate();

                                                                            //Set tab width
                                                                            var $tab = $(event.currentTarget).find('ul[role="tablist"] li');
                                                                            var tabCount = $tab.length;
                                                                            $tab.css('width', (100 / tabCount) + '%');

                                                                            //set button waves effect
                                                                            setButtonWavesEffect(event);
                                                                        },
                                                                        onStepChanging: function (event, currentIndex, newIndex) {
                                                                            if (currentIndex > newIndex) { return true; }

                                                                            if (currentIndex < newIndex) {
                                                                                form.find('.body:eq(' + newIndex + ') label.error').remove();
                                                                                form.find('.body:eq(' + newIndex + ') .error').removeClass('error');
                                                                            }

                                                                            form.validate().settings.ignore = ':disabled,:hidden';
                                                                            return form.valid();
                                                                        },
                                                                        onStepChanged: function (event, currentIndex, priorIndex) {
                                                                            setButtonWavesEffect(event);
                                                                        },
                                                                        onFinishing: function (event, currentIndex) {
                                                                            form.validate().settings.ignore = ':disabled';
                                                                            return form.valid();
                                                                        },
                                                                        onFinished: function (event, currentIndex) {
                                                                            swal("Good job!", "Submitted!", "success");
                                                                        }
                                                                    });

                                                                    form.validate({
                                                                        highlight: function (input) {
                                                                            $(input).parents('.form-line').addClass('error');
                                                                        },
                                                                        unhighlight: function (input) {
                                                                            $(input).parents('.form-line').removeClass('error');
                                                                        },
                                                                        errorPlacement: function (error, element) {
                                                                            $(element).parents('.form-group').append(error);
                                                                        },
                                                                        rules: {
                                                                            'confirm': {
                                                                                equalTo: '#password'
                                                                            }
                                                                        }
                                                                    });
                                                                });

                                                                function setButtonWavesEffect(event) {
                                                                    $(event.currentTarget).find('[role="menu"] li a').removeClass('waves-effect');
                                                                    $(event.currentTarget).find('[role="menu"] li:not(.disabled) a').addClass('waves-effect');
                                                                }
        </script>


 
<script>

    $(window).load(function() {
        
        $(".loader").fadeOut("1000");
    })
    
   

                   
    

                $('.annuler').on('click', function(e){

                    e.preventDefault();
                    var $link = $(this);
                    target = $link.attr('href');
                    if(window.confirm("Voulez-vous vraiment annuler?")){
                        $('.loader').show();
                        $('#main-content').load(target, function(){
                            $('.loader').hide();
                        });
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

        if($x != 'Autre'){

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
                    $('#departement').append('<option disabled selected>--Département--</option>');
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
                     $('#arrondissement').append('<option disabled selected>--Arrondissement--</option>');                   
                    $.each(json, function(index, val){
                        $('#arrondissement').append('<option value="'+index+'">'+val+'</option>')
                    });
                }
            });
        }
    });

    $('.statut_pro').on('click', function(){

        var x = $(this).val();

        if(x == 'ETUDIANT'){

            $('#etablissements').show();
            $('#employeur').hide();  
           $('#sans_emploi').show();

        }else if(x=='COMMERCANT' || x=='SANS EMPLOI'){

            $('#etablissements').hide();
            $('#employeur').hide();  
            $('#sans_emploi').show();

        }else{

            $('#etablissements').hide();
            $('#employeur').show();  
            $('#sans_emploi').show();
        }
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

    $('.test').on('click', function(e){

        alert($(this).val());
    });

    $('.datepicker').datepicker({
        autoclose: true
    });
    $(".select2").select2();
    $('.loader').hide();
</script>

<script type="text/javascript">
    
</script>
