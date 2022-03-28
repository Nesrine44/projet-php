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

	       		$sql = $db->prepare("SELECT idpersonne, idfidele, statut, codefidele, nom, prenom, idconfirmation, date_confirmation, lieu_confirmation FROM fidele, personne, confirmation where personne.lisible=1 and fidele.lisible = 1  AND personne.idpersonne=fidele.personne_idpersonne AND fidele.idfidele AND fidele.idfidele = confirmation.fidele_idfidele AND confirmation.lisible = 1 AND (nom LIKE '%".$txt."%' OR prenom LIKE '%".$txt."%' OR CONCAT(nom, ' ', prenom) LIKE '%".$txt."%'  OR codefidele LIKE '%".$txt."%') ORDER BY nom ASC LIMIT 0, 30");

	       		$sql->execute();

	    		if($sql){

	    			$output.='<div class="table_responsive"><span style="text-align:center;">Search Result</span>';
	    			$output.='<table class="table table-bordered table-advance table-hover">';
	    			$output.='<tr>
	                                        <th><i class=""></i>#</th>
	                                        <th><i class="icon_pin_alt"></i> Code</th>
	                                        <th><i class="icon_profile"></i>Noms et prenoms</th>                                        
	                                        <th><i class="icon_mail_alt"></i> Statut</th>
                                             <th>Date confirmation</th>
                                            <th>Lieu confirmation</th>
                                            <th><i class="icon_cogs"></i>Action</th>
	                                    </tr>';
	    			
	    			$n = 0;
	    			while($row=$sql->fetch(PDO::FETCH_OBJ)){

	    				$output.='

	    					<tr>
	    						<td>'.++$n.'</td>
	    						<td>'.$row->codefidele.'</td>
	    						<td>'.$row->nom.' '.$row->prenom.'</td> 
	    						<td>'.$row->statut.'</td>
                                <td>'.$row->date_confirmation.'</td>
                                <td>'.$row->lieu_confirmation.'</td>
                                <td>

                                        
                                                <a class="btn btn-primary afficher" href="afficherFidele.php?code='.$row->idpersonne.'" title="Visualiser"';

                                                    if(!has_Droit($idUser, "Afficher fidele"))
                                                    {
                                                        $output.='disabled';
                                                    }else{
                                                        $output.= "";
                                                    }

                                                    $output.='><i class="icon_plus_alt2"></i></a>
                                                <a class="btn btn-success afficher" href="mofidierCommunian.php?id='.$row->idconfirmation.'" title="Modifier"';

                                                    if(!has_Droit($idUser, "Modifier bapteme"))
                                                    {
                                                        $output.='disabled';
                                                    }else{
                                                        $output.= "";
                                                    }

                                                    $output.='><i class="icon_check_alt2"></i></a>
                                                <a class="btn btn-danger" href="supprimerCommunian.php?code='.$row->idconfirmation.'" title="Supprimer"';

                                                    if(!has_Droit($idUser, "Supprimer bapteme"))
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

	    			$output.='</table></div>';

	    			
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


$('.btn-danger').on('click', function(e){

                            e.preventDefault();

                            var $a = $(this);
                            var url = $a.attr('href');
                            if(window.confirm('Voulez-vous supprimer ce communian?')){
                                $.ajax(url, {

                                    success: function(){
                                        $('.loader').show();
                                        $('#main-content').load("listeCommunians.php", function(){
                                            $('.loader').hide();
                                        })
                                    },

                                    error: function(){

                                        alert("Une erreur est survenue lors de la suppresion du communian");
                                    }
                                });
                            }
                        });
				$('.loader').hide();
    </script>

