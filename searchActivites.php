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
	       		$sql = $db->prepare("SELECT *  FROM activite where lisible=1 AND (nomactivite LIKE '%".$txt."%' OR datedebut LIKE '%".$txt."%' OR datefin LIKE '%".$txt."%') ORDER BY nomactivite ASC");
	       		$sql->execute();

	    		if($sql){
	    			$output.='<h4 align="center">Search Result</h4>';
	    			$output.='<table class="table table-striped table-advance table-hover">';
	    			$output.='<tr>
                                <th><i class=""></i>#</th>
                                <th><i class="icon_pin_alt"></i>Activité</th>
                                <th><i class="icon_profile"></i>Description</th>
                                <th><i class="icon_calendar"></i>Date debut</th>
                                <th><i class="icon_pin_alt"></i>Date fin</th>
                                <th><i class="icon_cogs"></i> Action</th>
                            </tr>';
	    			$n = 0;
	    			while($row=$sql->fetch(PDO::FETCH_OBJ)){

	    				$output.='

	    					<tr>
	    						<td>'.++$n.'</td>
	    						<td>'.$row->nomactivite.'</td>
	    						<td>'.$row->description.'</td>
	    						<td>'.$row->datedebut.'</td>	    						
	    						<td>'.$row->datefin.'</td>
	    						<td>
	    								
                                                <a class="btn btn-primary afficher" href="afficherActivite.php?id='.$row->idactivite.'" title="groupes concernés"';
                                                 if(!has_Droit($idUser, "Afficher activite")){
                                                        $output.= 'disabled';
                                                      }else{
                                                        $output.="";
                                                    } 
                                                        $output.='><i class="icon_plus_alt2"></i></a>
                                                <a class="btn btn-success afficher" href="modifierActivite.php?id='.$row->idactivite.'" title="Modifier"';
                                                 if(!has_Droit($idUser, "Modifier activite")){                                              $output.='disabled';
                                                    }else{
                                                        $output.='';
                                                        }
                                                        $output.='><i class="icon_check_alt2"></i></a>
                                                <a class="btn btn-danger" href="supprimerActivite.php?id='.$row->idactivite.'" title="Supprimer"';
                                                    if(!has_Droit($idUser, "Supprimer activite"))
                                                    {
                                                        $output.= 'disabled';
                                                    }else{
                                                        $output.= "";
                                                    } 
                                                        $output.='><i class="icon_close_alt2"></i></a>
                                            
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

    	                         $('.btn-danger').on('click', function(e){

                            e.preventDefault();

                            var $a = $(this);
                            var url = $a.attr('href');
                            if(window.confirm('Voulez-vous supprimer cette activité?')){
                                $.ajax(url, {

                                    success: function(){
                                        $a.parents('tr').remove();
                                    },

                                    error: function(){

                                        alert("Une erreur est survenue lors de la suppresion de l\'activité");
                                    }
                                });
                            }
                        });
						$('.loader').hide();
 </script>

