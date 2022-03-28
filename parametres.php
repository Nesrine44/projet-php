<?php
    session_start();

    if(isset($_SESSION['login']) && isset($_SESSION['annee'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');
        require_once('includes/connexionDefault.php');

        if(!has_Droit($idUser, "Editer parametres")){

            header('Location:index.php');

        }else{

            $annee_encours = $_SESSION['annee'];                                       
            $new_annee = $annee_encours + 1;
            $base_new = "paroisse".$new_annee;
            $etat = 0;

            $parametre = null;

             $parametres = $db->prepare("SELECT * from parametre where idparametre = 1");
            $parametres->execute();

            while ($x=$parametres->fetch(PDO::FETCH_OBJ)) {
                
                $parametre = $x;
            }
            try{
                /*
                $transfert = $root->prepare("SELECT etat FROM base where annee = $annee_encours");
                $transfert->execute();
                $etat = 0;

                while($x=$transfert->fetch(PDO::FETCH_OBJ)){

                    $etat = $x->etat;
                }
            */
            }catch(Exception $ex){
                echo $ex;        
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
                    <li class="col-blue"><i class="material-icons">people</i><a href="#" class="col-blue"> Paramètres</a></li>             
                </ol>
            </div>
        </div>  

        <div class="row">
            <div class="col-lg-12">
                <section class="panel">

                   <header class="panel-heading tab-bg-primary ">
                              <ul class="nav nav-tabs">
                                  <li class="active">
                                      <a data-toggle="tab" href="#home" class="h4">Paramètres Systèmes</a>
                                  </li>
                                  <li class="">
                                      <a data-toggle="tab" href="#about" class="h4">Nouvelle année</a>
                                  </li>                                                   
                              </ul>
                    </header>
                    <div class="panel-body">
                        <div class="tab-content">
                            <!-- partie 1 -->
                            <div id="home" class="tab-pane active"><br>                            

                                <div class="form">
                                    <form class="form-validate form-horizontal" id="form-addParametres" method="POST" enctype="multipart/form-data" action="saveParametres.php">

                                        <fieldset>
                                            <legend class="h4 col-green">
                                                Configuration du système
                                            </legend>
                                            <div class="row clearfix inputTopSpace">
                                               <div class="col-md-6">
                                                <label for="Nom">Sigle: <span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="material-icons">edite</i>
                                                        </span>
                                                        <div class="form-line">
                                                            <input class="form-control" id="csigle" name="sigle"  type="text" required placeholder="Sigle de la paroisse"  value="<?php  echo $parametre->sigle;?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                <label for="Nom">Dénomination: <span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="material-icons">edite</i>
                                                        </span>
                                                        <div class="form-line">
                                                            <input class="form-control" id="cnom" name="nom_paroisse"  type="text" required  placeholder="Nom de votre paroisse" value="<?php  echo $parametre->nom;?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>

                                            <div class="row clearfix inputTopSpace">
                                               <div class="col-md-6">
                                                <label for="Nom">Boîte Postale: <span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="material-icons">card_travel</i>
                                                        </span>
                                                        <div class="form-line">
                                                            <input class="form-control" id="cboite" name="bp"  type="text" placeholder="Boîte postale" pattern="[0-9]*" value="<?php  echo $parametre->bp;?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                <label for="Nom">Ville: <span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="material-icons">location_on</i>
                                                        </span>
                                                        <div class="form-line">
                                                            <input class="form-control" id="cville" name="ville"  type="text" placeholder="Ville" value="<?php  echo $parametre->ville;?>"  required />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>

                                            <div class="row clearfix inputTopSpace">
                                               <div class="col-md-6">
                                                <label for="Nom">Email: <span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="material-icons">email</i>
                                                        </span>
                                                        <div class="form-line">
                                                            <input class="form-control" id="cemail" name="email_paroisse"  type="text" placeholder="exemple@exemple.com" pattern="^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$" required value="<?php  echo $parametre->email_paroisse;?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                <label for="Nom">Site web: <span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="material-icons">find_in_page</i>
                                                        </span>
                                                        <div class="form-line">
                                                            <input class="form-control" id="csite" name="site_web"  type="text" placeholder="www.exemple.com" value="<?php  echo $parametre->site_web;?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>                                 

                                            <div class="row clearfix inputTopSpace">
                                               <div class="col-md-6">
                                                <label for="Nom">Siège: <span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="material-icons">edite</i>
                                                        </span>
                                                        <div class="form-line">
                                                            <input class="form-control" id="csiege" name="siege"  type="text" required placeholder="Siège de la paroisse" value="<?php  echo $parametre->siege;?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                <label for="Nom">Téléphone: <span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="material-icons">call</i>
                                                        </span>
                                                        <div class="form-line">
                                                            <input class="form-control" id="ctelephone" name="telephone"  type="text" placeholder="676544214" value="<?php  echo $parametre->telephone;?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>

                                            <div class="row clearfix inputTopSpace">
                                               <div class="col-md-6">
                                                <label for="Nom">Date de création: <span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="material-icons">perm_contact_calendar</i>
                                                        </span>
                                                        <div class="form-line">
                                                            <input class="form-control" id="cdate" name="date_paroisse"  type="text" placeholder="yyyy/MM/dd" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" value="<?php  echo $parametre->date_paroisse;?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                <label for="Nom">Logo: <span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="material-icons">insert_photo</i>
                                                        </span>
                                                        <div class="form-line">
                                                            <input  id="clogo" type="file" name="logo" value="<?php  echo $parametre->logo;?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                        </fieldset><br>

                                        <fieldset><!-- 
                                            <legend>
                                                Action
                                            </legend> -->
                                            <div class="form-group">
                                                <div class="col-lg-offset-5 col-lg-10">
                                                    <a class="btn btn-warning annuler">Annuler</a>
                                                    <button class="btn btn-primary" name="submit" type="submit">Enregistrer</button><span class="envoi_en_cours" style="margin-left: 10px; display: none;">Enregistrement en cours...</span>
                                                </div>
                                            </div>
                                        </fieldset>

                                    </form>
                                </div>                                
                            </div>

                            <!-- partie 2 -->
                            <div id="about" class="tab-pane"><br>
                                                           
                                <div class="form">
                                    <form class="form-validate form-horizontal" id="form-addParametres" method="POST" enctype="multipart/form-data" action="">

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div classs="col-lg-7">
                                                        <div class="alert alert-success fade in col-lg-10" id="success" style="display: none; margin-left:7%; margin-right: 7%;">
                                                            <button data-dismiss="alert" class="close close-sm" type="button">
                                                                <i class="icon-remove"></i>
                                                            </button>
                                                            <strong>Terminée! </strong> <label class="resultat"></label>
                                                        </div>      
                                                        <!-- En cas d'échec -->
                                                        <div class="alert alert-block alert-danger fade in col-lg-10" id="echec" style="display: none; margin-left:7%; margin-right: 7%;">
                                                          <button data-dismiss="alert" class="close close-sm" type="button">
                                                              <i class="icon-remove"></i>
                                                          </button>
                                                          <strong>Désolé! </strong> <label class="resultat"></label>
                                                      </div>  
                                                  </div> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-12">   
                                                <div class="col-lg-4">
                                                    <a href="nouvelleAnnee.php" class="btn btn-primary nouvelle col-lg-5" title="Créer un instance pour la nouvelle année">
                                                        Nouvelle année
                                                    </a>
                                                </div>
                                                        
                                             
                                           
                                                <div class="col-lg-4">
                                                    <a  class="btn btn-primary activation col-lg-5" title="Activer les fidèles pour la nouvelle année" <?php if(!$etat){echo 'disabled';}else{echo '';} ?>>
                                                            Activation
                                                    </a>
                                                </div>
                                            </div> 
                                        </div>
                                      </div>
                                    </form>
                                </div>                             
                            </div>
                        </div>
                    </div>

                </section>
            </div>
        </div>
    
    </section>



<script type="text/javascript">    

    $('#form-addParametres').on('submit', function(e){
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

                            if(reponse == '0'){

                                alert("Echec de mise à jour");
                                $('.envoi_en_cours').hide();

                            }else if(reponse == '1'){

                                alert('Paramètres enregistrés avec succès!');
                                document.location.replace("index.php");
                                $('.envoi_en_cours').hide();
                            }else{

                                alert(reponse);
                                $('.envoi_en_cours').hide();
                            }
                        }
                    });
                });

            $('#form-addParametres').find('input[name="logo"]').on('change', function (e) {
                    var files = $(this)[0].files;
             
                    if (files.length > 0) {
                        // On part du principe qu'il n'y qu'un seul fichier
                        // étant donné que l'on a pas renseigné l'attribut "multiple"
                        var file = files[0],
                          $image_preview = $('#image_preview');
             
                        // Ici on injecte les informations recoltées sur le fichier pour l'utilisateur
                       // $image_preview.find('.thumbnail').removeClass('hidden');
                      //  $image_preview.find('img').attr('src', window.URL.createObjectURL(file));
                        //$image_preview.find('h4').html(file.name);
                       // $image_preview.find('h4').html(file.size +' bytes');
                    }
                });

        $('.btn-default').on('click', function(e){

                    e.preventDefault();
                    
                    if(window.confirm("Voulez-vous vraiment annuler?")){

                       document.location.replace("index.php");
                    }
                    
        });     

    $('.nouvelle').on('click', function(e){

        e.preventDefault();

        if(window.confirm("Voulez-vous vraiment créer une nouvelle année?")){

            $('.loader').show();

            

            $.ajax({

                url:'nouvelleAnnee.php',
                data:'',
                dataType:'json',
                success:function(json){

                    if(!json){

                        $('.resultat').html("Année créee avec succès!!!");
                        $('#success').show();
                        $('#echec').hide();
                        $('.loader').hide();
                        $('.activation').removeAttr('disabled');

                    }else{

                        $('.resultat').html(json);
                        $('#success').hide();
                        $('#echec').show();
                        $('.loader').hide();
                       // $('.transfert').hide();

                    }
                    
                }
            });
        }
        
    });

    $('.transert').on('click', function(e){

        e.preventDefault();

        if(window.confirm("Voulez-vous trnaférer ces données?")){          

            $('.loader').show();
            //url = (this).attr('href');
            

            $.ajax({

                url:'transfert.php',
                data:'',
                dataType:'json',
                success:function(json){

                    if(!json){

                        $('.resultat').html("Transfert effectué avec succès!!!");
                        $('#success').show();
                        $('#echec').hide();
                         $('.loader').hide();
                         $('.activation').removeAttr('disabled');

                    }else{

                        $('.resultat').html(json);
                        $('#success').hide();
                        $('#echec').show();
                         $('.loader').hide();

                    }
                    
                }
            });
        }
    });

    $('.activation').on('click', function(e){

        e.preventDefault();
        $('.loader').show();
        $("#main-content").load('activationFidele.php');
        $('.loader').hide();
    });

    $('.loader').hide();
</script>
