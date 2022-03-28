<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Enregistrer une participation")){

            header('Location:index.php');

        }else{

                $selectS = $db->prepare("SELECT * FROM saintescene where lisible=1 and valide = 0");
                $selectS->execute();
               
            
        }
        
    }else{
        header('Location:login.php');
    }
?>


    <section class="wrapper">
        <div style="background-color:orange; text-align:center; font-style: italic; font-style: bold; font-size: 1.5em; width:15%; margin-left:40%;" id="chargement">Chargement...</div>
        <div class="row">
         <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="index.php">Accueil</a></li>
                    <li><i class="fa fa-files-o"></i><a href="saintecene.php" class="afficher">Sainte Cène</a></li>
                    <li><i class="fa fa-files-o"></i>Participation</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <form class="form-validate form-horizontal" id="form-addParticipation" method="POST" enctype="multipart/form-data" action="saveParticipation.php">

            <div class="col-lg-6 formulaire">
                <section class="panel">
                    <header class="panel-heading">
                        Enregistrement d'une participation à une Sainte Scène
                    </header>

                    <div class="panel-body">
                        <div class="form">
                                <div class="form-group ">
                                    <label for="cstatut" class="control-label col-lg-2">Sainte Cène</label>
                                    <div class="col-lg-10">
                                        <select class="form-control " name="saintescene" required>
                                            <option disabled selected>Choisir une Sainte Cène</option>
                                            <?php
                                            while($s=$selectS->fetch(PDO::FETCH_OBJ)){
                                                ?>
                                                <option  value="<?php echo $s->idsaintescene;?>" class="choixSaintecene"><?php echo $s->mois.$s->annee;?></option>
                                            <?php
                                            }
                                            $db=NULL;
                                            ?>
                                        </select>
                                    </div>		
                                </div>
                                <div class="form-group ">
                                    <label for="ccontribution" class="control-label col-lg-2">Contribution </label>
                                    <div class="col-lg-10">
                                        <input class="form-control" id="ccontribution" name="contribution" type="number" min="0" max="5000000" required />
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="ccontribution" class="control-label col-lg-2">Notes/Remarques </label>
                                    <div class="col-lg-10">
                                        <textarea class="form-control" id="cnote" name="note"> </textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-offset-5 col-lg-10">
                                        <button class="btn btn-default" type="reset">Effacer</button>
                                      <button class="btn btn-primary submit" name="submit" disabled  type="submit">Enregistrer</button><span class="envoi_en_cours" style="margin-left: 10px; display: none;">Enregistrement en cours...</span>
                                    </div>
                                </div>      
                            
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-lg-6 fidele"> Choisir une sainte cène pour afficher la liste des participants</div>
            </form>
        </div>
    </section>

<script type="text/javascript">
    $('#chargement').hide();

     $( ".datepicker" ).datepicker({

                  changeMonth: true,
                  changeYear: true,
                  dateFormat: 'yy-mm-dd'
    });

     $('#recherche').keyup(function(){

            $(":checkbox").attr('checked', false);
                            var txt = $(this).val();

                           // alert(txt);
                            if(txt != ''){                                
                                $.ajax({
                                    
                                    url:"searchParticipation.php",
                                    method:"get",
                                    data:{search:txt},
                                    dataType:"text",
                                    success:function(data)
                                    {
                                        $('#old_table').hide();
                                        $('#result').html(data);
                                      //alert(txt);
                                    }
                                });

                            }else{
                               // alert(txt);
                                $('#result').html(txt);
                                $('#old_table').show();
                            }

                        });

        $('.checkAll').click(function() {                        
            var test='Cocher Tous';
            var test1='Decocher Tous';
            if(this.checked){ // si 'checkAll' est coché
                $(".checkbox-default").attr('checked', true);
                $('.modifiertext').html(test1);
                                
            }else{ // si on décoche 'checkAll'

                $(".checkbox-default").attr('checked', false);
                $('.modifiertext').html(test);                            
            }
        });

        $('#form-addParticipation').on('submit', function(e){

                              e.preventDefault();
                                var $form = $(this);
                                $form.find('.submit').text('Traitement');
                             
                                url = $form.attr('action');

                $('.envoi_en_cours').show();             
                $.post(url, $form.serializeArray())
 
                    .done(function(data, text, jqxhr){  
                        
                        alert('Participation enrégistrée avec succès');
                        $('#main-content').load('nouvelleParticipation.php');                        
                    })
                    .fail(function(jqxhr){
                        alert(jqxhr.responseText);
                    })
                    .always(function(){
                        $form.find('.submit').text('Enregistrer');
                        $('.envoi_en_cours').hide();
                    });
                                
                            });

        $('.afficher').on('click', function(af){

                            af.preventDefault();
                           var $b = $(this);
                            url = $b.attr('href');

                           $('#main-content').load(url);
                        });

                        $('.item').on('click', function(i){

                            i.preventDefault();
                            $('#modifiertext').html('Chargement...');
                            var $i = $(this);                            
                            url = $i.attr('href');

                             $('#main-content').load(url);

                            $('#modifiertext').html('');
                        });
        $('.choixSaintecene').on('click', function(e){
                    e.preventDefault();
                    var $a = $(this);
                    //url = "selectRole.php?id="+$a.val();
                    url = "participant.php?id="+$a.val();
                    
                    $('.fidele').load(url)
                    $('.submit').removeAttr('disabled');
                });

							$('.loader').hide();
</script>