
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

                if(!isset($_GET['id'])){

                    header('Location:index.php');
                }else{

                    $idbapteme = $_GET['id'];
                    $bapteme = null;

                    

                    $baptemes = $db->prepare("SELECT datebaptise, lieu_baptise, fidele_idfidele, nom, prenom, codefidele FROM personne 
                                              INNER JOIN fidele ON fidele.personne_idpersonne = personne.idpersonne
                                              INNER JOIN bapteme ON bapteme.fidele_idfidele = fidele.idfidele
                                              AND personne.lisible = 1 
                                              AND fidele.lisible = 1
                                              AND bapteme.lisible = 1
                                              AND bapteme.idbapteme = $idbapteme");
                    $baptemes->execute();
                    while ($x=$baptemes->fetch(PDO::FETCH_OBJ)) {
                        $bapteme = $x;
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
                    <li><i class="fa fa-files-o"></i>Nouveau Baptème</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        Enregistrement d'un baptème
                    </header>

                    <div class="panel-body">
                        <div class="form">
                            <form class="form-validate form-horizontal" id="form_updateBapteme" method="POST" enctype="multipart/form-data" action="updateBapteme.php?id=<?php echo $idbapteme; ?>">

                                <div class="form-group ">
                                    <label for="ccode" class="control-label col-lg-2">Baptisé</label>
                                    <div class="col-lg-10" required>
                                        <input class="form-control" type="text"  value="<?php echo $bapteme->codefidele.': '.$bapteme->nom.' '.$bapteme->prenom; ?>" disabled>
                                    </div>      
                                </div>

                                <div class="form-group ">
                                    <label for="cdateAdmin" class="control-label col-lg-2">Date Baptême </label>
                                    <div class="col-lg-10">
                                        <input class="form-control datepicker" name="date_bapteme" type="text"   value="<?php echo $bapteme->datebaptise; ?>" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="YYYY-MM-DD" required  />
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="ccode" class="control-label col-lg-2">Lieu Baptême</label>
                                    <div class="col-lg-10" required>
                                        <input class="form-control" type="text" name="lieu_bapteme" value="<?php echo $bapteme->lieu_baptise; ?>" required>
                                    </div>      
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-offset-5 col-lg-10">
                                        <a class="btn btn-default annuler" href="listeBaptises.php">Annuler</a>
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


                        $('#form_updateBapteme').on('submit', function(e){

                              e.preventDefault();
                                var $form = $(this);
                                $form.find('button').text('Traitement');
                             
                                url = $form.attr('action');

                               
                $.post(url, $form.serializeArray())
 
                    .done(function(data, text, jqxhr){  
                        
                        alert('Baptême modifié avec succès!');
                        $('.loader').show();
                        $('#main-content').load('listeBaptises.php', function(){
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
      $( ".datepicker" ).datepicker({});
	$('.loader').hide();


</script>