<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        $annee = $_SESSION['annee'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Afficher fidele")){

            header('Location:index.php');

        }else{

            $personne = null;
            $bap=null;
            $conf=null;
            $mal=null;
            if(!isset($_GET['code']) || empty($_GET['code'])){

                header('Location:index.php');

            }else{
                $idpersonne1=$_GET['code'];

                $selectInfo = $db->prepare("SELECT nom, prenom, datenaiss, annee_enregistrement, nombre_enfant, idpersonne, pere_vivant, mere_vivant, village, pere, mere, lieunaiss, idfidele, email, statut_pro, situation_matri, etablissement, domaine, diplome, employeur, niveau, serie, profession, sexe, photo, nomzone, idzone, telephone, statut, conjoint, religion_conjoint, codefidele, personne.arrondissement as idarron, iddepartement, idregion, arrondissement.arrondissement as nom_arro, departement.departement as nom_dept, region.region AS nom_region
                                            FROM personne, zone, fidele, arrondissement, departement, region
                                            WHERE idpersonne = $idpersonne1
                                            AND zone.idzone = personne.zone_idzone
                                            AND personne.idpersonne = fidele.personne_idpersonne
                                            AND departement_iddepartement = iddepartement
                                            AND region_idregion = idregion
                                            AND idarrondissement = personne.arrondissement
                                            AND arrondissement.lisible = 1
                                            AND departement.lisible = 1
                                            AND region.lisible = 1
                                            AND fidele.lisible=1
                                            AND fidele.etat=1
                                            AND personne.lisible=1
                                            ");
                $selectInfo->execute();


                while($s = $selectInfo->fetch(PDO::FETCH_OBJ)){

                    $personne = $s;
                }

                $ps=$db->prepare("SELECT *
                                    FROM bapteme
                                    WHERE fidele_idfidele=$personne->idfidele
                                    AND lisible=1
                                  ");
                $ps->execute();

                while($b = $ps->fetch(PDO::FETCH_OBJ)){

                    $bap = $b;
                }

                $ps1=$db->prepare("SELECT *
                                    FROM confirmation
                                    WHERE fidele_idfidele=$personne->idfidele
                                    AND lisible=1
                                  ");
                $ps1->execute();

                while($c = $ps1->fetch(PDO::FETCH_OBJ)){

                    $conf = $c;
                }

                $ps2=$db->prepare("SELECT *
                                    FROM malade
                                    WHERE fidele_idfidele=$personne->idfidele
                                    AND lisible=1
                                  ");
                $ps2->execute();

                while($m = $ps2->fetch(PDO::FETCH_OBJ)){

                    $mal = $m;
                }


                $decision=($bap?'Oui':'Non');
                $decision1=($conf?'Oui':'Non');
                $decision2=($mal?'Oui':'Non');



                $selectIdFid = "SELECT idfidele FROM fidele WHERE personne_idpersonne=$idpersonne1";
                $selectInfoselectIdFid=$db->query("$selectIdFid");
                while($idfidele=$selectInfoselectIdFid->fetch(PDO::FETCH_ASSOC)){
                    $idfidele1=$idfidele['idfidele'];
                }

                $selectMontantOffrande = "SELECT SUM(montant) as totaloffrande FROM contributionfidele WHERE fidele_idfidele=$personne->idfidele AND typecontribution='offrandes' AND lisible=1";

                $selectInfoMontantOffrande=$db->query("$selectMontantOffrande");
                while($idMontantOffrande=$selectInfoMontantOffrande->fetch(PDO::FETCH_ASSOC)){
                    $sommeoffrande=$idMontantOffrande['totaloffrande'];
                }

                $selectMontantTravaux = "SELECT SUM(montant) as totalotravaux FROM contributionfidele WHERE fidele_idfidele=$personne->idfidele AND typecontribution='travaux' AND lisible=1";
                $selectInfoMontantTravaux=$db->query("$selectMontantTravaux");
                while($idMontantTravaux=$selectInfoMontantTravaux->fetch(PDO::FETCH_ASSOC)){
                    $sommetravaux=$idMontantTravaux['totalotravaux'];
                }

                $selectMontantConsiegerie = "SELECT SUM(montant) as totaloconsiegerie FROM contributionfidele WHERE fidele_idfidele=$personne->idfidele AND typecontribution='conciergerie' AND lisible=1;";
                $selectInfoMontantConsiegerie=$db->query("$selectMontantConsiegerie");
                while($idMontantConsiegerie=$selectInfoMontantConsiegerie->fetch(PDO::FETCH_ASSOC)){
                    $sommeconsiegerie=$idMontantConsiegerie['totaloconsiegerie'];
                }

                $selectMontantDon = "SELECT SUM(montant) as totaldon FROM contributionfidele WHERE fidele_idfidele=$personne->idfidele AND typecontribution='don' AND lisible=1;";
                $selectInfoMontantDon=$db->query("$selectMontantDon");
                while($idMontantDon=$selectInfoMontantDon->fetch(PDO::FETCH_ASSOC)){
                    $sommedon=$idMontantDon['totaldon'];
                }

                $groupes = $db->prepare("SELECT nomgroupe, typegroupe, date_inscription, groupe_idgroupe FROM groupe, fidelegroupe WHERE fidele_idfidele=$personne->idfidele AND groupe_idgroupe = groupe.idgroupe AND groupe.lisible=1 AND fidelegroupe.lisible = 1");
                $groupes->execute();
            }
        }

    }else{
        header('Location:login.php');
    }
?>

    <section class="wrapper">
        
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb col-blue">
                    <li><i class="material-icons">home</i><a href="index.php" class="col-blue">Accueil</a></li>
                    <li><i class="material-icons">people</i>   <a href="#" class="col-blue"> Fidèles</a></li>
                    <li><i class="material-icons">list</i><a class="col-blue listeF" href="listeFideles.php">Liste Fidèles</a></li>
                    <li><i class="material-icons">person_pin</i> <a class="col-blue" href="#">Profile</a> </li>
                </ol>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="">

                    <div class="header text-center h4 card" style="line-height: 50px;">
                          
                            Informations personnelles
                         
                            
                    </div>

                <!-- profile-widget -->
            <div class="col-lg-12">
                <div class="profile-widget-info">
                    <div class="panel-body bg-blue-grey" >
                        <div class="col-lg-2">
                            <div class="">
                                <img src="images/<?php echo $personne->photo; ?>" width="150" height="150" alt="">
                            </div>
                        </div>
                        <div class="profile-widget">
                            <div class="col-lg-6 col-sm-4 follow-info">
                                <p><h4>Noms et prénoms:<strong> <?php echo $personne->nom.' '.$personne->prenom; ?></strong></h4></p>
                                <p><h4>Date de naissance: <strong><?php echo $personne->datenaiss; ?></strong></h4></p>
                                <p><h4>Lieu de naissance: <strong><?php echo $personne->lieunaiss; ?></strong></h4></p>
                                <p><h4>Sexe: <strong> <?php echo $personne->sexe ; ?></strong></h4></p>
                            </div>

                            <div class="col-lg-4 col-sm-3 follow-info">
                                <p><h4>Téléphone: <strong><?php echo $personne->telephone; ?></strong></h4></p>
                                <p><h4>Email: <strong><?php echo $personne->email; ?></strong></h4></p>
                                <p><h4>Zone d'habitation: <strong><?php echo $personne->nomzone; ?></strong></h4></p>
                                <p><h4>Code: <strong><?php echo $personne->codefidele; ?></strong></h4></p>
                            </div>
                        </div>
                    </div></div>

                    <div><br> 
                        <ul class="nav nav-tabs  col-sm-12 col-lg-10" >
                           <center>
                                <button data-toggle="tab" href="#etatCivil" class="btn btn-info waves-effect">Etat Civil</button>
                           
                                <button data-toggle="tab" href="#statutParoissial"  class="btn btn-info waves-effect">Statut Paroissial</button>
                            
                            
                                <button data-toggle="tab" href="#statutProfessionnel"  class="btn btn-info waves-effect">Statut Professionnel</button>
                           
                           
                                <button data-toggle="tab" href="#contributionsGroupes"  class="btn btn-info waves-effect">Contributions et Groupes</button>
                                <span style="float:right"> 

                                    <button class="btn bg-green waves-effect listeF" href="modifierFidele.php?id=<?php echo $personne->idpersonne; ?>" title="Modifier" 
                                        <?php if(!has_Droit($idUser, "Modifier un fidele")|| (date('Y') != $annee)){echo 'disabled';}
                                    else{echo "";} ?>><i class="material-icons">border_color</i> Modifier</button>
                                     <a class="btn bg-blue waves-effect" href="report/imprimer_param.php?file=profil_fidelee&param=<?php echo $personne->idpersonne; ?>" title="Imprimer la fiche du fidèle" target="_blank">
                                <i class="material-icons">print</i> Imprimer</a>
                                    
                                    <button data-toggle="tab" href="#ecrire"  class="btn bg-green waves-effect"><i class="material-icons">edit</i> Ecrire</button>
                                </span></center>
                         </ul> 
                    </div> 
                   
                
            </div>
        </div><br></div>
        </div>
        


        <div class="panel-body card">
            <div class="tab-content">

                <!-- Etat Civil -->
                <div id="etatCivil" class="tab-pane active">
                    <table class="table table-striped table-advance table-hover">
                        <tbody>
                            <tr>
                                <th>Nom du père</th>
                                <th>Nom de la mère</th>
                                <th>Situation matrimoniale</th>
                                <th>Nom conjoint</th>
                                <th>Religion conjoint</th>
                            </tr>
                            <tr>
                                <td><?php echo $personne->pere; ?>(<?php if($personne->pere_vivant == 0){ echo "Décédé";}else{ echo "Vivant";} ?>)</td>
                                <td><?php echo $personne->mere; ?>(<?php if($personne->mere_vivant == 0){ echo "Décédée";}else{ echo "Vivante";} ?>)</td>
                                <td><?php echo $personne->situation_matri; ?></td>
                                <td><?php echo $personne->conjoint; ?></td>
                                <td><?php echo $personne->religion_conjoint; ?></td>
                            </tr>
                            <tr>
                                <th>Nombre d'enfants</th>
                                <th>Région d'origine</th>
                                <th>Département</th>
                                <th>Arrondissement</th>
                                <th>Village</th>
                            </tr>
                            <tr>
                                <td><?php echo $personne->	nombre_enfant; ?></td>
                                <td><?php echo $personne->	nom_region; ?></td>
                                <td><?php echo $personne->	nom_dept; ?></td>
                                <td><?php echo $personne->	nom_arro; ?></td>
                                <td><?php echo $personne->	village; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Statut Paroissial -->
                <div id="statutParoissial" class="tab-pane">
                    <table class="table table-striped table-advance table-hover">
                        <tbody>
                        <tr>
                            <th>Statut</th>
                            <th>Baptisé</th>
                            <th>Date de baptème</th>
                            <th>Lieu de baptème</th>
                        </tr>
                        <tr>
                            <td><?php echo $personne->statut; ?></td>
                            <td><?php echo $decision; ?></td>
                            <td><?php echo ($bap? $bap->datebaptise: "" ) ?></td>
                            <td><?php echo ($bap?$bap->lieu_baptise: "" ) ?></td>
                        </tr>
                        <tr>
                            <th>Malade</th>
                            <th>Confirmé</th>
                            <th>Date de confirmation</th>
                            <th>Lieu de confirmation</th>
                        </tr>
                        <tr>
                            <td><?php echo $decision2; ?></td>
                            <td><?php echo $decision1; ?></td>
                            <td><?php echo ($conf? $conf->date_confirmation: "" ); ?></td>
                            <td><?php echo ($conf? $conf->lieu_confirmation: "" ); ?></td>
                        </tr>
                        <tr>
                            <th>Guide</th>
                            <th>Année d'adhésion à l'EEC</th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <td><?php echo ($mal? $mal->guide: "" ); ?></td>
                            <td><?php echo ($personne->annee_enregistrement); ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Statut Professionnel -->
                <div id="statutProfessionnel" class="tab-pane">
                    <table class="table table-striped table-advance table-hover">
                        <tbody>
                        <tr>
                            <th>Statut</th>
                            <th>Etablissement</th>
                            <th>Classe/Niveau</th>
                            <th>Série/Filère</th>
                        </tr>
                        <tr>
                            <td><?php echo $personne->statut_pro; ?></td>
                            <td><?php echo $personne->etablissement; ?></td>
                            <td><?php echo $personne->niveau; ?></td>
                            <td><?php echo $personne->serie; ?></td>
                        </tr>
                        <tr>
                            <th>Dernier diplome</th>
                            <th>Domaine</th>
                            <th>Profession</th>
                            <th>Employeur</th>
                        </tr>
                        <tr>
                            <td><?php echo $personne->diplome; ?></td>
                            <td><?php echo $personne->domaine; ?></td>
                            <td><?php echo $personne->profession; ?></td>
                            <td><?php echo $personne->employeur; ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Contributions et groupes -->
                <div id="contributionsGroupes" class="tab-pane">
                    <span class="h3 text-center">Bilan des contributions</span>
                    <br>
                    <table class="table table-striped table-advance table-hover">
                        <tbody>
                        <tr>
                            <th><i class="icon_pin_alt"></i> Offrandes</th>
                            <th><i class="icon_pin_alt"></i> Travaux</th>
                            <th><i class="icon_pin_alt"></i> Consiègerie</th>
                            <th><i class="icon_pin_alt"></i> Dons</th>
                            <th><i class=""></i> Total</th>
                            <th><i class="icon_cogs"></i> Action</th>
                        </tr>
                        <tr>
                            <td><?php echo $sommeoffrande; ?></td>
                            <td><?php echo $sommetravaux; ?></td>
                            <td><?php echo $sommeconsiegerie; ?></td>
                            <td><?php echo $sommedon; ?></td>
                            <td><?php echo $sommeoffrande+$sommetravaux+$sommeconsiegerie+$sommedon; ?></td>
                            <td>
                               
                                    <a class="col-blue listeF" href="afficherContributionFidele.php?idfidele=<?php echo $personne->idfidele; ?>&idpersonne=<?php echo $personne->idpersonne; ?>&page=afficherFidele.php?code=<?php echo $personne->idpersonne; ?>" title="Voir"><i class="material-icons">loupe</i></a>
                                
                            </td>
                        </tr>
                        </tbody>
                    </table><br>

                    <span style="h3 text-cenetr">Membre des groupes</span>
                    <table class="table table-striped table-advance table-hover">
                        <tbody>
                        <tr>
                            <th>#</th>
                            <th><i class="icon_profile"></i>Nom groupe</th>
                            <th><i class="icon_pin_alt"></i>Type</th>
                            <th><i class="icon_calendar"></i>Date inscription</th>
                            <th><i class="icon_cogs"></i>Action</th>
                        </tr>
                        <?php
                        $n = 0;
                        while($groupe=$groupes->fetch(PDO::FETCH_OBJ)){

                            ?>

                            <tr>
                                <td><?php echo ++$n; ?></td>
                                <td><?php echo $groupe->nomgroupe; ?></td>
                                <td><?php echo $groupe->typegroupe; ?></td>
                                <td><?php echo $groupe->date_inscription; ?></td>
                                <td><a class="col-blue listeF" href="listeMembreGroupe.php?id=<?php echo $groupe->groupe_idgroupe; ?>" title="Voir les membres" <?php if(!has_Droit($idUser, "Afficher groupe")){echo 'disabled';}else{echo "";} ?>><i class="material-icons">loupe</i></a></td>
                            </tr>
                        <?php
                        }
                        ?>
                        <tbody>
                    </table>
                </div>


<br>
                <div id="ecrire" class="tab-pane" style="with:300;hight:300">

                    <section class="panel">

                                <header class="panel-heading">

                                    <h4>Envoyer un message</h4>

                                </header>

                                <div class="panel-body">
                                    <div class="form">
                                        <form class="form-validate form-horizontal" id="form-send-fidele" method="POST" action="sendMailFidele.php?email=<?php echo $personne->email; ?>">
                                            <div class="form-group">
                                            <div class="col-md-6">
                                                <label for="Nom"> Objet:<span class="required">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="material-icons">edite</i>
                                                        </span>
                                                        <div class="form-line">
                                                            <input class="form-control" id="sujet" name="sujet" minlength="2" type="text" required placeholder="Objet du message" />
                                                        </div>
                                                    </div>
                                            </div>
                                                
                                            

                                            
                                                <!-- <label for="cmessage" class="control-label col-lg-2">Message: </label>-->
                                            
                                            <div class="col-sm-12">
                                                <div class="row clearfix">
                                                    <div class="input-form">
                                                    
                                                    <label for="Nom"> Contenu:<span class="required">*</span></label>
                                                        <div class="form-line">
                                                            <textarea class="form-control no-resize" placeholder="Contenu de votre texto" id="message" name="message" rows="6" style="border:1px solid grey;"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                           
                                                      <br> 
                                                      <div class="text-center"> 
                                                    <input class="btn btn-primary send-groupe"  type="submit" name="submit" value="Envoyer" style="margin-left:20px;" /><span class="envoi_en_cours" style="margin-left: 10px; display: none;">Envoi en cours...</span>
                                                </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                    </section>
                </div>
            </div>
        </div>
    </section>

    <Script>

        $('#chargement').hide();
                $('.listeF').on('click', function(af){

                            af.preventDefault();
                           var $b = $(this);
                            url = $b.attr('href');

                           $('#main-content').load(url);
                        });

            $('#form-send-fidele').on('submit', function(e){

                e.preventDefault();
                var $form = $(this);                                
                                     
                url = $form.attr('action');

                $('.envoi_en_cours').show();
                $.post(url, $form.serializeArray())
         
                    .done(function(data, text, jqxhr){  
                                
                        alert('Message envoyé avec succès!');
                       // $('#sujet').val("");
                        //$('#message').val("");

                        $('#main-content').load("afficherFidele.php?code=<?php echo $idpersonne1; ?>");
                                
                    })
                    .fail(function(jqxhr){

                        alert(jqxhr.responseText);
                    })
                    .always(function(){

                        $('.envoi_en_cours').hide();
                    });
                                
            });

            

        $('.loader').hide();


</Script>