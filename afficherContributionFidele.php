<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];

        $annee = $_SESSION['annee'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Consulter participation")){

            header('Location:index.php');

        }else{

             $i = 0;
             $idfidele=0;
             $idpersonne=0;
             $page='';

             if(!isset($_GET['idfidele']) || !isset($_GET['idpersonne']) || !isset($_GET['page'])){

                
                header('Location:index.php');

             }else{


                
                function getInfoContribution($idcontribution, $idfidele){

                    global $db;

                    $select = $db->prepare("

                                SELECT
                                     contributionfidele.`montant` AS montant,
                                     contributionfidele.`date` AS date_contribution,
                                     contributionfidele.`saintescene_idsaintescene` AS idsaintescene,
                                     saintescene.`mois` AS mois,
                                     saintescene.`annee` AS annee,
                                     idcontributionfidele
                                FROM
                                     `saintescene` saintescene INNER JOIN `contributionfidele` contributionfidele ON saintescene.`idsaintescene` = contributionfidele.`saintescene_idsaintescene`
                                     INNER JOIN fidele on idfidele = fidele_idfidele
                                AND contributionfidele.lisible = 1
                                AND fidele.lisible = 1
                                And saintescene.lisible = 1
                                AND saintescene.valide = 0
                                AND fidele_idfidele = $idfidele
                                AND contribution_idcontribution =$idcontribution
                                GROUP BY idsaintescene
                        ");

                    $select->execute();

                    return $select;
                }

                $idfidele=$_GET['idfidele'];
                $idpersonne=$_GET['idpersonne'];
                $page=$_GET['page'];

                $select2 = "SELECT nom, prenom FROM personne WHERE idpersonne=$idpersonne";
                        $res2=$db->query($select2);
                    while($id2=$res2->fetch(PDO::FETCH_ASSOC)){
                            $identifiant3=$id2['nom'];
                            $identifiant4=$id2['prenom'];
                    }

                $contributions = $db->prepare("SELECT idcontribution, type from contribution where lisible = 1");
                $contributions->execute();

                $total = 0;
            }
        }
            
    }else{
        header('Location:login.php');
    }
?>

    <section class="wrapper">
    
       
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="material-icons">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                    <li><i class="material-icons">assistant</i><a href="saintecene.php" class="afficher col-blue"> Sainte Cène</a></li>
                    <li><i class="material-icons">assistant</i><a class="afficher col-blue" href="ficheContributionParType.php"> Bilan des particaptions </a></li>
                    <li><i class="material-icons">people</i> Par Fidèle</li>
                     <li style="float: right;"> 
                        <a class="col-blue h4" href="report/imprimer_param.php?file=fichecontributionfidele&param=<?php echo $idpersonne;?>" title="Imprimer la liste des contributions du fidèle" target="_blank"><i class="material-icons">print</i> Imprimer</a>
                    </li>
                </ol>
            </div>
        </div>
        <div class="row card">
            <div class="col-lg-12">
                <section class="panel">

                    <div class=class="row">
                        <div class="col-lg-12">
                            <header class="panel-heading h4 text-center">

                                Fiche de contribution de <h3 style="display: inline;"><a class="afficher" href="afficherFidele.php?code=<?php echo $idpersonne; ?>" title="Visualiser" <?php if(!has_Droit($idUser, "Afficher fidele")){echo 'disabled';}else{echo "";} ?>><?php echo $identifiant3.' '.$identifiant4; ?></a></h3>
                                <a class="btn btn-warning afficher" title="retourner à la page précédente" href="<?php echo $page;?>" style="float: right;">&laquo;  Précédent</a>
                            </header>

                            <br>
                            
                            <?php
                               
                                while($type=$contributions->fetch(PDO::FETCH_OBJ)){

                            ?>     
                                    <div style="font-size: 1.5em;"><b>Contribution de type <?php echo ' '.$type->type; ?></b></div>
                                    <table class="table table-striped table-advance table-hover">
                                        <tbody>
                                            <tr>
                                                <th>Sainte Cène</th>
                                                <th><i class="icon_calendar"></i>Date</th>
                                                <th><i class="icon_pin_alt"></i>Montant(Fcfa)</th>
                                                <th>Action</th>
                                            </tr>                                    

                                            <?php 
                                                $sous_total = 0;
                                                $query = getInfoContribution($type->idcontribution, $idfidele);

                                                while($data=$query->fetch(PDO::FETCH_OBJ)){

                                            ?>
                                                <tr>
                                                    <td><?php echo $data->mois.' '.$data->annee; ?></td>
                                                    <td><?php echo $data->date_contribution; ?></td>
                                                    <td><?php echo $data->montant; ?></td>
                                                    <td width="10%"> 
                                                     <a class="col-green afficher" href="modifierContribution.php?id=<?php echo $data->idcontributionfidele; ?>&amp;page=<?php echo $page; ?>" title="Modifier" <?php if(!has_Droit($idUser, "Modifier contribution")|| (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">border_color</i></a>

                                                    <a class="col-red" href="supprimerContribution.php?id=<?php echo $data->idcontributionfidele; ?>" title="Supprimer" <?php if(!has_Droit($idUser, "Supprimer contribution")|| (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">delete</i></a>
                                                </td>
                                                </tr>
                                               
                                            <?php
                                                    $sous_total += $data->montant;
                                                }
                                            ?>

                                            <tr>
                                                <td colspan="2">Total</td>
                                                <td><h4><?php echo $sous_total; ?></h4></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table><br>
                            <?php
                                   
                                }
                            ?>
                            <div align="center">
                               <a class="btn btn-success" href="report/imprimer_param.php?file=fichecontributionfidele&param=<?php echo $idpersonne; ?>" title="Imprimer la liste des contributions du fidèle" target="_blank"><i class="material-icons">print</i> Imprimer</a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>

<script type="text/javascript">
    
    $('#chargement').hide();

        $('.afficher').on('click', function(af){
            $('.loader').show();
        af.preventDefault();
        var $b = $(this);
        url = $b.attr('href');
        

       $('#main-content').load(url);
    });

    $('.col-red').on('click', function(e){

                            e.preventDefault();

                            var $a = $(this);
                          var url = $a.attr('href');
                          
                            if(window.confirm('Voulez-vous supprimer?')){
                                $.ajax(url, {

                                    success: function(){
                                        $a.parents('tr').remove();
                                       /*url = 'ficheContributionParType.php';
                                        $('#main-content').load(url);*/

                                    },

                                    error: function(){

                                        alert("Une erreur est survenue lors de la suppresion de la contribution");
                                    }
                                });
                            }
                        });
						$('.loader').hide();

</script>

