<?php
    session_start();
    if(isset($_SESSION['login'])){
       $idUser = $_SESSION['login'];
       $annee = $_SESSION['annee'];
        //require_once('layout.php');
       require_once('includes/connexionbd.php');
       require_once('includes/function_role.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Lister guerison")){
            header('Location:index.php');
        }else{

            
            //nombre de fidele a afficher par page
            $nbeParPage=100;
            //total de fideles enregistrés
            $total = 0;

            //selection du nombre de fideles enregistrés
            $selectNombreFidele = $db->prepare("SELECT COUNT(idmalade) AS nbretotalfidele FROM malade WHERE lisible=0 AND est_retabli = 1 AND est_decede = 0");
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
                                             malade.`idmalade` AS idmalade,
                                             malade.`dateGuerison` AS dateguerison,
                                             zone.`nomzone` AS nomzone,
                                             zone.`idzone` AS idzone
                                        FROM
                                            `personne` personne 
                                        INNER JOIN `fidele` fidele ON personne.`idpersonne` = fidele.`personne_idpersonne`
                                        INNER JOIN `malade` malade ON fidele.`idfidele` = malade.`fidele_idfidele`
                                        INNER JOIN `zone` zone ON personne.`zone_idzone` = zone.`idzone`
                                        AND fidele.lisible = true
                                        AND malade.lisible = false
                                        AND personne.lisible = true
                                        AND zone.lisible = true
                                        AND malade.est_retabli = true
                                         AND malade.est_decede = false
                                        ORDER BY nom ASC LIMIT $premierElementDeLaPage, $nbeParPage");
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
                    <li> <i class="material-icons text-primary">format_list_bulleted</i><a href="#" class="col-blue"> Liste des guérisons</a></li>
                     <li style="float: right;"> 
                        <a class="col-blue" href="report/imprimer.php?file=liste_fideles_guerison" title="Imprimer la liste des malades guéris" target="_blank"><i class="material-icons">print</i> Imprimer</a>
                    </li>
                </ol>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="card">

                    <div class="header text-center h4">
                         
                            <?php echo $total; ?> Malades guéris
                    
                            
                    </div>
                    <div class="body">

                        <div id="old_table" class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable tableau_dynamique">
                                <thead>
                                    <tr>
                                    <th><i class="material-icons iconposition">confirmation_number</i>Numéro</th>
                                        <th><i class="material-icons iconposition">code</i>Code</th>
                                        <th><i class="material-icons iconposition">people</i>Noms et prenoms</th>
                                        <th><i class="material-icons iconposition">people</i>Sexe</th>
                                        <th><span class="material-icons iconposition">location_on</span>Zone</th>
                                        <th><i class="material-icons iconposition">event</i>Date de guérison</th>
                                        <th><i class="material-icons iconposition">settings</i>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                     <tr>
                                     <th><i class="material-icons iconposition">confirmation_number</i>Numéro</th>
                                        <th><i class="material-icons iconposition">code</i>Code</th>
                                        <th><i class="material-icons iconposition">people</i>Noms et prenoms</th>
                                        <th><i class="material-icons iconposition">people</i>Sexe</th>
                                        <th><span class="material-icons iconposition">location_on</span>Zone</th>
                                        <th><i class="material-icons iconposition">event</i>Date de guérison</th>
                                        <th><i class="material-icons iconposition">settings</i>Action</th>
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
                                        <td><?php echo $liste->sexe; ?></td>
                                        <td><?php echo $liste->nomzone; ?></td>
                                        <td><?php echo $liste->dateguerison; ?></td>
                                        <td width="10%">
                                            
                                                <a class="col-blue afficher" href="afficherFidele.php?code=<?php echo $liste->idpersonne; ?>" title="Visualiser" <?php if(!has_Droit($idUser, "Afficher fidele")){echo 'disabled';}else{echo "";} ?>><i class="material-icons">loupe</i></a>
                                                <a class="col-red" href="supprimerMalade.php?code=<?php echo $liste->idmalade; ?>" title="Supprimer" <?php if(!has_Droit($idUser, "Supprimer un malade") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">delete</i> </a>
                                                
                                            
                                        </td>

                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>                            
                        </div>
                        <br>
                        <div style="text-align: center">
                                <a class="btn btn-success" href="report/imprimer.php?file=liste_fideles_guerison" title="Imprimer la liste des malades guéris" target="_blank"><i class="fa fa-print"></i> Imprimer</a>
                        </div>
                    </div>                           
                </div>
            </div> 
        </div>           
    </section>
                 
      

                    <script>

                    $('#chargement').hide();
                    
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

                             $('#main-content').load(url);

                            $('#modifiertext').html('');
                        });
                        

                    $(".tableau_dynamique").DataTable();   
					$('.loader').hide();

                    </script>

