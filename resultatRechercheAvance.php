<?php
session_start();

if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login'];
    $annee= $_SESSION['annee'];

    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');

    if(!has_Droit($idUser, "Creer un fidele")){

        header('Location:index.php');

    }else{

        function isAjax(){
            return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
        }

        if(isAjax()){

            $annee_en_cours=$_SESSION['annee'];
            //nombre de fidele a afficher par page
            $nbeParPage=50;
            //total de fideles enregistrés
            $total = 0;

            $requette='SELECT * FROM personne, fidele, zone';
            $requete_count = "SELECT count(idfidele) as total FROM personne, fidele, zone";

            $requette1=' WHERE personne.idpersonne=fidele.personne_idpersonne AND personne.lisible=1 AND fidele.lisible=1 AND fidele.etat=1 AND zone.idzone=personne.zone_idzone AND zone.lisible=1';

            if(isset($_POST['sexe'])){
                $sexe=$_POST['sexe'];
                $requette1 .= " AND sexe='$sexe'";                
            }

            if(isset($_POST['age'])){
                if($_POST['age']=1){
                    if(isset($_POST['age_min']) && isset($_POST['age_max'])){
                        $age_min=$_POST['age_min'];
                        $age_max=$_POST['age_max'];
                        $requette1 .= " AND ($annee_en_cours - year(datenaiss)) >='$age_min' AND ($annee_en_cours - year(datenaiss)) <='$age_max' ";
                       
                    }
                }else{
                    if(isset($_POST['age_mine'])){
                        $age_min=$_POST['age_mine'];
                        $requette1 .= " AND (($annee_en_cours - year(datenaiss)) = '$age_min')";
                    }
                }
            }

            if(isset($_POST['baptise'])){
                $baptise=$_POST['baptise'];
                if($baptise==1){
                    $requette .= ", bapteme";
                    $requete_count .= ", bapteme";
                    $requette1 .= " AND fidele.idfidele=bapteme.fidele_idfidele AND bapteme.lisible=1";
                }else{
                    $requette1 .= " AND idfidele NOT IN (SELECT fidele_idfidele FROM fidele, bapteme WHERE fidele.idfidele=bapteme.fidele_idfidele AND bapteme.lisible=1)";
                }
            }

            if(isset($_POST['confirme'])){
                if($_POST['confirme']==1){
                    $baptise=$_POST['confirme'];
                    $requette .= ", confirmation";
                    $requete_count .= ", confirmation";
                    $requette1 .= " AND fidele.idfidele=confirmation.fidele_idfidele AND confirmation.lisible=1";
                }else{
                    $requette1 .= " AND idfidele NOT IN (SELECT fidele_idfidele FROM fidele, confirmation WHERE fidele.idfidele=confirmation.fidele_idfidele AND confirmation.lisible=1)";
                }
            }

            if(isset($_POST['malade'])){
                $baptise=$_POST['malade'];
                if($baptise==1){
                    $requette .= ", malade";
                    $requete_count .= ", malade";
                    $requette1 .= " AND fidele.idfidele=malade.fidele_idfidele AND malade.lisible=1";
                }else{
                    $requette1 .= " AND idfidele NOT IN (SELECT fidele_idfidele FROM fidele, malade WHERE fidele.idfidele=malade.fidele_idfidele AND malade.lisible=1)";
                }
            }

            if(isset($_POST['choix_zonne'])){
                $zones=$_POST['choix_zonne'];
                $requette1 .= " AND idzone IN (SELECT idzone FROM zone WHERE nomzone='base'";

                foreach($zones as $zone){
                    $x=addslashes($zone);
                    $requette1 .=" OR nomzone='$x'";
                }
                $requette1 .=")";
            }


            if(isset($_POST['choix_statut_matrimonial'])){
                $statu_matri=$_POST['choix_statut_matrimonial'];
                $requette1 .= " AND (situation_matri='base'";

                foreach($statu_matri as $statut_matrimonial){
                    $requette1 .= " OR situation_matri='$statut_matrimonial'";
                }
                $requette1 .=")";
            }


            if(isset($_POST['choix_statut_professionnel'])){
                $statu_pro=$_POST['choix_statut_professionnel'];
                $requette1 .= " AND (statut_pro='base'";

                foreach($statu_pro as $statut_professionnel){

                    $y = addslashes($statut_professionnel);
                    $requette1 .= " OR statut_pro='$y'";
                }
                $requette1 .=")";
            }


            if(isset($_POST['choix_statut_paroissial'])){
                $statu_paroi=$_POST['choix_statut_paroissial'];
                $requette1 .= " AND (statut='base'";

                foreach($statu_paroi as $statut_paroissial){
                    $x = addslashes($statut_paroissial);
                    $requette1 .=" OR statut='$x'";
                }
                $requette1 .=")";
            }

            $requette1 .= " ORDER BY nom";           
            $requette .= $requette1;

            $requette = (isset($_GET['query']) ? str_replace("+", " ", $_GET['query']) : $requette);



            $req = $db->prepare($requette);
            $req->execute();

           while ($req->fetch(PDO::FETCH_OBJ)) {
               ++$total;
           }
           
            $nbDePage = ceil($total/$nbeParPage);
            $pageCourante = 1;

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


             $query = str_replace(" ", "+", $requette);

            $premierElementDeLaPage = ($pageCourante - 1) * $nbeParPage;


            $requette = $requette." LIMIT $premierElementDeLaPage, $nbeParPage";



          //  echo $_SESSION['query'];
           
            $ps=$db->prepare($requette);
            $ps->execute();

           

            ?>


            <section class="wrapper">
                <!--<div class="loader"></div>     -->

                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb">
                            <li class="col-blue"><i class="material-icons">home</i><a href="index.php" class="col-blue">Accueil</a></li>
                            <li class="col-blue"> <i class="material-icons">filter</i> Recherche Avancée</li>
                            <li  class="col-blue" style="float: right;">                        
                                <a class="col-blue" href="report/imprimer_param.php?file=liste_fideles_recherche&param=<?php echo  str_replace("+", " ", $query);
                                 ?>" title="Imprimer le résultat" target="_blank"><i class="material-icons ">local_printshop</i> Imprimer</a>
                            </li>                            
                        </ol>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">

                            <div class="row">
                                <div class="col-lg-12">
                                    <header class="panel-heading card text-center col-brown" style="font-size: 2em;">
                                        <?php echo $total;?> résultat(s) trouvé(s)
                                    </header>

                                    <div id="result"> </div>

                         <div class="body">
                            <div class="table-responsive" id="old_table">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable tableau_dynamique">  
                                            <thead>
                                            <tr>
                                                <th><i class="material-icons iconposition">confirmation_number</i> Numéro</th>
                                                <th><i class="material-icons iconposition">code</i> Code</th>
                                                <th><i class="material-icons iconposition">people</i> Noms et prenoms</th>
                                                <th><i class="material-icons iconposition">ev_station</i> Statut paroissial</th>
                                                <th><i class="material-icons iconposition">people</i> Sexe</th>
                                                <th><span class="material-icons iconposition">location_on</span> Zones</th>
                                                
                                                <th><i class="material-icons iconposition">settings</i> Action</th>
                                            </tr>
                                           </thead>
                                            <tfoot>
                                            <tr>
                                                <th><i class="material-icons iconposition">confirmation_number</i> Numéro</th>
                                                <th><i class="material-icons iconposition">code</i> Code</th>
                                                <th><i class="material-icons iconposition">people</i> Noms et prenoms</th>
                                                <th><i class="material-icons iconposition">ev_station</i> Statut paroissial</th>
                                                <th><i class="material-icons iconposition">people</i> Sexe</th>
                                                <th><span class="material-icons iconposition">location_on</span> Zones</th>
                                                
                                                <th><i class="material-icons iconposition">settings</i> Action</th>
                                            </tr>
                                           </tfoot>
                                            <tbody>
                                            <?php
                                            $n=0;
                                            $ps->execute();
                                            while($liste=$ps->fetch(PDO::FETCH_OBJ)){

                                                /*$ps1=$db->prepare("SELECT nomzone FROM zone WHERE idzone=$liste->zone_idzone");
                                                $ps1->execute();
                                                $zonesss=$ps1->fetch(PDO::FETCH_OBJ)*/
                                                ?>
                                                <tr>
                                                    <td><?php echo ++$n; ?></td>
                                                    <td><?php echo $liste->codefidele; ?></td>
                                                    <td><?php echo $liste->nom.' '.$liste->prenom; ?></td>
                                                    <td><?php echo $liste->statut?></td>
                                                    <td><?php echo $liste->sexe?></td>
                                                    <td><?php echo $liste->nomzone?></td>
                                                   
<td>
     <a class="col-blue afficher" href="afficherFidele.php?code=<?php echo $liste->idpersonne; ?>" title="Visualiser" <?php if(!has_Droit($idUser, "Afficher fidele")){echo 'disabled';}else{echo "";} ?>><i class="material-icons">loupe</i>  </a>

        <a   href="modifierFidele.php?id=<?php echo $liste->idpersonne; ?>" title="Modifier" class="col-green afficher" <?php if(!has_Droit($idUser, "Modifier un fidele") || (date('Y') != $annee)){echo 'hidden';} else{echo "";}?>><i class="material-icons" >border_color</i>  </a>

    <a href="javascript:void(0);" class="col-red" href="supprimerFidele.php?code=<?php echo $liste->idpersonne; ?>" title="Supprimer" <?php if(!has_Droit($idUser, "Supprimer fidele")|| (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">delete</i>  </a>
</td>

                                                </tr>
                                            <?php
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                  </div>


                                        <div class="text-center">
                                <?php //pagination($pageCourante, $nbDePage, "resultatRechercheAvance"); ?>
                            </div>   
                          </div>
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
                    if(window.confirm('Voulez-vous supprimer ce fidèle?')){
                        $.ajax(url, {

                            success: function(){
                                $a.parents('tr').remove();
                            },

                            error: function(){

                                alert("Une erreur est survenue lors de la suppresion du fidèle");
                            }
                        });
                    }
                });

                $('.item').on('click', function(i){

                            i.preventDefault();
                            $('#modifiertext').html('Chargement...');
                            var $i = $(this);                            
                            url = $i.attr('href')+"&query=<?php echo $query; ?>";

                            url

                             $('#main-content').load(url);

                            $('#modifiertext').html('');
                        });

                $('.afficher').on('click', function(af){

                    af.preventDefault();
                    var $b = $(this);
                    url = $b.attr('href');

                    $('#main-content').load(url);
                });

                $('.loader').hide();
                $(".tableau_dynamique").DataTable();

            </script>

        <?php
        }else{
            header('Location:index.php');


        }

    }
}
?>