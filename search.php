<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
		
	   require_once('includes/connexionbd.php');
       require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Lister fidele")){

            header('Location:index.php');

        }else{

       		$output = "";
       		$sql = null;

       		if(!isset($_GET['search'])){

       			echo 'Aucun resultat';

       		}else{

       			$txt = addslashes($_GET['search']);

	       		$sql = $db->prepare("SELECT idpersonne, nom, prenom, sexe, nomzone, idzone, codefidele FROM personne, fidele, zone  WHERE idpersonne=personne_idpersonne AND zone.idzone = personne.zone_idzone AND personne.lisible=1 AND fidele.lisible=1 AND (nom LIKE '%".$txt."%' OR prenom LIKE '%".$txt."%' OR CONCAT(nom, ' ', prenom) LIKE '%".$txt."%' OR codefidele LIKE '%".$txt."%') ORDER BY nom ASC LIMIT 0, 30");

	       		$sql->execute();

	    		if($sql){

	    			$output.='<h4 align="center">Search Result</h4>';
	    			$output.='<table class="table table-responsive table-bordered table-hover tableau_dynamique">';
	    			$output.=' <thead>
                                <tr>
	                                        <th><i class=""></i>#</th>
	                                        <th><i class="icon_pin_alt"></i> Code</th>
	                                        <th><i class="icon_profile"></i>Noms et prenoms</th>
	                                        <th><i class="icon_calendar"></i>Sexe</th>
	                                        <th><i class="icon_pin_alt"></i>Zone</th>
	                                        <th><i class="icon_cogs"></i> Action</th>
	                                    </tr>
                               </thead>    <tbody>     ';
	    			
	    			$n = 0;
	    			while($row=$sql->fetch(PDO::FETCH_OBJ)){

	    				$output.='

	    					<tr>
	    						<td>'.++$n.'</td>
	    						<td>'.$row->codefidele.'</td>
	    						<td>'.$row->nom.' '.$row->prenom.'</td>
	    						<td>'.$row->sexe.'</td>	    						
	    						<td>'.$row->nomzone.'</td>
	    						<td>
	    								
                                                <a class="btn btn-primary afficher" href="afficherFidele.php?code='.$row->idpersonne.'" title="Visualiser"';

                                                    if(!has_Droit($idUser, "Afficher fidele"))
                                                    {
                                                        $output.='disabled';
                                                    }else{
                                                        $output.= "";
                                                    }

                                                    $output.='><i class="icon_plus_alt2"></i></a>
                                                <a class="btn btn-success afficher" href="modifierFidele.php?id='.$row->idpersonne.'" title="Modifier"';

                                                    if(!has_Droit($idUser, "Modifier un fidele"))
                                                    {
                                                        $output.='disabled';
                                                    }else{
                                                        $output.= "";
                                                    }

                                                    $output.='><i class="icon_check_alt2"></i></a>
                                                <a class="btn btn-danger" href="supprimerFidele.php?code='.$row->idpersonne.'" title="Supprimer"';

                                                    if(!has_Droit($idUser, "Supprimer fidele"))
                                                    {
                                                        $output.='disabled';
                                                    }else{
                                                        $output.= "";
                                                    }

                                                    $output.='><i class="icon_close_alt2"></i></a>
                                            
                                </td>
	    					</tr>
	    				';
	    			}

	    			$output.=' </tbody></table>';

	    			
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

    	                         $('.btn-danger').on('click', function(e){

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
                         $(".tableau_dynamique").DataTable();
						$('.loader').hide();
    </script>

