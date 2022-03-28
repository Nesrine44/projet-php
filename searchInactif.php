<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
		
	   require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');
        require_once('includes/connexionDefault.php');

        if(!has_Droit($idUser, "Editer parametres")){

            header('Location:index.php');

        }else{

       		$output = "";
       		$sql = null;

       		if(!isset($_GET['search'])){

       			echo 'Aucun resultat';

       		}else{

       			$txt = addslashes($_GET['search']);

                $annee_encours = $_SESSION['annee'];
                $base_old = "paroisse".$annee_encours;
                                               
                

	       		$sql = $db->prepare("SELECT idfidele, nom, prenom, sexe, nomzone, idzone, statut, codefidele FROM ".$base_old.".personne, ".$base_old.".fidele, ".$base_old.".zone  WHERE idpersonne=personne_idpersonne AND zone.idzone = personne.zone_idzone AND personne.lisible=1 AND fidele.lisible=0 and fidele.etat = 1 AND (nom LIKE '%".$txt."%' OR prenom LIKE '%".$txt."%' OR CONCAT(nom, ' ', prenom) LIKE '%".$txt."%' OR codefidele LIKE '%".$txt."%') ORDER BY nom ASC LIMIT 0, 10");

	       		$sql->execute();

	    		if($sql){

	    			$output.='<h4 align="center">Search Result</h4>';
	    			$output.='<table class="table table-striped table-advance table-hover">';
	    			$output.='<tr>
	                                        
	                                        <th><i class="icon_pin_alt"></i> Code</th>
	                                        <th><i class="icon_profile"></i>Noms et prenoms</th>
                                            <th><i class="icon_calendar"></i>Statut</th>
	                                        <th><i class="icon_calendar"></i>Sexe</th>
	                                        <th><i class="icon_pin_alt"></i>Zone</th>
	                                        <th><i class="icon_cogs"></i> Action</th>
	                                    </tr>';
	    			
	    			$n = 0;
	    			while($row=$sql->fetch(PDO::FETCH_OBJ)){

	    				$output.='

	    					<tr>
	    						<td>'.$row->codefidele.'</td>
	    						<td>'.$row->nom.' '.$row->prenom.'</td>
                                <td>'.$row->statut.'</td>
	    						<td>'.$row->sexe.'</td>	    						
	    						<td>'.$row->nomzone.'</td>
	    						<td>    								
                                    
                                    <a class="btn btn-success activation" href="activation.php?id='.$row->idfidele.'" title="Activer le fidele"><i class="icon_check_alt2"></i></a>
                                            
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

        $('.activation').on('click', function(e){

        e.preventDefault();

        var $a = $(this);
        var url = $a.attr('href');
        
        if(window.confirm('Voulez-vous activer ce fidèle?')){
            $.ajax(url, {

                dataType: "json",
                success: function(json){

                    if(json){

                        $('.resultat').html(json);
                        $('#success').hide();
                        $('#echec').show();
                        $('.loader').hide();

                    }else{
                        
                        $('#main-content').load('activationFidele.php');
                    }
                    
                },

                error: function(){

                    alert("Une erreur est survenue lors de l\' du fidèle");
                }
            });
        }
    });
    
    $('.loader').hide();
</script>

