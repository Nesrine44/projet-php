<?php
    session_start();
    if(isset($_SESSION['login'])){
       $idUser = $_SESSION['login'];
        //require_once('layout.php');
       require_once('includes/connexionbd.php');
       require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Lister malade")){
            header('Location:index.php');
        }else{

          
            $zone=0;

                if(!isset($_GET['zone'])){

                    $zone = $_SESSION['zone'];

                }else{

                    $zone=$_GET['zone'];
                    $_SESSION['zone'] = $zone;
                }


            //selection des information sur les fideles
            $selectAllFidele= $db->prepare("SELECT
                                             fidele.`codeFidele` AS codeFidele,
                                             fidele.`statut` AS statut,
                                             personne.`idpersonne` AS idpersonne,
                                             personne.`nom` AS nom,
                                             personne.`prenom` AS prenom,
                                             personne.`sexe` AS sexe,
                                             personne.`zone_idzone` AS idzone,
                                             fidele.`idfidele` AS idfidele,
                                             malade.`guide` AS guide,
                                             malade.`residence` AS residence,
                                             malade.`idmalade` AS idmalade,
                                             malade.`dateEnregistrementMaladie` AS datesave,
                                             malade.`dateDebutMaladie` AS datestart,
                                             zone.`nomzone` AS nomzone,
                                             zone.`idzone` AS idzone
                                        FROM
                                            `personne` personne 
                                        INNER JOIN `fidele` fidele ON personne.`idpersonne` = fidele.`personne_idpersonne`
                                        INNER JOIN `malade` malade ON fidele.`idfidele` = malade.`fidele_idfidele`
                                        INNER JOIN `zone` zone ON personne.`zone_idzone` = zone.`idzone`
                                        AND fidele.lisible = true
                                        AND malade.lisible = true
                                        AND personne.lisible = true
                                        AND zone.lisible = true 
                                        AND malade.est_retabli = false
                                         AND malade.est_decede = false
                                         AND zone.idzone=$zone
                                        ORDER BY nom ASC");
            $selectAllFidele->execute();

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
                     <li><i class="fa fa-files-o"></i><a href = "listeMalades.php" class="afficher">Liste malades</a></li>
                    <li><i class="fa fa-files-o"></i>Liste malades par zone</li>
                    <li style="float: right;"> 
                         <a class="" href="report/imprimer_param.php?file=liste_fideles_malades_zone&param=<?php echo $zone; ?>" title="Imprimer la liste des malades de la zone" target="_blank"><i class="fa fa-print"></i> Imprimer</a>
                    </li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <section class="panel">

                    <div class=class="row">
                        <div class="col-lg-12">
                            <header class="panel-heading">
                                Liste des fidèles malades de la zone                                
                            </header>  
                            
                             <div id="old_table" class="table-responsive">
                                <table class="table table-responsive table-bordered table-hover tableau_dynamique">
                                    <thead>
                                        <tr>
                                            <th><i class=""></i>#</th>
                                            <th><i class="icon_pin_alt"></i> Code</th>
                                            <th><i class="icon_profile"></i>Noms et prenoms</th>
                                            <th><i class="icon_calendar"></i>Sexe</th>
                                            <th><i class="icon_profile"></i> Guide</th>
                                            <th><i class="icon_calendar"></i> Date Eregistrement</th>
                                            <th><i class="icon_calendar"></i> Date Debut</th>
                                            <th><i class="icon_pin_alt"></i> Résidence</th>
                                            <th><i class="icon_cogs"></i> Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>    
                                        <?php
                                            $n=0;
                                            while($liste=$selectAllFidele->fetch(PDO::FETCH_OBJ)){

                                            ?>
                                        <tr>
                                            <td><?php echo ++$n; ?></td>
                                            <td><?php echo $liste->codefidele; ?></td>
                                            <td><?php echo $liste->nom.' '.$liste->prenom; ?></td>
                                            <td><a class="afficher" href="traitementListeMaladeParSexe.php?sexe=<?php echo $liste->sexe; ?>" title="Afficher les malades de sexe <?php echo $liste->sexe; ?>"><?php echo $liste->sexe; ?></a></td>
                                            <td>
                                            <?php echo $liste->guide; ?>
                                            </td>
                                            <td><?php echo $liste->datesave; ?></td>
                                            <td><?php echo $liste->datestart; ?></td>
                                            <td>
                                                    <?php echo $liste->residence; ?>
                                                </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a class="btn btn-primary afficher" href="afficherFidele.php?code=<?php echo $liste->idpersonne; ?>" title="Visualiser" <?php if(!has_Droit($idUser, "Afficher fidele")){echo 'disabled';}else{echo "";} ?>><i class="icon_plus_alt2"></i></a>
                                                    <a class="btn btn-success afficher" href="modifierMalade.php?id=<?php echo $liste->idmalade; ?>" title="Modifier" <?php if(!has_Droit($idUser, "Modifier un malade")){echo 'disabled';}else{echo "";} ?>><i class="icon_check_alt2"></i></a>
                                                    <a class="btn btn-danger" href="supprimerMalade.php?code=<?php echo $liste->idmalade; ?>" title="Supprimer" <?php if(!has_Droit($idUser, "Supprimer un malade")){echo 'disabled';}else{echo "";} ?>><i class="icon_close_alt2"></i></a>
                                                </div>
                                            </td>

                                        </tr>
                                        <?php
                                        }
                                        ?>

                                </tbody>
                            </table>
                            </div>
                        
                            <div align="center">
                                    <a class="btn btn-success" href="report/imprimer_param.php?file=liste_fideles_malades_zone&param=<?php echo $zone; ?>" title="Imprimer la liste des malades de la zone" target="_blank"><i class="fa fa-print"></i> Imprimer</a>
                           </div>
                           
                        </div>
                    </div>

                    <script>

                        $('.btn-danger').on('click', function(e){

                            e.preventDefault();

                            var $a = $(this);
                            var url = $a.attr('href');
                            if(window.confirm('Voulez-vous supprimer le malade?')){
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
