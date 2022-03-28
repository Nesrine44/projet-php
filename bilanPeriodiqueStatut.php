<?php
session_start();
if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login'];
    //require_once('layout.php');
    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');

    if(!has_Droit($idUser, "Enregistrer une participation")){
        header('Location:index.php');
    }else{
       
       if(!isset($_GET['statut'])){

            $statut = $_SESSION['statut'];

        }else{

            $statut=$_GET['statut'];
            $_SESSION['statut'] = $statut;
        }

        $date_jour = date('Y-m-d');
        $idsaintescene = 0;

         $select_contribution= $db->prepare("SELECT *
                                            FROM contributionfidele, fidele
                                            WHERE utilisateur_idutilisateur=$idUser
                                            AND  idfidele = fidele_idfidele
                                            AND contributionfidele.lisible = 1
                                            AND fidele.statut = '$statut'
                                            AND date='$date_jour'
                                            AND fidele.lisible=1");
        $select_contribution->execute();

         
        $select_somme_montant= $db->prepare("SELECT SUM(montant) AS total
                                            FROM contributionfidele, fidele
                                            WHERE utilisateur_idutilisateur=$idUser
                                            AND  idfidele = fidele_idfidele
                                            AND contributionfidele.lisible = 1
                                            AND fidele.statut = '$statut'
                                            AND date='$date_jour'
                                            AND fidele.lisible=1");
        $select_somme_montant->execute();

        $somme=$select_somme_montant->fetch(PDO::FETCH_OBJ);
        $total=$somme->total;

        $select_urilisateur=$db->prepare("SELECT *
                                            FROM personne, utilisateur
                                            WHERE utilisateur.lisible=1
                                            AND personne.lisible=1
                                            AND personne.idpersonne=utilisateur.personne_idpersonne
                                            AND idutilisateur=$idUser");
        $select_urilisateur->execute();




        $user='';
        $utilisateur=$select_urilisateur->fetch(PDO::FETCH_OBJ);
        if($utilisateur->sexe == 'Masculin'){
            $user=$user.'M. ';
        }else{
            $user=$user.'Mme. ';
        }

        $user=$user.$utilisateur->nom. ' ';
        $user=$user.$utilisateur->prenom;


    }

}else{
    header('Location:login.php');
}
?>

<section class="wrapper">
    <!--<div class="loader"></div>     -->

    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <ol class="breadcrumb">
                <li class="col-blue"><i class="materiel-icons"></i>home<a href="index.php"> Accueil</a></li>
                <li class="col-blue"><i class="materiel-icons">assistant</i> Sainte Cène</li>
                <li class="col-blue"><i class="materiel-icons">assistant</i><a href="bilanperiodique.php" class="afficher" title="Afficher mon bilan périodique"> Bilan périodique</a></li>
                

                <!--<li style="float: right;">
                    <a class="" href="report/imprimer.php?file=liste_fideles" title="Imprimer la liste des fidèles" target="_blank"><i class="fa fa-print"></i> Imprimer</a>
                </li>-->
            </ol>
        </div>
    </div>

    <div class="row card">
        <div class="col-lg-12">
            <section class="panel">

                <div class=class="row">
                    <div class="col-lg-12">
                        <header class="panel-heading">
                            <span style="font-size: 1.5em;">Contributions enregistrées par <?php echo $user.' le '.$date_jour; ?> </span>
                        </header>
                        <div id="result"> </div>
                        <div id="old_table" class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <tbody>
                                <tr>
                                    <th>Numéro</th>
                                    <th>Code</th>
                                    <th></i>Noms et prenoms</th>
                                    <th>Statut paroissial</th>
                                    <th>Sainte Cène</th>
                                    <th>Type de contribution</a></th>
                                    <th>Montant</a></th>
                                </tr>
                                <?php
                                $n=0;
                                while($liste=$select_contribution->fetch(PDO::FETCH_OBJ)){

                                    if($liste->saintescene_idsaintescene==1){
                                        $sainteCene='Janvier';
                                    }elseif($liste->saintescene_idsaintescene==2){
                                        $sainteCene='Février';
                                    }elseif($liste->saintescene_idsaintescene==3){
                                        $sainteCene='Mars';
                                    }elseif($liste->saintescene_idsaintescene==4){
                                        $sainteCene='Avril';
                                    }elseif($liste->saintescene_idsaintescene==5){
                                        $sainteCene='Mai';
                                    }elseif($liste->saintescene_idsaintescene==6){
                                        $sainteCene='Juin';
                                    }elseif($liste->saintescene_idsaintescene==7){
                                        $sainteCene='Juillet';
                                    }elseif($liste->saintescene_idsaintescene==8){
                                        $sainteCene='Aout';
                                    }elseif($liste->saintescene_idsaintescene==9){
                                        $sainteCene='Septembre';
                                    }elseif($liste->saintescene_idsaintescene==10){
                                        $sainteCene='Octobre';
                                    }elseif($liste->saintescene_idsaintescene==11){
                                        $sainteCene='Novembre';
                                    }elseif($liste->saintescene_idsaintescene==12){
                                        $sainteCene='Decembre';
                                    }

                                    $idsaintescene = $liste->saintescene_idsaintescene;

                                    $fidele=$db->prepare("SELECT *
                                                            FROM fidele, personne
                                                            WHERE personne.lisible=1
                                                            AND fidele.lisible=1
                                                            AND personne.idpersonne=fidele.personne_idpersonne
                                                            AND fidele.idfidele=$liste->fidele_idfidele");
                                    $fidele->execute();

                                    while($liste1=$fidele->fetch(PDO::FETCH_OBJ)){
                                        $statut = str_replace(" ", "+", $liste1->statut);




                                    ?>
                                            <tr>
                                                <td><?php echo ++$n; ?></td>
                                                <td><a href="bilanperiodiqueCode.php?codefidele=<?php echo $liste1->codefidele; ?>" class="afficher" title="afficher les contributions suivant le code"><?php echo $liste1->codefidele; ?></a></td>
                                                <td><?php echo $liste1->nom.' '.$liste1->prenom; ?></td>
                                                <td><a class="afficher" href="bilanPeriodiqueStatut.php?statut=<?php echo $statut; ?>" title="Afficher les contributions du statut"><?php echo $liste1->statut; ?></a></td>
                                                <td><?php echo $sainteCene; ?></td>
                                                <td><?php echo $liste->typecontribution; ?></td>
                                                <td><?php echo $liste->montant; ?></td>
                                            </tr>

                                        <?php
                                        }
                                     }
                                        ?>
                                    <tr>
                                        <td colspan="6"><h3>Total</h3></td>
                                       
                                        <td><h3><?php echo $total; ?></h3></td>
                                    </tr>
                                </tbody>
                            </table>                     

                        </div>
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
        $('.loader').show();
        var $b = $(this);
        url = $b.attr('href');

       $('#main-content').load(url);
    });
    $('.loader').hide();
</script>
