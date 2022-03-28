<?php
session_start();

if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login'];

    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');

    if(!has_Droit($idUser, "Consulter participation")){

        header('Location:index.php');

    }else{

        $contributions = $db->prepare("SELECT type from contribution where lisible = 1");
        $contributions->execute();

        $output = "";
        $sql = null;
        

        if(!isset($_GET['search'])){

            echo 'Aucun resultat';

        }else{

            $txt = addslashes($_GET['search']);

            $sql = $db->prepare("SELECT fidele_idfidele as idfidele, idsaintescene, nom, prenom, sexe,  codefidele, idpersonne, idcontributionfidele FROM personne, fidele, saintescene, contributionfidele   WHERE idpersonne=personne_idpersonne AND saintescene_idsaintescene = idsaintescene AND fidele.idfidele = contributionfidele.fidele_idfidele AND personne.lisible=1 AND fidele.lisible=1 AND saintescene.lisible = 1 AND contributionfidele.lisible=1 AND recu = 0 GROUP BY idfidele
                                    AND (nom LIKE '%".$txt."%' OR prenom LIKE '%".$txt."%' OR CONCAT(nom, ' ', prenom) LIKE '%".$txt."%' OR codefidele LIKE '%".$txt."%'OR statut LIKE '%".$txt."%') ORDER BY nom ASC LIMIT 0, 10");

            $sql->execute();

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

            if($sql){

                $output.='<h4 align="center"><Search></Search> Result</h4>';
                $output.='<table class="table table-striped table-hover">';
                $output.='<tbody>';
                $output.='<tr>
                            <th>Code</th>
                            <th><i class="icon_profile"></i>Noms et prenom</th>';

                             while ($cont = $allContributions->fetch(PDO::FETCH_OBJ)) {
                                
                                $output.='<th style="text-align:center;">' .$cont->type. '</th>';
                                array_push($idcontributions, $cont->idcontribution);

                            }
                                $output.='<th><i class=""></i>Total</th>
                                    <th><i class="icon_cogs"></i> Action</th>;
                         </tr>';
                            while($liste=$sql->fetch(PDO::FETCH_OBJ)){

                                $totalFidele = 0;
                                $total_contribution = 0;

                                $output.='<tr>
                                             <td>'.$liste->codefidele.'</td>
                                             <td><a class="afficher" title="Afficher le fidèle" ';

                                             if(has_Droit($idUser, "Afficher fidele"))
                                            {
                                                $output.='href="afficherFidele.php?code='.$liste->idpersonne.'"';

                                            }

                                $output .= '>'.$liste->nom.' '.$liste->prenom.'</a></td>';
                                             
                                for($i=0; $i < count($idcontributions); $i++){

                                    $output .= '<td style="text-align: center;">';

                                    $val = getMontant($liste->idfidele, $liste->idsaintescene, $idcontributions[$i]); 

                                    $output .= $val;

                                    $total_contribution += $val;

                                    $output .= '</td>';
                                }

                                 $output .= '<td style="text-align: center;">'.$total_contribution.'</td>';

                                 $output .= '<td>
                                                <div class="btn-group">
                                                    <a class="btn btn-primary valider" href="reception.php?id1='.$liste->idfidele.'&amp;id2='.$liste->idsaintescene.'&amp;codefidele='.$liste->codefidele.'" title="Valider"';
                                                if(!has_Droit($idUser, "Consulter participation")){

                                                    $output .='disabled';

                                                }else{

                                                    $output .= "";
                                                } 
                                                    $output .= '><i class="icon_plus_alt2"></i></a>
                                                </div>                                            
                                            </td>
                                        </tr>
                                    ';

                            }
                $output.='</table>';


                echo $output;



            }else{

                echo 'Data  Not Found';
            }

        }

    }
    //return json data
    //   echo json_encode($data);

}else{

    header('Location:login.php');
}

?>

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

    $('.afficher').on('click', function(af){

        af.preventDefault();
        var $b = $(this);
        url = $b.attr('href');

        $('#main-content').load(url);
    });

    $('.ordre_rangement').on('click', function(e){

        e.preventDefault();

        var z = $(this);
        target = z.attr('href');

        $('#main-content').load(target);


    });

   
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

            $('#main-content').load('listeAttente.php');
          }
        }
      });
    }

    $('.loader').hide();

  });
	$('.loader').hide();
</script>

