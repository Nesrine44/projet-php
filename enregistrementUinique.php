<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Inscrire a un groupe")){

            header('Location:index.php');

        }else{
            
            $select = $db->prepare("SELECT nom, prenom, codefidele, idfidele FROM fidele, personne where fidele.personne_idpersonne = personne.idpersonne AND fidele.est_decede=0 AND personne.lisible=1 AND fidele.lisible = 1  ORDER BY nom ASC ");
            $select->execute();

            $selectGroupe = $db->prepare("SELECT * FROM groupe where lisible = 1 AND typegroupe != 'ANCIENS' ORDER BY nomgroupe ASC");
            $selectGroupe->execute();
		
			$annee_creation = 0;
            $annee = $_SESSION['annee'];

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
                    <li> <i class="material-icons text-primary" class="col-blue">group</i><a href="#" class="col-blue"> Groupes</a></li>
                    <li> <i class="material-icons text-primary">group_add</i><a href="#" class="col-blue"> Ajouter à un groupe</a></li>
                </ol>
            </div>
        </div>



        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header text-center h4">
                   
                        Ajouter un fidele à un groupe 
                </div>
                <div class="body">
                   <form class="form-validate form-horizontal" id="form-fideleGoupe" method="POST" action="ajoutFidelGroupe.php">  
                    
                        <h2 class="card-inside-title">Rechercher le fidèle:</h2>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons text-primary">person</i> 
                                    </span>
                                    
                                    <div class="form-line ">
                                     <input  class="form-control" id="recherche_fidele" type="text" name="search" placeholder="Rechercher un fidèle" />
                                    </div>
                                    <div id="result"> </div>
                                </div>
                            </div>


                                        <!-- resultat de la recherche -->
                                       


                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="input-group">
                                    <div class="form-line">
                                          <input type="text" class="form-control date"  id="fidele" type="text" name="fidele" value="" required disabled/>
                                        <input type="hidden" id="idfidele"; name="idfidele" required/>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 class="card-inside-title">Choix du groupe</h2>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                 
                                   
                                        
                                   
                                  <select class="form-control show-tick"  id="cchoixgroupe" name="idgroupe[]" required>
                                          <option value="">-- Selectionner le groupe --</option>
                                            <?php 

                                                while ($groupe = $selectGroupe->fetch(PDO::FETCH_OBJ)) {                       
                                            ?>
                                                    <option value="<?php echo $groupe->idgroupe; ?>">
                                                        <?php echo $groupe->nomgroupe; ?>
                                                    </option>
                                            <?php            
                                                                    
                                                }
                                            ?>

                                  </select>
                               
                            </div> 
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-5 col-lg-10">
                          
                               <a class="btn btn-warning annuler" href="listeGroupe.php">Annuler</a>
                               <button class="btn btn-primary" name="submit" type="submit">Enregistrer</button><span class="envoi_en_cours" style="margin-left: 10px; display: none;">Enregistrement en cours...</span>
                            </div>
                        </div>
                        </div>
                                             
                    </form>
                </div>
            </div>
        </div>



    </section>    

        <script type="text/javascript">
         

            $('#form-fideleGoupe').on('submit', function(e){

                              e.preventDefault();
                                var $form = $(this);
                                $form.find('button').text('Traitement');
                             
                                url = $form.attr('action');

                               
                $.post(url, $form.serializeArray())
 
                    .done(function(data, text, jqxhr){  
                        
                        alert('Fidèle ajouté avec succès!');
                        $('.loader').show();
                        $('#main-content').load('enregistrementUinique.php', function(){
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

                 $('.afficher').on('click', function(af){

                            af.preventDefault();
                            $('.loader').show();
                           var $b = $(this);
                            url = $b.attr('href');
                           $('#main-content').load(url, function(){
                                $('.loader').hide();
                           });
                        });

                $('.annuler').on('click', function(e){

                    e.preventDefault();
                    var $link = $(this);
                    target = $link.attr('href');
                    if(window.confirm("Voulez-vous vraiment annuler?")){
                        $('.loader').hide();
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
                url:"searchAjouterFideleGroupe.php",
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

    </section>

