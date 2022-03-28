<?php
    session_start();
    if(isset($_SESSION['login']) && isset($_SESSION['annee'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');
        require_once('includes/connexionDefault.php');

        if(!has_Droit($idUser, "Editer parametres")){

            header('Location:index.php');

        }else{

            $annee_encours = $_SESSION['annee'];
            $base_old = "paroisse".$annee_encours;
                                           
      

                    //nombre de fidele a afficher par page
                $nbeParPage=50;
                //total de fideles enregistrés
                $total = 0;

                //selection du nombre de fideles enregistrés
                $selectNombreFidele = $db->prepare("SELECT COUNT(idfidele) AS nbretotalfidele FROM ".$base_old.".fidele WHERE lisible=0 and etat = 1");
                $selectNombreFidele->execute();
                
                while($idselectNombreFidele=$selectNombreFidele->fetch(PDO::FETCH_OBJ)){
                    $total = $idselectNombreFidele->nbretotalfidele;
                }

                //calcul du nombre de pages
                $nbDePage = ceil($total/$nbeParPage);

                //navigation dans le paginator
                if(isset($_GET['p']) && !empty($_GET['p']) && ctype_digit($_GET['p'])==1){
                    if($_GET['p'] > $nbDePage){
                        $pageCourante = $nbDePage;
                    }else{
                        $pageCourante = $_GET['p'];
                    }

                }else{
                    $pageCourante = 1;
                }

                $premierElementDeLaPage = ($pageCourante - 1) * $nbeParPage;


                //selection des information sur les fideles
                $selectAllFidele= $db->prepare("SELECT idfidele, nom, prenom, sexe, nomzone, idzone, statut, codefidele FROM ".$base_old.".personne, ".$base_old.".fidele, ".$base_old.".zone  WHERE idpersonne=personne_idpersonne AND zone.idzone = personne.zone_idzone AND personne.lisible=1 AND fidele.lisible=0 AND fidele.etat = 1 ORDER BY nom ASC LIMIT $premierElementDeLaPage, $nbeParPage");
                $selectAllFidele->execute();
        }

    }else{
        header('Location:login.php');
    }
?>
    
    <section class="wrapper">    
		  <!--<div class="loader"></div>     -->
        
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="index.php">Accueil</a></li>
                    <li><i class="icon_document_alt"></i>Fidèles</li>
                    <li><i class="fa fa-files-o"></i>Liste Fidèles inactifs</li>
                    <li style="float: right;">
                        
                        <a class="" href="report/imprimer.php?file=liste_fideles_inactif" title="Imprimer la liste des fidèles" target="_blank"><i class="fa fa-print"></i> Imprimer</a>
                    </li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <section class="panel">

                    <div class="row">
                        <div class="col-lg-12">
                            <div classs="col-lg-7">
                                <div class="alert alert-success fade in col-lg-10" id="success" style="display: none; margin-left:7%; margin-right: 7%;">
                                    <button data-dismiss="alert" class="close close-sm" type="button">
                                        <i class="icon-remove"></i>
                                    </button>
                                    <strong>Terminée! </strong> <label class="resultat"></label>
                                </div>      
                                <!-- En cas d'échec -->
                                <div class="alert alert-block alert-danger fade in col-lg-10" id="echec" style="display: none; margin-left:7%; margin-right: 7%;">
                                    <button data-dismiss="alert" class="close close-sm" type="button">
                                        <i class="icon-remove"></i>
                                    </button>
                                    <strong>Echec! </strong> <label class="resultat"></label>
                                </div>  
                            </div> 
                        </div>
                    </div>

                    <div class=class="row">
                        <div class="col-lg-12">
                            <header class="panel-heading">
                                Fidèles Inactifs
                                    <!-- Module de recherche -->
                                    <div class="form-group">
                                        <input class="form-control" id="recherche" type="text" name="search" placeholder="Rechercher un fidèle">
                                    </div>                                                      
                            </header>  
                            <div id="result"> </div>
                            <div id="old_table">
                            <table class="table table-responsive table-advance table-hover tableau_dynamique">
                                <thead>
                                    <tr>
                                        <th><i class="icon_pin_alt"></i> Code</th>
                                        <th><i class="icon_profile"></i>Noms et prenoms</th>
										<th><i class=""></i>Statut paroissial</th>
                                        <th><i class="icon_calendar"></i>Sexe</th>
                                        <th><i class="icon_pin_alt"></i>Zone</th>
                                        <th><i class="icon_cogs"></i> Action</th>
                                    </tr>
                                 </thead>  
                                <tbody>    
                                    <?php
                                        $n=0;
                                        while($liste=$selectAllFidele->fetch(PDO::FETCH_OBJ)){

                                        ?>
                                    <tr>
                                        <td><?php echo $liste->codefidele; ?></td>
                                        <td><?php echo $liste->nom.' '.$liste->prenom; ?></td>
										<td><?php echo $liste->statut; ?></td>
                                        <td><?php echo $liste->sexe; ?></td>
                                        <td><?php echo $liste->nomzone; ?></td>
                                        <td>
                                            <a class="btn btn-success activation" href="activation.php?id=<?php echo $liste->idfidele; ?>" title="Activer le fidele"><i class="icon_check_alt2"></i></a>
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>

                             <?php pagination($pageCourante, $nbDePage, "activationFidele"); ?>

                            <div align="center">
                                <a class="btn btn-success" href="report/imprimer.php?file=liste_fideles_inactif" title="Imprimer la liste des fidèles inactifs" target="_blank"><i class="fa fa-print"></i> Imprimer</a>
                            </div>
                            </div>
                            </div>
                        </div>
                    </div>

                    
                </section>
            </div>
        </div>
    </section>

<script>
    
     $('.activation').on('click', function(e){

        e.preventDefault();

        var $a = $(this);
        var url = $a.attr('href');
        
        if(window.confirm('Voulez-vous activer ce fidèle?')){
            $.ajax(url, {

                dataType: "json",
                success: function(json){

                    if(json){

                        $('.resultat').html(json);
                        $('#success').hide();
                        $('#echec').show();
                        $('.loader').hide();

                    }else{
                        
                        $('#main-content').load('activationFidele.php');
                        //$('#main-content').("Fidèle activé avec succès!");
                        
                    }   
                    
                },

                error: function(){

                    alert("Une erreur est survenue lors de l\' du fidèle");
                }
            });
        }
    });

                                      
    $('#recherche').keyup(function(){
	    $('.loader').show();
        var txt = $(this).val();

        if(txt != ''){                                
            $.ajax({
                                    
                url:"searchInactif.php",
                method:"get",
                data:{search:txt},
                dataType:"text",
                success:function(data){

                    $('#old_table').hide();
                    $('#result').html(data);
                                      
                }
            });

        }else{
                               
            $('#result').html(txt);
            $('#old_table').show();
			$('.loader').hide();
        }

    });
     
      $('.item').on('click', function(i){

                            i.preventDefault();
                            $('#modifiertext').html('Chargement...');
                            var $i = $(this);                            
                            url = $i.attr('href');

                             $('#main-content').load(url);

                            $('#modifiertext').html('');
                        });
      $(".tableau_dynamique").DataTable();
	$('.loader').hide();

                    </script>
