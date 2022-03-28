
<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Modifier bapteme")){

            header('Location:index.php');

        }else{
            
           if(!isset($_GET['id'])){

                header('Location:index.php');
           }else{
                $idconfirmation = $_GET['id'];
                $confirmation = null;

                
                $confirmations = $db->prepare("SELECT date_confirmation, lieu_confirmation, fidele_idfidele, nom, prenom, codefidele FROM personne 
                                          INNER JOIN fidele ON fidele.personne_idpersonne = personne.idpersonne
                                          INNER JOIN confirmation ON confirmation.fidele_idfidele = fidele.idfidele
                                          AND personne.lisible = 1 
                                          AND fidele.lisible = 1
                                          AND confirmation.lisible = 1
                                          AND confirmation.idconfirmation = $idconfirmation");
                $confirmations->execute();
                while ($x=$confirmations->fetch(PDO::FETCH_OBJ)) {
                    $confirmation = $x;
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
                    <li><i class="fa fa-files-o"></i><a href="listeCommunians.php" class="afficher">Liste confirmés</a></li>
                    <li><i class="fa fa-file-o"></i>Modifier confirmé</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        Modifier d'un baptème
                    </header>

                    <div class="panel-body">
                        <div class="form">
                            <form class="form-validate form-horizontal" id="form_updateConfirmation" method="POST" enctype="multipart/form-data" action="updateCommunian.php?id=<?php echo $idconfirmation;  ?>">

                                <div class="form-group ">
                                    <label class="control-label col-lg-2">Fidèle</label>
                                    <div class="col-lg-10">
                                        <input class="form-control"  type="text" value="<?php echo $confirmation->codefidele.': '.$confirmation->nom.' '.$confirmation->prenom; ?>" disabled/>
                                    </div>		
                                </div>

                                 <div class="form-group">
                                    <label for="cdateAdmin" class="control-label col-lg-2">Date Confirmation </label>
                                    <div class="col-lg-10">
                                        <input class="form-control datepicker" name="date_confirmation" type="text" value="<?php echo $confirmation->date_confirmation; ?>" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="YYYY-MM-DD" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="ccode" class="control-label col-lg-2">Lieu Confirmation</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" type="text" name="lieu_confirmation" value="<?php echo $confirmation->lieu_confirmation; ?>" required />
                                    </div>      
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-offset-5 col-lg-10">
                                        <a class="btn btn-default annuler" href="listeCommunians.php">Annuler</a>
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

<script type="text/javascript">

    $('#chargement').hide();
    

    $('#form_updateConfirmation').on('submit', function(e){
        e.preventDefault();
        var $form = $(this);

        $form.find('button').text('Traitement');
            url = $form.attr('action');

            $.post(url, $form.serializeArray())
 
               .done(function(data, text, jqxhr){  
                        
                    alert('Confirmé modifié avec succès!');
                    $('.loader').show();
                    $('#main-content').load('listeCommunians.php', function(){
                        $('.loader').hide();
                    });
                })

                .fail(function(jqxhr){
                    alert(jqxhr.responseText);
                })

                .always(function(){
                    $form.find('button').text('Mettre à jour');
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