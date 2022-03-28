<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];

        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Creer un groupe")){

            header('Location:index.php');

        }

      
    }else{
        header('Location:login.php');
    }
?>

    <section class="wrapper">
        
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li> <i class="material-icons text-primary">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                    <li> <i class="material-icons text-primary" >group</i> <a href="#"class="col-blue">Groupes</a> </li>
                    <li> <i class="material-icons text-primary">group_add</i> <a href="#"class="col-blue">Nouveau Groupe</a></li>
                </ol>
            </div>
        </div>


        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header  text-center h4">
              
                        Enregistrement d'un nouveau groupe
                    
                </div>
                <div class="body">
           
                    <form class="form-validate form-horizontal" id="form_addGroupe" method="POST" enctype="multipart/form-data" action="saveGroupe.php">
                       
                        <h2 class="card-inside-title">Nom du groupe <span class="required">*</span></h2>
                        <div class="row clearfix">
                        
                            <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
                                <div class="input-group">
                                  
                                    <div class="form-line">
                                        <input input class="form-control" id="cnomGroupe" name="nomGroupe" minlength="5" type="text" required placeholder="Ce champs est requis">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <h2 class="card-inside-title">Date de création</h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons text-primary">event</i> 
                                    </span>
                                    
                                    <div class="form-line">
                                    <input style="cursor: pointer" class="form-control datepicker" id="cdateCreation" name="dateCreation"  type="text" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="YYYY-MM-DD" />                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <h2 class="card-inside-title">
                            Type de groupe
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons ">group</i> 
                                    </span>
                                    <select class="form-control " name="typeGroupe">
                                        <option desabled selected="selected">--- Selectionner ---</option>
                                            <option value="Chorale" id="chorale" >Chorale</option>
                                            <option value="Mouvement" id="mouvement">Mouvement</option>
                                            <option value="Anciens" id="anciens">Anciens</option>
                                            <option value="Informel" id="informel">Informel</option>
                                    </select>
                                </div>
                            </div>
                    
                            <div class="form-group">
                                <div class="col-lg-offset-5 col-lg-10">
                                      <a class="btn btn-warning annuler"  href="listeGroupe.php">Annuler</a>
                                      <button class="btn btn-primary" name="submit" type="submit">Enregistrer</button>
                                </div>
                            </div>
                        </div>                             
                    </form>
                </div>
                
            </div>
  </section>
   

 <script>
                        $('#form_addGroupe').on('submit', function(e){

                              e.preventDefault();
                                var $form = $(this);
                                $form.find('button').text('Traitement');
                             
                                url = $form.attr('action');

                               
                $.post(url, $form.serializeArray())
 
                    .done(function(data, text, jqxhr){  
                        
                        alert('Groupe enregistré avec succès!');
                        $('.loader').show();
                        $('#main-content').load('nouveauGroupe.php', function(){
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

                 $('.afficher').on('click', function(af){

                            af.preventDefault();
                            $('.loader').show();
                           var $b = $(this);
                            url = $b.attr('href');
                           $('#main-content').load(url, function(){
                                $('.loader').hide();
                           });
                        });

                 $( ".datepicker" ).datepicker({});         
				$('.loader').hide();
     </script>

