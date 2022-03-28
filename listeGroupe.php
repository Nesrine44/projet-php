<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        $annee  = $_SESSION['annee'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Lister groupe")){

            header('Location:index.php');

        }else{

            $insert1 = "SELECT * FROM groupe where lisible=1 ORDER BY nomgroupe";
            $res=$db->query($insert1);
        }
    		
	}else{
        
        header('Location:login.php'); 
    }

?>


    <section class="wrapper">       

			<div class="row">
						<div class="col-lg-12">
							<ol class="breadcrumb">
								<li><i class="material-icons">home</i><a href="index.php" class="col-blue">Accueil</a></li>
								<li> <i class="material-icons text-primary" >group</i> <a href="#"class="col-blue">Groupes</a> </li>
                                 <li> <i class="material-icons text-primary">group_add</i> <a href="#"class="col-blue">Liste Groupe</a></li>
                                <li style="float: right;">
                        
                                    <a class="" href="report/imprimer.php?file=liste_groupes" title="Imprimer la liste des groupes paroissiaux" target="_blank"><i class="material-icons">print</i> Imprimer</a>
                               </li>
							</ol>
						</div>
                    </div>
                    


                
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="card">

                                <div class="header text-center h4">
                                        
                                    Liste de groupe enregistrés
                                      
                                        
                                </div>
                                <div class="body">

                                    <div id="old_table" class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable tableau_dynamique">
                                            <thead>
                                            <tr>
                                                <th><i class="material-icons iconposition">confirmation_number</i>Numéro</th>
                                                <th><i class="material-icons iconposition">people</i>Nom</th>
                                                <th><i class="material-icons iconposition">merge_type</i>Type</th>
                                                <th><i class="material-icons iconposition">event</i>Date de création</th>
                                                <th><i class="material-icons iconposition">settings</i>Action</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>                                               
                                                    <th><i class="material-icons iconposition">confirmation_number</i>Numéro</th>
                                                <th><i class="material-icons iconposition">people</i>Nom</th>
                                                <th><i class="material-icons iconposition">merge_type</i>Type</th>
                                                <th><i class="material-icons iconposition">event</i>Date de création</th>
                                                <th><i class="material-icons iconposition">settings</i>Action</th>
                                                </tr>
                                            </tfoot>  
                                            <tbody>    
                                                <?php
                                                $n=0;
                                                while($id=$res->fetch(PDO::FETCH_ASSOC)){  ?>
                                                        <tr>
                                                            <td><?php echo ++$n;?></td>
                                                            <td><?php echo $id['nomgroupe'];?></td>
                                                            <td><?php echo $id['typegroupe'];?></td>
                                                            <td><?php echo $id['datecreation'];?></td>
                                                            <td width="15%">
                                                                
                                                                    <a class="col-blue afficher" href="listeMembreGroupe.php?id=<?php echo($id['idgroupe']); ?>" title="Voir les membres" <?php if(!has_Droit($idUser, "Afficher groupe"))
                                                                    {echo 'disabled';}else{echo "";} ?>><i class="material-icons">loupe</i></a>
                                                                    <a class="col-green afficher" href="modifiergroupe.php?param=<?php echo($id['idgroupe']); ?>" title="Modifier" <?php if(!has_Droit($idUser, "Modifier un groupe") || (date('Y') != $annee))
                                                                    {echo 'hidden';}else{echo "";} ?>><i class="material-icons">border_color</i></a>
                                                                    <a class="col-red" href="supprimergroupe.php?id=<?php echo($id['idgroupe']); ?>" title="Supprimer" <?php if(!has_Droit($idUser, "Supprimer un groupe") || (date('Y') != $annee)){echo 'hidden';}else
                                                                    {echo "";} ?>><i class="material-icons">delete</i></a>
                                                               
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                 ?>
                                                </tbody>
                                                 <?php
                                                    $db=NULL;
                                                    ?>
                                        </table>
                                    </div>
                                    <br>
                                    <div style="text-align: center">
                                            <a class="btn btn-success" href="report/imprimer.php?file=liste_groupes" title="Imprimer la liste des groupes paroissiaux" target="_blank"><i class="material-icons">print</i>Imprimer</a>
                                   </div>
							</section>
						</div>
					</div>

  <script>
        $('.col-red').on('click', function(e){

                            e.preventDefault();

                            var $a = $(this);
                            var url = $a.attr('href');
                            if(window.confirm('Voulez-vous supprimer cette zone?')){
                                $.ajax(url, {

                                    success: function(){
                                        $a.parents('tr').remove();
                                    },

                                    error: function(){

                                        alert("Une erreur est survenue lors de la suppresion du malade");
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
