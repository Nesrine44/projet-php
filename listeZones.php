<?php
    session_start();
    if(isset($_SESSION['login'])){
       $idUser = $_SESSION['login'];
       $annee = $_SESSION['annee'];
        //require_once('layout.php');
       require_once('includes/connexionbd.php');
       require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Lister les zones")){
            header('Location:index.php');
        }else{

          
            //selection des information sur les fideles
            $selectAllZone= $db->prepare("SELECT
                                             idzone, nomzone, description
                                        FROM
                                           zone
                                        where lisible = 1
                                        ORDER BY nomzone");
            $selectAllZone->execute();

        }

    }else{
        header('Location:login.php');
    }
?>

    <section class="wrapper">
       
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li class="col-blue"><i class="material-icons">home </i><a href="index.php" class="col-blue"> Accueil</a></li>
                    <li class="col-blue"><i class="material-icons">people </i> Fidèle</li>
                    <li class="col-blue"><i class="material-icons">location_on</i><a href="#" class="col-blue"> Liste zones</a></li>
                    <li style="float: right;"> 
                        <a class="col-blue" href="report/imprimer.php?file=Liste des zones" title="Imprimer la liste des ancien" target="_blank"><i class="material-icons">print</i> Imprimer</a>
                    </li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <section class="panel">

                    <div class=class="row">
                        <div class="col-lg-12">
                            <header class="panel-heading h4 text-center">
                                Liste des zones enrégistrées
                                
                            </header>  
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover tableau_dynamique">
                                <thead>
                                    <tr>
                                        <th><i class="material-icons iconposition">confirmation_number </i>numéro</th>
                                        <th><i class="material-icons iconposition">location_on </i>Nom de la zone</th>
                                        <th><i class="material-icons iconposition">description </i>Description</th>
                                        <th><i class="material-icons iconposition">settings </i> Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th><i class="material-icons iconposition">confirmation_number </i>numéro</th>
                                        <th><i class="material-icons iconposition">location_on </i>Nom de la zone</th>
                                        <th><i class="material-icons iconposition">description </i>Description</th>
                                        <th><i class="material-icons iconposition">settings </i> Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>    
                                    <?php
                                        $n=0;
                                        while($liste=$selectAllZone->fetch(PDO::FETCH_OBJ)){

                                        ?>
                                    <tr>
                                        <td style="vertical-align: middle;"><?php echo ++$n; ?></td>
                                        <td style="vertical-align: middle;"><?php echo $liste->nomzone; ?></td>
                                        <td style="vertical-align: middle;"><?php 

                                             $texte = str_replace(',', ';', $liste->description); 
                                             $texte = str_replace(';', '|', $texte);
                                             $texte = explode('|', $texte);
                                             for($i=0; $i<count($texte); $i++){

                                                echo "-".$texte[$i]."<br>";

                                             }
                                            ?></td>
                                        <td style="vertical-align: middle;" width="10%">
                                            
                                                <a class="col-green afficher" href="modifierZone.php?param=<?php echo $liste->idzone; ?>" title="Modifier" <?php if(!has_Droit($idUser, "Modifier zone") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">border_color</i></a>                              
                                                <a class="col-red" href="supprimerZone.php?code=<?php echo $liste->idzone; ?>" title="Supprimer" <?php if(!has_Droit($idUser, "Supprimer zone") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">delete</i></a>
                                            
                                        </td>

                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            </div>
                            </div>

                             <div align="center">
                                <a class="btn btn-success" href="report/imprimer.php?file=Liste des zones" title="Imprimer la liste des ancien" target="_blank"><i class="material-icons">print </i> Imprimer</a>
                            </div>

                           
                        </div>
                    </div>

                    <script>
                        $('#chargement').hide();
                    
                        $('.col-red').on('click', function(e){

                            e.preventDefault();

                            var $a = $(this);
                            var url = $a.attr('href');
                            if(window.confirm('Voulez-vous supprimer cette zone?')){
                                $.ajax(url, {

                                    success: function(){
                                        $a.parents('tr').remove();
                                    },

                                    error: function(){

                                        alert("Une erreur est survenue lors de la suppresion du malade");
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

                          $(".tableau_dynamique").DataTable();
						$('.loader').hide();

                    </script>

                </section>
            </div>
        </div>
    </section>
