<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Creer un pasteur")){

            header('Location:index.php');

        }else{

            $selectGrade=$db->prepare("SELECT nomgrade, idgrade
                                        FROM grade
                                        WHERE lisible=1
                                        AND estpris=1");
            $selectGrade->execute();

            $selectzone = $db->prepare("SELECT idzone, nomzone, description
                                        FROM zone
                                        WHERE lisible = 1 ORDER BY nomzone");
            $selectzone->execute();

            $regions = $db->prepare("SELECT *
                                     FROM region
                                     WHERE lisible=1 ORDER BY region");
            $regions->execute();

          

            $n = 1;
            $annee = $_SESSION['annee'];
            $anneeMin = $annee - 30;
            $annee_creation = 0;


        }

    }else{
        header('Location:login.php');
    }
?>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="material-icons text-primary">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                <li><i class="material-icons text-primary">people</i><a href="#" class="col-blue"> Colège Pastoral</a> </li>
                <li><i class="material-icons text-primary">fiber_new</i><a href="#" class="col-blue"> Colège Pastoral</a> </li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <section class="panel">

               

                <form id="wizard_with_validatio" method="POST"   action="savePasteur.php" enctype="multipart/form-data">
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
                                                    <input class="form-control" value="pasteur <?php echo rand(0,100); ?>" id="cnom" name="nom"  type="text" required placeholder="Nom" />
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
                                                <input class="form-control" value="nom Pasteur <?php echo rand(0,100); ?>" id="cprenom" name="prenom" type="text" placeholder="Prenom(s)" />
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
                                                <input value="<?php echo rand(1960,2018);?>-<?php echo rand(0,12);?>-<?php echo rand(0,31);?>" class="form-control datepicker" id="dateNaiss" name="dateNaiss" type="text" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])"
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
                                                <input value="Dschang <?php echo rand(0,100); ?>" class="form-control" id="lieunaiss" name="lieunaiss" type="text" required />
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
                                                        <input value="69593077<?php echo rand(0,9); ?>" class="form-control " id="ctel" type="text" name="tel" required minlength="9" required />
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
                                                <input class="form-control " value="email<?php echo rand(0,999); ?>@email<?php echo rand(0,100); ?>.com"id="cemail" type="email" name="email" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br> 

                                    <div class="row clearfix inputTopSpace">
                                        <div class="col-md-4">
                                            <label for="Nom">Sexe: <span class="required">*</span></label>
                                            <select class="form-control " name="sexe" required>
                                                <option value="<?php $alpha = array( 
                                                           "Masculin", 
                                                            "Feminin"); $n=rand(0,1);  echo $alpha[$n]; ?>" selected><?php echo $alpha[$n];  ?></option>
                                                <option value="Feminin" id="Feminin">Feminin</option>
                                                <option value="Masculin" id="masculin">Masculin</option>   
                                            </select>
                                        </div>
                                       
                                        <div class="col-md-4">
                                            <label for="cquatier" class="control-label col-lg-4">Zone d'habitation<span class="required">*</span></label>
                                            <div class="input-group">
                                        
                                                <div class="form-line">
                                                    <select class="form-control zone" name="lieu" required>
                                                        
                                                        <?php
                                                        while($zones=$selectzone->fetch(PDO::FETCH_OBJ)){

                                                                $texte = str_replace(',', ';', $zones->description); 
                                                                $texte = str_replace(';', '|', $texte);
                                                                $texte = explode('|', $texte);
                                                                

                                                                for($i=0; $i<count($texte); $i++){
                                                            ?>  
                                                                    <option  value="<?php echo $zones->idzone;?>">
                                                            
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
                                                   <!--   <img src="" /> -->
                                                    <input  id="cphoto" type="file" name="photo" />
                                                </div>
                                            </div>
                                        </div>  
                                                                                
                                    </div>                                         
                                </div>
                                            
                                        

                            </fieldset>

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
                                                    <input class="form-control" value="papa <?php echo rand(0,100); ?>" id="cnom" name="pere"  type="text" rewuired/>
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
                                                    <input type="radio" checked name="pere_vivant" value="1" id="radio_34" class="with-gap radio-col-green" /><label for="radio_34" class="col-black">&nbsp;Oui</label>&nbsp;&nbsp;&nbsp;
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
                                                    <input class="form-control" value="papa <?php echo rand(0,100); ?>" id="cnom" name="mere"  type="text"/>
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
                                                    <input checked type="radio" name="mere_vivante" value="1" id="radio_36" class="with-gap radio-col-green" /><label for="radio_36"  class="col-black">&nbsp;Oui</label>&nbsp;&nbsp;&nbsp;
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
                                                    <input type="radio" checked name="situation" value="CELIBATAIRE" id="radio_38" class="with-gap radio-col-green choix_situation" checked/><label for="radio_38" class="col-black">&nbsp;Célibataire</label>
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
                                                    <input type="radio" name="religion" value="EEC" id="radio_43" class="with-gap radio-col-green religion" checked/><label for="radio_43" class="col-black">&nbsp;EEC</label>&nbsp;&nbsp;&nbsp;
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
                                                    <input class="form-control " value="Dschang <?php echo rand(0,100); ?>" id="cVllage" type="text" name="village" required/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br> 


                                    <div class="row clearfix inputTopSpace">
                                        <div class="col-md-6">
                                        <label for="coriginal">originaire de: <span class="required">*</span></label>
                                            <select class="form-control " name="region" id="region">
                                                
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
                                                <option selected value="20">KADEY</option>
                                                
                                                </select>
                                        </div> 

                                        <div class="col-md-3">
                                        <label for="cVillage">Arrondissement: <span class="col-brown">*</span></label>
                                                <select class="form-control" name="arrondissement" id="arrondissement"  required>
                                                     <option selected value="83">BATOURY</option>           
                                                </select>
                                        </div>
                                    </div>
                                    <br> 
                                </fieldset>

                                <h3>Statut Professionnel</h3>
                                <fieldset>
                                <div class="row clearfix inputTopSpace">
                                               <div class="col-md-3">
                                                  <label for="inputSuccess">Activité ménée<span class="col-brown">*</span></label>
                                                </div>
                                        <div class="col-lg-10">

                                        <div class="form-line col-lg-2">
                                                <input type="radio" id="radio_47" name="statut_pro" value="Fonctionnaire" onclick="add()" class="with-gap radio-col-green statut_pro"/>
                                                <label for="radio_47" class="col-black">&nbsp;Fonctionnaire </label>
                                        </div>
                                            <div  class="control-label col-lg-2">
                                                <input type="radio" id="1"name="statut_pro" onclick="add()" value="Commerçant" class="with-gap radio-col-green statut_pro"/>
                                                <label for="1">&nbsp;Commerçant</label>
                                            </div>
                                            <div  class="control-label col-lg-2">
                                                <input checked type="radio" id="2"name="statut_pro" onclick="add()"  value="Sans Emploi" class="with-gap radio-col-green statut_pro" />
                                                <label for="2">&nbsp;Sans Emploi</label>
                                            </div>
                                            <div  class="control-label col-lg-2">
                                                <input type="radio"id="3"name="statut_pro"  onclick="add()" value="Activité libérale" class="with-gap radio-col-green statut_pro" />
                                                <label for="3">&nbsp;Activité libérale</label>
                                            </div>
                                            <div  class="control-label col-lg-2">
                                                <input type="radio" id="4"name="statut_pro" onclick="add()" value="Retraité" class="with-gap radio-col-green statut_pro" />
                                                <label for="4">&nbsp;Retraité</label>
                                            </div>
                                        </div>
                                    </div>

                                   
                                    <br>       
                                    
                                    <div   id="employeur" style="display: none;">
                                        <div class="col-md-6">
                                            <label for="cNbre">Profession: <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">edite</i>
                                                </span>
                                                <div class="form-line">
                                                    <input input class="form-control" id="cprofession" name="profession"  type="text"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                        
                                            <label for="cNbre">Employeur: <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">edite</i>
                                                </span>
                                                <div class="form-line">
                                                    <input  class="form-control" id="cemployeur" name="employeur"  type="text"/>
                                                </div>
                                            </div>
                                        </div>
                                            <!-- <label class="control-label col-lg-2"></label>
                                            <div class="col-lg-4 form-line">
                                                <input class="form-control" id="cprofession" name="profession"  type="text"/>
                                            </div>
                                            <label  class="control-label col-lg-2">Employeur
                                            </label>
                                            <div class="col-lg-4">
                                                <input class="form-control" id="cemployeur" name="employeur"  type="text"/>
                                            </div> -->
                                    </div>

                                    <div  id="sans_emploi" style="display: none;">
                                        <div class="col-md-6">
                                            <label for="cNbre">Dernier diplome: <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">edite</i>
                                                </span>
                                                <div class="form-line">
                                                    <input value="Doplome <?php echo rand(1,10) ?>" class="form-control" id="cdiplome" name="diplome"  type="text"/>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="col-md-6">
                                    
                                        <label for="cNbre">Domaine: <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">edite</i>
                                            </span>
                                                <div class="form-line">
                                                <input class="form-control" value="Domaine <?php echo rand(1,10) ?>" id="cdomaine" name="domaine"  type="text"/>
                                            </div>
                                        </div>
                                    </div>
                                        <!-- <label class="control-label col-lg-2">Dernier diplôme</label>
                                        <div class="col-lg-4">
                                            <input class="form-control" id="cdiplome" name="diplome"  type="text"/>
                                        </div>
                                        <label  class="control-label col-lg-2">Domaine
                                        </label>
                                        <div class="col-lg-4">
                                            <input class="form-control" id="cdomaine" name="domaine"  type="text"/>
                                        </div>
                                    </div> -->
                                   
                                </div>

                               
                                </fieldset>

                                

                                <div class="form-group">
                                        <div class="col-lg-offset-5 col-lg-10">
                                        <a class="btn btn-warning annuler" href="listeFideles.php" id="jquer">Annuler</a>
                                        <button class="btn btn-primary" id = "catrine" name="submit" type="submit">Enregistrer</button><span class="envoi_en_cours" style="margin-left: 10px; display: none;">Enregistrement en cours...</span>
                                        
                                        </div>
                                </div>
                            </form>
            </section>
        </div>
    </div>
</section>

<script>

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
    
    //var anneeInscription = document.getElementById("anneeInscription").value;

        $('#chargement').hide();
        $(".tableau_dynamique").DataTable();

        $('#wizard_with_validatio').on('submit', function (e) {
                    // On empêche le navigateur de soumettre le formulaire
                    e.preventDefault();
             
                    var $form = $(this);
                    var formdata = (window.FormData) ? new FormData($form[0]) : null;
                    var data = (formdata !== null) ? formdata : $form.serialize();
                    
                    $('.envoi_en_cours').show();

                    $.ajax({
                        url: $form.attr('action'),
                        type: $form.attr('method'),
                        contentType: false, // obligatoire pour de l'upload
                        processData: false, // obligatoire pour de l'upload
                        dataType: 'json', // selon le retour attendu
                        data: data,
                        success: function(reponse) {

                            if(reponse != ''){

                                alert(reponse);
                                $('.envoi_en_cours').hide();

                            }else{

                                alert('Pasteur enregistré avec succès!');
                                $('.envoi_en_cours').show();
                                $('#main-content').load('nouveauPasteur.php', function(){
                                    $('.envoi_en_cours').hide();
                                });
                                
                            }
                        }
                    });
                });

                 $('#wizard_with_validatio').find('input[name="photo"]').on('change', function (e) {
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
                       // $image_preview.find('h4').html(file.size +' bytes');
                    }
                });



/*  $('#form-addFidele').on('submit', function(e){

 e.preventDefault();
 var $form = $(this);
 $form.find('button').text('Traitement');
 $tof = $('#cphoto').val();

 url = ("saveFidele.php?file="+$tof);


 $.post(url, $form.serializeArray())

 .done(function(data, text, jqxhr){

 alert('Fidèle enregistré avec succès!');
 //$('#main-content').load('nouveauFidele.php');

 })
 .fail(function(jqxhr){
 alert(jqxhr.responseText);
 })
 .always(function(){
 $form.find('button').text('Enregistrer');
 });

 });
 */

$('.annuler').on('click', function(e){

    e.preventDefault();
    var $link = $(this);
    target = $link.attr('href');
    if(window.confirm("Voulez-vous vraiment annuler?")){

        $('#main-content').load(target);
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
    if($x == 'Célibataire'){

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

 $('#sans_emploi').show();

$('.statut_pro').on('click', function(){

    var x = $(this).val();

    if(x == 'Etudiant'){

        $('#etablissement').show();
        $('#employeur').hide();
        $('#sans_emploi').show();

    }else if(x=='Commerçant' || x=='Sans Emploi'){

        $('#etablissement').hide();
        $('#employeur').hide();
        $('#sans_emploi').show();

    }else{

        $('#etablissement').hide();
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



$( ".datepicker" ).datepicker({

    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd'
});

$('.loader').hide();
</script>




  


