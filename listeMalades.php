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

           
            //nombre de fidele a afficher par page
            $nbeParPage=100;
            //total de fideles enregistrés
            $total = 0;

            //selection du nombre de fideles enregistrés
            $selectNombreFidele = $db->prepare("SELECT COUNT(idmalade) AS nbretotalfidele FROM malade WHERE lisible=1 AND est_retabli = 0");
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
                                        AND fidele.lisible = 1
                                        AND malade.lisible = 1
                                        AND personne.lisible = 1
                                        AND zone.lisible = 1
                                        AND malade.est_retabli = 0
                                         AND malade.est_decede = 0
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
                    <li> <i class="material-icons text-primary">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                    <li> <i class="material-icons text-primary">local_hospital</i><a href="#" class="col-blue"> Santé</a></li>
                     <li> <i class="material-icons text-primary">format_list_bulleted</i><a href="#" class="col-blue"> Liste des malades</a></li>
                                       
                    <li style="float: right;"> 
                        <a class="col-blue" href="report/imprimer.php?file=liste_fideles_malades" title="Imprimer la liste des malades" target="_blank"><i class="material-icons iconposition">print</i>Imprimer</a>
                    </li>
                    <li style="float: right;"><a href="report/imprimer.php?file=fiche_communion_malades" class="col-blue" title="Imprimer la fiche de communion des malades" target="_blank"><i class="material-icons iconposition">print</i>Fiche de communion des malades</a></li>
                </ol>
            </div>
        </div>
        
            <div class="row clearfix card">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 card">
                   

                        <div class="header text-center h4">
                              
                                <?php echo $total; ?> Malades enregistrés
                                 
                        </div>

                        <div class="body">
                            <div class="table-responsive" id="old_table">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable tableau_dynamique">  
                                    <thead>
                                        <tr>
                                            <th><i class="material-icons iconposition"></i>Numéro</th>
                                            <th><i class="material-icons iconposition">code</i>Code</th>
                                            <th><i class="material-icons iconposition"></i>Noms et prenoms</th>
                                            <th><i class="material-icons iconposition"></i>Sexe</th>
                                            <th><i class="material-icons iconposition">rowing</i>Guide</th>
                                            <th><i class="material-icons iconposition"></i>Date Eregistrement</th>
                                            <th><i class="material-icons iconposition"></i>Date Debut</th>
                                            <th><i class="material-icons iconposition">location_city</i>Résidence</th>
                                            <th><i class="material-icons iconposition">settings</i>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th><i class="material-icons iconposition">confirmation_number</i>Numéro</th>
                                            <th><i class="material-icons iconposition">code</i>Code</th>
                                            <th><i class="material-icons iconposition">people</i>Noms et prenoms</th>
                                            <th><i class="material-icons iconposition">people</i>Sexe</th>
                                            <th><i class="material-icons iconposition">rowing</i>Guide</th>
                                            <th><i class="material-icons iconposition">event</i>Date Eregistrement</th>
                                            <th><i class="material-icons iconposition">event</i>Date Debut</th>
                                            <th><i class="material-icons iconposition">location_city</i>Résidence</th>
                                            <th><i class="material-icons iconposition">settings</i>Action</th>
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
                                            <td>
                                                <a class="afficher" href="traitementListeMaladeParSexe.php?sexe=<?php echo $liste->sexe; ?>" title="Afficher les malades de sexe <?php echo $liste->sexe; ?>"><?php echo $liste->sexe; ?></a>
                                            </td>
                                        
                                            <td>
                                                <?php echo $liste->guide; ?>
                                            </td>
                                            <td><?php echo $liste->datesave; ?></td>
                                            <td><?php echo $liste->datestart; ?></td>
                                            <td><a class="afficher" href="traitementListeMaladeParResidence.php?residence=<?php echo $reside; ?>" title="Afficher les malades de cette residence"><?php echo $liste->residence; ?></a></td>
                                            <td width="15%">
                                                
                                                    <a class="col-blue afficher" href="afficherFidele.php?code=<?php echo $liste->idpersonne; ?>" title="Visualiser" <?php if(!has_Droit($idUser, "Afficher fidele")){echo 'disabled';}else{echo "";} ?>><i class="material-icons">loupe</i></a>
                                                    <a class="col-green afficher" href="modifierMalade.php?id=<?php echo $liste->idmalade; ?>" title="Modifier" <?php if(!has_Droit($idUser, "Modifier un malade") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">border_color</i></a>
                                                    <a class="col-red" href="supprimerMalade.php?code=<?php echo $liste->idmalade; ?>" title="Supprimer" <?php if(!has_Droit($idUser, "Supprimer un malade") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">delete</i></a>
                                                
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
                                    <a class="btn btn-success" href="report/imprimer.php?file=liste_fideles_malades" title="Imprimer la liste des malades" target="_blank"><i class="material-icons iconposition">print</i> Imprimer</a>
                            </div>
                        </div>
                </div>
            </div>                
                            

                       
                    

                    <script>
                    
                        $('.col-red').on('click', function(e){

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
                            $('.loader').show();
                            af.preventDefault();
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

                             $('#main-content').load(url);

                            $('#modifiertext').html('');
                        });

                        $(".tableau_dynamique").DataTable();
						$('.loader').hide();

                    </script>
        
    </section>
