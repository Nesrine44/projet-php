<?php
session_start();
if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login'];
    //require_once('layout.php');
    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');

    if(!has_Droit($idUser, "Consulter participation")){
        header('Location:index.php');
    }else{


        $p1=$db->prepare("SELECT *
                            FROM saintescene
                            WHERE lisible=1
                            AND valide=0");
        $p1->execute();

        $offrandes=0;
        $consiegerie=0;
        $don=0;
        $travaux=0;
        $moisss="";

        while($d1=$p1->fetch(PDO::FETCH_ASSOC)){
            $mois=$d1['mois'];
            $idsaintecene=$d1['idsaintescene'];
            $moisss=$moisss.'"'.$mois.'"'.', ';

            $p1_offrandes=$db->prepare("SELECT SUM(montant) AS somme_offrandes
                                                    FROM contributionfidele
                                                    WHERE typecontribution='offrandes'
                                                    AND lisible=1
                                                    AND saintescene_idsaintescene=$idsaintecene");
            $p1_offrandes->execute();

            $p1_consiegerie=$db->prepare("SELECT SUM(montant) AS somme_consiegerie
                                                    FROM contributionfidele
                                                    WHERE typecontribution='conciergerie'
                                                    AND lisible=1
                                                    AND saintescene_idsaintescene=$idsaintecene");
            $p1_consiegerie->execute();

            $p1_travaux=$db->prepare("SELECT SUM(montant) AS somme_travaux
                                                    FROM contributionfidele
                                                    WHERE typecontribution='travaux'
                                                    AND lisible=1
                                                    AND saintescene_idsaintescene=$idsaintecene");
            $p1_travaux->execute();

            $p1_don=$db->prepare("SELECT SUM(montant) AS somme_don
                                                    FROM contributionfidele
                                                    WHERE typecontribution='don'
                                                    AND lisible=1
                                                    AND saintescene_idsaintescene=$idsaintecene");
            $p1_don->execute();

            $id_p1_offrandes=$p1_offrandes->fetch(PDO::FETCH_ASSOC);
            $offrandesss=$id_p1_offrandes['somme_offrandes'];
            $offrandes=$offrandes.$offrandesss.", ";

            $id_p1_consiegerie=$p1_consiegerie->fetch(PDO::FETCH_ASSOC);
            $consiegerieee=$id_p1_consiegerie['somme_consiegerie'];
            $consiegerie=$consiegerie.$consiegerieee.", ";

            $id_p1_travaux=$p1_travaux->fetch(PDO::FETCH_ASSOC);
            $travauxxxx=$id_p1_travaux['somme_travaux'];
            $travaux=$travaux.$travauxxxx.", ";

            $id_p1_don=$p1_don->fetch(PDO::FETCH_ASSOC);
            $donnnn=$id_p1_don['somme_don'];
            $don=$don.$donnnn.", ";
        }





        $ps_typeContribution=$db->prepare("SELECT type
                                            FROM contribution
                                            WHERE lisible=1 ORDER BY type ASC");
        $ps_typeContribution->execute();

        $typecont="";
        $valeur_montant=0;
        while($idps_typeContribution=$ps_typeContribution->fetch(PDO::FETCH_ASSOC)){
           $nom=$idps_typeContribution['type'];
           $typecont=$typecont.'"'.$nom.'"'.', ';

            $ps_montant_contribution=$db->prepare("SELECT SUM(montant) AS somme
                                                    FROM contributionfidele
                                                    WHERE typecontribution='$nom'
                                                    AND lisible=1");
            $ps_montant_contribution->execute();

            $idps_montant_contribution=$ps_montant_contribution->fetch(PDO::FETCH_ASSOC);
            $nom1=$idps_montant_contribution['somme'];
            $valeur_montant=$valeur_montant.$nom1.", ";
        }

        $ps_typeContribution1=$db->prepare("SELECT type
                                            FROM contribution
                                            WHERE lisible=1 ORDER BY type ASC");
        $ps_typeContribution1->execute();

        $ps_typeContribution2=$db->prepare("SELECT type
                                            FROM contribution
                                            WHERE lisible=1 ORDER BY type ASC");
        $ps_typeContribution2->execute();


        //gestion Sainte Scene

        $ps_Sainte_Scene=$db->prepare("SELECT *
                                        FROM saintescene
                                         WHERE lisible=1
                                         ");
        $ps_Sainte_Scene->execute();

        $saintescess='';
        $montantsaintesceness=0;

        while($saintesce=$ps_Sainte_Scene->fetch(PDO::FETCH_OBJ)){
            $noms=$saintesce->mois;
            $saintescess=$saintescess.'"'.$noms.'"'.', ';

            $ps_montant_sainte_scene=$db->prepare("SELECT SUM(montant) AS montantsaintescene
                                                    FROM contributionfidele
                                                    WHERE 	saintescene_idsaintescene=$saintesce->idsaintescene
                                                    AND lisible=1");
            $ps_montant_sainte_scene->execute();

            $montantsaintesce=$ps_montant_sainte_scene->fetch(PDO::FETCH_OBJ);
            if(!isset($montantsaintesce->montantsaintescene)){
                $valeur_montants=0;
            }else{
                $valeur_montants=$montantsaintesce->montantsaintescene;
            }
            $montantsaintesceness=$montantsaintesceness.$valeur_montants.", ";

        }

        $ps_Sainte_Scene1=$db->prepare("SELECT *
                                        FROM saintescene
                                         WHERE lisible=1
                                         AND valide=0");
        $ps_Sainte_Scene1->execute();

        $ps_Sainte_Scene2=$db->prepare("SELECT *
                                        FROM saintescene
                                         WHERE lisible=1
                                         AND valide=0");
        $ps_Sainte_Scene2->execute();


    }


}

        /*selection du nombre de femmes*/

        // $femmes = $db->prepare("SELECT COUNT(idfidele) AS nbreFemmes FROM fidele, personne 
        //                                                                 WHERE lisible=1 
        //                                                                 AND est_decede=0
        //                                                                 AND fidele.lisible=1
        //                                                                 AND personne.sexe = 'Feminin' 
        //                                                                 ");
        //         $femmes->execute();

        $femmes=$db->prepare("SELECT COUNT(idfidele) as nbreFemmes
                                            FROM fidele, personne
                                            WHERE personne.sexe = 'Feminin' 
                                            AND personne.idpersonne=fidele.personne_idpersonne 
                                            and fidele.est_decede=0
                                            AND personne.lisible=1 
                                            AND fidele.lisible=1");
        $femmes->execute();
       $femmes = $femmes->fetch();
        // $d=date('1989-10-18')-date('1988-10-18');
        // echo date('Y-m-d H:i:s');
       
        // $femmesMoins18=$db->prepare("SELECT COUNT(idfidele) as nbreFemmesMoins18
        //                                     FROM fidele, personne
        //                                     WHERE personne.sexe = 'Feminin'
        //                                     AND (date('Y-m-d H:i:s')-personne.datenaiss) > 18");
        // $femmesMoins18->execute();
        // var_dump($femmesMoins18->fetch());

        // $femmesPlus18=$db->prepare("SELECT COUNT(idfidele) as nbreFemmesPlus18
        //                                     FROM fidele, personne
        //                                     WHERE personne.sexe = 'Feminin'
        //                                      AND personne.age >= 18");
        // $femmesPlus18->execute();
        // var_dump($femmesPlus18->fetch(PDO::FETCH_OBJ));
      

        /*selection du nombre de hommes*/
         $hommes=$db->prepare("SELECT COUNT(idfidele) as nbreHommes
                                            FROM fidele, personne
                                            WHERE personne.sexe = 'Masculin' 
                                            AND personne.idpersonne=fidele.personne_idpersonne 
                                            and fidele.est_decede=0
                                            AND personne.lisible=1 
                                            AND fidele.lisible=1");
                                            
                                            
        $hommes->execute();
       $hommes = $hommes->fetch();




        //section du nombre de baptisé hommes

        $baptiseHommes=$db->prepare("SELECT COUNT(idbapteme) as baptiseHommes
                                            FROM bapteme,fidele, personne 
                                             WHERE personne.sexe = 'Masculin' 
                                             AND personne.idpersonne=fidele.personne_idpersonne
                                             AND fidele.idfidele=bapteme.fidele_idfidele And bapteme.lisible=1 AND fidele.lisible = 1");
        $baptiseHommes->execute();
                                           
        $baptiseHommes = $baptiseHommes->fetch();


        //section du nombre de baptisé hommes

        $baptiseFemmes=$db->prepare("SELECT COUNT(idbapteme) as baptiseFemmes
                                            FROM bapteme,fidele, personne 
                                             WHERE personne.sexe = 'Feminin' 
                                             AND personne.idpersonne=fidele.personne_idpersonne
                                             AND fidele.idfidele=bapteme.fidele_idfidele and bapteme.lisible=1 AND fidele.lisible = 1");
        $baptiseFemmes->execute();
                                           
        $baptiseFemmes = $baptiseFemmes->fetch();

        //section du nombre de homme

        $decedeHomme=$db->prepare("SELECT COUNT(*) as nbredecedeHomme
                                            FROM fidele, personne
                                            WHERE personne.sexe = 'Masculin' AND personne.idpersonne=fidele.personne_idpersonne
                                            AND fidele.est_decede=1");
        $decedeHomme->execute();
        $decedeHomme=$decedeHomme->fetch();
     


        //section du nombre de femme

        $decedeFemme=$db->prepare("SELECT COUNT(*) as nbredecedeFemme
                                            FROM fidele, personne
                                            WHERE personne.sexe = 'Feminin' AND personne.idpersonne=fidele.personne_idpersonne
                                            AND fidele.est_decede=1");
        $decedeFemme->execute();

        $decedeFemme = $decedeFemme->fetch();


        $nbredeces = $decedeFemme[0] + $decedeHomme[0];
        $baptise = $baptiseFemmes[0] + $baptiseHommes[0];
        $nbreFidele = $femmes[0] +$hommes[0];

        $absice = 0;

        //communian
        $hommeCommunian = $db->prepare("SELECT COUNT(*)  as hommeCommunian 
            from personne , fidele , confirmation 
            where idpersonne=personne_idpersonne 
            and personne.lisible=1 
            AND fidele.lisible=1 
            and fidele.idfidele = confirmation.fidele_idfidele 
            AND confirmation.lisible = 1 
            AND personne.sexe='Masculin'");
        $hommeCommunian->execute();
        $hommeCommunian=$hommeCommunian->fetch();

        //femmes communian

        $femmeCommunian = $db->prepare("SELECT COUNT(*)  as femmeCommunian 
            from personne , fidele , confirmation 
            where idpersonne=personne_idpersonne 
            and personne.lisible=1 
            AND fidele.lisible=1 
            and fidele.idfidele = confirmation.fidele_idfidele 
            AND confirmation.lisible = 1 
            AND personne.sexe='Feminin'");
        $femmeCommunian->execute();
        $femmeCommunian=$femmeCommunian->fetch();


        //selection de la liste d'ancien

        $ancienFemme= $db->prepare("SELECT count(*)
                                        FROM personne, fidele, zone
                                        WHERE idpersonne=personne_idpersonne
                                        AND zone.idzone = personne.zone_idzone
                                        AND personne.lisible=1 AND fidele.lisible=1
                                        AND statut='ancien' AND personne.sexe='Feminin'");
        $ancienFemme->execute();
        $ancienFemme=$ancienFemme->fetch();

        //selection d'ancien hommes

        $ancienHomme= $db->prepare("SELECT count(*)
                                        FROM personne, fidele, zone
                                        WHERE idpersonne=personne_idpersonne
                                        AND zone.idzone = personne.zone_idzone
                                        AND personne.lisible=1 AND fidele.lisible=1
                                        AND statut='ancien' AND personne.sexe='Masculin'");
        $ancienHomme->execute();
        $ancienHomme=$ancienHomme->fetch();

        ///selecton de conseiller femmes
        $conseillerFemme= $db->prepare("SELECT count(*)
                                        FROM personne, fidele, zone
                                        WHERE idpersonne=personne_idpersonne
                                        AND zone.idzone = personne.zone_idzone
                                        AND personne.lisible=1 AND fidele.lisible=1
                                        AND statut='conseiller' 
                                        AND personne.sexe = 'Feminin'");
        $conseillerFemme->execute();
        $conseillerFemme=$conseillerFemme->fetch();

        ///selection du nombre de conseiller hommes

        $conseillerHomme= $db->prepare("SELECT count(*)
                                        FROM personne, fidele, zone
                                        WHERE idpersonne=personne_idpersonne
                                        AND zone.idzone = personne.zone_idzone
                                        AND personne.lisible=1 AND fidele.lisible=1
                                        AND statut='conseiller' 
                                        AND personne.sexe = 'Masculin'");
        $conseillerHomme->execute();
        $conseillerHomme=$conseillerHomme->fetch();

        //tratement du no,bre de fidele par sexe

        $ans = date('Y-m-d');
      
        $nombreHommeSup18=0;
         $nombreHommeInf18=0;
          $n3=0;


             $age=$db->prepare("SELECT datenaiss FROM fidele, personne
                                                WHERE personne.sexe = 'Masculin' 
                                                AND personne.idpersonne=fidele.personne_idpersonne 
                                                and fidele.est_decede=0
                                                AND personne.lisible=1 
                                                AND fidele.lisible=1");
            $age->execute();   

       while($ages = $age->fetch(PDO::FETCH_OBJ)) {

             if (($ans-$ages->datenaiss) > 18){
                    $nombreHommeSup18++;
                   
                }
                else
                {
                    $nombreHommeInf18++;
                   
                }
           $n3++;
       }

       ///no,bre de femme autour de 18

         $nombreFemmeSup18=0;
         $nombreFemmeInf18=0;
         


             $age=$db->prepare("SELECT datenaiss FROM fidele, personne
                                                WHERE personne.sexe = 'Feminin' 
                                                AND personne.idpersonne=fidele.personne_idpersonne 
                                                and fidele.est_decede=0
                                                AND personne.lisible=1 
                                                AND fidele.lisible=1");
            $age->execute();   

       while($ages = $age->fetch(PDO::FETCH_OBJ)) {

             if (($ans-$ages->datenaiss) > 18){
                    $nombreFemmeSup18++;
                   
                }
                else
                {
                    $nombreFemmeInf18++;
                   
                }
           $n3++;

       }


       //////selection des femmes diacres
        $diacreFemme= $db->prepare("SELECT count(*)
                                        FROM personne, fidele, zone
                                        WHERE idpersonne=personne_idpersonne
                                        AND zone.idzone = personne.zone_idzone
                                        AND personne.lisible=1 AND fidele.lisible=1
                                        AND statut='diacre' 
                                        AND personne.sexe = 'Feminin'");
        $diacreFemme->execute();
        $diacreFemme=$diacreFemme->fetch();

        /////seelection des hommes diacres

        $diacreHomme= $db->prepare("SELECT count(*)
                                        FROM personne, fidele, zone
                                        WHERE idpersonne=personne_idpersonne
                                        AND zone.idzone = personne.zone_idzone
                                        AND personne.lisible=1 AND fidele.lisible=1
                                        AND statut='diacre' 
                                        AND personne.sexe = 'Masculin'");
        $diacreHomme->execute();
        $diacreHomme=$diacreHomme->fetch();
          
            
         

               
          

                                            
        


        


  




        // $ages=$db->prepare("SELECT COUNT(*) as nbreages
        //                                     FROM fidele, personne
        //                                     WHERE personne.sexe = 'Masculin' and (time()-strtotime(personne.datenaiss)) >= 18*3600");
        // $ages->execute();
        // $ages=$ages->fetch();
     







?>


<div class="wrapper" xmlns="http://www.w3.org/1999/html">

    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <ol class="breadcrumb">
                <li><i class="material-icons text-primary">home</i><a href="index.php">Accueil</a></li>
                <li><i class="material-icons text-primary">poll</i><a href="#"> Statistiques</a></li>
            </ol>
        </div>
    </div>


   

    <div class="row col-xs-12">
        <div class="col-sm-12">
            <ol class="breadcrumb  col-lg-12" style="background-color: #394a59; height: 40px; width: 100%;">
                <li><button class="contributions" value="meilleurs_contributions" title="Voir les personnes ayant le plus contribuer"><i class="fa fa-bars"></i><b>Meilleurs Contributions</b></button></li>
                <li><button class= "contributions" value="contributions_periodiques"><i class="icon_table"></i><b>Contributions Périodiques</b></button></li>
                 <li><button class= "contributions" value="catrine"><i class="icon_table"></i><b>Statistiques fidèles</b></button></li>
              
            </ol>
        </div>
    </div>



    <div id="contribution1" style="display: none;">
        <div class="row">
            <form class="form-validate form-horizontal" method="POST" action="contribution1.php" id="form_find">
                <div class="form-group ">
                    <div class="col-lg-3 col-lg-offset-1">
                        <select class="form-control" name="contribution" required>
                            <option disabled selected>Veuillez choisir le type de contribution</option>
                            <?php
                                while($contribution=$ps_typeContribution1->fetch(PDO::FETCH_OBJ)){
                            ?>
                                    <option value="<?php echo $contribution->type;?>"><?php echo $contribution->type;?></option>
                                    <?php
                                    }
                                    ?>
                        </select>
                    </div>

                    <label for="cVillage" class="control-label col-lg-2">Nombre d'occurrence<span class="required">*</span></label>
                    <div class="col-lg-2">
                        <input class="form-control"  type="number" name="occurrence" min="1" required/>
                    </div>

                    <div class="col-lg-2">
                        <select class="form-control" name="mois" required>
                            <option disabled selected>Vueillez choisir le mois</option>
                            <option value="01">Janvier</option>
                            <option value="02">Fevrier</option>
                            <option value="03">Mars</option>
                            <option value="04">Avril</option>
                            <option value="05">Mai</option>
                            <option value="06">Juin</option>
                            <option value="07">Juillet</option>
                            <option value="08">Aout</option>
                            <option value="09">Septembre</option>
                            <option value="10">Octobre</option>
                            <option value="11">Novembre</option>
                            <option value="12">Decembre</option>
                        </select>
                    </div>

                    
                </div>
                <br>
                <div class="form-group">
                        <div class="col-lg-12 text-center">
                            <a class="btn btn-warning annuler" href="statistiques.php">Annuler</a>
                            <button class="btn btn-primary" name="submit" type="submit" id="">Valider</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>

    <div id="contribution2" style="display: none">
        <div class="row">
            <div class="form-group" >
                <form class="form-validate form-horizontal afficher" method="POST" action="contribution2.php" id="form_find1">
                    <div class="form-group ">
                        <div class="col-lg-3 col-lg-offset-1">
                            <select class="form-control" name="contribution1" required>
                                <option disabled selected>Veuillez choisir le type de contribution</option>
                                <?php
                                while($contribution2=$ps_typeContribution2->fetch(PDO::FETCH_OBJ)){
                                    ?>
                                    <option value="<?php echo $contribution2->type;?>"><?php echo $contribution2->type;?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>

                        <label for="cVillage" class="control-label col-lg-2">Choix de l'intervalle de date<span class="required">*</span></label>
                        <div class="col-lg-2">
                            <input class="form-control datepicker" id="min" name="min" type="text" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="YYYY-MM-DD"  required  />
                        </div>

                        <div class="col-lg-2">
                            <input class="form-control datepicker" id="max" name="max" type="text" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="YYYY-MM-DD"  required  />
                        </div>

                      
                    </div>
                    <br>
                    <div class="form-group">
                            <div class="col-lg-12 text-center">
                                <a class="btn btn-warning annuler" href="statistiques.php">Annuler</a>
                                <button class="btn btn-primary" name="submit" type="submit">Valider</button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>


    <div id="saintecene1" style="display: none">
        <div class="row">
            <form class="form-validate form-horizontal" method="POST" action="saintecene1.php" id="form_find2">
                <div class="form-group ">
                    <label class="control-label col-lg-2">Choix de la Sainte Cène<span class="required">*</span></label>
                    <div class="col-lg-3">
                        <select class="form-control" name="contribution" required>
                            <option disabled selected>Sélectionner la Sainte Cène</option>
                            <?php
                            while($sainteCenee=$ps_Sainte_Scene1->fetch(PDO::FETCH_OBJ)){
                                ?>
                                <option value="<?php echo $sainteCenee->mois;?>"><?php echo $sainteCenee->mois;?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <label for="cVillage" class="control-label col-lg-2 col-lg-offset-1">Nombre d'occurrence<span class="required">*</span></label>
                    <div class="col-lg-2">
                        <input class="form-control"  type="number" name="occurrence" min="1" required/>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-2">
                            <a class="btn btn-warning annuler" href="statistiques.php">Annuler</a>
                            <button class="btn btn-primary" name="submit" type="submit" id="">Valider</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div id="saintecene2" style="display: none">
        <div class="form-group" >
            <form class="form-validate form-horizontal afficher" method="POST" action="saintecene2.php" id="form_find3">
                <div class="form-group ">
                    <div class="col-lg-3 col-lg-offset-1">
                        <select class="form-control" name="contribution2" required>
                            <option disabled selected>Sélectionner la Sainte Cène</option>
                            <?php
                            while($sainteCenee2=$ps_Sainte_Scene2->fetch(PDO::FETCH_OBJ)){
                                ?>
                                <option value="<?php echo $sainteCenee2->mois;?>"><?php echo $sainteCenee2->mois;?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <label for="cVillage" class="control-label col-lg-2">Choix de l'intervalle de date<span class="required">*</span></label>
                    <div class="col-lg-2">
                        <input class="form-control datepicker" name="min1" type="date" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="YYYY-MM-DD"  required  />
                    </div>

                    <div class="col-lg-2">
                        <input class="form-control datepicker"  name="max1" type="date" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="YYYY-MM-DD"  required  />
                    </div>

                    <div class="form-group">
                        <div class="col-lg-2">
                            <a class="btn btn-warning annuler" href="statistiques.php">Annuler</a>
                            <button class="btn btn-primary" name="submit" type="submit">Valider</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div> 



    <div id="result"></div>

    <br><div id="old_table">

        <div class="row" id="cahes">
            <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                <section class="panel">
                    <header class="panel-heading">
                        <strong><center>Contributions cumulées mensuelles à la Sainte Cène</center></strong>
                    </header>
                    <div class="panel-body text-center">
                        <canvas id="canvas3" height="450" width="1000"></canvas>
                    </div>
                </section>
                <script>
                    var barChartData = {
                        labels : [ <?php echo $saintescess; ?>],
                        datasets : [
                            {
                                fillColor : "rgba(151,187,205,0.5)",
                                strokeColor : "rgba(151,187,205,1)",
                                data : [<?php echo $montantsaintesceness; ?>]
                            }
                        ]
                    }
                    var myLine = new Chart(document.getElementById("canvas3").getContext("2d")).Bar(barChartData);
                </script>
            </div>
        </div>


         <div class="row" id="cache">
             <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                 <section class="panel">
                     <header class="panel-heading">
                         <strong></strong>
                     </header>
                     <div class="panel-body text-center">
                         <canvas id="canvas2" height="450" width="1000"></canvas>


                             <ul id="ul_id">
                                 <li style="color:#69D2E7;"><i class=""></i> <h3>Offrandes </h3></li>
                                 <li style="color:#21323D;"><i class=""></i> <h3> Conciergerie </h3></li>
                                 <li style="color:#9D9B7F;"><i class=""></i> <h3> Travaux </h3></li>
                                 <li style="color:#D97041;"><i class=""></i><h3> Dons </h3></li>
                             </ul>


                     </div>


                  
                 <script>
                     var barChartData = {
                         labels : [ <?php echo $moisss; ?>],
                         datasets : [
                             {
                                 fillColor : "#69D2E7",
                                 //strokeColor : "rgba(151,187,205,1)",
                                 data : [<?php echo $offrandes; ?>]
                             },

                             {
                                 fillColor : "#21323D",
                                 //strokeColor : "rgba(152,188,206,1.5)",
                                 data : [<?php echo $consiegerie; ?>]
                             },

                             {
                                 fillColor : "#9D9B7F",
                                 //strokeColor : "rgba(151,187,205,1)",
                                 data : [<?php echo $travaux; ?>]
                             },

                             {
                                 fillColor : "#D97041",
                                 //strokeColor : "rgba(151,187,205,1)",
                                 data : [<?php echo $don; ?>]
                             }
                         ]
                     }
                     var myLine = new Chart(document.getElementById("canvas2").getContext("2d")).Bar(barChartData);
                 </script>
             </section>
             </div>
         </div>
         <br>
         <br>

           <div class="row card" id="sonia">
                                <div class="col-lg-12">

                                        
                                                <header class="panel-heading input-group">
                                                    <h4 class="text-center">  <?php echo $femmes[0] + $hommes[0]; ?> Fidèles enregistrés</h4>
                                                        <!-- Module de recherche -->
                                                       <!--  <div class="form-line">
                                                            <input class="form-control col-black h4" id="recherche" type="text" name="search" placeholder="Rechercher un fidèle">
                                                        </div>  -->           

                                                               
                                                </header>  
                                             <div id="old_table" class="table-responsive ">
                                                <table class="table table-responsive table-advance table-bordered table-striped table-hover tableau_dynamique">
                                                    <thead>
                                                        <tr>
                                                            <th><i class="material-icons  iconposition"></i> Libellés</th>
                                                            <th class="text-center"><i class="material-icons iconposition">cod</i> Femmes</th>
                                                            <th class="text-center"><i class="material-icons iconposition">peopl</i>Hommes</th>
                                                            <th class="text-center"><i class="material-icons iconposition">peopl</i> Total</th>

                                                           
                                                        </tr>
                                                    </thead> 
                                                    
                                                      <tbody>
                                                       
                                                        <tr>
                                                            <td >
                                                                Fideles
                                                            </td>
                                                            <td class="text-center">
                                                                <?php echo $femmes[0]; ?>
                                                            </td>
                                                             <td class="text-center">
                                                                <?php echo $hommes[0]; ?>
                                                            </td>
                                                             <td class="text-center">
                                                                <?php echo $femmes[0] + $hommes[0]; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td > 
                                                                Fideles de moins de 18 ans
                                                            </td>
                                                             <td class="text-center"> 
                                                                <?php echo $nombreFemmeInf18; ?>
                                                            </td>
                                                             <td class="text-center"> 
                                                                <?php echo $nombreHommeInf18; ?>
                                                            </td>
                                                            <td class="text-center"> 
                                                                <?php echo $nombreHommeInf18 +  $nombreFemmeInf18; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td > 
                                                                Fideles de plus de 18 ans
                                                            </td>
                                                             <td class="text-center"> 
                                                                <?php echo $nombreFemmeSup18; ?>
                                                            </td>
                                                             <td class="text-center"> 
                                                                <?php echo $nombreHommeSup18; ?>
                                                            </td>
                                                            <td class="text-center"> 
                                                                <?php echo $nombreHommeSup18 +  $nombreFemmeSup18; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td > 
                                                                Nombres de decès
                                                            </td>
                                                             <td class="text-center"> 
                                                                <?php echo $decedeFemme[0]; ?>
                                                            </td>
                                                             <td class="text-center"> 
                                                                <?php echo $decedeHomme[0]; ?>
                                                            </td>
                                                             <td class="text-center">
                                                                <?php echo $decedeHomme[0] + $decedeFemme[0]; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td > 
                                                                Baptisés
                                                            </td>
                                                             <td class="text-center"> 
                                                               <?php echo $baptiseFemmes[0]; ?>
                                                            </td>
                                                             <td class="text-center"> 
                                                                <?php echo $baptiseHommes[0]; ?>
                                                            </td>
                                                             <td class="text-center">
                                                                <?php echo $baptiseHommes[0] + $baptiseFemmes[0]; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td > 
                                                                Communiés
                                                            </td>
                                                             <td class="text-center"> 
                                                                <?php echo $femmeCommunian[0]; ?>
                                                            </td>
                                                             <td class="text-center"> 
                                                                <?php echo $hommeCommunian[0]; ?>
                                                            </td>
                                                             <td class="text-center"> 
                                                                <?php echo $femmeCommunian[0] + $hommeCommunian[0]; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td > 
                                                                Anciens
                                                            </td>
                                                             <td class="text-center"> 
                                                                <?php echo $ancienFemme[0]; ?>
                                                            </td>
                                                             <td class="text-center"> 
                                                                <?php echo $ancienHomme[0]; ?>
                                                            </td>
                                                             <td class="text-center"> 
                                                                <?php echo $ancienHomme[0] + $ancienFemme[0]; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td > 
                                                                Conseillers
                                                            </td>
                                                             <td class="text-center"> 
                                                                <?php echo $conseillerFemme[0]; ?>
                                                            </td>
                                                             <td class="text-center"> 
                                                                <?php echo $conseillerHomme[0]; ?>
                                                            </td>
                                                             <td class="text-center"> 
                                                                <?php echo $conseillerHomme[0] + $conseillerFemme[0]; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td > 
                                                                Diacres
                                                            </td>
                                                             <td class="text-center"> 
                                                                <?php echo $diacreFemme[0]; ?>
                                                            </td>
                                                             <td class="text-center"> 
                                                                <?php echo $diacreHomme[0]; ?>
                                                            </td>
                                                             <td class="text-center"> 
                                                                 <?php echo $diacreHomme[0] + $diacreFemme[0]; ?>
                                                            </td>
                                                        </tr>
                                                            
                                                               
                                                            

                                                    </tbody>
                                                      </table>  
                                                 </div>
                                            </div>


                            </div>
                        </div>
                    </div>
                    <br>













<script>

    $('.contributions').on('click', function(e){
        e.preventDefault();
        var x = $(this).val();
        if(x == 'meilleurs_contributions'){
            $('#contribution1').show();
            $('#contribution2').hide();
            $('.saintecenes').hide();
            $('#saintecene1').hide();
            $('#saintecene2').hide();
             $('#sonia').hide();
              $('#cache').show()
             $('#cahes').show()
        }else if(x == 'contributions_periodiques'){
            $('#contribution2').show();
            $('#contribution1').hide();
            $('.saintecenes').hide();
            $('#saintecene1').hide();
            $('#saintecene2').hide();
             $('#sonia').hide();
              $('#cache').show()
             $('#cahes').show()
        }
        else if(x == 'catrine'){
             $('#sonia').show();
            $('#contribution2').hide();
            $('#contribution1').hide();
            $('.saintecenes').hide();
            $('#saintecene1').hide();
            $('#saintecene2').hide();
            $('#cache').hide()
             $('#cahes').hide()
        }else{
            $('.saintecenes').show();
            $('#contribution1').hide();
            $('#contribution2').hide();
            $('#saintecene1').hide();
            $('#saintecene2').hide();
             $('#sonia').hide();
              $('#cache').show()
             $('#cahes').show()
        }

    });

    $('#meilleursaintecene').on('click', function(e){
        e.preventDefault();

        $('#saintecene1').show();
        $('#saintecene2').hide();
    });

    $('#contributionperiodiquesaintecene').on('click', function(e){
        e.preventDefault();

        $('#saintecene2').show();
        $('#saintecene1').hide();
    });



    $('.annuler').on('click', function(e){

        e.preventDefault();
        var $link = $(this);
        target = $link.attr('href');
        if(window.confirm("Voulez-vous vraiment annuler?")){

            $('#main-content').load(target);
        }

    });

    $('#form_find').on('submit', function(e){

        e.preventDefault();
        $('.loader').show();

        var $form = $(this);
        $form.find('button').text('Traitement');

        url = $form.attr('action');


        $.post(url, $form.serializeArray())

            .done(function(data, text, jqxhr){

                $("#old_table").hide();
                $('#result').html(jqxhr.responseText);

                $('.loader').hide();
            })
            .fail(function(jqxhr){
                alert(jqxhr.responseText);
            })
            .always(function(){
                $form.find('button').text('Valider');
                $('.loader').hide();
            });
        //$('.loader').hide();
    });


    $('#form_find1').on('submit', function(e){

        e.preventDefault();
        $('.loader').show();

        var $form = $(this);
        $form.find('button').text('Traitement');

        url = $form.attr('action');


        $.post(url, $form.serializeArray())

            .done(function(data, text, jqxhr){

                $("#old_table").hide();
                $('#result').html(jqxhr.responseText);

                $('.loader').hide();
            })
            .fail(function(jqxhr){
                alert(jqxhr.responseText);
            })
            .always(function(){
                $form.find('button').text('Valider');
                $('.loader').hide();
            });
        //$('.loader').hide();
    });

    $('#form_find2').on('submit', function(e){

        e.preventDefault();
        $('.loader').show();

        var $form = $(this);
        $form.find('button').text('Traitement');

        url = $form.attr('action');


        $.post(url, $form.serializeArray())

            .done(function(data, text, jqxhr){

                $("#old_table").hide();
                $('#result').html(jqxhr.responseText);

                $('.loader').hide();
            })
            .fail(function(jqxhr){
                alert(jqxhr.responseText);
            })
            .always(function(){
                $form.find('button').text('Valider');
                $('.loader').hide();
            });
        //$('.loader').hide();
    });

    $('#form_find3').on('submit', function(e){

        e.preventDefault();
        $('.loader').show();

        var $form = $(this);
        $form.find('button').text('Traitement');

        url = $form.attr('action');


        $.post(url, $form.serializeArray())

            .done(function(data, text, jqxhr){

                $("#old_table").hide();
                $('#result').html(jqxhr.responseText);

                $('.loader').hide();
            })
            .fail(function(jqxhr){
                alert(jqxhr.responseText);
            })
            .always(function(){
                $form.find('button').text('Valider');
                $('.loader').hide();
            });
        //$('.loader').hide();
    });

    $(".datepicker").datepicker({
        changeMonth:true,
        changeYear:true,
        dateFormat:'yy-mm-dd'
    });




    $('.loader').hide();

</script>

<style>
    #ul_id li, h3{
        display: inline;
    }
</style>