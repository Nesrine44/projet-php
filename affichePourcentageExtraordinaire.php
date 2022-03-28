<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Assiduite a un conseil")){

            header('Location:index.php');

        }else{

            $insert1 = "SELECT idfidele, nom, prenom FROM fidele, personne WHERE statut= 'ancien' AND  fidele.lisible=1 AND personne.lisible = 1 AND personne.idpersonne = fidele.personne_idpersonne";
            $res=$db->query("$insert1");

            $breAncien = "SELECT  COUNT(idfidele) AS nombreanciens FROM fidele as fil WHERE statut= 'ancien' AND  fil.lisible=1";
            $resnombreAnciens=$db->query("$breAncien");
            while($idnombreAnciens=$resnombreAnciens->fetch(PDO::FETCH_ASSOC)){
                $nbreAnciens=$idnombreAnciens['nombreanciens'];
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
                    <li><i class="material-icons">school</i> Conseil Anciens</li>
                    <li><i class="material-icons">assistant</i><a class="link col-blue" href="Assiduite.php" > Assiduité aux conseils</a></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <section class="panel card">

                    <div class="row">
                        <div class="col-lg-12">
                            <header class="panel-heading h4 text-center">
                                Assiduité des anciens aux Conseils extraordinaires
                            </header><div id="old_table" class="table-responsive">
                                <table class="table table-bordered table-striped table-hover tableau_dynamique">
                                    <thead>
                                        <tr>
                                            <th><i class="material-icons iconposition">confirmation_number</i> Numéro</th>
                                            <th><i class="material-icons iconposition">people</i> Noms et Penoms</th>
                                            <th><i class="material-icons iconposition">note_add</i> Notes</th>
                                            <th><i class="material-icons iconposition">graphic_eq</i> Pourcentage</th>

                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th><i class="material-icons iconposition">confirmation_number</i> Numéro</th>
                                            <th><i class="material-icons iconposition">people</i> Noms et Penoms</th>
                                            <th><i class="material-icons iconposition">note_add</i> Notes</th>
                                            <th><i class="material-icons iconposition">graphic_eq</i> Pourcentage</th>

                                        </tr>
                                    </tfoot>
                                    <tbody>    
                                        <?php
                                        $n = 0;
                                        $pourcentageGnenerale=0;
                                        while($id=$res->fetch(PDO::FETCH_ASSOC)){
                                            $identifiant0=$id['idfidele'];
                                            $identifiant1=$id['nom'];
                                            $identifiant2=$id['prenom'];


                                            $insert2 = "SELECT COUNT(fidele_idfidele)as nombre FROM  fideleconseil WHERE type = 'conseil extraordinaire' AND lisible=1 AND fidele_idfidele=$identifiant0";
                                            $res2=$db->query("$insert2");
                                            while($id2=$res2->fetch(PDO::FETCH_ASSOC)){
                                                $identifiant3=$id2['nombre'];
                                            }

                                            $insert3 = "SELECT extraordinaire FROM occurrence WHERE idoccurrence=1 ";
                                            $res3=$db->query("$insert3");
                                            while($id3=$res3->fetch(PDO::FETCH_ASSOC)){
                                                $identifiant4=$id3['extraordinaire'];
                                            }
                                            $taux=($identifiant3==0 ? 0 : number_format($identifiant3 * 100/$identifiant4, 2));
                                            $pourcentageGnenerale = $pourcentageGnenerale + $taux;
                                            ?>

                                            <tr>
                                                <td><?php echo ++$n; ?></td>
                                                <td><?php echo($identifiant1.' '.$identifiant2); ?></td>
                                                <td><?php echo($identifiant3.'/'.$identifiant4); ?></td>
                                                <td><?php echo($taux.'%'); ?></td>
                                            </tr>
                                        <?php

                                        }
                                        $pourcentageGnenerale = ($nbreAnciens==0? 0 : number_format($pourcentageGnenerale/$nbreAnciens, 2));

                                        ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td><b><h4>Pourcentage générale</h4></b></td>
                                                <td></td>
                                                <td></td>
                                                <td><b><?php echo($pourcentageGnenerale.'%'); ?></b></td>
                                            </tr>
                                        </tfoot>    
                                    </tbody>
                                </table>
                            </div>    
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>

<script type="text/javascript">

    $('#chargement').hide();
  
    $('.link').on('click', function(e){

        e.preventDefault();
        $link = $(this);       
        var $url = $link.attr('href');
        $('#main-content').load($url);
    });
    $(".tableau_dynamique").DataTable();
	$('.loader').hide();

</script>