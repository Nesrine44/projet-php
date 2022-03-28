<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Modifier un groupe")){

            header('Location:index.php');

        }else{

           $groupe = null;
			if(!isset($_GET['param'])){
				header('Location:index.php');
			}else{
	            $idgroupe=$_GET['param'];

	            $select = $db->prepare("SELECT * FROM groupe where idgroupe=$idgroupe AND lisible = true");
	            $select->execute();

	            while($s=$select->fetch(PDO::FETCH_OBJ)){

	                $groupe = $s;
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
								<li> <i class="material-icons text-primary" >group</i> <a href="#"class="col-blue">Groupes</a> </li>
                                 <li> <i class="material-icons text-primary">group_add</i> <a href="#"class="col-blue">Liste Groupe</a></li>
                                 <li> <i class="material-icons text-primary">border_color</i> <a href="#"class="col-blue">Modifier un groupe</a></li>
                             
							</ol>
						</div>
                    </div>
                    

                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="card">

                                <div class="header text-center h4">
                                        
                                    Modifier un groupe
                                      
                                        
                                </div>
                  
                       
                    <div class="body">
                    <form class="form-validate form-horizontal" id="form_updateGroupe" method="POST" enctype="multipart/form-data" action="updateGroupe.php?param=<?php echo $groupe->idgroupe; ?>">   
                    
                        
                        <div class="row clearfix">

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <h2 class="card-inside-title">Nom du groupe:</h2>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons text-primary">edit</i> 
                                    </span>
                                    
                                    <div class="form-line ">
                                    <input class="form-control" id="cnomGroupe" name="nomGroupe"  value="<?php echo $groupe->nomgroupe; ?>" type="text" required />
                                    </div>
                                   
                                </div>
                            </div>
                      

                          
                        
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <h2 class="card-inside-title">Date de creation:</h2>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons text-primary">event</i> 
                                    </span>
                                    
                                    <div class="form-line ">
                                         <input style="cursor: pointer" class="form-control datepicker" id="cdateCreation" value="<?php echo $groupe->datecreation; ?>"  name="dateCreation"  type="text" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="YYYY-MM-DD" />
                                    </div>
                                   
                                </div>
                            </div>
                         </div>
                               
                                <div class="row clearfix ">
                                   
                                    <div class="col-lg-12">
                                    <label for="ctypeGroupe" class="control-label ">Type de groupe</label>
                                        <select class="form-control " name="typeGroupe">
                                            <option value="chorale" id="chorale" <?php if($groupe->typegroupe == "chorale") echo "desabled"; ?> >Chorale</option>
                                            <option value="mouvement" id="mouvement" <?php if($groupe->typegroupe == "mouvement") echo "desabled"; ?>>Mouvement</option>
                                            <option value="anciens" id="anciens" <?php if($groupe->typegroupe == "anciens") echo "desabled"; ?>>Anciens</option>
                                            <option value="informel" id="informel" <?php if($groupe->typegroupe == "informel") echo "desabled"; ?>>Informel</option>
                                        </select>
                                    </div>
                                </div>
                                


									<div class="form-group">
                                    <div class="col-lg-offset-5 col-lg-10">
                                        <a class="btn btn-warning annuler" href="listeGroupe.php">Annuler</a>
                                      <button class="btn btn-primary" name="submit" type="submit">Mettre à jour</button>
                                    </div>
                                </div>

								<?php
									$db=NULL;
								?>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
<script>

    $('#chargement').hide();

             $('#form_updateGroupe').on('submit', function(e){
                e.preventDefault();
                 var $form = $(this); 

                 $form.find('#modifier').text('Chargement');
                 
                    $.post($form.attr('action'), $form.serializeArray())
                        .done(function(data, text, jqxhr){
                            alert('Groupe modifié avec succès!');
                            $('.loader').show();
                          $('#main-content').load('listeGroupe.php', function(){
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
            $('.annuler').on('click', function(en){

                    en.preventDefault();
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
