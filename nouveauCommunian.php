
<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Enregistrer bapteme")){
            header('Location:index.php');
        }else{
            $fideles = $db->prepare("SELECT idfidele, codefidele, nom, prenom FROM fidele, personne where personne.lisible=1 and fidele.lisible = 1  AND personne.idpersonne=fidele.personne_idpersonne AND fidele.idfidele NOT IN(select fidele_idfidele from confirmation where lisible = true) ORDER BY nom");
            $fideles->execute();
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
                        <li> <i class="material-icons text-primary">fiber_new</i><a href="#" class="col-blue">Nouveau Communian</a></li>
                    </ol>
        </div>



        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">

                    <div class="header text-center h4">
                          
                    Enregistrement d'une confirmation
                         
                            
                    </div>
                     
            <div class="body">

              
                            <form class="form-validate form-horizontal" id="form_addcommunian" method="POST" enctype="multipart/form-data" action="savecommunian.php">
                            <h2 class="card-inside-title">Rechercher le fidèle:</h2>
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons text-primary">person</i> 
                                        </span>
                                        
                                        <div class="form-line ">
                                            <input class="form-control" id="recherche_fidele" type="text" name="search" placeholder="Rechercher un fidèle" />  
                                        </div>
                                        <div id="result"> </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="input-group">
                                    <div class="form-line">
                                        <input class="form-control" id="fidele" type="text" name="idfidele" value="" required disabled/>
                                        <input class="form-control" type="hidden" id="idfidele" name="idfidele" required/>
                                        <input class="form-control" type="hidden" id="dateNaissFidele" name="dateNaissFidele" required/>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2 class="card-inside-title">Date et lieu de confirmation</h2>
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons text-primary">event</i> 
                                        </span>
                                        
                                        <div class="form-line ">
                                        <input class="form-control datepicker" id="dateAdmin" name="date_confirmation" type="text"  pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="Entrez la date de confirmation: YYYY-MM-DD"  required  />
                                        </div>
                                        <div id="result"> </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="input-group">
                                    <div class="form-line">
                                        <input class="form-control" type="text" name="lieu_confirmation" required placeholder="Entrez le lieu de confirmation">                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                               
                                <div class="form-group">
                                    <div class="col-lg-offset-5 col-lg-10">
                                        <a class="btn btn-warning annuler" href="listeCommunians.php">Annuler</a>
                                      <button class="btn btn-primary" name="submit" type="submit">Enregistrer</button>
                                    </div>
                                </div>   
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
       </form>
    </section>

<script type="text/javascript">
    $('#chargement').hide();
    $('#form_addcommunian').on('submit', function(e){
        e.preventDefault();
        var $form = $(this);
        $form.find('button').text('Traitement');
        url = $form.attr('action');

        $.post(url, $form.serializeArray())
            .done(function(data, text, jqxhr){  
                alert('Communian enregistré avec succès!');
                $('#main-content').load('nouveauCommunian.php');
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
    $(".datepicker").datepicker({});

    $('#recherche_fidele').keyup(function(){
        $('.loader').show();
        var txt = $(this).val();
        // alert(txt);
        if(txt != ''){
            $.ajax({
                url:"searchAjouterConfirme.php",
                method:"get",
                data:{search:txt},
                dataType:"text",
                success:function(data) {
                    $('#old_table').hide();
                    $('#result').html(data);
                    $('#submit').removeAttr('disabled');
                }
            });
        }else{
            // alert(txt);
            $('#result').html(txt);
            $('#old_table').show();
            $('.loader').hide();
        }
    });
    $(".datepicker" ).datepicker({});
    $('.loader').hide();
</script>