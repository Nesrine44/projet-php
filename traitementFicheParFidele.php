<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Afficher contribution")){

            header('Location:index.php');

        }else{
            
            if(isset($_POST['submit'])){
                if(isset($_POST['choixcode'])){
                    $codefidele=$_POST['choixcode'];

                    try{
                        $select1 = "SELECT idfidele, personne_idpersonne FROM fidele WHERE codeFidele='$codefidele';";
                        $res1=$db->query($select1);
                        while($id1=$res1->fetch(PDO::FETCH_ASSOC)){
                            $identifiant1=$id1['idfidele'];
                            $identifiant2=$id1['personne_idpersonne'];
                        }

                        $select2 = "SELECT nom, prenom FROM personne WHERE idpersonne=$identifiant2;";
                        $res2=$db->query($select2);
                        while($id2=$res2->fetch(PDO::FETCH_ASSOC)){
                            $identifiant3=$id2['nom'];
                            $identifiant4=$id2['prenom'];
                        }

                        $select3 = "SELECT * FROM contributionfidele WHERE fidele_idfidele=$identifiant1 AND typecontribution='offrandes' AND lisible=1;";
                        $res3=$db->query($select3);


                        $select31 = "SELECT SUM(montant) as total FROM contributionfidele WHERE fidele_idfidele=$identifiant1 AND typecontribution='offrandes' AND lisible=1;";
                        $res31=$db->query($select31);
                        while($id31=$res31->fetch(PDO::FETCH_ASSOC)){
                            $identifiant31=$id31['total'];
                        }

                        $select4 = "SELECT * FROM contributionfidele WHERE fidele_idfidele=$identifiant1 AND typecontribution='travaux' AND lisible=1;";
                        $res4=$db->query($select4);

                        $select41 = "SELECT SUM(montant) as total FROM contributionfidele WHERE fidele_idfidele=$identifiant1 AND typecontribution='travaux' AND lisible=1;";
                        $res41=$db->query($select41);
                        while($id41=$res41->fetch(PDO::FETCH_ASSOC)){
                            $identifiant41=$id41['total'];
                        }

                        $select5 = "SELECT * FROM contributionfidele WHERE fidele_idfidele=$identifiant1 AND typecontribution='consiegerie' AND lisible=1;";
                        $res5=$db->query($select5);

                        $select51 = "SELECT SUM(montant) as total FROM contributionfidele WHERE fidele_idfidele=$identifiant1 AND typecontribution='consiegerie' AND lisible=1;";
                        $res51=$db->query($select51);
                        while($id51=$res51->fetch(PDO::FETCH_ASSOC)){
                            $identifiant51=$id51['total'];
                        }

                        $select6 = "SELECT * FROM contributionfidele WHERE fidele_idfidele=$identifiant1 AND typecontribution='don' AND lisible=1;";
                        $res6=$db->query($select6);

                        $select61 = "SELECT SUM(montant) as total FROM contributionfidele WHERE fidele_idfidele=$identifiant1 AND typecontribution='don' AND lisible=1;";
                        $res61=$db->query($select61);
                        while($id61=$res61->fetch(PDO::FETCH_ASSOC)){
                            $identifiant61=$id61['total'];
                        }

                    }catch(Exception $ex){
                        echo"<script type='text/javascript'>alert('erreur');</script>";
                    }
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
                    <li><i class="icon_document_alt"></i>Finances</li>
                    <li><i class="fa fa-files-o"></i>Fiche Contribution</li>
                    <li><i class="fa fa-files-o"></i>Par Fidèle</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">

                    <div class=class="row">
                        <div class="col-lg-12">
                            <header class="panel-heading">
                                Fiche de contribution de <?php echo $identifiant3.' '.$identifiant4; ?>
                            </header>

                            <div><b>Contribution de type offrandes</b></div>
                            <table class="table table-striped table-advance table-hover">
                                <tbody>
                                <tr>
                                    <th>#</th>
                                    <th><i class="icon_calendar"></i>Date</th>
                                    <th><i class="icon_pin_alt"></i>Montant(Fcfa)</th>
                                </tr>
                                <?php
                                $n=0;
                                while($id3=$res3->fetch(PDO::FETCH_ASSOC)){
                                    $identifiant5=$id3['date'];
                                    $identifiant6=$id3['montant'];

                                    ?>

                                    <tr>
                                        <td><?php echo ++$n; ?></td>
                                        <td><?php echo $identifiant5; ?></td>
                                        <td><?php echo $identifiant6; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <td>Total</td>
                                    <td></td>
                                    <td><b><?php echo $identifiant31; ?></b></td>
                                </tr>
                                </tbody>
                            </table><br>

                            <div><b>Contribution de type travaux</b></div>
                            <table class="table table-striped table-advance table-hover">
                                <tbody>
                                <tr>
                                    <th><i class=""></i>#</th>
                                    <th><i class="icon_calendar"></i>Date</th>
                                    <th><i class="icon_pin_alt"></i>Montant(Fcfa)</th>
                                </tr>
                                <?php
                                $n=0;
                                while($id4=$res4->fetch(PDO::FETCH_ASSOC)){
                                    $identifiant7=$id4['date'];
                                    $identifiant8=$id4['montant'];

                                    ?>

                                    <tr>
                                        <td><?php echo ++$n; ?></td>
                                        <td><?php echo $identifiant7; ?></td>
                                        <td><?php echo $identifiant8; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <td>Total</td>
                                    <td></td>
                                    <td><b><?php echo $identifiant41; ?></b></td>
                                </tr>
                                </tbody>
                            </table><br>

                            <div><b>Contribution de type consiègerie</b></div>
                            <table class="table table-striped table-advance table-hover">
                                <tbody>
                                <tr>
                                    <th><i class=""></i>#</th>
                                    <th><i class="icon_calendar"></i>Date</th>
                                    <th><i class="icon_pin_alt"></i>Montant(Fcfa)</th>
                                </tr>
                                <?php
                                $n=0;
                                while($id5=$res5->fetch(PDO::FETCH_ASSOC)){
                                    $identifiant9=$id5['date'];
                                    $identifiant10=$id5['montant'];

                                    ?>

                                    <tr>
                                        <td><?php echo ++$n; ?></td>
                                        <td><?php echo $identifiant9; ?></td>
                                        <td><?php echo $identifiant10; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <td>Total</td>
                                    <td></td>
                                    <td><b><?php echo $identifiant51; ?></b></td>
                                </tr>
                                </tbody>
                            </table>



                            <div><b>Contribution de type don de reconnaissance</b></div>
                            <table class="table table-striped table-advance table-hover">
                                <tbody>
                                <tr>
                                    <th><i class=""></i>#</th>
                                    <th><i class="icon_calendar"></i>Date</th>
                                    <th><i class="icon_pin_alt"></i>Montant(Fcfa)</th>
                                </tr>
                                <?php
                                $n=0;
                                while($id6=$res6->fetch(PDO::FETCH_ASSOC)){
                                    $identifiant11=$id6['date'];
                                    $identifiant12=$id6['montant'];

                                    ?>

                                    <tr>
                                        <td><?php echo ++$n; ?></td>
                                        <td><?php echo $identifiant11; ?></td>
                                        <td><?php echo $identifiant12; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <td>Total</td>
                                    <td></td>
                                    <td><b><?php echo $identifiant61; ?></b></td>
                                </tr>
                                </tbody>
                            </table><br>



                        </div>
                    </div>
                </section>
            </div>

            <div align="center">
                <a class="btn btn-success" href="report/imprimer.php?file=..." title="Imprimer la liste des ancien" target="_blank"><i class="fa fa-print"></i> Imprimer</a>
            </div>

        </div>
    </section>

<script type="text/javascript">

$('.loader').hide();
</script>