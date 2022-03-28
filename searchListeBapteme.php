<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
		
	   require_once('includes/connexionbd.php');
       require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Lister baptises")){

            header('Location:index.php');

        }else{

       		$output = "";
       		$sql = null;

       		if(!isset($_GET['search'])){

       			echo 'Aucun resultat';

       		}else{

       			$txt = addslashes($_GET['search']);

	       		$sql = $db->prepare("SELECT idpersonne, nom, prenom, statut, datenaiss, idfidele, codefidele, idbapteme, datebaptise, lieu_baptise from personne as pers, fidele as fil, bapteme where idpersonne=personne_idpersonne and pers.lisible=1 AND fil.lisible=1 and fil.idfidele = bapteme.fidele_idfidele and bapteme.lisible = 1 AND (nom LIKE '%".$txt."%' OR prenom LIKE '%".$txt."%' OR CONCAT(nom, ' ', prenom) LIKE '%".$txt."%'  OR codefidele LIKE '%".$txt."%') ORDER BY nom ASC LIMIT 0, 30");

	       		$sql->execute();

	    		if($sql){
                    $output .='<div class="table_responsive">';
	    			$output.='<span style="text-align:center;">Search Result</span>';
	    			$output.='<table class="table table-responsive table-bordered table-advance table-hover">';
	    			$output.='<tr>
	                                        <th><i class=""></i>#</th>
	                                        <th><i class="icon_pin_alt"></i> Code</th>
	                                        <th><i class="icon_profile"></i>Noms et prenoms</th>                                        
	                                        <th><i class="icon_mail_alt"></i> Statut</th>
                                             <th>Date baptême</th>
                                            <th>Lieu baptême</th>
                                            <th><i class="icon_cogs"></i>Action</th>
	                                    </tr><tbody>';
	    			
	    			$n = 0;
	    			while($row=$sql->fetch(PDO::FETCH_OBJ)){

	    				$output.='

	    					<tr>
	    						<td>'.++$n.'</td>
	    						<td>'.$row->codefidele.'</td>
	    						<td>'.$row->nom.' '.$row->prenom.'</td> 
	    						<td>'.$row->statut.'</td>
                                <td>'.$row->datebaptise.'</td>
                                <td>'.$row->lieu_baptise.'</td>
                                <td>

                                        
                                                <a class="col-blue afficher" href="afficherFidele.php?code='.$row->idpersonne.'" title="Visualiser"';

                                                    if(!has_Droit($idUser, "Afficher fidele"))
                                                    {
                                                        $output.='disabled';
                                                    }else{
                                                        $output.= "";
                                                    }

                                                    $output.='><i class="material-icons">loupe</i></a>
                                                <a class="col-green afficher" href="modifierBapteme.php?id='.$row->idbapteme.'" title="Modifier"';

                                                    if(!has_Droit($idUser, "Modifier bapteme"))
                                                    {
                                                        $output.='disabled';
                                                    }else{
                                                        $output.= "";
                                                    }

                                                    $output.='><i class="material-icons">border_color</i></a>


                                                <a class="col-red" href="supprimerBapteme.php?code='.$row->idbapteme.'" title="Supprimer"';

                                                    if(!has_Droit($idUser, "Supprimer bapteme"))
                                                    {
                                                        $output.='disabled';
                                                    }else{
                                                        $output.= "";
                                                    }

                                                    $output.='><i class="material-icons">delete</i></a>
                                           

                                </td>
	    					</tr>
	    				';
	    			}

	    			$output.='</tbody></table></div>';

	    			
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
                            $('.loader').show();
                           var $b = $(this);
                            url = $b.attr('href');
                           $('#main-content').load(url, function(){
                                $('.loader').hide();
                           });
                        });
           $('.col-red').on('click', function(e){

                            e.preventDefault();

                            var $a = $(this);
                            var url = $a.attr('href');
                            if(window.confirm('Voulez-vous supprimer ce baptisé?')){
                                $.ajax(url, {

                                    success: function(){
                                        $('.loader').show();
                                        $('#main-content').load("listeBaptises.php", function(){
                                            $('.loader').hide();
                                        })
                                    },

                                    error: function(){

                                        alert("Une erreur est survenue lors de la suppresion du baptisé");
                                    }
                                });
                            }
                        });
                $('.loader').hide();

    </script>

