<?php
    session_start();
    if(isset($_SESSION['login'])){
       $idUser = $_SESSION['login'];
       $annee = $_SESSION['annee'];
        //require_once('layout.php');
       require_once('includes/connexionbd.php');
       require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Lister malade")){
            header('Location:index.php');
        }else{

           $sexe="";

                if(!isset($_GET['sexe'])){

                    $sexe = $_SESSION['sexe'];

                }else{

                    $sexe=$_GET['sexe'];
                    $_SESSION['sexe'] = $sexe;
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
                                             malade.`residence` AS residence,
                                             malade.`guide` AS guide,
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
                                         AND personne.sexe = '$sexe'
                                        ORDER BY nom ASC ");
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
                    <li class="col-blue"><i class="material-icons">local_hospital</i> Santé</li>
                     <li class="col-blue"><i class="material-icons">format_list_bulleted</i><a href = "listeMalades.php" class="afficher col-blue"> Liste malades</a></li>
                    <li class="col-blue"><i class="material-icons">format_list_bulleted</i> Liste malades par sexe</li>
                    <li style="float: right;"> 
                        <a class="col-blue h4" href="report/imprimer_param.php?file=liste_fideles_malades_sexe&param=<?php echo $sexe; ?>" title="Imprimer la liste des malades de sexe <?php echo $sexe; ?>" target="_blank"><i class="material-icons">print</i> Imprimer</a>
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
                                Liste des Malades de sexe "<?php echo $sexe; ?>"                                
                            </header>  
                            
                             <div id="old_table" class="table-responsive">
                                <table class="table table-responsive table-advance table-bordered table-striped table-hover tableau_dynamique">
                                    <thead>
                                        <tr>
                                            <th><i class="material-icons  iconposition"></i> Numéro</th>
                                            <th><i class="material-icons  iconposition">code</i> Code</th>
                                            <th><i class="material-icons  iconposition"></i> Noms et prenoms</th>
                                            <th><i class="material-icons  iconposition"></i> Sexe</th> 
                                            <th><i class="material-icons  iconposition">rowing</i> Guide</th>
                                            <th><i class="material-icons  iconposition"></i> Date Eregistrement</th>
                                            <th><i class="material-icons  iconposition"></i> Date Debut</th>
                                            <th><i class="material-icons  iconposition">location_city</i> Résidence</th>
                                            <th><i class="material-icons  iconposition">settings</i> Action</th>
                                        </tr>
                                    </thead>                                    
                                    <tfoot>
                                        <tr>
                                            <th><i class="material-icons  iconposition">confirmation_number</i> Numéro</th>
                                            <th><i class="material-icons  iconposition">code</i> Code</th>
                                            <th><i class="material-icons  iconposition">people</i> Noms et prenoms</th>
                                            <th><i class="material-icons  iconposition">people</i> Sexe</th> 
                                            <th><i class="material-icons  iconposition">rowing</i> Guide</th>
                                            <th><i class="material-icons  iconposition">event</i> Date Eregistrement</th>
                                            <th><i class="material-icons  iconposition">event</i> Date Debut</th>
                                            <th><i class="material-icons  iconposition">location_city</i> Résidence</th>
                                            <th><i class="material-icons  iconposition">settings</i> Action</th>
                                        </tr>
                                    </tfoot>  
                                    <tbody> 
                                        <?php
                                            $n=0;
                                            $reside = "";
                                            while($liste=$selectAllFidele->fetch(PDO::FETCH_OBJ)){
                                                $reside = addslashes(str_replace(' ', '+', $liste->residence));
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
                                            <td><a class="afficher" href="traitementListeMaladeParResidence.php?residence=<?php echo $reside; ?>" title="Afficher les malades de cette residence"><?php echo $liste->residence; ?></a></td>
                                            <td width="15%">
                                                <div class="">
                                                    <a class="col-blue afficher" href="afficherFidele.php?code=<?php echo $liste->idpersonne; ?>" title="Visualiser" <?php if(!has_Droit($idUser, "Afficher fidele")){echo 'disabled';}else{echo "";} ?>><i class="material-icons">loupe</i></a>
                                                    <a class="col-green afficher" href="modifierMalade.php?id=<?php echo $liste->idmalade; ?>" title="Modifier" <?php if(!has_Droit($idUser, "Modifier un malade") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">border_color</i></a>
                                                    <a class="col-red" href="supprimerMalade.php?code=<?php echo $liste->idmalade; ?>" title="Supprimer" <?php if(!has_Droit($idUser, "Supprimer un malade") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">delete</i></a>
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
                                    <a class="btn btn-success h3" href="report/imprimer_param.php?file=liste_fideles_malades_sexe&param=<?php echo $sexe; ?>" title="Imprimer la liste des malades de sexe <?php echo $sexe; ?>" target="_blank"><i class="material-icons">print</i> Imprimer</a>
                           </div>
                        </div>
                    </div>

                    <script>
                   
                        $('.btn-danger').on('click', function(e){

                            e.preventDefault();

                            var $a = $(this);
                            var url = $a.attr('href');
                            if(window.confirm('Voulez-vous supprimer ce malade?')){
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
