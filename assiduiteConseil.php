
<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Assiduite a un conseil")){

            header('Location:index.php');

        }else{

            if(isset($_POST['submit'])){
                if(isset($_POST['choixconseil'])){
                    $choixconseil1 = $_POST['choixconseil'];
                }
            }
            $insert1 = "SELECT * FROM personne as pers, fidele as fil WHERE statut= 'ancien' AND pers.lisible=1 AND fil.lisible=1 AND idpersonne=personne_idpersonne";
            $res=$db->query("$insert1");

            $breAncien = "SELECT  COUNT(idpersonne) AS nombreanciens FROM personne as pers, fidele as fil WHERE statut= 'ancien' AND pers.lisible=1 AND fil.lisible=1 AND idpersonne=personne_idpersonne";
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
        <!-- arborescence -->
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="index.php">Accueil</a></li>
                    <li><i class="icon_document_alt"></i>Conseil Anciens</li>
                    <li><i class="fa fa-files-o"></i>Assiduité aux conseils</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">

                    <div class=class="row">
                        <div class="col-lg-12">
                            <header class="panel-heading">
                                Assiduité des anciens aux <?php echo $choixconseil1;  ?>
                            </header>

                            <table class="table table-striped table-advance table-hover">
                                <tbody>
                                <tr>
                                    <th><i class=""></i>#</th>
                                    <th><i class="icon_profile"></i>Noms et Penoms</th>
                                    <th><i class="icon_cogs"></i> Notes</th>
                                    <th><i class="icon_cogs"></i> Pourcentage</th>

                                </tr>
                                <?php
                                    $n = 0;
                                    $pourcentageGnenerale=0;
                                    while($id=$res->fetch(PDO::FETCH_ASSOC)){
                                        $identifiant0=$id['idpersonne'];
                                        $identifiant1=$id['nom'];
                                        $identifiant2=$id['prenom'];

                                        if($choixconseil1=='conseil mensuel'){
                                            $insert2 = "SELECT COUNT(fidelepersonne_idpersonne)as nombre FROM  fideleconseil WHERE type = 'conseil mensuel' AND lisible=1 AND fidelepersonne_idpersonne=$identifiant0";
                                            $res2=$db->query("$insert2");
                                            while($id2=$res2->fetch(PDO::FETCH_ASSOC)){
                                                $identifiant3=$id2['nombre'];
                                            }

                                            $insert3 = "SELECT mensuel FROM occurrence WHERE idoccurrence=1 ";
                                            $res3=$db->query("$insert3");
                                            while($id3=$res3->fetch(PDO::FETCH_ASSOC)){
                                                $identifiant4=$id3['mensuel'];
                                            }
                                            $taux=$identifiant3 * 100/$identifiant4;
                                            $pourcentageGnenerale = $pourcentageGnenerale + $taux
                                ?>

                                            <tr>
                                                <td><?php echo ++$n; ?></td>
                                                <td><?php echo($identifiant1.' '.$identifiant2); ?></td>
                                                <td><?php echo($identifiant3.'/'.$identifiant4); ?></td>
                                                <td><?php echo($taux.'%'); ?></td>
                                            </tr>
                                        <?php
                                            }elseif($choixconseil1=='conseil extraordinaire'){
                                                $insert2 = "SELECT COUNT(fidelepersonne_idpersonne)as nombre FROM  fideleconseil WHERE type = 'conseil extraordinaire' AND lisible=1 AND fidelepersonne_idpersonne=$identifiant0";
                                                $res2=$db->query("$insert2");
                                                while($id2=$res2->fetch(PDO::FETCH_ASSOC)){
                                                    $identifiant3=$id2['nombre'];
                                                }

                                                $insert3 = "SELECT extraordinaire FROM occurrence WHERE idoccurrence=1 ";
                                                $res3=$db->query("$insert3");
                                                while($id3=$res3->fetch(PDO::FETCH_ASSOC)){
                                                    $identifiant4=$id3['extraordinaire'];
                                                }
                                                $taux=$identifiant3 * 100/$identifiant4;
                                                $pourcentageGnenerale = $pourcentageGnenerale + $taux
                                        ?>
                                                <tr>
                                                   <td><?php echo ++$n; ?></td>
                                                   <td><?php echo($identifiant1.' '.$identifiant2); ?></td>
                                                   <td><?php echo($identifiant3.'/'.$identifiant4); ?></td>
                                                    <td><?php echo($taux.'%'); ?></td>
                                                </tr>
                                            <?php
                                                }elseif($choixconseil1=='conseil elargi'){
                                                     $insert2 = "SELECT COUNT(fidelepersonne_idpersonne)as nombre FROM  fideleconseil WHERE type = 'conseil elargi' AND lisible=1 AND fidelepersonne_idpersonne=$identifiant0";
                                                     $res2=$db->query("$insert2");
                                                     while($id2=$res2->fetch(PDO::FETCH_ASSOC)){
                                                        $identifiant3=$id2['nombre'];
                                                     }

                                                    $insert3 = "SELECT elargi FROM occurrence WHERE idoccurrence=1 ";
                                                    $res3=$db->query("$insert3");
                                                    while($id3=$res3->fetch(PDO::FETCH_ASSOC)){
                                                        $identifiant4=$id3['elargi'];
                                                    }
                                                    $taux=$identifiant3 * 100/$identifiant4;
                                                    $pourcentageGnenerale = $pourcentageGnenerale + $taux
                                            ?>
                                                    <tr>
                                                        <td><?php echo ++$n; ?></td>
                                                        <td><?php echo($identifiant1.' '.$identifiant2); ?></td>
                                                        <td><?php echo($identifiant3.'/'.$identifiant4); ?></td>
                                                        <td><?php echo($taux.'%'); ?></td>
                                                    </tr>
                                                <?php
                                                    }elseif($choixconseil1=='consistoire'){
                                                        $insert2 = "SELECT COUNT(fidelepersonne_idpersonne)as nombre FROM  fideleconseil WHERE type = 'consistoire' AND lisible=1 AND fidelepersonne_idpersonne=$identifiant0";
                                                        $res2=$db->query("$insert2");
                                                        while($id2=$res2->fetch(PDO::FETCH_ASSOC)){
                                                            $identifiant3=$id2['nombre'];
                                                        }
                                                        $insert3 = "SELECT consistoire FROM occurrence WHERE idoccurrence=1 ";
                                                        $res3=$db->query("$insert3");
                                                        while($id3=$res3->fetch(PDO::FETCH_ASSOC)){
                                                            $identifiant4=$id3['consistoire'];
                                                        }
                                                        $taux=$identifiant3 * 100/$identifiant4;
                                                        $pourcentageGnenerale = $pourcentageGnenerale + $taux
                                                ?>
                                                        <tr>
                                                            <td><?php echo ++$n; ?></td>
                                                            <td><?php echo($identifiant1.' '.$identifiant2); ?></td>
                                                            <td><?php echo($identifiant3.'/'.$identifiant4); ?></td>
                                                            <td><?php echo($taux.'%'); ?></td>
                                                        </tr>
                                                    <?php
                                                        }elseif($choixconseil1=='cinode regional'){
                                                            $insert2 = "SELECT COUNT(fidelepersonne_idpersonne)as nombre FROM  fideleconseil WHERE type = 'cinode regional' AND lisible=1 AND fidelepersonne_idpersonne=$identifiant0";
                                                            $res2=$db->query("$insert2");
                                                            while($id2=$res2->fetch(PDO::FETCH_ASSOC)){
                                                                $identifiant3=$id2['nombre'];
                                                            }
                                                            $insert3 = "SELECT cinode FROM occurrence WHERE idoccurrence=1 ";
                                                            $res3=$db->query("$insert3");
                                                            while($id3=$res3->fetch(PDO::FETCH_ASSOC)){
                                                                $identifiant4=$id3['cinode'];
                                                            }
                                                             $taux=$identifiant3 * 100/$identifiant4;
                                                             $pourcentageGnenerale = $pourcentageGnenerale + $taux
                                                    ?>
                                                            <tr>
                                                                <td><?php echo ++$n; ?></td>
                                                                <td><?php echo($identifiant1.' '.$identifiant2); ?></td>
                                                                <td><?php echo($identifiant3.'/'.$identifiant4); ?></td>
                                                                <td><?php echo($taux.'%'); ?></td>
                                                            </tr>
                                                        <?php
                                                             }
                                            }
                                            $pourcentageGnenerale = $pourcentageGnenerale/$nbreAnciens;

                                ?>

                                <tr>
                                    <td><b><h4>Pourcentage générale</h4></b></td>
                                    <td></td>
                                    <td></td>
                                    <td><b><h4><?php echo($pourcentageGnenerale.'%'); ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
	<script>$('.loader').hide();</script>

