<?php
    session_start();
    if(isset($_SESSION['login'])){
       $idUser = $_SESSION['login'];
       $annee = $_SESSION['annee'];
        //require_once('layout.php');
       require_once('includes/connexionbd.php');
       require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Lister fidele")){
            header('Location:index.php');
        }else{
            //nombre de fidele a afficher par page
            $nbeParPage=100;
            //total de fideles enregistrés
            $total = 0;

            //selection du nombre de fideles enregistrés
            $selectNombreFidele = $db->prepare("SELECT COUNT(idfidele) AS nbretotalfidele FROM fidele WHERE lisible=1 AND est_decede=0");
            $selectNombreFidele->execute();

             
            while($idselectNombreFidele=$selectNombreFidele->fetch(PDO::FETCH_OBJ)){
                 $total =  $idselectNombreFidele->nbretotalfidele;
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
            $selectAllFidele= $db->prepare("SELECT distinct idpersonne, nom, prenom, sexe, nomzone, idzone, statut, codefidele FROM personne, fidele, zone  WHERE idpersonne=personne_idpersonne AND zone.idzone = personne.zone_idzone AND personne.lisible=1 AND fidele.lisible=1 AND est_decede=0 ORDER BY nom ASC LIMIT $premierElementDeLaPage, $nbeParPage");
            $selectAllFidele->execute();
             

            }

    }else{
        header('Location:login.php');
    }
?>
    
    <section class="wrapper">    
		  <!--<div class="loader"></div>     -->
        
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <ol class="breadcrumb">
                    <li class="col-blue"><i class="material-icons">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                    <li class="col-blue"><i class="material-icons">people</i> Fidèles</li>
                    <li class="col-blue"><i class="material-icons">format_list_bulleted</i> Liste Fidèles</li>
                    <li style="float: right;">
                        
                            <a class="col-blue" href="report/imprimer.php?file=liste_fideles" title="Imprimer la liste des fidèles" target="_blank"><i class="fa material-icons">print</i> Imprimer</a>
                    </li>
                    <li style="float: right;">          
                            <a class="col-blue" href="export/export_liste_fideles.php" target="_blank" title="Exporter la liste des fidèles" ><i class="fa material-icons">filter</i> Exporter vers Excel</a>
                       
                    </li>  
                </ol>
                </div>
        </div>

        <div class="row card">
            <div class="col-lg-12">
                <section class="panel ">

                    <div class="row card">
                        <div class="col-lg-12">
                            <header class="panel-heading input-group">
                                <h4 class="text-center"><?php echo $total; ?> Fidèles enregistrés</h4>
                                    <!-- Module de recherche -->
                                   <!--  <div class="form-line">
                                        <input class="form-control col-black h4" id="recherche" type="text" name="search" placeholder="Rechercher un fidèle">
                                    </div>  -->           

                                           
                            </header>  
                            <div id="result" class="table-responsive"> </div>
                            <div id="old_table" class="table-responsive ">
                            <table class="table table-responsive table-advance table-bordered table-striped table-hover tableau_dynamique">
                                <thead>
                                    <tr>
                                        <th><i class="material-icons  iconposition">confirmation_number</i> Numéro</th>
                                        <th><i class="material-icons iconposition">code</i> Code</th>
                                        <th><i class="material-icons iconposition">people</i> Noms et prenoms</th>
										 <th><i class="material-icons iconposition">ev_station</i> Statut paroissial</th>
                                        <th><i class="material-icons iconposition">people</i> Sexe</th>
                                        <th><i class="material-icons iconposition">location_on</i> Zone</th>
                                        <th><i class="material-icons iconposition">settings</i> Action</th>
                                    </tr>
                                </thead> 
                                <tfoot>
                                    <tr>
                                        <th><i class="material-icons iconposition">confirmation_number</i> Numéro</th>
                                        <th><i class="material-icons iconposition">code</i> Code</th>
                                        <th><i class="material-icons iconposition">people</i> Noms et prenoms</th>
                                         <th><i class="material-icons iconposition">ev_station</i> Statut paroissial</th>
                                        <th><i class="material-icons iconposition">people</i> Sexe</th>
                                        <th><i class="material-icons iconposition">location_on</i> Zone</th>
                                        <th><i class="material-icons iconposition">settings</i> Action</th>
                                    </tr>
                                </tfoot>     
                                <tbody>
                                    <?php
                                        $n=0;
                                        while($liste=$selectAllFidele->fetch(PDO::FETCH_OBJ)){
                                             $statut = str_replace(" ", "+", $liste->statut);
                                        ?>
                                    <tr>
                                        <td><?php echo ++$n; ?></td>
                                        <td><?php echo $liste->codefidele; ?></td>
                                        <td><?php echo $liste->nom.' '.$liste->prenom; ?></td>
										<td><a class="afficher" href="traitementListeFideleParStatut.php?statut=<?php echo $statut; ?>" title="Afficher les fidèle de statut <?php echo $liste->statut; ?>"><?php echo $liste->statut; ?></a></td>
                                        <td><a class="afficher" href="traitementListeFideleParSexe.php?sexe=<?php echo $liste->sexe; ?>" title="Afficher les fidèle de sexe <?php echo $liste->sexe; ?>"><?php echo $liste->sexe; ?></a></td>
                                        <td><a class="afficher" href="traitementListeFideleParZone.php?zone=<?php echo $liste->idzone; ?>" title="Afficher les fideles de cette zone"><?php echo $liste->nomzone; ?></a></td>

                                        <td>
                                            
                                                <a class=" col-blue afficher" href="afficherFidele.php?code=<?php echo $liste->idpersonne; ?>" title="Visualiser" <?php if(!has_Droit($idUser, "Afficher fidele")){echo 'disabled';}else{echo "";} ?>><i class="material-icons">loupe</i></a>

                                                <a class=" col-green afficher" href="modifierFidele.php?id=<?php echo $liste->idpersonne; ?>" title="Modifier" <?php if(!has_Droit($idUser, "Modifier un fidele") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">border_color</i></a>

                                                <a class=" col-red" href="supprimerFidele.php?code=<?php echo $liste->idpersonne; ?>" title="Supprimer" <?php if(!has_Droit($idUser, "Supprimer fidele") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">delete</i></a>
                                           
                                        </td>

                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <?php //pagination($pageCourante, $nbDePage, "listeFideles"); ?>
                                
                         
                            </div>
                            </div>
                        </div>
                    </div>

                    
                </section>
            </div>
        </div>
    </section>

<script>
                        $('.col-red').on('click', function(e){

                            e.preventDefault();

                            var $a = $(this);
                           var url = $a.attr('href');
                           
                            if(window.confirm('Voulez-vous supprimer ce fidèle?')){
                                $.ajax(url, {

                                    success: function(){
                                        $a.parents('tr').remove();
                                    },

                                    error: function(){

                                        alert("Une erreur est survenue lors de la suppresion du fidèle");
                                    }
                                });
                            }
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

                        $('.item').on('click', function(i){

                            i.preventDefault();
                            $('#modifiertext').html('Chargement...');
                            var $i = $(this);                            
                            url = $i.attr('href');
                           $('.loader').show();
                             $('#main-content').load(url, function(){
                                $('.loader').hide();
                                $('#modifiertext').html('');
                             });

                            
                        });
                       
                    
                        $('#recherche').keyup(function(){
							$('.loader').show();
                            var txt = $(this).val();

                           // alert(txt);
                            if(txt != ''){                                
                                $.ajax({
                                    
                                    url:"search.php",
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
								$('.loader').hide();
                            }

                        });

						$('.loader').hide();
                         $(".tableau_dynamique").DataTable();

                    </script>
