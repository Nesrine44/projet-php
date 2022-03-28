<?php
    session_start();
    if(isset($_SESSION['login'])){
       $idUser = $_SESSION['login'];
        //require_once('layout.php');
       require_once('includes/connexionbd.php');
       require_once('includes/function_role.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Consulter participation")){
            header('Location:index.php');
        }else{

            if (!isset($_GET['id'])) {
                
                header('Location:index.php');
            }else{

            $idsaintescene = $_GET['id'];
            $saintecene = "";
             //nombre de fidele a afficher par page
                $nbeParPage=50;
                //total de fideles enregistrés
                $total = 0;

                //selection du nombre de fideles enregistrés
                $selectNombreFidele = $db->prepare("SELECT COUNT(fidele_idfidele) AS nbretotalfidele FROM fidelesaintescene, fidele WHERE fidele.lisible=1 AND fidele.idfidele = fidelesaintescene.fidele_idfidele and saintescene_idsaintescene = $idsaintescene");
                $selectNombreFidele->execute();
                
                while($idselectNombreFidele=$selectNombreFidele->fetch(PDO::FETCH_OBJ)){
                    $total = $idselectNombreFidele->nbretotalfidele;
                }

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


               $selectAllFidele = $db->prepare("SELECT fidele_idfidele as idfidele, idsaintescene, nom, prenom, sexe,  codefidele, idpersonne FROM personne, fidele, saintescene, fidelesaintescene   WHERE idpersonne=personne_idpersonne AND saintescene_idsaintescene = idsaintescene AND fidele.idfidele = fidelesaintescene.fidele_idfidele AND saintescene_idsaintescene = $idsaintescene AND personne.lisible=1 AND fidele.lisible=1 AND saintescene.lisible = 1 GROUP BY idfidele ORDER BY nom ASC LIMIT $premierElementDeLaPage, $nbeParPage");

                $selectAllFidele->execute();

               
                function getMontant($idfidele, $idsaintescene){

                    $montant = 0;

                    global $db; 

                    $selectMontant = $db->prepare("SELECT sum(contribution) as sommes from fidelesaintescene where fidele_idfidele = $idfidele and saintescene_idsaintescene = $idsaintescene");
                    $selectMontant->execute();

                    while($s=$selectMontant->fetch(PDO::FETCH_OBJ)){

                        $montant = ($s->sommes ? $s->sommes : 0);
                    }

                    return $montant;
                 }

                 $selectSainteC = $db->prepare("SELECT  idsaintescene, mois, annee from saintescene where idsaintescene = $idsaintescene AND saintescene.lisible = 1");
                $selectSainteC->execute();

                while ($x=$selectSainteC->fetch(PDO::FETCH_OBJ)) {
                    
                    $saintecene = $x;
                }

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
                <li><i class="fa fa-home"></i><a href="index.php">Accueil</a></li>
                <li><i class="fa fa-files-o"></i><a href="saintecene.php" class="afficher">Sainte Cène</a></li>
                <li><i class="fa fa-files-o"></i>Liste Participation du mois de <?php echo $saintecene->mois.$saintecene->annee; ?></li>
                <li style="float: right;"> 
                        <a class="" href="report/imprimer_param.php?file=fichecontributionssaintecenemois&param=<?php echo $idsaintescene; ?>" title="Imprimer la liste des contributions du mois <?php echo $saintecene->mois.$saintecene->annee; ?>" target="_blank"><i class="fa fa-print"></i> Imprimer</a>
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
                            <span style="font-size: 1.5em;"> Participation des fidèles à la Sainte Cène du mois de <?php echo $saintecene->mois.$saintecene->annee; ?></span>


                            <!-- Module de recherche -->
                            <div class="form-group">
                                <input class="form-control" id="recherche" type="text" name="search" placeholder="Rechercher un fidèle">
                            </div>        
                        </header>  
                        
                        <div id="result"> </div>
                        
                       <div id="old_table" class="table-responsive">
                            <table class="table table-striped table-hover">
                                <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th><i class="icon_pin_alt"></i> Code</th>
                                        <th><i class="icon_profile"></i>Noms et prenoms</th>                      
                                      <th style="text-align: center;">
                                        <?php echo $saintecene->mois.$saintecene->annee; ?>
                                      </th>                                         
                                        <th><i class="icon_cogs"></i> Action</th>   
                                    </tr>
                                    <!-- Insertion des infos dans la table-->
                                    <?php
                                        $n=0;
                                        while($liste=$selectAllFidele->fetch(PDO::FETCH_OBJ)){
                                            $totalFidele = 0;
                                        ?>
                                    <tr>
                                        <td><?php echo ++$n; ?></td>
                                        <td><?php echo $liste->codefidele; ?></td>
                                        <td>
                                            <a 
                                             <?php 
                                                if(has_Droit($idUser, "Afficher fidele")){
                                                    echo 'href="afficherFidele.php?code='.$liste->idpersonne.'"';
                                                }else{echo "";}
                                              ?> class="afficher" title="Afficher le fidèle"><?php echo $liste->nom.' '.$liste->prenom; ?>                                                  
                                            </a>
                                        </td>                                        
                                        <td style="text-align: center;"><?php echo getMontant($liste->idfidele, $idsaintescene); ?></td>                                        
                                        <td>
                                            <div class="btn-group">
                                                <a class="btn btn-primary afficher" href="afficherParticipation.php?code=<?php echo $liste->idfidele; ?>" title="Visualiser les details" <?php if(!has_Droit($idUser, "Consulter participation")){echo 'disabled';}else{echo "";} ?>><i class="icon_plus_alt2"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>                          
                             <?php pagination($pageCourante, $nbDePage, "listeParticipationsParMois"); ?>
                       </div>

                       <div align="center">
                            <a class="btn btn-success"  href="report/imprimer_param.php?file=fichecontributionssaintecenemois&param=<?php echo $idsaintescene; ?>" title="Imprimer la liste des contributions du mois <?php echo $saintecene->mois.$saintecene->annee; ?>" target="_blank"><i class="fa fa-print"></i> Imprimer</a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>                    
</section>

<script>
    
    $('.afficher').on('click', function(af){

                            af.preventDefault();
                           var $b = $(this);
                            url = $b.attr('href');

                           $('#main-content').load(url);
                        });

                        $('.item').on('click', function(i){

                            i.preventDefault();
                            $('#modifiertext').html('Chargement...');
                            var $i = $(this);                            
                            url = $i.attr('href')+"&id=<?php echo $idsaintescene; ?>";

                             $('#main-content').load(url);

                            $('#modifiertext').html('');
                        });

                        $('#recherche').keyup(function(){

                            var txt = $(this).val();

                           // alert(txt);
                            if(txt != ''){                                
                                $.ajax({
                                    
                                    url:"searchListeContribution.php?id=<?php echo $idsaintescene; ?>",
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
						$('.loader').hide();

</script>


