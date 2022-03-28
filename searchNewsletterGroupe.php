<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
		
	   require_once('includes/connexionbd.php');
       require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Envoyer newsletter")){

            header('Location:index.php');

        }else{

           		$output = "";
           		$sql = null;

           		if(!isset($_GET['searchGroupe'])){

           			echo 'Aucun resultat';

           		}else{

           			$txt = addslashes($_GET['search']);

    	       		$sql = $db->prepare("SELECT nomgroupe, typegroupe, idgroupe, count(idfidele) AS nbrefidele from groupe, fidele, fidelegroupe WHERE groupe.idgroupe = fidelegroupe.groupe_idgroupe AND fidele.idfidele = fidelegroupe.fidele_idfidele AND fidele.lisible = 1 AND groupe.lisible = 1 AND fidelegroupe.lisible = 1 AND (nomgroupe LIKE '%".$txt."%' OR typegroupe LIKE '%".$txt."%') 
                        GROUP BY idgroupe
                        ORDER BY nomgroupe ASC");

    	       		$sql->execute();

    	    		if($sql){

    	    		//	$output.='<h4 align="center">Search Results</h4>';
    	    			$output.='<table class="table table-striped table-advance table-hover">';
                        $output.='<tbody>';
    	    			$output.='<tr>
                                                <th>#</th>
                                                <th>Nom du groupe</th>
                                                <th>Type</th>
                                                <th>Nombre de fidèles</th>
                                                <th style="text-align: right;"><div id="cochetou" style="text-align:right;width:82%; float:right;">
                                                <label id="modifiertext">Cocher Tous</label> 
                                                <input id="checkAll" onclick="CocheTout(this)" type="checkbox">
                                            </div></th>
                                    </tr>';
    	    			
    	    			$n = 0;
    	    			while($row=$sql->fetch(PDO::FETCH_OBJ)){

    	    				$output.='

    	    					<tr>
    	    						<td>'.++$n.'</td>
                                    <td>'.$row->nomgroupe.'</td>
    	    						<td>'.$row->typegroupe.'</a></td>
    	    						<td>'.$row->nbrefidele.'</td>	    						
    	    						
    	    								<td style="text-align: right;">
                                                        <div class="checkboxes">
                                                            <label class="label_check" for="checkbox-01">
                                                                <input name="choix[]" id="checkbox-01" value="'.$row->idgroupe.'"  type="checkbox"/>
                                                            </label>
                                                        </div>
                                                    </td>
                                    
    	    					</tr>
    	    				';
    	    			}
                        $output.='</tbody>';
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

    	             	                         
    	                $('#checkAll').click(function() {
                        // on cherche les checkbox à l'intérieur de l'id  'magazine'
                        //var magazines = $("#magazine").find(':checkbox');
                        var test='Cocher Tous';
                        var test1='Decocher Tous';
                        if(this.checked){ // si 'checkAll' est coché
                            $(":checkbox").attr('checked', true);
                            $('#modifiertext').html(test1);
                            //magazines.prop('checked', true);
                        }else{ // si on décoche 'checkAll'
                            $(":checkbox").attr('checked', false);
                            $('#modifiertext').html(test);
                            //magazines.prop('checked', false);
                        }
                    });
					
					$('.loader').hide();

    </script>

