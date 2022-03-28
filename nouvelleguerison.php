


   

<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Enregistrer guerison")){
            header('Location:index.php');
        }else{
           $selectAllMalades= $db->prepare("SELECT
                                                 fidele.`codeFidele` AS codefidele,
                                                 personne.`nom` AS nom,
                                                 personne.`prenom` AS prenom,
                                                 malade.`idmalade` AS idmalade
                                            FROM
                                                `personne` personne 
                                            INNER JOIN `fidele` fidele ON personne.`idpersonne` = fidele.`personne_idpersonne`
                                            INNER JOIN `malade` malade ON fidele.`idfidele` = malade.`fidele_idfidele`
                                            AND fidele.lisible = true
                                            AND malade.lisible = true
                                            AND personne.lisible = true
                                            AND malade.est_retabli = false
                                            AND malade.est_decede = false
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
                    <li> <i class="material-icons text-primary">sentiment_very_satisfied</i><a href="#" class="col-blue"> Nouvelle guérison</a></li>
                </ol>
            </div>
        </div>



<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header text-center h4">
                    
                        Enregistrement d'une nouvelle guérison
                    
                </div>
                <div class="body">
                    <form class="form-validate form-horizontal" id="form-addGuerison" method="POST" action="saveGuerison.php">   
                        
                        <h2 class="card-inside-title">Sélectionner le malade:</h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick "   id="selectmalade" name="idmalade" required>
                                        <option value="">-- Selectionner --</option>
                                        <?php 
                                                while ($fidele = $selectAllMalades->fetch(PDO::FETCH_OBJ)) {                       
                                            ?>
                                                <option value="<?php echo $fidele->idmalade; ?>">
                                                    <?php echo $fidele->codefidele.' : '.$fidele->nom.' '.$fidele->prenom; ?>
                                                </option>
                                            <?php                   
                                                }
                                        ?>
                                        
                                       
                                    </select>
                            </div>
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
                                        pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="Date d'enregistrement du malade: YYYY-MM-DD">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <h2 class="card-inside-title">Date de guérison</h2>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons text-primary">event</i> 
                                    </span>
                                
                                    <div class="form-line">
                                        <input  class="form-control datepicker" id="cdateGuerison" type="text" name="dateGuerison"  
                                        pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="Date de guérison du malade: YYYY-MM-DD">
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


    <section class="wrapper">
       
    </section>
    <script>
            $('#form-addGuerison').on('submit', function(e){
                e.preventDefault();
                var $form = $(this);
                $form.find('button').text('Traitement');
                
                url = $form.attr('action');
                               
                $.post(url, $form.serializeArray())
                    .done(function(data, text, jqxhr){  
                        alert('Guérison enregistrée avec succès!');
                        $('.loader').show();
                        $('#main-content').load('nouvelleguerison.php', function(){
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
            $( ".datepicker" ).datepicker({});                  
            $(".select2").select2();
			$('.loader').hide();
     </script>











