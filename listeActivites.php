<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        $annee = $_SESSION['annee'];
        require_once('includes/connexionbd.php');
       
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Lister activites")){

            header('Location:index.php');

        }else{
           
        
            $Activites = $db->prepare("SELECT *  FROM activite where lisible=1 ORDER by nomactivite");
            $Activites->execute();

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
                    <li> <i class="material-icons text-primary">fiber_new</i><a href="#" class="col-blue">Nouvelle activité</a></li>
                </ol>
    </div>
      
    <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">

                        <div class="header text-center h4">
                              
                        Liste de toutes les activités
                             
                                
                        </div>

                                
                           
                        <div class="body">
                            <div class="table-responsive" id="old_table">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable tableau_dynamique">  
                                    <thead>
                                        <tr>
                                            <th><i class="material-icons iconposition">confirmation_number</i>Numéro</th>
                                            <th><i class="material-icons iconposition">people</i>Activité</th>
                                            <th><i class="material-icons iconposition">description</i>Description</th>
                                            <th><i class="material-icons iconposition">event</i>Date debut</th>
                                            <th><i class="material-icons iconposition">event</i>Date de fin</th>
                                            <th><i class="material-icons iconposition">settings</i>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th><i class="material-icons iconposition">confirmation_number</i>Numéro</th>
                                            <th><i class="material-icons iconposition">people</i>Activité</th>
                                            <th><i class="material-icons iconposition">description</i>Description</th>
                                            <th><i class="material-icons iconposition">event</i>Date debut</th>
                                            <th><i class="material-icons iconposition">event</i>Date de fin</th>
                                            <th><i class="material-icons iconposition">settings</i>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>    
                                        <?php 
                                            $n = 0;
                                            while($activite=$Activites->fetch(PDO::FETCH_OBJ)){?>
                                            <tr>
                                                <td><?php echo ++$n; ?></td>
                                                <td><?php echo $activite->nomactivite;?></td>
                                                <td><?php echo $activite->description;?></td>
                                                <td><?php echo $activite->datedebut;?></td>
                                                <td><?php echo $activite->datefin;?></td>
                                                <td>
                                                    
                                                        <a class="col-blue afficher" href="afficherActivite.php?id=<?php echo $activite->idactivite; ?>" title="groupes concernés" <?php if(!has_Droit($idUser, "Afficher activite")){echo 'disabled';}else{echo "";} ?>><i class="material-icons">loupe</i></a>
                                                        <a class="col-green success afficher" href="modifierActivite.php?id=<?php echo $activite->idactivite; ?>" title="Modifier" <?php if(!has_Droit($idUser, "Modifier activite") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">border_color</i></a>
                                                        <a class="col-red" href="supprimerActivite.php?id=<?php echo $activite->idactivite; ?>" title="Supprimer" <?php if(!has_Droit($idUser, "Supprimer activite") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">delete</i></a>
                                                    
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                    <?php
                                    $db=NULL;
                                    ?>
                                </table>
                                </div>
                                <br>
                                <div align="center">
                                    <a class="btn bg-blue" href="report/imprimer.php?file=liste_activites" title="Imprimer la liste des activités" target="_blank"><i class="fa fa-print"></i> Imprimer</a>
                                </div>

                            
                        </div>
                    </div>
            </div>
        </div>

    </section>

<script>


                        $('.btn-danger').on('click', function(e){

                            e.preventDefault();

                            var $a = $(this);
                            var url = $a.attr('href');
                            if(window.confirm('Voulez-vous supprimer cette activité?')){
                                $.ajax(url, {

                                    success: function(){
                                        $a.parents('tr').remove();
                                    },

                                    error: function(){

                                        alert("Une erreur est survenue lors de la suppresion de l'\activité");
                                    }
                                });
                            }
                        });

                      $(".tableau_dynamique").DataTable();

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



                </section>

