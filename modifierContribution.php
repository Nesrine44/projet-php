<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Modifier contribution")){

            header('Location:index.php');

        }else{

           $contribution = null;
			if(!isset($_GET['id']) || !isset($_GET['page'])){
				header('Location:index.php');
			}else{

	            $idCont=$_GET['id'];
                $page = $_GET['page'];
	            $select = $db->prepare("SELECT f.codefidele as codefidele, p.nom as nom, p.prenom as prenom,p.idpersonne as idpersonne, f.statut as statut,  f.idfidele as idfidele, c.date as date_contribution, c.montant as montant, c.typecontribution as type FROM  personne p, fidele f, contributionfidele c where p.idpersonne = f.personne_idpersonne AND f.idfidele = c.fidele_idfidele AND c.idcontributionfidele = $idCont AND p.lisible = true AND f.lisible = true AND c.lisible = true");
	            $select->execute();

	            while($s=$select->fetch(PDO::FETCH_OBJ)){

	                $contribution= $s;
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
                    <li class='col-blue'><i class="material-icons">home</i><a href="index.php" class='col-blue'> Accueil</a></li>
                    <li class='col-blue'><i class="material-icons">monetization_on</i> Finances</li>
                    <li class='col-blue'><i class="material-icons">monetization_on</i><a class="afficher col-blue" href="ficheContributionParType.php"> Liste Contributions</a></li>
                    <li class='col-blue'><i class="material-icons">monetization_on</i><a class="afficher col-blue" id="retour" href="afficherContributionFidele.php?idfidele=<?php echo $contribution->idfidele;?>&amp;idpersonne=<?php echo $contribution->idpersonne;?>&amp;page=<?php echo $page; ?>"> Contributions par fidèle</a>
                    </li>
                    <li class='col-blue'><i class="material-icons">border_color</i> Modification</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading h4 text-center">
                        Modifier contribution
                    </header>
                    <div class="panel-body">
                        <div class="form">
                        
                            <form class="form-validate form-horizontal" id="form_updatecontribution" method="POST" enctype="multipart/form-data" action="updateContribution.php?id=<?php echo $idCont; ?>">
                                
                                <div class="row clearfix inputTopSpace">
                                    <div class="col-md-6">
                                    <label for="Nom">Code fidèle: <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">code</i>
                                            </span>
                                            <div class="form-line">
                                               <input class="form-control"  value="<?php echo $contribution->codefidele; ?>" type="text" disabled />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    <label for="Nom">Noms et prénoms: <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">edite</i>
                                            </span>
                                            <div class="form-line">
                                            <input class="form-control"  name="fidele"  value="<?php echo $contribution->nom.' '.$contribution->prenom; ?>" type="text" disabled />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                                                
                                <div class="row clearfix inputTopSpace">
                                    <div class="col-md-6">
                                    <label for="Nom">Type contribution: <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">ev_station</i>
                                            </span>
                                            <div class="form-line">
                                            <input class="form-control"   value="<?php echo $contribution->type; ?>" type="text" disabled />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    <label for="Nom">Date de contribution: <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">event</i>
                                            </span>
                                            <div class="form-line">
                                                <input class="form-control datepicker" id="cdateContribution" value="<?php echo $contribution->date_contribution; ?>" 
                                                     pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])"  name="date_contribution"  type="text"  required/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="row clearfix inputTopSpace">
                                    <div class="col-md-6">
                                    <label for="Nom">Montant: <span class="required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">monetization_on</i>
                                            </span>
                                            <div class="form-line">
                                               <input class="form-control " id="cmontant" type="number" name="montant" min="50" max="50000000" 
                                                value="<?php echo $contribution->montant; ?>" required/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>

								<div class="form-group">
                                    <div class="col-lg-offset-5 col-lg-10">
                                        <a class="btn btn-warning annuler" href="">Annuler</a>
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

             $('#form_updatecontribution').on('submit', function(e){
                e.preventDefault();
                 var $form = $(this); 
                 
                 $form.find('#modifier').text('Chargement');
                 
                    $.post($form.attr('action'), $form.serializeArray())
                        .done(function(data, text, jqxhr){

                            alert('contribution modifiée avec succès!');

                            url = $('#retour').attr('href');

                            $('#main-content').load(url);

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
                    target = $('#retour').attr('href');
                    if(window.confirm("Voulez-vous vraiment annuler?")){

                        $('#main-content').load(target);
                    }
                    
                });                

                $('.afficher').on('click', function(e){

                    e.preventDefault();

                    var z = $(this);
                                
                    target = z.attr('href');

                    $('#main-content').load(target);                                
                            
                });

                $( ".datepicker" ).datepicker({

                  changeMonth: true,
                  changeYear: true,
                  dateFormat: 'yy-mm-dd'
                });
				
				$('.loader').hide();
                       
           </script>              
