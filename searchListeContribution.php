<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
		
	   require_once('includes/connexionbd.php');
       require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Afficher contribution")){

            header('Location:index.php');

        }else{

       		$output = "";
       		$sql = null;

       		if(!isset($_GET['search'])){

       			echo 'Aucun resultat';

       		}else{

       			$txt = addslashes($_GET['search']);

                $idsaintescene = $_GET['id'];
                

                function getMontant($idfidele, $idsaintescene){

                    $montant = 0;

                    global $db; 

                    $selectMontant = $db->prepare("SELECT sum(contribution) as sommes from fidelesaintescene where fidele_idfidele = $idfidele and saintescene_idsaintescene = $idsaintescene");
                    $selectMontant->execute();

                    while($s=$selectMontant->fetch(PDO::FETCH_OBJ)){

                        $montant = ($s->sommes ? $s->sommes : 0);
                    }

                    return $montant;
                 }

	       		$sql = $db->prepare("SELECT fidele_idfidele as idfidele, idsaintescene, nom, prenom, sexe,  codefidele, idpersonne FROM personne, fidele, saintescene, fidelesaintescene   WHERE idpersonne=personne_idpersonne AND saintescene_idsaintescene = idsaintescene AND fidele.idfidele = fidelesaintescene.fidele_idfidele AND saintescene_idsaintescene = $idsaintescene AND personne.lisible=1 AND fidele.lisible=1 AND saintescene.lisible = 1 AND (nom LIKE '%".$txt."%' OR prenom LIKE '%".$txt."%' OR CONCAT(nom, ' ', prenom) LIKE '%".$txt."%' OR codefidele LIKE '%".$txt."%') GROUP BY idfidele ORDER BY nom ASC");
	       		$sql->execute();

                $selectSainteC = $db->prepare("SELECT  idsaintescene, mois, annee from saintescene where idsaintescene = $idsaintescene AND saintescene.lisible = 1");
                $selectSainteC->execute();

                while ($x=$selectSainteC->fetch(PDO::FETCH_OBJ)) {
                    
                    $saintecene = $x;
                }

                $totalFidele = 0;

	    		if($sql){

	    			$output.='<h4 align="center">Search Result</h4>';
	    			$output.='<table class="table table-striped table-advance table-hover">';
	    			$output.='<tr>
	                                        <th><i class="material-icons"></i>Numéro</th>
	                                        <th><i class="material-icons"></i> Code</th>
	                                        <th><i class="material-icons"></i>Noms et prenoms</th>	
                                            <th style="text-align: center;"><i class="material-icons"></i>Montant</th>';
                    $output .= '            <th><i class="material-icons"></i> Action</th>  
	                          </tr>';
	    			
	    			$n = 0;
	    			while($row=$sql->fetch(PDO::FETCH_OBJ)){

	    				$output.='

	    					<tr>
	    						<td>'.++$n.'</td>
	    						<td>'.$row->codefidele.'</td>
	    						<td><a';

                                        if(has_Droit($idUser, "Afficher fidele")){

                                            $output .=  ' href="afficherFidele.php?code='.$row->idpersonne.'"';

                                        }else{

                                            $output .=  "";
                                        }


                              $output .=  ' class="afficher">'.$row->nom.' '.$row->prenom.'</a></td>';

                                           

                        $output .= '<td style="text-align: center;">'.getMontant($row->idfidele, $idsaintescene).'</td>
	    						<td>
                                     <a class="btn btn-primary afficher" href="afficherParticipation.php?code='.$row->idfidele.'"'; 

                                        if(!has_Droit($idUser, "Consulter participation")){

                                            $output .= 'disabled';
                                        }else{
                                            $output .= "";
                                        }
                        $output .=  'title="Visualiser les details"><i class="icon_plus_alt2"></i></a>
                                                
                                           
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

                         $('.listeSexe').on('click', function(e){

                                e.preventDefault();

                                var z = $(this);
                                target = z.attr('href');

                                $('#main-content').load(target);                                
                            
                        });
						$('.loader').hide();
    </script>

