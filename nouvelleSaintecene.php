<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Creer sainte cene")){
            header('Location:index.php');
        }else{
            $annee = $_SESSION['annee'];
            $selectMois = $db->prepare("SELECT mois, idsaintescene FROM saintescene where lisible = 1 and valide = 1");
            $selectMois->execute();
        }
    }else{
        header('Location:login.php');
    }
?>

    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li class="col-blue"><i class="material-icons">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                    <li class="col-blue"><i class="material-icons">assistant</i><a href="saintecene.php" class="afficher col-blue"> Sainte Cène</a></li>
                    <li class="col-blue"><i class="material-icons">assistant </i> Nouvelle Sainte Scène</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading h4 text-center">
                        Enregistrement d'une nouvelle Sainte Scène
                    </header>
                    <div class="panel-body">
                        <div class="form">
                            <form class="form-validate form-horizontal" id="form-addS" method="POST" enctype="multipart/form-data" action="saveSainteCene.php">
                            <div class="row clearfix inputTopSpace">
                               <div class="col-md-6">
                                <label for="Nom">Année: <span class="required">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons">edite</i>
                                        </span>
                                        <div class="form-line">
                                          <input class="form-control" id="cannee" name="annee" type="text" value="<?php echo $annee; ?>" disabled />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                <label for="Nom">Mois?: <span class="required">*</span></label>
                                        <select class="form-control " name="mois" required>
                                            <option disabled  selected>Choisir un mois</option>
                                            <?php 
                                                while ($mois = $selectMois->fetch(PDO::FETCH_OBJ)) {
                                            ?>
                                            <option value="<?php echo $mois->idsaintescene; ?>"><?php echo $mois->mois; ?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                </div>
                            </div>
                            <br> 
                                <div class="form-group">
                                    <div class="col-lg-offset-5 col-lg-10">
                                        <a class="btn btn-warning annuler" href="saintecene.php">Annuler</a>
                                      <button class="btn btn-primary" name="submit" type="submit">Enregistrer</button>
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
    $( ".datepicker" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
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

    $('#form-addS').on('submit', function(e){
        e.preventDefault();
        var $form = $(this);
        $form.find('button').text('Traitement');
        url = $form.attr('action');
        $.post(url, $form.serializeArray())
            .done(function(data, text, jqxhr){  
                alert('Sainte Cène enregistrée avec succès!');
                $('.loader').show();
                $('#main-content').load('nouvelleSaintecene.php', function(){
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
	$('.loader').hide();
</script>