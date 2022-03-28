<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Creer activite")){
            header('Location:index.php');
        }else{
            $insert1 = "SELECT codeFidele FROM fidele where lisible=1;";
            $res=$db->query($insert1);
        }
    }else{
        header('Location:login.php');
    }
?>
    <section class="wrapper">


        <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li> <i class="material-icons text-primary">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                        <li> <i class="material-icons text-primary">grain</i><a href="#" class="col-blue"> Activité</a></li>
                        <li> <i class="material-icons text-primary">fiber_new</i><a href="#" class="col-blue">Nouvelle activité</a></li>
                    </ol>
        </div>
   
       
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="card">

                    <div class="header text-center h4">
                            
                    Enregistrement d'une nouvelle activité 
                            
                            
                    </div>
                    
                    <div class="panel-body">
                        <div class="alert alert-success fade in" style="display: none;" id="succes">
                            <button data-dismiss="alert" class="close close-sm" type="button" onload="hide();">
                                <i class="icon-remove"></i>
                            </button>
                            <strong>Activité</strong> enrégistrée avec succès!
                        </div>  
                        <div class="alert alert-block alert-danger fade in" style="display: none;" id="echec">
                            <button data-dismiss="alert" class="close close-sm" type="button">
                                <i class="icon-remove"></i>
                            </button>
                            <strong>Oh snap!</strong> Erreur lors de l'enregistrement de l'activité!.
                        </div>  

                   
                    <form class="form-validate form-horizontal" id="form-addActivite" method="POST"  enctype="multipart/form-data"  action="ajoutNouvelActivite.php">   

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">Nom de l'activité</h2>
                                <div class="row clearfix">
                                
                                    <div class="input-group">
                                        
                                        <div class="form-line ">
                                            <input type="text" placeholder="Entrez le nom de votre activitée" class="form-control" id="cnomActivite" name="nomActivite" minlength="2" type="text" required>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <h2 class="card-inside-title">Date de début</h2>
                            <div class="row clearfix">
                                
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons text-primary">event</i> 
                                        </span>
                                        <div class="form-line">
                                            <input class="form-control datepicker" id="cdateDebut" name="dateDebut" type="text"  pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" 
                                            placeholder="YYYY-MM-DD" required />
                                        </div>
                                    </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <h2 class="card-inside-title">Date de fin</h2>
                            <div class="row clearfix">
                                
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons text-primary">event</i> 
                                        </span>
                                        
                                        <div class="form-line">
                                            <input class="form-control datepicker" id="cdateFin" name="dateFin" type="text"  pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" 
                                            placeholder="YYYY-MM-DD" required/>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        
                        <h2 class="card-inside-title">Description de l'activité</h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    
                                    <div class="form-line ">
                                    
                                        <textarea rows="4" class="form-control no-resize" placeholder="Entrez votre description" id="cdescription" name="description"></textarea>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                                        
                        <div class="table-responsive" id="old_table">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable tableau_dynamique">  
                                <thead>
                                    <tr>
                                        <th><i class="material-icons iconposition">confirmation_number</i>Numéro</th>
                                        <th><i class="material-icons iconposition">people</i>Nom des groupes</th>
                                    
                                        
                                        <th style="text-align: center">
                                            <input id="checkAll" onclick="CocheTout(this)" type="checkbox">
                                            <label for="checkAll"></label>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>    
                                    <?php
                                        $req3 = "SELECT * FROM groupe where lisible=1;";
                                        $result=$db->query($req3);
                                        $n = 0;
                                        while($identi=$result->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                    <tr>
                                        <td><?php echo ++$n; ?></td>
                                        <td><?php echo($identi['nomgroupe']);?></td>
                                        <td style="text-align: center;">
                                            <div class="checkboxes">
                                                
                                                    <input name="choixA[]" class="magazine" id="checkbox-01<?php echo $n; ?>" value="<?php echo($identi['idgroupe']); ?>" type="checkbox" />
                                                    <label class="label_check" for="checkbox-01<?php echo $n; ?>"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <br>

                        <div class="form-group">
                            <div class="col-lg-offset-5 col-lg-10">
                                <a class="btn btn-warning annuler" href="listeActivites.php">Annuler</a>
                                <button class="btn btn-primary" name="submit" type="submit">Enregistrer</button>
                                <span class="envoi_en_cours" style="margin-left: 10px; display: none;">Enregistrement en cours...</span>
                            </div>
                        </div>
                        <br>
                    </form>
                        </div>
                        <?php
                            $db=NULL;
                        ?>
                      
                </div>
            </div>
        </section>
    </div>
</div>
</section>

<script type="text/javascript">
    $('#chargement').hide();
    $('.annuler').on('click', function(e){
        e.preventDefault();
        var $link = $(this);
        target = $link.attr('href');
        if(window.confirm("Voulez-vous vraiment annuler?")){
            $('#main-content').load(target, function(){
                $('.loader').hide();
            });
        }
    });        

    $('#checkAll').click(function() {
        // on cherche les checkbox à l'intérieur de l'id  'magazine'
        //var magazines = $("#magazine").find(':checkbox');
        var test='Cocher Tous';
        var test1='Decocher Tous';
        if(this.checked){ // si 'checkAll' est coché
            $(":checkbox").attr('checked', true);
            $('#modifiertext').html(test1);
            //magazines.prop('checked', true);
        }else{ // si on décoche 'checkAll'
            $(":checkbox").attr('checked', false);
            $('#modifiertext').html(test);
            //magazines.prop('checked', false);
        }
    });

    $('#form-addActivite').on('submit', function (e) {
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
                if(reponse != ''){
                    alert(reponse);
                    $('.envoi_en_cours').hide();
                }else{
                    alert('Activité enregistrée avec succès!');
                    $('.envoi_en_cours').show();
                    $('#main-content').load('nouvelleActivite.php', function(){
                        $('.envoi_en_cours').hide();
                    });
                }
            }
        });
    });

    $(".tableau_dynamique").DataTable();
    $( ".datepicker" ).datepicker({});
    $('.loader').hide();
</script>