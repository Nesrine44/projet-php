<?php
session_start();
if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login'];
    $annee = $_SESSION['annee'];
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
                                            FROM fidele
                                            WHERE lisible=1
                                            AND statut='conseiller'");
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
        $selectAllFidele= $db->prepare("SELECT idpersonne, nom, prenom, sexe, nomzone, idzone, statut, codefidele
                                        FROM personne, fidele, zone
                                        WHERE idpersonne=personne_idpersonne
                                        AND zone.idzone = personne.zone_idzone
                                        AND personne.lisible=1 AND fidele.lisible=1
                                        AND statut='conseiller' ORDER BY nom ASC LIMIT $premierElementDeLaPage, $nbeParPage");
        $selectAllFidele->execute();

    }

}else{
    header('Location:login.php');
}
?>

<section class="wrapper">
    <!--<div class="loader"></div>     -->

    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
            <ol class="breadcrumb">
                <li> <i class="material-icons text-primary">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                <li> <i class="material-icons text-primary">school</i><a href="#" class="col-blue"> Conseils d'Anciens</a></li>
                <li> <i class="material-icons text-primary">format_list_numbered</i><a href="#" class="col-blue">Liste de conseillers</a></li>
                
                <li style="float: right;">                      
                   <a class="" href="report/imprimer_param.php?file=liste_fideles_statut&param=ANCIEN" title="Imprimer la liste des anciens" target="_blank"><i class="material-icons text-primary">print</i> Imprimer</a>
                </li>
        </div>
    </div>

    <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="card">

                    <div class="header text-center h4">
                            
                    Liste des Conseillers enrégistrés
                            
                            
                    </div>
  
                    <div class="body">
                            <div class="table-responsive" id="old_table">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable tableau_dynamique">  
                                    <thead>

                                        <tr>
                                            <th><i class="material-icons iconposition">confirmation_number</i>Numéro</th>
                                            <th><i class="material-icons iconposition">code</i>Code</th>
                                            <th><i class="material-icons iconposition">people</i>Noms et prenoms</th>
                                            <th><i class="material-icons iconposition">ev_station</i>Statut paroissial</th>
                                            <th><i class="material-icons iconposition">people</i>Sexe</th>
                                            
                                            <th><i class="material-icons iconposition">location_on</i>Zone</th>
                                        
                                            <th><i class="material-icons iconposition">settings</i>Action</th>
                                        </tr>                                    
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th><i class="material-icons iconposition">confirmation_number</i>Numéro</th>
                                            <th><i class="material-icons iconposition">code</i>Code</th>
                                            <th><i class="material-icons iconposition">people</i>Noms et prenoms</th>
                                            <th><i class="material-icons iconposition">ev_station</i>Statut paroissial</th>
                                            <th><i class="material-icons iconposition">people</i>Sexe</th>
                                            
                                            <th><i class="material-icons iconposition">location_on</i>Zone</th>
                                        
                                            <th><i class="material-icons iconposition">settings</i>Action</th>
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
                                            <td><?php echo $liste->statut; ?></td>
                                            <td><a class="afficher" href="traitementListeconseillerParSexe.php?sexe=<?php echo $liste->sexe; ?>" title="Afficher les conseillers de sexe <?php echo $liste->sexe; ?>"><?php echo $liste->sexe; ?></a></td>
                                            <td><a class="afficher" href="traitementListeconseillerParZone.php?zone=<?php echo $liste->idzone; ?>" title="Afficher les conseillers de cette zone"><?php echo $liste->nomzone; ?></a></td>

                                            <td width="15%">

                                                <a class="col-blue afficher" href="afficherFidele.php?code=<?php echo $liste->idpersonne; ?>" title="Visualiser" <?php if(!has_Droit($idUser, "Afficher fidele")){echo 'disabled';}else{echo "";} ?>><i class="material-icons">loupe</i></a>
                                                <a class="col-green afficher" href="modifierFidele.php?id=<?php echo $liste->idpersonne; ?>" title="Modifier" <?php if(!has_Droit($idUser, "Modifier un fidele") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">border_color</i></a>
                                                <a class="col-red" href="supprimerFidele.php?code=<?php echo $liste->idpersonne; ?>" title="Supprimer" <?php if(!has_Droit($idUser, "Supprimer fidele") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">delete</i></a>

                                            </td>

                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>

                          
                        </div>
                    </div>
                    <div style="text-align: center">
                                 <a class="btn btn-success" href="report/imprimer_param.php?file=liste_fideles_statut&param=CONSEILLER" title="Imprimer la liste des fidèles" target="_blank"><i class="fa fa-print"></i> Imprimer</a>
                    </div>
                    <br>
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
        if(window.confirm('Voulez-vous supprimer cet conseillé?')){
            $.ajax(url, {

                success: function(){
                    $a.parents('tr').remove();
                },

                error: function(){

                    alert("Une erreur est survenue lors de la suppresion de l'conseillé");
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
