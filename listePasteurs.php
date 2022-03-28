<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        $annee = $_SESSION['annee'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Lister pasteurs")){

            header('Location:index.php');

        }else{

            $pasteurs = $db->prepare("SELECT DISTINCT *
                                      FROM personne, pasteur, grade
                                      WHERE personne.idpersonne=pasteur.personne_idpersonne
                                      AND pasteur.grade = grade.idgrade
                                      AND  grade.lisible = 1
                                      AND personne.lisible=1
                                      AND pasteur.lisible=1 ORDER BY personne.nom");
            $pasteurs->execute();
        }

    }else{
        header('Location:login.php');
    }
?>

    <section class="wrapper">
        
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="material-icons text-primary">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                <li><i class="material-icons text-primary">people</i><a href="#" class="col-blue"> Colège Pastoral</a> </li>
                <li><i class="material-icons text-primary">list</i><a href="#" class="col-blue"> Liste des pasteurs</a> </li>
               <li style="float: right;">
                        <a class="col-blue" href="report/imprimer.php?file=liste_pasteurs" title="Imprimer la liste des pasteurs" target="_blank"><i class="material-icons">print</i> Imprimer</a>
                    </li>
                </ol>
            </div>
        </div>
        <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">

                        <div class="header text-center h4">
                              
                                Liste des Pasteurs
                             
                                
                        </div>

                        <div class="body">
                            <div class="table-responsive" id="old_table">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable tableau_dynamique">  
                                <thead>
                                    <tr>
                                        <th><i class="material-icons iconposition">confirmation_number</i>Numéro</th>
                                        <th><i class="material-icons iconposition">people</i>Noms et prenoms</th>
                                        <th><i class="material-icons iconposition">people</i>Sexe</th>
                                        <th><i class="material-icons iconposition">location_on</i> Zone</th>
                                        <th><i class="material-icons iconposition">sort</i> Grade</th>
                                        <th><i class="material-icons iconposition">settings</i> Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $n=0;
                                    while($pasteur=$pasteurs->fetch(PDO::FETCH_OBJ)){

                                        $ps=$db->prepare("SELECT nomzone
                                                          FROM zone
                                                          WHERE idzone=$pasteur->adresse");
                                        $ps->execute();
                                        while($lieus=$ps->fetch(PDO::FETCH_OBJ)){
                                            $lieu=$lieus->nomzone;
                                        }

                                ?>

                                    <tr>
                                        <td><?php echo ++$n; ?></td>
                                        <td><?php echo $pasteur->nom.' '.$pasteur->prenom; ?></td>
                                        <td><?php echo $pasteur->sexe; ?></td>
                                        <td><?php echo $lieu; ?></td>
                                        <td><?php echo $pasteur->nomgrade; ?></td>
                                        <td width="15%">
                                            <a class="col-blue afficher" href="afficherPasteur.php?idpersonne=<?php echo $pasteur->idpersonne; ?>" title="Visualiser" <?php if(!has_Droit($idUser, "Afficher le college")){echo 'disabled';}else{echo "";} ?>><i class="material-icons">loupe</i></a>
                                            <a class="col-green updatePasteur" href="modifierPasteur.php?idpersonne=<?php echo $pasteur->idpersonne; ?>" title="Modifier" <?php if(!has_Droit($idUser, "Modifier un pasteur") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">border_color</i></a>
                                            <a class="col-red" href="supprimerPasteur.php?idpersonne=<?php echo $pasteur->idpersonne; ?>&idgrade=<?php echo $pasteur->idgrade; ?>" title="Supprimer" <?php if(!has_Droit($idUser, "Supprimer pasteur") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">delete</i></a>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>
                           
                            </div>
                            <br>
                            <div align="center">
                               <a class="btn btn-success" href="report/imprimer.php?file=liste_pasteurs" title="Imprimer la liste des pasteurs" target="_blank"><i class="fa fa-print"></i> Imprimer</a>
                            </div>

                            
                        </div>
                    </div>                   
                </section>
            </div>
        </div>
    </section>

 <script>
    $('#chargement').hide();

    $('.btn-danger').on('click', function(e){

        e.preventDefault();

        var $a = $(this);
        var url = $a.attr('href');
        if(window.confirm('Voulez-vous supprimer ce pasteur?')){
            $.ajax(url, {

                success: function(){
                    $a.parents('tr').remove();
                },

                error: function(){

                    alert("Une erreur est survenue lors de la suppresion du pasteur");
                }
            });
        }
    });

    $('.afficher').on('click', function(af){

        af.preventDefault();
        var $b = $(this);
        url = $b.attr('href');

        $('#main-content').load(url);
    });

    $('#college').on('click', function(e){
                        e.preventDefault();
                        var z = $(this);
                        target = z.attr('href');
                        $('#main-content').load(target);                                
                            
                   });

                   

                    $('.updatePasteur').on('click', function(e){
                        e.preventDefault();
                        var z = $(this);
                        target = z.attr('href');
                        $('#main-content').load(target);                                
                            
                   });
	$('.loader').hide();
    $(".tableau_dynamique").DataTable();

                    </script>