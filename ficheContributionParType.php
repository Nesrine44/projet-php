<?php
    session_start();

    if(isset($_SESSION['login'])){
        
        $annee = $_SESSION['annee'];
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Consulter participation")){

            header('Location:index.php');

        }else{

            
             if(!isset($_GET['id'])){

                    $idsaintescene = $_SESSION['id'];

                }else{

                    $idsaintescene=$_GET['id'];
                    $_SESSION['id'] = $idsaintescene;
                }

                $_SESION['id'] = $idsaintescene;
                        

            $contributions = array();
          
            //nombre de fidele a afficher par page
            $nbeParPage=100;
            //total de fideles enregistrés
            $total = 0;

            //selection du nombre de fideles enregistrés
            $selectNombreFidele = $db->prepare("SELECT COUNT(idfidele) AS nbretotalfidele FROM fidele WHERE lisible=1");
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

            $fideles = $db->prepare("SELECT * FROM fidele, personne where fidele.personne_idpersonne = personne.idpersonne and fidele.lisible=1 and personne.lisible = 1 ORDER BY nom ASC LIMIT $premierElementDeLaPage, $nbeParPage");
            $fideles->execute();
            

             $selectSainteC = $db->prepare("SELECT  idsaintescene, mois, annee from saintescene where idsaintescene = $idsaintescene AND saintescene.lisible = 1");
                $selectSainteC->execute();

                while ($x=$selectSainteC->fetch(PDO::FETCH_OBJ)) { $saintecene = $x; }

                //selection des differentes contributions
                $selectC = $db->prepare("SELECT idcontribution, type FROM contribution where lisible = 1");
                $selectC->execute();
                while($c= $selectC->fetch(PDO::FETCH_OBJ)){

                    $contributions[$c->idcontribution] = $c->type;
                }

                //fonction qui retoutne la somme pour chaque type de contribution
                function getTotalContributionByType($typecontribution, $idsaintescene){
                    global $db;
                    $select = $db->prepare("SELECT sum(montant) as montant from contributionfidele inner join fidele on fidele_idfidele = idfidele AND saintescene_idsaintescene = $idsaintescene  AND fidele.lisible = 1 AND contributionfidele.lisible = 1 and contribution_idcontribution = $typecontribution");
                    $select->execute();

                    if($x=$select->fetch(PDO::FETCH_OBJ)){
                        return $x->montant;
                    }else{
                        return 0;
                    }
                }

                //function qui retourne la contribution de chaque fidele pour chaque type
                function getContributionFideleBytype($idfidele, $idcontribution, $idsaintescene){
                    global $db;
                    $select = $db->prepare("SELECT sum(montant) as montant from contributionfidele inner join fidele on fidele_idfidele = idfidele AND saintescene_idsaintescene = $idsaintescene  AND fidele.lisible = 1 AND contributionfidele.lisible = 1 and contribution_idcontribution = $idcontribution and idfidele = $idfidele");
                    $select->execute();

                    if($x=$select->fetch(PDO::FETCH_OBJ)){
                        return $x->montant;
                    }else{
                        return 0;
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
                    <li><i class="material-icons">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                    <li><i class="material-icons">assistant</i><a href="saintecene.php" class="afficher col-blue"> Sainte Cène</a></li>
                    <li><i class="material-icons">people</i> Liste Participation du mois de <?php echo $saintecene->mois.$saintecene->annee; ?></li>
                    <li style="float: right;"> 
                        <a class=" col-blue h4" href="report/imprimer_param.php?file=fichecontributionMensuelle&amp;param=<?php echo $idsaintescene; ?>" title="Imprimer la liste des contributions de <?php echo $saintecene->mois.$saintecene->annee; ?>" target="_blank"><i class="material-icons">print</i> Imprimer</a>
                    </li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading h4 text-center">
                        Liste Participation du mois de <?php echo $saintecene->mois.$saintecene->annee; ?>
    <!--                     <div class="row clearfix inputTopSpace">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">edite</i>
                                </span>
                            <div class="form-line">
                                <input class="form-control" id="recherche" type="text" name="search" placeholder="Rechercher un fidèle">
                            </div>
                            </div>
                        </div>
                        <div class="col-md-8 ">
                            
                        </div>
                        </div> -->
                    </header>
                    <div id="result" class="table-responsive"></div>

                    <div id="old_table" class="table-responsive">
                        <table class="table table-bordered table-advance table-responsive table-hover tableau_dynamique">
                            <thead>
                                <tr>
                                   <th><i class="material-icons iconposition"></i> Numéro</th>
                                   <th><i class="material-icons iconposition">code</i> Code</th>
                                   <th><i class="material-icons iconposition">people</i> Noms et prenom</th>
                                   <th><i class="material-icons iconposition">people</i> Statut</th>
                                   <?php
                                       foreach ($contributions as $type){
                                            echo '<th><i class="material-icons iconposition"></i>' .$type.'</th>';
                                        }
                                    ?>    
                                    <th><i class="material-icons iconposition"></i> Total</th>
                                    <th><i class="material-icons iconposition">settings</i> Action</th>
                                </tr>
                            </thead>
                             <tfoot>
                                <tr>
                                   <th><i class="material-icons iconposition"></i> Numéro</th>
                                   <th><i class="material-icons iconposition">code</i> Code</th>
                                   <th><i class="material-icons iconposition">people</i> Noms et prenom</th>
                                   <th><i class="material-icons iconposition">people</i> Statut</th>
                                   <?php
                                       foreach ($contributions as $type){
                                            echo '<th><i class="material-icons iconposition"></i>' .$type.'</th>';
                                        }
                                    ?>    
                                    <th><i class="material-icons iconposition"></i> Total</th>
                                    <th><i class="material-icons iconposition">settings</i> Action</th>
                                </tr>
                            </tfoot>
                            <tbody>    
                             <?php
                                $n = 0;
                                while($fidele = $fideles->fetch(PDO::FETCH_OBJ)){
                             ?>
                                    <tr>
                                        <td><?php echo ++$n; ?></td>
                                        <td><?php echo $fidele->codefidele; ?></td>
                                        <td><?php echo $fidele->nom.' '.$fidele->prenom; ?></td>
                                        <td><?php echo $fidele->statut; ?></td>
                                    <?php
                                        $stotal = 0;
                                       foreach ($contributions as $idcontribution=>$type){
                                            $rond = getContributionFideleBytype($fidele->idfidele, $idcontribution, $idsaintescene);
                                            echo '<td align=center>'.$rond.'</td>';
                                            $stotal+=$rond;
                                        } 
                                        echo'<td align=center><b>'.$stotal.'</b></td>';                                       
                                    ?> 
                                        <td>
                                            <a class="col-blue afficher" href="afficherContributionFidele.php?idfidele=<?php echo $fidele->idfidele; ?>&idpersonne=<?php echo $fidele->personne_idpersonne; ?>&page=ficheContributionParType.php?code=<?php echo $fidele->idpersonne; ?>" title="Voir"><i class="material-icons">loupe</i></a>

                                            <a class="col-green" href="report/imprimer_param2.php?file=ticket&param=<?php echo $fidele->idfidele; ?>&param2=<?php echo $idsaintescene ?>" title="Imprimer le reçu" target="_blank"><i class="material-icons">print</i>
                                            </a>
                                        </td>
                                    </tr>
                             <?php     
                                }    
                             ?>         
                            </tbody>
                            <tfoot>
                                <tr>
                                <td colspan="4" align=center><h4><b>Total</b></h4></td>
                                <?php 
                                    $total = 0;
                                    foreach ($contributions as $idcontribution=>$type){
                                            $srond = getTotalContributionByType($idcontribution, $idsaintescene);
                                            echo '<td align=center><b>'.$srond.'</b></td>';
                                            $total+=$srond;
                                        }  
                                    echo '<td align=center><h3><b>'.$total.'</b><h3></td>';    
                                 ?>   
                                 <td></td>
                                </tr>         
                            </tfoot>
                        </table>

                                 <?php //pagination($pageCourante, $nbDePage, "ficheContributionParType"); ?>
                            
                                  <div align="center">
                                    <a class="col-blue h3" href="report/imprimer_param.php?file=fichecontributionMensuelle&amp;param=<?php echo $idsaintescene; ?>" title="Imprimer la liste des contributions de <?php echo $saintecene->mois.$saintecene->annee; ?>" target="_blank"><i class="material-icons iconposition">print</i> Imprimer</a><br>
                                </div>  
                    </div>
                </section>
            </div>
        </div>
    </section>

<script type="text/javascript">

   $(".tableau_dynamique").DataTable();

    $('.afficher').on('click', function(af){

                            af.preventDefault();
                            $('.loader').show();
                           var $b = $(this);
                            url = $b.attr('href');
                           $('#main-content').load(url, function(){
                                $('.loader').hide();
                           });
                        });


    $('.item').on('click', function(i){
        $('.loader').show();

        i.preventDefault();
        $('#modifiertext').html('Chargement...');
        var $i = $(this);                            
        url = $i.attr('href');
        $('.loader').show();
        $('#main-content').load(url,function(){
            $('.loader').hide();
        });
        $('#modifiertext').html('');
    });   
  

    $('#recherche').keyup(function(){

        var txt = $(this).val();

        // alert(txt);
        if(txt != ''){
            $.ajax({

                url:"searchCont.php?id=<?php echo $idsaintescene; ?>",
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
