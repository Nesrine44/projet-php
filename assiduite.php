<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Assiduite a un conseil")){

            header('Location:index.php');

        }else{

           $mensuel = 0;
           $extraordinaire = 0;
           $elargi = 0;
           $consistoire = 0;
           $cinode = 0;
           $nbreAnciens = 0;
           $presenceMensuel = 0;
           $presenceExtra = 0;
           $presenceElargi = 0;
           $presenceConcis = 0;
           $presenceCinode = 0;


           //selection des occurence des conseils
           $selectNbre = $db->prepare("SELECT mensuel, extraordinaire, elargi, consistoire, cinode FROM occurrence WHERE idoccurrence = 1");
           $selectNbre->execute();

           while($nbre=$selectNbre->fetch(PDO::FETCH_OBJ)){

                $mensuel = $nbre->mensuel;
                $extraordinaire = $nbre->extraordinaire;
                $elargi = $nbre->elargi;
                $consistoire = $nbre->consistoire;
                $cinode = $nbre->cinode;
           }

           //selection nu nbre d'anciens
           $selectNbreAnciens = $db->prepare("SELECT COUNT(idfidele) AS nombre FROM fidele WHERE statut = 'ancien' AND lisible = true");
           $selectNbreAnciens->execute();

           while($nbre = $selectNbreAnciens->fetch(PDO::FETCH_OBJ)){

                $nbreAnciens = $nbre->nombre;
           }

           //selection des presences aus differents conseils
                //mensuel
           $selectPM = $db->prepare("SELECT COUNT(fidele_idfidele) AS pmensuel FROM fideleconseil WHERE type = 'conseil mensuel' AND lisible = 1");
           $selectPM->execute();

           while($nbre=$selectPM->fetch(PDO::FETCH_OBJ)){

                $presenceMensuel = $nbre->pmensuel;
            }
                //Extraordinaire
            $selectPE = $db->prepare("SELECT COUNT(fidele_idfidele) AS pextra FROM fideleconseil WHERE type = 'conseil extraordinaire' AND lisible = 1");
            $selectPE->execute();

           while($nbre=$selectPE->fetch(PDO::FETCH_OBJ)){

                $presenceExtra = $nbre->pextra;
            }
                //Elargi
            $selectPEl = $db->prepare("SELECT COUNT(fidele_idfidele) AS pelargi FROM fideleconseil WHERE type = 'conseil elargi' AND lisible = 1");
            $selectPEl->execute();

           while($nbre=$selectPEl->fetch(PDO::FETCH_OBJ)){

                $presenceElargi = $nbre->pelargi;
            }
                //consistoire

            $selectPC = $db->prepare("SELECT COUNT(fidele_idfidele) AS pconcis FROM fideleconseil WHERE type = 'consistoire' AND lisible = 1");
            $selectPC->execute();

           while($nbre=$selectPC->fetch(PDO::FETCH_OBJ)){

                $presenceConcis = $nbre->pconcis;
            } 

                //cinode regional
            $selectPCi = $db->prepare("SELECT COUNT(fidele_idfidele) AS pcinode FROM fideleconseil WHERE type = 'cinode regional' AND lisible = 1");
            $selectPCi->execute();

           while($nbre=$selectPCi->fetch(PDO::FETCH_OBJ)){

                $presenceCinode = $nbre->pcinode;
            }

            $tauxMensuel = ($nbreAnciens==0 ? 0 : number_format(($mensuel==0 ? 0 : $presenceMensuel*100/$mensuel)/$nbreAnciens, 2));
            $tauxExtra = ($nbreAnciens==0 ? 0 : number_format(($extraordinaire==0 ? 0 : $presenceExtra*100/$extraordinaire)/$nbreAnciens, 2));
            $tauxElargi = ($nbreAnciens==0 ? 0 : number_format(($elargi==0 ? 0 : $presenceElargi*100/$elargi)/$nbreAnciens, 2));
            $tauxConcis = ($nbreAnciens==0 ? 0 : number_format(($consistoire==0 ? 0 : $presenceConcis*100/$consistoire)/$nbreAnciens, 2));
            $tauxCinode = ($nbreAnciens==0 ? 0 : number_format(($cinode==0 ? 0 : $presenceCinode*100/$cinode)/$nbreAnciens, 2));
      }
      
  }else{
    header('Location:login.php');
}
?>


    <section class="wrapper">

            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li> <i class="material-icons text-primary">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                    <li> <i class="material-icons text-primary">school</i><a href="#" class="col-blue"> Conseils des Anciens</a></li>
                    <li> <i class="material-icons text-primary">assistant</i><a href="#" class="col-blue">Assiduité aux conseils</a></li>
                </ol>
            </div>
   
            <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="card">

                    <div class="header text-center h4">
                            
                    Assiduité des anciens aux différents conseils
                            
                            
                    </div>
                        
                    

                    <div class="body">
                            <div class="table-responsive" id="old_table">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable tableau_dynamique">  
                                    <thead>
                                  <tr>
                                    <th style="text-align: center;"><i class="material-icons iconposition">confirmation_number</i>Numéro</th>

                                      <th style="text-align: center;"><i class="material-icons iconposition">assistant</i> Conseil</th>
                                      <th style="text-align: center;"><i class="material-icons iconposition">format_list_numbered</i>Nombre total</th>
                                      <th style="text-align: center;"><i class="material-icons iconposition">group_add</i>Nombre d'anciens présents</th>
                                      <th style="text-align: center;"><i class="material-icons iconposition">more_horiz</i>Total d'anciens</th>
                                      <th style="text-align: center;"><i class="material-icons iconposition">assistant</i>Assudité en %</th>
                                  </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th style="text-align: center;"><i class="material-icons iconposition">confirmation_number</i>Numéro</th>

                                      <th style="text-align: center;"><i class="material-icons iconposition">assistant</i> Conseil</th>
                                      <th style="text-align: center;"><i class="material-icons iconposition">format_list_numbered</i>Nombre total</th>
                                      <th style="text-align: center;"><i class="material-icons iconposition">group_add</i>Nombre d'anciens présents</th>
                                      <th style="text-align: center;"><i class="material-icons iconposition">more_horiz</i>Total d'anciens</th>
                                      <th style="text-align: center;"><i class="material-icons iconposition">assistant</i>Assudité en %</th>
                                  </tr>
                                </tfoot>

                                <tbody>  
                                <tr>
                                    <td>1</td>
                                    <td><a href="affichePourcentageMensuel.php" class="link">Mensuel</a></td>
                                    <td style="text-align: center;"><?php echo $mensuel; ?></td>
                                    <td style="text-align: center;"><?php echo $presenceMensuel; ?></td>
                                    <td style="text-align: center;"><?php echo $nbreAnciens; ?></td>
                                    <td style="text-align: center;"><?php echo $tauxMensuel; ?></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td><a href="affichePourcentageExtraordinaire.php" class="link">Extraordinaire</a></td>
                                    <td style="text-align: center;"><?php echo $extraordinaire; ?></td>
                                    <td style="text-align: center;"><?php echo $presenceExtra; ?></td>
                                    <td style="text-align: center;"><?php echo $nbreAnciens; ?></td>
                                    <td style="text-align: center;"><?php echo $tauxExtra; ?></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td><a href="affichePourcentageElargi.php" class="link">Elargi</a></td>
                                    <td style="text-align: center;"><?php echo $elargi; ?></td>
                                    <td style="text-align: center;"><?php echo $presenceElargi; ?></td>
                                    <td style="text-align: center;"><?php echo $nbreAnciens; ?></td>
                                    <td style="text-align: center;"><?php echo $tauxElargi; ?></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td><a href="affichePourcentageConsistoire.php" class="link">Consistoire</a></td>
                                    <td style="text-align: center;"><?php echo $consistoire; ?></td>
                                    <td style="text-align: center;"><?php echo $presenceConcis; ?></td>
                                    <td style="text-align: center;"><?php echo $nbreAnciens; ?></td>
                                    <td style="text-align: center;"><?php echo $tauxConcis; ?></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td><a href="affichePourcentageCinode.php" class="link">Cinode régional</a></td>
                                    <td style="text-align: center;"><?php echo $cinode; ?></td>
                                    <td style="text-align: center;"><?php echo $presenceCinode; ?></td>
                                    <td style="text-align: center;"><?php echo $nbreAnciens; ?></td>
                                    <td  style="text-align: center;"><?php echo $tauxCinode; ?></td>
                                </tr>                                        
                            </table>
                    </div>
                </section>
            </div>
        </div>
    </section>

<script type="text/javascript">
  
    $('.link').on('click', function(e){

        e.preventDefault();
        $link = $(this);       
        var $url = $link.attr('href');
        $('#main-content').load($url);
    });
    $(".tableau_dynamique").DataTable();
	
	$('.loader').hide();

</script>