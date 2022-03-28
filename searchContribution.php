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

	       		$sql = $db->prepare("SELECT nom, prenom, codeFidele, idfidele FROM fidele, personne where fidele.personne_idpersonne = personne.idpersonne and fidele.lisible=1 and personne.lisible = 1  AND (nom LIKE '%".$txt."%' OR prenom LIKE '%".$txt."%' OR CONCAT(nom, ' ', prenom) LIKE '%".$txt."%' OR codefidele LIKE '%".$txt."%') ORDER BY nom ASC");

	       		$sql->execute();

	    		if($sql){

	    			$output.='<h4 align="center">Search Result</h4>';
	    			$output.='<table class="table table-striped table-advance table-hover">';
	    			$output.='<tr>
	                                        <th><i class=""></i>#</th>
	                                        <th><i class="icon_pin_alt"></i> Code</th>
	                                        <th><i class="icon_profile"></i>Noms et prenoms</th>                         
	                                        <th style="text-align: right;">
											<!--<div class="cochetou" style="text-align:right;width:82%; float:right;">
                                                <label class="modifiertext">Cocher Tous</label> 
                                                <input class="checkAll" onclick="CocheTout(this)" type="checkbox"></div>-->Choix
                                        </th>
	                                    </tr>';
	    			
	    			$n = 0;
	    			while($row=$sql->fetch(PDO::FETCH_OBJ)){

	    				$output.='

	    					<tr>
	    						<td>'.++$n.'</td>
	    						<td>'.$row->codefidele.'</td>
	    						<td>'.$row->nom.' '.$row->prenom.'</td>						
	    						
	    								<td style="text-align: right;">
                                            <div class="checkboxes">
                                                <label class="label_check" for="checkbox-01">
                                                    <input name="choix[]" class="checkbox-search" value="'.$row->idfidele.'"  type="checkbox"/>
                                                </label>
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
                         $('.checkAll').click(function() {                        
            var test='Cocher Tous';
            var test1='Decocher Tous';
            if(this.checked){ // si 'checkAll' est coché
                $(".checkbox-search").attr('checked', true);
                $('.modifiertext').html(test1);
                                
            }else{ // si on décoche 'checkAll'

                $(".checkbox-search").attr('checked', false);
                $('.modifiertext').html(test);                            
            }
        });
		$('.loader').hide();

    </script>

