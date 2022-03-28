 <?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        $annee = $_SESSION['annee'];
        require_once('includes/connexionbd.php');
       require_once('includes/function_role.php');

       	if(!has_Droit($idUser, "consulter user")){
        	header('Location:index.php');
        }else{
        	
	       $SelectAll = $db->prepare("

	       		SELECT
			     role.`nomRole` AS role,
			     userrole.`has_role` AS has_role,
			     personne.`nom` AS nom,
			     personne.`prenom` AS prenom,
			     utilisateur.`login` AS login,
			     utilisateur.`idutilisateur` AS id
			     
			FROM
			    personne, utilisateur, role, userrole
			WHERE
			    userrole.role_idrole = role.idrole AND
			   userrole.utilisateur_idutilisateur = utilisateur.idutilisateur AND
			   utilisateur.personne_idpersonne = personne.idpersonne AND 
			   utilisateur.lisible = true");
	       $SelectAll->execute();
    	}
    }else{

    	header('Location:login.php');
    }
  ?>

    <section class="wrapper">
    	

		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
					<li class="col-blue"><i class="material-icons">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
					<li class="col-blue"><i class="material-icons">people</i><a href="#" class="col-blue"> Liste utilisateurs</a></li>
				</ol>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="row card">
						<div class="col-lg-12">
							<header class="panel-heading text-center h4">
								Utilisateurs enrégistrés
							</header>							
								 <div class="table-responsive">
                           <table class="table table-bordered table-striped table-hover tableau_dynamique">
		                           <thead>
		                              <tr>
		                              	 <th><i class="material-icons iconposition">confirmation_number</i>Numéro</th>
		                                 <th><i class="material-icons iconposition">people</i> Noms et prénoms</th>
		                                 <th><i class="material-icons iconposition">account_circle</i> Rôle</th>
		                                 <th><i class="material-icons iconposition">people</i> Statut</th>
		                                 <th><i class="material-icons iconposition">people</i> Login</th>
		                                 <th><i class="material-icons iconposition">settings</i> Action</th>
		                              </tr>
		                            </thead>
		                              <tfoot>
		                              <tr>
		                              	 <th><i class="material-icons iconposition">confirmation_number</i>Numéro</th>
		                                 <th><i class="material-icons iconposition">people</i> Noms et prénoms</th>
		                                 <th><i class="material-icons iconposition">account_circle</i> Rôle</th>
		                                 <th><i class="material-icons iconposition">people</i> Statut</th>
		                                 <th><i class="material-icons iconposition">people</i> Login</th>
		                                 <th><i class="material-icons iconposition">settings</i> Action</th>
		                              </tr>
		                              </tfoot>
		                              <tbody>
		                              	<?php 
		                              		$n = 0;
		                              		while ($liste=$SelectAll->fetch(PDO::FETCH_OBJ)) {
		                              	?>
		                              <tr>
		                              	 <td><?php echo ++$n; ?></td>
		                              	 <td><?php echo $liste->nom.' '.$liste->prenom; ?></td>
		                              	 <td><?php echo $liste->role; ?></td>
		                              	 <td><?php if($liste->has_role) echo 'Actif'; else echo 'Inactif'; ?></td>
		                              	 <td><?php echo $liste->login; ?></td>
		                              	 <td width="10%">
		                                        <a class="col-green afficher" href="modifierUser.php?id=<?php echo $liste->id; ?>" title="Modifier" <?php if(!has_Droit($idUser, "modifier user") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">border_color</i> </a>

		                                      <a class="col-red" href="supprimerUser.php?id=<?php echo $liste->id; ?>" title="Supprimer" <?php if(!has_Droit($idUser, "supprimer user") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">delete</i></a>
		                                  	
		                                </td>
		                              </tr>		


		                              	<?php		
		                              		}
		                              	?>
		                              
		                            </tbody>
		                         </table>
		                         </div>
							</div>
							
					</div>
				</section>
			</div>
		</div>
		<script>

		$('#chargement').hide();
			$('.col-red').on('click', function(e){

			 		e.preventDefault();
			 		
			 		var $a = $(this);
			 		var url = $a.attr('href');
			 		if(window.confirm('Voules-vous supprimer cet utilisateur?')){
				 		$.ajax(url, {

				 			success: function(){
				 				$a.parents('tr').remove();		 				
				 			},

				 			error: function(){

				 				alert("Une erreur est survenue lors de la suppresion de l\'utilisateur");
				 			}
				 		});
				 	}
			 	});

			$('.afficher').on('click', function(af){

                            af.preventDefault();
                            $('.loader').show();
                           var $b = $(this);
                            url = $b.attr('href');
                           $('#main-content').load(url, function(){
                                $('.loader').hide();
                           });
                        });

			
			$(".tableau_dynamique").DataTable();
			$('.loader').hide();
				
		</script>

	</section>

									