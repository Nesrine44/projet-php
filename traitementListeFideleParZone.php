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
          
            $zone="";

                if(!isset($_GET['zone'])){

                    $zone = $_SESSION['zone'];

                }else{

                    $zone=$_GET['zone'];
                    $_SESSION['zone'] = $zone;
                }

             

                //selection des information sur les fideles
                $selectAllFidele= $db->prepare("SELECT idpersonne, nom, prenom, sexe, idzone, nomzone, codefidele FROM personne, fidele, zone  WHERE idpersonne=personne_idpersonne AND personne.zone_idzone = zone.idzone AND personne.lisible=1 AND zone.lisible = true AND zone.idzone=$zone AND fidele.lisible=1 ORDER BY nomzone ASC ");
                $selectAllFidele->execute();
        }

    
}else{

    header('Location:login.php');
}
?>

    <section class="wrapper">
       
        <div class="row ">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li class="col-blue"><i class="material-icons">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                    <li class="col-blue"><i class="material-icons">people</i> Fidèles</li>
                    <li class="col-blue"><i class="material-icons">format_list_bulleted</i><a class="afficher col-blue" href="listeFideles.php"> Liste Fidèles</a></li>
                    <li class="col-blue"><i class="material-icons">format_list_bulleted</i> Liste fidèle par zone</li>
                    <li style="float: right;">
                        
                           <a class="col-blue h4" href="report/imprimer_param.php?file=liste_fideles_zone&param=<?php echo $zone; ?>" title="Imprimer la liste des fidèles" target="_blank"><i class="material-icons">print</i> Imprimer</a>
                    </li>
                    <li style="float: right;">          
                            <a class="col-blue" href="export/export_liste_fideles_zone.php?zone=<?php echo $zone; ?>" target="_blank" title="Exporter la liste des fidèles par zone" ><i class="material-icons">filter</i> Exporter vers Excel</a>
                       
                    </li>  
                </ol>
            </div>
        </div>

        <div class="row card">
            <div class="col-lg-12">
                <section class="panel">

                    <div class="row">
                        <div class="col-lg-12">
                            <header class="panel-heading h4 text-center">
                                Liste des fidèles dans la zone
                            </header> 

                             <div id="result"> </div>
                             <div id="old_table" class="table-responsive">
                                <table class="table table-responsive table-advance table-bordered table-striped table-hover tableau_dynamique">
                                    <thead>
                                        <tr>
                                            <th><i class="material-icons  iconposition">confirmation_number</i> Numéro</th>
                                            <th><i class="material-icons iconposition">code</i> Code</th>
                                            <th><i class="material-icons iconposition">people</i> Noms et prenoms</th>
                                            <th><i class="material-icons iconposition">people</i> Sexe</th>
                                            <th><i class="material-icons iconposition">location_on</i> Zone</th>
                                            <th><i class="material-icons iconposition">settings</i> Action</th>
                                        </tr>
                                    </thead>                                    
                                    <tfoot>
                                        <tr>
                                            <th><i class="material-icons  iconposition">confirmation_number</i> Numéro</th>
                                            <th><i class="material-icons iconposition">code</i> Code</th>
                                            <th><i class="material-icons iconposition">people</i> Noms et prenoms</th>
                                            <th><i class="material-icons iconposition">people</i> Sexe</th>
                                            <th><i class="material-icons iconposition">location_on</i> Zone</th>
                                            <th><i class="material-icons iconposition">settings</i> Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>    
                                        <?php
                                            $n=0;
                                            while($liste=$selectAllFidele->fetch(PDO::FETCH_OBJ)){

                                            ?>
                                        <tr>
                                            <td><?php echo ++$n; ?></td>
                                            <td><?php echo $liste->codefidele; ?></td>
                                            <td><?php echo $liste->nom.' '.$liste->prenom; ?></td>
                                            <td><a class="afficher" href="traitementListeFideleParSexe.php?sexe=<?php echo $liste->sexe; ?>" title="Afficher les fidèle de <?php echo $liste->sexe; ?>"><?php echo $liste->sexe; ?></a></td>
                                            <td><a class="afficher" href="traitementListeFideleParZone.php?zone=<?php echo $liste->idzone; ?>" title="Afficher les fideles de cette zone"><?php echo $liste->nomzone; ?></a></td>

                                            <td width="15%">
                                                <div class="">
                                                    <a class="col-blue afficher" href="afficherFidele.php?code=<?php echo $liste->idpersonne; ?>" title="Visualiser" <?php if(!has_Droit($idUser, "Afficher fidele")){echo 'disabled';}else{echo "";} ?>><i class="material-icons">loupe</i></a>
                                                    <a class="col-green afficher" href="modifierFidele.php?id=<?php echo $liste->idpersonne; ?>" title="Modifier" <?php if(!has_Droit($idUser, "Modifier un fidele") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">border_color</i></a>
                                                    <a class="col-red" href="supprimerFidele.php?code=<?php echo $liste->idpersonne; ?>" title="Supprimer" <?php if(!has_Droit($idUser, "Supprimer fidele") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">delete</i></a>
                                                </div>
                                            </td>

                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
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

                    

$('#recherche').keyup(function(){

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
                            
                        $(".tableau_dynamique").DataTable();
                        $('.loader').hide();
                    </script>
