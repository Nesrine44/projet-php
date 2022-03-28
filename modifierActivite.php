<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Modifier activite")){

            header('Location:index.php');

        }else{
            
            if(!isset($_GET['id'])){

                header('Location:index.php');

            }else{

                $code=$_GET['id'];

                $insert1 = "SELECT * FROM activite WHERE idactivite = $code and lisible = 1";
                $res=$db->query($insert1);
                while($id=$res->fetch(PDO::FETCH_ASSOC)){
                    $identifiant1=$id['nomactivite'];
                    $identifiant2=$id['description'];
                    $identifiant3=$id['datedebut'];
                    $identifiant4=$id['datefin'];
                }

                $groupes = $db->prepare("SELECT idgroupe, nomgroupe 
                                         FROM groupe WHERE lisible = true");
                $groupes->execute();

                 function is_for_group($idactivite, $idgroupe){
                    global $db; 
                    $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $selectE = $db->prepare("SELECT idgroupeactivite from groupeactivite where lisible=1 AND activite_idactivite = $idactivite AND groupe_idgroupe = $idgroupe");
                    $selectE->execute();

                    if($selectE->fetch(PDO::FETCH_OBJ)){
                        return true;
                    }else{

                        return false;
                    }
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
                    <li><i class="fa fa-home"></i><a href="index.php">Accueil</a></li>
                    <li><i class="icon_document_alt"></i>Activités</li>
                    <li><i class="fa fa-files-o"></i><a id="listeF" href="listeActivites.php">Liste Activités</a></li>
                    <li><i class="fa fa-files-o"></i>Modifier Activité</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        Modification d'une activité
                    </header>
                    <div class="panel-body">
                        <div class="form">
                            <form class="form-validate form-horizontal" id="form_updateActivite" method="POST" enctype="multipart/form-data" action="updateActivite.php?id=<?php echo $code; ?>">

                                <div class="form-group ">
                                    <label for="cnomActivite" class="control-label col-lg-2">Nom de l'activité</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" id="cnomActivite" name="nomActivite" minlength="5" type="text" value="<?php echo($identifiant1); ?>" required />
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="cdescription" class="control-label col-lg-2">Description de l'activité </label>
                                    <div class="col-lg-10">
                                        <textarea class="form-control" id="cdescription" name="description"><?php echo $identifiant2; ?> </textarea>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="cdateDebut" class="control-label col-lg-2">Date de début</label>
                                    <div class="col-lg-10">
                                        <input class="form-control datepicker" id="cdateDebut" name="dateDebut" type="text" value="<?php echo($identifiant3); ?>" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="YYYY-MM-DD" required />
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="cdateFin" class="control-label col-lg-2">Date de fin</label>
                                    <div class="col-lg-10">
                                        <input class="form-control datepicker" id="cdateFin" name="dateFin" type="date" value="<?php echo($identifiant4); ?>" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="YYYY-MM-DD" required />
                                    </div>
                                </div>

                                

                                <div class="form-group">
                                        <div class="col-lg-offset-5 col-lg-10">
                                            <a class="btn btn-default annuler" href="listeActivites.php">Annuler</a>
                                            <button class="btn btn-primary" name="submit" type="submit">Mettre à jour
                                            </button><span class="envoi_en_cours" style="margin-left: 10px; display: none;">Enregistrement en cours...</span>
                                        </div>
                                    </div>

                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>

<script type="text/javascript">

    
      $('#form_updateActivite').on('submit', function(e){

                e.preventDefault();

                $('.envoi_en_cours').show();
                
                 var $form = $(this);

                 url = $form.attr('action');

                    $.post(url, $form.serializeArray())
                        .done(function(data, text, jqxhr){
                          alert('Activité modifiée avec succès!');
                          $('.envoi_en_cours').hide();
                         $('#main-content').load('listeActivites.php');

                        })

                        .fail(function(jqxhr){

                            alert(jqxhr.responseText);
                        })

                        .always(function(){

                            $('.envoi_en_cours').hide();
                        })
                });

             $('.annuler').on('click', function(e){

                    e.preventDefault();
                    var $link = $(this);
                    target = $link.attr('href');
                    if(window.confirm("Voulez-vous vraiment annuler?")){

                        $('#main-content').load(target);
                    }
                    
                });                

                         $('#listeF').on('click', function(e){

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





















