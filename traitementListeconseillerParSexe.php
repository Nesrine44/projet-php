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
      
        $sexe="";

        if(!isset($_GET['sexe'])){

            $sexe = $_SESSION['sexe'];

        }else{

            $sexe=$_GET['sexe'];
            $_SESSION['sexe'] = $sexe;
        }

        $_SESION['sexe'] = $sexe;
      
        //selection des information sur les fideles
        $selectAllFidele= $db->prepare("SELECT idpersonne, nom, prenom, sexe, idzone, nomzone, codefidele
                                        FROM personne, fidele, zone
                                        WHERE idpersonne=personne_idpersonne
                                        AND personne.zone_idzone = zone.idzone
                                        AND personne.lisible=1
                                        AND personne.sexe='$sexe'
                                        AND statut='conseiller'
                                        AND fidele.lisible=1 ORDER BY nom ASC");
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
            <li><i class="material-icons col-blue">home</i><a class="col-blue" href="index.php">Accueil</a></li>
            <li class="col-blue">assignment<i class="material-icons"></i>Conseil Anciens</li>
            <li class="col-blue">list<i class="material-icons"></i><a class="afficher col-blue" href="listeAnciens.php">Liste Conseillers</a></li>
            <li class="col-blue"><i class="material-icons">list</i>Liste des conseillers de sexe <?php echo $sexe; ?></li>
            <li style="float: right;">                      
                   <a class="" href="report/imprimer_param.php?file=liste_fideles_conseillers_sexe&param=<?php echo $sexe; ?>" title="Imprimer la liste des conseillers de sexe <?php echo $sexe; ?>" target="_blank">
                    <i class="material-icons">print</i> Imprimer</a>
            </li>
        </ol>
    </div>
</div>




<div class="row">
<div class="col-lg-12">
    <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="card">

                    <div class="header text-center h4">
                            
                   Liste des Conseillers de sexe "<?php echo $sexe; ?>" enregistrés
                            
                            
                    </div>  
             
                        <div id="old_table" class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable tableau_dynamique">
                        <thead>
                            <tr>
                                <th><i class="material-icons iconposition">confirmation_number</i>Numero</th>
                                <th><i class="material-icons iconposition">code</i> Code</th>
                                <th><i class="material-icons iconposition">people</i>Noms et prenoms</th>
                                <th><i class="material-icons iconposition">people</i>Sexe</th>
                                <th><i class="material-icons iconposition">location_on</i>Zone</th>
                                <th><i class="material-icons iconposition">settings</i> Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th><i class="material-icons iconposition">confirmation_number</i>Numero</th>
                                <th><i class="material-icons iconposition">code</i> Code</th>
                                <th><i class="material-icons iconposition">people</i>Noms et prenoms</th>
                                <th><i class="material-icons iconposition">people</i>Sexe</th>
                                <th><i class="material-icons iconposition">location_on</i>Zone</th>
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
                                    <td><a class="afficher" href="traitementListeconseillerParSexe.php?sexe=<?php echo $liste->sexe; ?>" title="Afficher les fidèle de <?php echo $liste->sexe; ?>"><?php echo $liste->sexe; ?></a></td>
                                    <td><a class="afficher" href="traitementListeconseillerParZone.php?zone=<?php echo $liste->idzone; ?>" title="Afficher les fideles de cette zone"><?php echo $liste->nomzone; ?></a>
                                    </td>

                                    <td>
                                        
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
                    <br>
                     <div align="center">
                <a class="btn btn-success" href="report/imprimer_param.php?file=liste_fideles_conseillers_sexe&param=<?php echo $sexe; ?>" title="Imprimer la liste des conseillers de sexe <?php echo $sexe; ?>" target="_blank"><i class="material-icons">print</i> Imprimer</a>
            </div>
            <br>

                </div>
            </div>

           
        </div>
</div>

<script>
    
    $('.btn-danger').on('click', function(e){

        e.preventDefault();

        var $a = $(this);
        var url = $a.attr('href');
        if(window.confirm('Voulez-vous supprimer ce conseillé?')){
            $.ajax(url, {

                success: function(){
                    $a.parents('tr').remove();
                },

                error: function(){

                    alert("Une erreur est survenue lors de la suppresion du conseillé");
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
