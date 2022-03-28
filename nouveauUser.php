<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
       require_once('includes/function_role.php');


        if(!has_Droit($idUser, "creer utilisateur")){
        	header('Location:index.php');
        }else{
	        //$message = "";
	    	$listePersonne = null;

	    	$listePersonne = $db->prepare("SELECT idpersonne, nom, prenom from personne where lisible = true AND idpersonne NOT IN(select personne_idpersonne from utilisateur where lisible = true)");
	    	$listePersonne->execute();
			
			$listeRole = $db->prepare('SELECT idrole, nomRole as nrole FROM role where lisible = true');
			$listeRole->execute();

			
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
                    <li class="col-blue"> <a href="listeUser.php" class="col-blue afficher"><i class="material-icons">people</i> Utilisteurs /</li>
                    <li class="col-blue"><a href="#" class="col-blue"><i class="material-icons">people</i> Nouveau utilisateur</a></li>
                </ol>
            </div>
        </div>

        <style type="text/css">
            .inputTopSpace{
                  margin-top: 5px;
                  margin-bottom: 10px;
                }
        </style>
         <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading text-center h4">
                        Enregistrement d'un nouveau utilisateur
                    </header>
                    <div class="panel-body">
                        <div class="body">
                            <form class="form-validate form-horizontal" id="form-addUser" method="POST" action="saveUser.php">
                            <div class="row clearfix inputTopSpace">
                                <div class="col-md-12">
                                  <label for="Nom">Nom d'utilisateur: <span class="required">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons">person</i>
                                        </span>
                                        <div class="form-line">
                                            <input type="text" class="form-control" id="cUsername" minlength="5" name="username" placeholder="Nom d'utilisateur *"/>
                                        </div>
                                    </div>
                                </div> 
                            </div>

                            <div class="row clearfix inputTopSpace">
                               <div class="col-md-6">
                                <label for="Nom">Mot de passe: <span class="required">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons">person</i>
                                        </span>
                                        <div class="form-line">
                                            <input type="password" id="cPassword" class="form-control" minlength="6" name="password" placeholder="Mot de passe *" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                <label for="Nom">Confirmer le mot de passe: <span class="required">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons">person</i>
                                        </span>
                                        <div class="form-line">
                                            <input class="form-control" id="cConfirmation" type="password" minlength="6" name="confirmation"  placeholder="confirmer le mot de passe *" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row clearfix inputTopSpace">
                                <div class="col-sm-6 ">   
                                <label for="Nom">Personne: <span class="required">*</span></label>
                                    <select class="form-control show-tick" name="personne" id="cPersonne" required>
                                         <?php
                                                while($liste = $listePersonne->fetch(PDO::FETCH_OBJ)){
                                            ?>
                                                <option value="<?php echo $liste->idpersonne; ?>"><?php echo $liste->nom.' '.$liste->prenom; ?></option>
                                            <?php       
                                                }
                                            ?>
                                        </select>
                                </div>
                                <div class="col-sm-6 ">
                                <label for="Nom">Rôle: <span class="required">*</span></label>
                                    <select class="form-control show-tick" name="role" id="cRole" required>
                                         <?php
                                                while($listeR = $listeRole->fetch(PDO::FETCH_OBJ)){
                                            ?>
                                                <option value="<?php echo $listeR->idrole; ?>"><?php echo $listeR->nrole; ?></option>
                                            <?php       
                                                }
                                            ?>
                                        </select>
                                </div>
                            </div>
                             <br>
                            <div class="row clearfix inputTopSpace">
	                             <div class="form-group ">
	                            	<div class="form-group">
                                          <div class="col-lg-offset-5 col-lg-10 ">
                                          	  <a class="btn btn-warning annuler" href="listeUser.php">Annuler</a>
                                              <button class="btn btn-primary" name="enregistrer" type="submit">Enregistrer</button>
                                              
                                          </div>
                                      </div>
                                </div>
	                            </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>                

        <script type="text/javascript">
        	$('#form-addUser').on('submit', function(e){

                              e.preventDefault();
                                var $form = $(this);
                                $form.find('button').text('Traitement');
                             
                                url = $form.attr('action');
                               
                $.post(url, $form.serializeArray())
 
                    .done(function(data, text, jqxhr){  
                        
                        alert('Utilisateur enregistré avec succès!');
                        $('.loader').show();
                        $('#main-content').load('nouveauUser.php', function(){
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
             
                $(".select2").select2();
				$('.loader').hide();
        </script>
