<?php
    session_start();

    if(isset($_SESSION['login'])){
        $annee = $_SESSION['annee'];
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Afficher activite")){

            header('Location:index.php');

        }else{

            $activite = null;
            if(!isset($_GET['id'])){

            	header('Location:index.php');

            }else{

            	$idactivite = $_GET['id'];
            	$selectA = $db->prepare("SELECT * from activite where lisible = 1 AND idactivite = $idactivite");
            	$selectA->execute();

            	while ($result = $selectA->fetch(PDO::FETCH_OBJ)) {
            		$activite = $result;
            	}

            	$groupes = $db->prepare("SELECT idgroupe, nomgroupe, typegroupe, datecreation 
            							 FROM groupe 
            							 INNER JOIN groupeactivite ON idgroupe = groupe_idgroupe
            							 AND groupe.lisible = 1
            							 AND groupeactivite.lisible = 1
            							 AND groupeactivite.activite_idactivite = $idactivite
            		       ");
            	$groupes->execute();
            }
        }    

    }else{
       // unset($db);
        header('Location:login.php');
    }
?>


    <section class="wrapper">


    <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li> <i class="material-icons text-primary">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                    <li> <i class="material-icons text-primary">grain</i><a href="#" class="col-blue"> Activité</a></li>
                    <li> <i class="material-icons text-primary">fiber_new</i><a href="#" class="col-blue">Nouvelle activité</a></li>
                    <li> <i class="material-icons text-primary">list</i><a href="#" class="col-blue">Afficher</a></li>

                    <li style="float: right;">                      
                       <a class="col-blue h4" href="report/imprimer_param.php?file=liste_groupes_activites&param=<?php
                        echo $idactivite; ?>" title="Imprimer la liste des groupes concercnés" target="_blank">
                        <i class="material-icons">print</i> Imprimer</a>
                    </li>

                </ol>
    </div>

   
   
    <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">

                        <div class="header text-center h4">
                              
                        <?php echo $activite->nomactivite; ?>
                             
                                
                        </div>

                        <div class="row">                            
                            <div class="bio-row col-lg-3">
                                <p class="h5"><span>Description</span>: <?php echo $activite->description; ?></p>
                            </div>
                            
                            <div class="bio-row col-lg-3">
                                <p class="h5"><span>Date de debut </span>: <?php echo $activite->datedebut; ?></p>
                            </div>                                              
                            <div class="bio-row col-lg-3">
                                    <p class="h5"><span>Date de fin</span>: <?php echo $activite->datefin; ?></p>
                            </div>
                </div>

         	<div class="panel">
        		<header class="header h4">
        			Groupes concernés
        		</header>
        		<table class="table table-bordered table-striped table-hover js-basic-example">
        			<tbody>
        				<tr>
                            <th><i></i> Numéro</th>
                            <th><i class="icon_profile"></i>nom du groupe</th>
                            <th><i class="icon_pin_alt"></i> Type de groupe</th>
                             <th><i class="icon_calendar"></i> Date de création</th>
                        </tr>

                        <?php  
                        	$n=0;
                        	while ($groupe = $groupes->fetch(PDO::FETCH_OBJ)) {
                        	?>
                        		<tr>
                        		<td><?php echo ++$n; ?></td>
                        		<td><a class="liste" href="listeMembreGroupe.php?id=<?php echo $groupe->idgroupe; ?>" title="Afficher les membre du groupe"><?php echo $groupe->nomgroupe; ?></a></td>
                        		<td><?php echo $groupe->typegroupe; ?></td>
                        		<td><?php echo $groupe->datecreation; ?></td>
                        		</tr>

                        	<?php
                        	}
                        	//unset($db);
                        ?>
        			</tbody>
        		</table>
        	</div>

            <div class="form-group">
                <div class="col-lg-offset-5 col-lg-10">
                    <a class=" bg-green liste" <?php if(!has_Droit($idUser, "Modifier activite") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?> href="modifierActiviteGroupe.php?id=<?php echo $idactivite; ?>" title="Modification des groupes concernés par cette activité" ><i class="material-icons">border_color</i> Modifier</a>
                    <a class="btn bg-blue" href="report/imprimer_param.php?file=liste_groupes_activites&param=<?php echo $idactivite; ?>" title="Imprimer la liste des groupes concercnés" target="_blank"><i class="material-icons">print</i> Imprimer</a>
                </div>
            </div>
        </div>
    </section>

<script type="text/javascript">
    $('#chargement').hide();
    $('.liste').on('click', function(af){
        af.preventDefault();
        var $b = $(this);
        url = $b.attr('href');
        $('#main-content').load(url);
    });
	$('.loader').hide();
</script>