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
                $activite = null;

                $act = $db->prepare("SELECT * FROM activite WHERE idactivite = $code and lisible = 1");
                $act->execute();

                while($x=$act->fetch(PDO::FETCH_OBJ)){
                   $activite = $x;
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
                    <li class="col-blue"><i class="material-icons col-blue">home</i><a href="index.php" class="col-blue">Accueil</a></li>
                    <li><i class="material-icons col-blue">grain</i>Activités</li>
                    <li><i class="material-icons col-blue">fiber_new</i><a class="listeF col-blue" href="listeActivites.php">Liste Activités</a></li>
                    <li><i class="material-icons col-blue">list</i><a class="listeF col-blue" href="afficherActivite.php?id=<?php echo $activite->idactivite; ?>">AfficherActivité</a></li>
                    <li class="col-blue"><i class="material-icons ">border_color</i>Modifier activité groupe</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading h4 text-center">
                        Modification des groupes pour cette activité
                    </header>
                    <div class="panel-body card">
                        <div class="form">
                            <form class="form-validate form-horizontal" method="POST" enctype="multipart/form-data" >

                            <div class="panel-body bio-graph-info">
                                <div class="col-lg-12">
                                    <div class="row">                            
                                        <div class="bio-row col-lg-4">
                                            <p><span>Description</span>: <?php echo $activite->description; ?></p>
                                        </div>
                                        <div class="bio-row col-lg-4">
                                            <p><span>Date de debut </span>: <?php echo $activite->datedebut; ?></p>
                                        </div>                                              
                                        <div class="bio-row col-lg-4">
                                             <p><span>Date de fin</span>: <?php echo $activite->datefin; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4><?php echo $activite->nomactivite; ?></h4>
                            <div id="cochetou" style="text-align:center;float: right; padding-right: 160px;"><label id="modifiertext"></label>
                            </div>
                            <table class="table table-bordered table-striped table-hover js-basic-example">
                                <tbody>
                                    <tr>
                                        <th><i></i> Numéro</th>
                                        <th><i class="icon_document_alt"></i> Nom des groupes</th>
                                        <th style="text-align: center;"><i class="icon_cogs"></i>Choix </th>
                                    </tr>

                                    <?php                                    
                                    
                                    $n = 0;
                                    while($groupe=$groupes->fetch(PDO::FETCH_OBJ)){
                                        ?>
                                        <tr>
                                            <td><?php echo ++$n; ?></td>
                                            <td><?php echo $groupe->nomgroupe;?></td>
                                            <td style="text-align: center;">
                                                <div class="checkboxes">
                                                    <label class="label_check" for="checkbox-01">
                                                        <input name="choix[]" class="magazine" id="checkbox-01" value="<?php echo 'activite='.$code.'&amp;groupe='.$groupe->idgroupe; ?>" type="checkbox" <?php if(is_for_group($code, $groupe->idgroupe)) echo "checked"; ?>/>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    </tbody>
                            </table>

                              
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

                         $('.listeF').on('click', function(e){

                                e.preventDefault();

                                var z = $(this);
                                target = z.attr('href');

                                $('#main-content').load(target);                                
                            
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

$(':checkbox').click(function() {

                 var $x = $(this);                      
                 var valeur;             
               // alert('ok');
                if(this.checked){ // si 'checkAll' est coché
                  valeur = true;
                }else{ // si on décoche 'checkAll'
                  valeur = false;
                }
                var url = "saveUpdateActivite.php?"+$x.val()+"&valeur="+valeur;
                $('#modifiertext').html("Traitement...");
 
                $.ajax(url, {
                  success: function(){
                            
                   },

                  error: function(){

                    alert('Une erreur est survenue lors de la modification de l\'activité');
                  }
                });
                $('#modifiertext').html("");

              });

 $( ".datepicker" ).datepicker({

                  changeMonth: true,
                  changeYear: true,
                  dateFormat: 'yy-mm-dd'
                });
				
				$('.loader').hide();

</script>





















