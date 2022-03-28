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

            $mois  = array('01'=>"Janvier", '02'=>"Fevrier", '03'=>"Mars", '04'=>"Avril", '05'=>"Mai", '06'=>"Juin", '07'=>"Juillet", '08'=>"Aout", '09'=>"Septembre", '10'=>"Octobre", '11'=>"Novembre", '12'=>"Decembre");
              $current = $mois[date('m')];

              $code = array();
            

              $selectAllFidele = $db->prepare("SELECT fidele_idfidele as idfidele, idsaintescene, nom, prenom, sexe,  codefidele, idpersonne, idcontributionfidele FROM personne, fidele, saintescene, contributionfidele   WHERE idpersonne=personne_idpersonne AND saintescene_idsaintescene = idsaintescene AND fidele.idfidele = contributionfidele.fidele_idfidele AND personne.lisible=1 AND fidele.lisible=1 AND saintescene.lisible = 1 AND contributionfidele.lisible=1 AND recu = 0 GROUP BY idfidele  ORDER BY nom");

                $selectAllFidele->execute();

                $allContributions = $db->prepare("SELECT idcontribution, type from contribution where lisible = 1");
                $allContributions->execute();


               function getMontant($idfidele, $idsaintescene, $idcontribution){

                    $montant = 0;

                    global $db; 

                    $selectMontant = $db->prepare("SELECT sum(montant) as sommes from contributionfidele where fidele_idfidele = $idfidele and saintescene_idsaintescene = $idsaintescene AND contribution_idcontribution = $idcontribution AND lisible = 1 And recu = 0");
                    $selectMontant->execute();

                    while($s=$selectMontant->fetch(PDO::FETCH_OBJ)){

                        $montant = ($s->sommes ? $s->sommes : 0);
                    }

                    return $montant;
                 }

                 $idcontributions = array();              

            
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
                <li><i class="fa fa-files-o"></i><a href="saintecene.php" class="afficher">Sainte Cène</a></li>
                <li><i class="fa fa-files-o"></i>Liste d'attente</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <section class="panel">

                <div class=class="row">
                    <div class="col-lg-12">
                        <header class="panel-heading">
                          Liste des fidèles dans la liste d'attente pour la sainte cène de <?php echo $current; ?>
                        </header>  
                        
                        <div id="result" class="table-responsive"> </div>
                        
                       <div id="old_table" class="table-responsive">
                            <table class="table table-bordered table-advance table-hover tableau_dynamique">
                                <thead>
                                    <tr>
                                        <th><i class="icon_pin_alt"></i> Code</th>
                                        <th><i class="icon_profile"></i>Noms et prenoms</th>    
                                        <?php
                                          while ($cont = $allContributions->fetch(PDO::FETCH_OBJ)) {
                                            
                                        ?>

                                            <td style="text-align: center"><?php echo $cont->type ?></td>

                                        <?php

                                            array_push($idcontributions, $cont->idcontribution);
                                          }
                                        ?>                  
                                      <th style="text-align: center;">
                                        Total
                                      </th>                                         
                                        <th><i class="icon_cogs"></i> Action</th>   
                                    </tr>
                                </thead>
                                <tbody>    
                                    <!-- Insertion des infos dans la table-->
                                    <?php                                        
                                        while($liste=$selectAllFidele->fetch(PDO::FETCH_OBJ)){
                                            $totalFidele = 0;
                                            $total_contribution = 0;
                                        ?>
                                    <tr>
                                        <td><?php echo $liste->codefidele; ?></td>
                                        <td>
                                            <a 
                                             <?php 
                                                if(has_Droit($idUser, "Afficher fidele")){
                                                    echo 'href="afficherFidele.php?code='.$liste->idpersonne.'"';
                                                }else{echo "";}
                                              ?> class="afficher" title="Afficher le fidèle"><?php echo $liste->nom.' '.$liste->prenom; ?>                                                  
                                            </a>
                                        </td>                                       

                                        <?php 

                                            for($i=0; $i < count($idcontributions); $i++){

                                        ?>
                                            
                                          <td style="text-align: center;">
                                            <?php 
                                              $val = getMontant($liste->idfidele, $liste->idsaintescene, $idcontributions[$i]); 

                                                echo $val;

                                                $total_contribution += $val;
                                              ?></td>  

                                        <?php
                                          }   
                                          ?>  
                                          <td style="text-align: center;"><?php echo $total_contribution; ?></td>                                 
                                        <td>
                                            <div class="btn-group">
                                                <a class="btn btn-primary valider" href="reception.php?id1=<?php echo $liste->idfidele;?>&amp;id2=<?php echo $liste->idsaintescene;?>&amp;codefidele=<?php echo $liste->codefidele;?>" title="Valider" <?php if(!has_Droit($idUser, "Consulter participation")){echo 'disabled';}else{echo "";} ?>><i class="icon_plus_alt2"></i></a>
                                            </div>
                                            
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>    
                       </div>
                     
                    </div>
                </div>
            </section>
        </div>
    </div>                    
</section>


<script>
    
   function $_GET(param, url) {
      var vars = {};
      url.replace( location.hash, '' ).replace( 
        /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
        function( m, key, value ) { // callback
          vars[key] = value !== undefined ? value : '';
        }
      );

      if ( param ) {
        return vars[param] ? vars[param] : null;  
      }
      return vars;
  }

  $('.valider').on('click', function(e){     

    e.preventDefault();

     $('.loader').show();


    var link = $(this).attr('href');

    var code = $_GET('codefidele', link); 
       

    if(window.confirm("Voulez vous valider le fidèle de code "+code)){

       $.ajax({

        url:link,
        data:'',
        dataType:'json',
        success:function(json){

          if(json == 1){
            $('.loader').show();
            $('#main-content').load('listeAttente.php', function(){
                $('.loader').hide();
            });
          }
        }
      });
    }

    $('.loader').hide();

  });

$('.afficher').on('click', function(af){

                            af.preventDefault();
                           var $b = $(this);
                            url = $b.attr('href');

                           $('#main-content').load(url);
                        });

                        $('.item').on('click', function(i){

                            i.preventDefault();
                            $('#modifiertext').html('Chargement...');
                            var $i = $(this);                            
                            url = $i.attr('href');

                             $('#main-content').load(url);

                            $('#modifiertext').html('');
                        });
  $('#recherche').keyup(function(){

        var txt = $(this).val();

        // alert(txt);
        if(txt != ''){
            $.ajax({

                url:"searchAttente.php",
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
    $(".tableau_dynamique").DataTable();
    $('.loader').hide();

</script>