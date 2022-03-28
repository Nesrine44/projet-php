<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Modifier un malade")){

            header('Location:index.php');

        }else{

           $malade = null;

           $idmalade = $_GET['id'];

            //selection de la liste fideles
           $selectfideles = $db->prepare("SELECT codeFidele, nom, prenom, idfidele, idpersonne FROM fidele, personne where personne.idpersonne = fidele.personne_idpersonne AND fidele.lisible=1 AND personne.lisible=1 AND fidele.idfidele NOT IN(SELECT fidele_idfidele FROM malade where est_retabli = 0  AND malade.est_decede = 0 AND lisible = 1)");
            $selectfideles->execute();



            //selection du malade à modifier
            $selectMalade = $db->prepare("SELECT codefidele, nom, prenom, guide, residence, dateEnregistrementMaladie AS datesave, dateDebutMaladie AS datestart FROM personne
                INNER JOIN fidele ON personne.idpersonne = fidele.personne_idpersonne
                INNER JOIN malade ON fidele.idfidele = malade.fidele_idfidele
                AND malade.idmalade = $idmalade
                AND personne.lisible = true
                AND fidele.lisible = true
                AND malade.lisible = true
                AND malade.est_retabli = false");
            $selectMalade->execute();
           
           while ($x = $selectMalade->fetch(PDO::FETCH_OBJ)) {
               
               $malade = $x;
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
                    <li><i class="icon_document_alt"></i>Santé</li>
                    <li><i class="fa fa-files-o"></i><a href="listeMalades.php" id="listeM">Liste Malades</a></li>
                    <li><i class="fa fa-files-o"></i>Modifier Malade</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        Modification d'un Malade
                    </header>
                    <div class="panel-body">
                        <div class="form">
                            <form class="form-validate form-horizontal" id="form_updateMalade" method="POST" enctype="multipart/form-data" action="updateMalade.php?id=<?php echo $idmalade; ?>">
                                
                                <div class="form-group">
                                    <label for="cCodefidele" class="control-label col-lg-2">Code malade</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" id="codefidele" type="text" name="codefidele" value="<?php echo $malade->codefidele.': '.$malade->nom.' '.$malade->prenom;?>" disabled/>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="cdateEnregistrement" class="control-label col-lg-2">Date d'enregistrement</label>
                                    <div class="col-lg-10">
                                        <input class="form-control datepicker" id="cdateEnregistrement" type="text" name="dateEnregistrement" value="<?php echo $malade->datesave;?>" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="YYYY-MM-DD"/>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="cdateDebutMaladie" class="control-label col-lg-2">Date début maladie</label>
                                    <div class="col-lg-10">
                                        <input class="form-control datepicker" id="cdateDebutMaladie" type="text" name="dateDebutMaladie" value="<?php echo $malade->datestart;?>"  pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="YYYY-MM-DD"/>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="cresidence" class="control-label col-lg-2">Résidence<span class="required">*</span></label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control " name="residence" value="<?php echo $malade->residence; ?>" required>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="cguide" class="control-label col-lg-2">Guide<span class="required">*</span></label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control " name="guide" value="<?php echo $malade->guide; ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-offset-5 col-lg-10">
                                        <button class="btn btn-default" id="annuler" type="button">Annuler</button>
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
<script>
 $('#listeM').on('click', function(af){

                            af.preventDefault();
                           var $b = $(this);
                            url = $b.attr('href');

                           $('#main-content').load(url);
                        });

                $('#form_updateMalade').on('submit', function(e){

                              e.preventDefault();
                                var $form = $(this);
                                $form.find('button').text('Traitement');
                                
                                url = $form.attr('action');

                               
                $.post(url, $form.serializeArray())
 
                    .done(function(data, text, jqxhr){  
                        
                        alert('Malade modifié avec succès!');

                        $('.loader').show();
                        $('#main-content').load('listeMalades.php', function(){
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

                $('#annuler').on('click', function(en){

                    en.preventDefault();
                    var $link = $(this);
                    target = $link.attr('href');
                    if(window.confirm("Voulez-vous vraiment annuler?")){
                        $('.loader').show();
                        $('#main-content').load('listeMalades.php', function(){
                            $('.loader').hide();
                        });
                    }
                    
                });           

                 $( ".datepicker" ).datepicker({

                  changeMonth: true,
                  changeYear: true,
                  dateFormat: 'yy-mm-dd'
                });     
				$('.loader').hide();
</script>