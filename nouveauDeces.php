<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Enregistrer deces")){

            header('Location:index.php');

        }else{

            // $selectAllMalades= $db->prepare("SELECT
            //                                      fidele.`codeFidele` AS codeFidele,
            //                                      personne.`nom` AS nom,
            //                                      personne.`prenom` AS prenom,
            //                                      malade.`idmalade` AS idmalade
            //                                 FROM
            //                                     `personne` personne 
            //                                 INNER JOIN `fidele` fidele ON personne.`idpersonne` = fidele.`personne_idpersonne`
            //                                 INNER JOIN `malade` malade ON fidele.`idfidele` = malade.`fidele_idfidele`
            //                                 AND fidele.lisible = true
            //                                 AND malade.lisible = true
            //                                 AND personne.lisible = true
            //                                 AND malade.est_retabli = false
            //                                 AND malade.est_decede = false
            //                                 ORDER BY nom");
            //     $selectAllMalades->execute();

             $selectAllMalades= $db->prepare("SELECT DISTINCT
                                                 fidele.codeFidele AS codeFidele,
                                                 personne.nom AS nom,
                                                 personne.prenom AS prenom,
                                                 fidele.idfidele as idfidele
                                            FROM
                                                 personne,fidele where personne.idpersonne = fidele.personne_idpersonne
                                      
                                            AND fidele.lisible = 1                                           
                                            AND personne.lisible = 1
                                            AND fidele.est_decede=0
                                           
                                          
                                            ORDER BY nom");
                $selectAllMalades->execute();
                
        }
    
    }else{
        header('Location:login.php');
    }
?>

    <section class="wrapper">
        
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li> <i class="material-icons text-primary">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                    <li> <i class="material-icons text-primary">local_hospital</i><a href="#" class="col-blue"> Santé</a></li>
                    <li> <i class="material-icons text-primary">airline_seat_flat</i><a href="#" class="col-blue"> Santé</a></li>
                </ol>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header text-center h4">
                   
                        Enregistrement d'un nouveau décès
                   
                </div>
                <div class="body">
                    <form class="form-validate form-horizontal" id="form-addDeces" method="POST" action="saveDeces.php">   
                        
                        <h2 class="card-inside-title">Sélectionner le decès:</h2>
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
                        
                       <!--  <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick"   id="selectmalade" name="idfidele" required>
                                        <option value="">-- Selectionner --</option>
                                        <?php 
                                               // while ($fidele = $selectAllMalades->fetch(PDO::FETCH_OBJ)) {                       
                                            ?>
                                                <option value="<?php //echo $fidele->idfidele; ?>">
                                                    <?php //echo $fidele->codefidele.' : '.$fidele->nom.' '.$fidele->prenom; ?>
                                                </option>
                                            <?php             
                                            //    }
                                         ?>
                                        
                                       
                                    </select>
                            </div> -->
                        </div>                          
                      

                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                               <h2 class="card-inside-title">Date d'enregistrement</h2>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons text-primary">event</i> 
                                    </span>
                                    
                                    <div class="form-line">
                                        <input type="text" class="form-control datepicker" id="cdateEnregistrement" type="text" name="dateEnregistrement"  
                                        pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="Date d'enregistrement: YYYY-MM-DD">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <h2 class="card-inside-title">Date de décès</h2>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons text-primary">event</i> 
                                    </span>
                                
                                    <div class="form-line">
                                        <input  class="form-control datepicker" id="dateDeces" type="text" name="dateDeces"  
                                        pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="Date de décès: YYYY-MM-DD">
                                    </div>
                                    
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
    </section>
    <script>
            $('#form-addDeces').on('submit', function(e){
                e.preventDefault();
                var $form = $(this);
                $form.find('button').text('Traitement');
                
                url = $form.attr('action');
                               
                $.post(url, $form.serializeArray())
                    .done(function(data, text, jqxhr){  
                        alert('Décès enregistrée avec succès!');
                        $('.loader').show();
                        $('#main-content').load('nouveauDeces.php', function(){
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

                $( ".datepicker" ).datepicker({});          
                $(".select2").select2();
            	$('.loader').hide();
     </script>



