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

           		if(!isset($_GET['searchFidele'])){

           			echo 'Aucun resultat';

           		}else{

           			$txt = addslashes($_GET['searchFidele']);

    	       		$sql = $db->prepare("SELECT idpersonne, nom, prenom, email, codefidele FROM personne, fidele, zone  WHERE idpersonne=personne_idpersonne AND zone.idzone = personne.zone_idzone AND personne.lisible=1 AND fidele.lisible=1 AND (nom LIKE '%".$txt."%' OR prenom LIKE '%".$txt."%' OR CONCAT(nom, ' ', prenom) LIKE '%".$txt."%' OR codefidele LIKE '%".$txt."%' OR email LIKE '%".$txt."%') ORDER BY nom ASC");

    	       		$sql->execute();

    	    		if($sql){

    	    		//	$output.='<h4 align="center">Search Results</h4>';
    	    			$output.='<table class="table table-striped table-advance table-hover">';
                        $output.='<tbody>';
    	    			$output.='<tr>
                                                <th>#</th>
                                                <th>Code</th>
                                                <th><i class="icon_profile"></i> Nom et prénom</th>
                                                <th>Email</th>
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
                                    <td>'.$row->codefidele.'</td>
    	    						<td><a class ="link" href="afficherFidele.php?code='.$row->idpersonne.'">'.$row->nom.' '.$row->prenom.'</a></td>
    	    						<td>'.$row->email.'</td>	    						
    	    						
    	    								<td style="text-align: right;">
                                                        <div class="checkboxes">
                                                            <label class="label_check" for="checkbox-01">
                                                                <input name="choix[]" id="checkbox-01" value="'.$row->email.'"  type="checkbox"/>
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

                $('.link').on('click', function(e){

                    e.preventDefault();
                    var $link = $(this);
                    target = $link.attr('href');

                        $('#main-content').load(target);
                    
                });         
				$('.loader').hide();
    </script>

