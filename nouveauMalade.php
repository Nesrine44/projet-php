<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Creer un malade")){
            header('Location:index.php');
        }else{
            $listeFideles = null;
            $selectzone = $db->prepare("SELECT * FROM zone where lisible = 1 ORDER BY nomzone");
            $selectzone->execute();
            $selectFidele = $db->prepare("SELECT codeFidele, nom, prenom, idfidele, idpersonne FROM fidele, personne where personne.idpersonne = fidele.personne_idpersonne AND fidele.lisible=1 AND personne.lisible=1 AND fidele.idfidele  NOT IN(SELECT fidele_idfidele FROM malade where est_retabli = 0  AND malade.est_decede = 0 AND lisible = 1)");
            $selectFidele->execute();           
        }
    }else{
        header('Location:login.php');
    }
?>




<div class="row clearfix">

<div class="row">
            <div class="col-lg-12">
               
                    <ol class="breadcrumb">
                       <li> <i class="material-icons text-primary">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                       <li> <i class="material-icons text-primary">local_hospital</i><a href="#" class="col-blue"> Santé</a></li>
                       <li> <i class="material-icons text-primary">hotel</i><a href="#" class="col-blue"> Nouveau Malade</a></li>
                    </ol>
                   
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header text-center h4">
                   
                        Enregistrement d'un malade
                   
                </div>
                <div class="body">
                    <form class="form-validate form-horizontal" id="form-addMalade" method="POST" action="saveMalade.php">   
                    
                        <h2 class="card-inside-title">Rechercher le fidèle:</h2>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons text-primary">person</i> 
                                    </span>
                                    
                                    <div class="form-line ">
                                        <input type="text" class="form-control" id="recherche_fidele"  name="search" placeholder="Nom du fidèle à rechercher">
                                    </div>
                                    <div id="result"> </div>
                                </div>
                            </div>


                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="input-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control date"  id="fidele" type="text" name="fidele" value="" required disabled/>
                                        <input type="hidden" id="idfidele"; name="idfidele" required/>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 class="card-inside-title">Dates</h2>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons text-primary">event</i> 
                                    </span>
                                    
                                    <div class="form-line">
                                        <input type="text" class="form-control datepicker"  id="cdateEnregistrement" name="dateEnregistrement"  pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="Date d'enregistrement du malade: YYYY-MM-DD">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons text-primary">event</i> 
                                    </span>
                                
                                    <div class="form-line">
                                        <input type="text" class="form-control datepicker" id="cdateDebutMaladie" type="text" name="dateDebutMaladie"  pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="Date de début de la maladie: YYYY-MM-DD">
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Résidence et guide
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons ">location_city</i> 
                                    </span>
                                    <div class="form-line">
                                        <input type="text" class="form-control"  name ="residence"   placeholder="Résidence *">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">rowing</i> 
                                    </span>
                                    
                                
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="choixguide" placeholder="Guide *" required>
                                    </div>
                                    
                                </div>
                            </div> 
                            <div class="form-group">
                                <div class="col-lg-offset-5 col-lg-10">
                                    <a class="btn btn-warning waves-effect annuler" href="listeMalades.php">Annuler</a>
                                    <button type="submit" name="submit"class="btn btn-primary waves-effect">Enregistrer</button>
                                </div>
                            </div>
                        </div>                             
                    </form>
                </div>
            </div>
        </div>
</div>
            <!-- #END# Input Group -->
<script>          
    $('#form-addMalade').on('submit', function(e){
        e.preventDefault();
        $('.loader').show();
        var $form = $(this);
        $form.find('button').text('Traitement');
        
        url = $form.attr('action');
                        
        $.post(url, $form.serializeArray())
            .done(function(data, text, jqxhr){  
                alert('Malade enregistré avec succès!');
                $('#main-content').load('nouveauMalade.php', function(){
                    $('.loader').hide();
                });
            })
            .fail(function(jqxhr){
                $('.loader').hide();
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
    
    $( ".datepicker" ).datepicker({ });

    $('#recherche_fidele').keyup(function(){
        $('.loader').show();
        var txt = $(this).val();
         //alert(txt);
        if(txt != ''){
            $.ajax({
                url:"searchAjouterMalade.php",
                method:"get",
                data:{search:txt},
                dataType:"text",
                success:function(data)
                {
                    $('#old_table').hide();
                    $('#result').html(data);
                    $('#submit').removeAttr('disabled');
                    //alert(txt);
                }
            });
        }else{
            // alert(txt);
            $('#result').html(txt);
            $('#old_table').show();
            $('.loader').hide();
        }
    });
    $(".select2").select2();
    $('.loader').hide();
</script>



