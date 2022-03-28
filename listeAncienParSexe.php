<?php
session_start();
if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login'];
    //require_once('layout.php');
    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');
    require_once('includes/function_role.php');

    if(!has_Droit($idUser, "Lister fidele")){
        header('Location:index.php');
    }else{
        //nombre de fidele a afficher par page
        $nbeParPage=50;
        //total de fideles enregistrés
        $total = 0;

        //selection du nombre de fideles enregistrés
        $selectNombreFidele = $db->prepare("SELECT COUNT(idfidele) AS nbretotalfidele
                                            FROM fidele, personne
                                            WHERE fidele.personne_idpersonne = personne.idpersonne
                                            AND personne.lisible=1
                                            AND statut='ancien'
                                            AND fidele.lisible = 1 ORDER BY personne.sexe");
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
        $selectAllFidele= $db->prepare("SELECT idpersonne, nom, prenom, sexe, nomzone, idzone, codefidele
                                        FROM personne, fidele, zone
                                        WHERE idpersonne=personne_idpersonne
                                        AND personne.zone_idzone = zone.idzone
                                        AND personne.lisible=1  AND fidele.lisible=1
                                        AND statut='ancien'
                                        AND zone.lisible = true ORDER BY sexe ASC LIMIT $premierElementDeLaPage, $nbeParPage");
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
                <li><i class="icon_document_alt"></i>Conseil Anciens</li>
                <li><i class="icon_document_alt"></i><a id="listeF" href="listeAnciens.php">Liste Anciens</a></li>
                <li><i class="icon_document_alt"></i>Liste anciens reorganisés par sexe</li>
                <li style="float: right;">                      
                   <a class="" href="report/imprimer_param.php?file=liste_fideles_statut&param=ANCIEN" title="Imprimer la liste des anciens" target="_blank"><i class="fa fa-print"></i> Imprimer</a>
                </li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <section class="panel">

                <div class="row">
                    <div class="col-lg-12">
                        <header class="panel-heading">
                           <span style="font-size: 1.5em;"><?php echo $total; ?> Anciens enregistrés, reorganisés par sexe</span>
                            <!-- Module de recherche -->
                            <div class="form-group">
                                <input class="form-control" id="recherche" type="text" name="search" placeholder="Rechercher un ancien">
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
                                    <th><i class="icon_calendar"></i><a class="ordre_rangement"  href="listeAncienParSexe.php" title="réorganiser par sexe">Sexe</a></th>
                                    <th><i class="icon_pin_alt"></i><a href="listeAncienParZone.php" class="ordre_rangement" title="Réorganiser par zone">Zone</a></th>
                                    <th><i class="icon_cogs"></i> Action</th>
                                </tr>
                                <?php
                                $n=0;
                                while($liste=$selectAllFidele->fetch(PDO::FETCH_OBJ)){

                                    ?>
                                    <tr>
                                        <td><?php echo ++$n; ?></td>
                                        <td><?php echo $liste->codefidele; ?></td>
                                        <td><?php echo $liste->nom.' '.$liste->prenom; ?></td>
                                        <td><a class="listeSexe" href="traitementListeAncienParSexe.php?sexe=<?php echo $liste->sexe; ?>" title="Afficher les fidèle de <?php echo $liste->sexe; ?>"><?php echo $liste->sexe; ?></a></td>
                                        <td><a class="form_liste_fidele_zone" href="traitementListeAncienParZone?zone=<?php echo $liste->idzone; ?>" title="Afficher les fideles de cette zone"><?php echo $liste->nomzone; ?></a></td>

                                        <td width="15%">
                                            <div class="btn-group">
                                                <a class="btn btn-primary afficher" href="afficherFidele.php?code=<?php echo $liste->idpersonne; ?>" title="Visualiser" <?php if(!has_Droit($idUser, "Afficher fidele")){echo 'disabled';}else{echo "";} ?>><i class="icon_plus_alt2"></i></a>
                                                <a class="btn btn-success updateFidele" href="modifierFidele.php?id=<?php echo $liste->idpersonne; ?>" title="Modifier" <?php if(!has_Droit($idUser, "Modifier un fidele")){echo 'disabled';}else{echo "";} ?>><i class="icon_check_alt2"></i></a>
                                                <a class="btn btn-danger" href="supprimerFidele.php?code=<?php echo $liste->idpersonne; ?>" title="Supprimer fidele" <?php if(!has_Droit($idUser, "Supprimer fidele")){echo 'disabled';}else{echo "";} ?>><i class="icon_close_alt2"></i></a>
                                            </div>
                                        </td>

                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>

                           <?php pagination($pageCourante, $nbDePage, "listeAncienParSexe"); ?>

                           <div align="center">
                                <a class="btn btn-success" href="report/imprimer.php?file=..." title="Imprimer la liste des ancien" target="_blank"><i class="fa fa-print"></i> Imprimer</a>
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
    $('#chargement').hide();

    $('.btn-danger').on('click', function(e){

        e.preventDefault();

        var $a = $(this);
        var url = $a.attr('href');
        if(window.confirm('Voulez-vous supprimer cet ancien?')){
            $.ajax(url, {

                success: function(){
                    $a.parents('tr').remove();
                },

                error: function(){

                    alert("Une erreur est survenue lors de la suppresion de l'ancien");
                }
            });
        }
    });


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
        url = $i.attr('href');

        $('#main-content').load(url);

        $('#modifiertext').html('');
    });


    $('.updateFidele').on('click', function(e){

        e.preventDefault();
        var $link = $(this);
        target = $link.attr('href');

        $('#main-content').load(target);
    });


    $('#form-find').on('submit', function(e){

        e.preventDefault();

        var $a = $(this);
        var url = $a.attr('action');

        $.ajax(url, {

            success: function(){
                $('main-content').load(url);

            },

            error: function(){

                //alert("Une erreur est survenue lors de la suppresion du fidèle");
            }
        });

    });

    $('.form_liste_fidele_zone').on('click', function(e){

        e.preventDefault();

        var z = $(this);
        target = z.attr('href');

        $('#main-content').load(target);


    });
    $('.ordre_rangement').on('click', function(e){

        e.preventDefault();

        var z = $(this);
        target = z.attr('href');

        $('#main-content').load(target);


    });

    $('.listeSexe').on('click', function(e){

        e.preventDefault();

        var z = $(this);
        target = z.attr('href');

        $('#main-content').load(target);

    });

    $('#listeF').on('click', function(af){

        af.preventDefault();
        var $b = $(this);
        url = $b.attr('href');

        $('#main-content').load(url);
    });



    $('#recherche').keyup(function(){

        var txt = $(this).val();

        // alert(txt);
        if(txt != ''){
            $.ajax({

                url:"searchAnc.php",
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