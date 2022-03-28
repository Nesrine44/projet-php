    <?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Afficher activite")){

            header('Location:index.php');

        }else{
            
           $zone = null;
            
                if(isset($_GET['param']) ){
                    $idzone = $_GET['param'];                
                   
                        $selectZone = $db->prepare("SELECT * FROM zone where lisible = true AND idzone = $idzone");
                        $selectZone->execute();

                    while ($x=$selectZone->fetch(PDO::FETCH_OBJ)) {
                        $zone = $x;
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
                <ol class="breadcrumb">
                    <li><i class="material-icons">home</i><a href="index.php" class="col-blue">Accueil</a></li>
                    <li><i class="material-icons">people</i>Fidèles</li>
                    <li><i class="material-icons">location_on</i><a href="listeZones.php" class="afficher col-blue">Liste zones</a></li>
                    <li><i class="material-icons">people</i><a href="#" class=" col-blue">Modifier zone</a></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading h4 text-center">
                        Modification d'une zone
                    </header>
                    <div class="panel-body">
                        <div class="form">
                            <form class="form-validate form-horizontal" id="form_updateZone" method="POST" action="updateZone.php?param=<?php echo $zone->idzone; ?>">
                                 <div class="row clearfix inputTopSpace">
                                   <div class="col-md-6">
                                    <label for="cnomzone">Nom zone: <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">edite</i>
                                            </span>
                                             <div class="form-line">
                                              <input class="form-control " id="cnomzone" type="text" name="nomzone" value="<?php echo $zone->nomzone; ?>" />
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
                                                 <input class="form-control " id="cnomzone" type="text" name="description" value="<?php echo $zone->description; ?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group">
                                    <div class="col-lg-offset-5 col-lg-10">
                                        <a class="btn btn-warning annuler" href="listeZones.php">Annuler</a>
                                      <button class="btn btn-primary" name="submit" type="submit">Mettre à jour</button>
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

       $('#chargement').hide();

             $('#form_updateZone').on('submit', function(e){
                e.preventDefault();
                 var $form = $(this); 

                 $form.find('#modifier').text('Chargement');
                 
                    $.post($form.attr('action'), $form.serializeArray())
                        .done(function(data, text, jqxhr){
                            alert('Zone modifiée avec succès!');
                            $('.loader').show();
                          $('#main-content').load('listeZones.php', function(){
                                $('.loader').hide();
                          });

                        })

                        .fail(function(jqxhr){

                            alert(jqxhr.responseText);
                        })

                        .always(function(){

                            $form.find('#modifier').text('Mettre à jour');
                        })
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
	$('.loader').hide();
                       
           </script>              
