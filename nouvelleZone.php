<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Creer un zone")){

            header('Location:index.php');

        }else{

            

        }
        
    }else{
        header('Location:login.php');
    }
?>


    <section class="wrapper">
        
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li class="col-blue"><i class="material-icons">home </i><a href="index.php" class="col-blue"> Accueil</a></li>
                    <li class="col-blue"><i class="material-icons">people</i> Fidèles</li>
                    <li class="col-blue"><i class="material-icons">location_on </i><a href="#" class="col-blue"> Nouvelle zone</a></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading text-center h4">
                        Enregistrement d'une nouvelle zone
                    </header>
                    <div class="panel-body">
                        <div class="form">
                            <form class="form-validate form-horizontal" id="form-addZone" method="POST" action="saveZone.php">
                                <div class="row clearfix inputTopSpace">
                                   <div class="col-md-6">
                                    <label for="cnomzone">Nom zone: <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">edite</i>
                                            </span>
                                             <div class="form-line">
                                              <input class="form-control " id="cnomzone" type="text" name="nomzone" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    <label for="cdescription">Description : <span class="col-yellow">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">edite</i>
                                            </span>
                                            <div class="form-line">
                                                 <input class="form-control " id="cnomzone" type="text" name="description" placeholder="Saisir les quartiers en les séparant par des virgules(;)" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br> 

                                <div class="form-group">
                                    <div class="col-lg-offset-5 col-lg-10">
                                        <a class="btn btn-warning annuler" href="listezones.php">Annuler</a>
                                      <button class="btn btn-primary" name="submit" type="submit">Enregistrer</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </section>
            </div>
        </div>
    </section>
 <script>
            
                        $('#form-addZone').on('submit', function(e){

                              e.preventDefault();
                                var $form = $(this);
                                $form.find('button').text('Traitement');
                             
                                url = $form.attr('action');

                               
                $.post(url, $form.serializeArray())
 
                    .done(function(data, text, jqxhr){  
                        
                        alert('Zone enregistrée avec succès!');
                        $('.loader').show();
                        $('#main-content').load('nouvellezone.php', function(){
                            $('.loader').hide();
                        });
                        
                    })
                    .fail(function(jqxhr){
                        alert(jqxhr.responseText);
                    })
                    .always(function(){
                        $form.find('button').text('Enregistrer');
                    });
                                
                            });

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
					$('.loader').hide();
     </script>

