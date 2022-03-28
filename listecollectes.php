<?php
session_start();

if (isset($_SESSION['login'])){
	$iduser = $_SESSION['login'];
	require_once('includes/connexionbd.php');
	require_once('includes/function_role.php');

	if (!has_droit($iduser, 'Consulter participation')){
		header('location:index.php');
	} else {

        $listeCollectes = $db->prepare("SELECT * FROM collectes");
        $listeCollectes->execute();

	}

} else {
	header('location:login.php');
}

?>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
			<ol class="breadcrumb">
				<li class="col-blue"><i class="material-icons">home</i><a href="index.php" class="col-blue">Accueil</a></li>
				<li class="col-blue"><i class="material-icons">assistant</i>Liste de collecte</li>

				<li style="float: right;">
					<a class="afficher col-blue" href="#" data-toggle="modal" data-target="#modalcollecte" title="Nouveau don"><i class="material-icons">plus_one</i>Nouvelle collecte</a>
				</li>
			</ol>
		</div>
	</div>

	<div class="row card">
		<div class="col-lg-12">
			<section class="panel">
				<div class="row">
					<div class="col-lg-12">
						<header class="panel-heading h4 text-center">
							<span style="font-size: 1.5em;">Liste des collecte pour l'année <?php echo $_SESSION['annee']; ?></span>
						</header>
						<div id="old_table" class="table-responsive">
							<table class="table table-bordered table-striped table-hover tableau_dynamique">
								<thead>
									<tr>
                                        <th><i class="material-icons iconposition">confirmation_number</i>Numéro</th>
                                        <th><i class="material-icons iconposition">code</i>Intitule</th>
                                        <th><i class="material-icons iconposition">people</i>Montant</th>
                                         <th><i class="material-icons iconposition">ev_station</i>durée</th>
                                            <th><i class="material-icons iconposition">settings</i>Action</th>
                                    </tr>
								</thead>
								<tfoot>
                                    <tr>
                                        <th><i class="material-icons iconposition">confirmation_number</i>Numéro</th>
                                        <th><i class="material-icons iconposition">code</i>Intitule</th>
                                        <th><i class="material-icons iconposition">people</i>Montant</th>
                                        <th><i class="material-icons iconposition">ev_station</i>Durée</th>
                                        <th><i class="material-icons iconposition">settings</i>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>    
                                    <?php
                                    $n=0;
                                    while($liste=$listeCollectes->fetch(PDO::FETCH_OBJ)){

                                        ?>
                                        <tr>
                                            <td><?php echo ++$n; ?></td>
                                            <td><?php echo $liste->intitule; ?></td>
                                            <td><?php echo $liste->montant; ?></td>
                                            <td><?php echo $liste->duree; ?></td>
                                            <td width="15%">
                                                <a class="col-green afficher" href="modifierFidele.php?id=<?php echo $liste->idcollecte; ?>" title="Modifier" <?php if(!has_Droit($iduser, "Modifier une collecte") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">border_color</i>
                                                </a>
                                                <a class="col-red" href="supprimerFidele.php?code=<?php echo $liste->idcollecte; ?>" title="Supprimer" <?php if(!has_Droit($iduser, "Supprimer collecte") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">delete</i>
                                                </a>

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
</section>

<div class="modal" id="modalcollecte">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h4>Ajouter une nouvelle collecte</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
	        <div class="modal-body">
	            <form action="savecollecte.php" method="POST">
	                <div class="form-group">
	                    <label for="montant">Intitule</label>
	                    <input type="text" name="intitule" class="form-control" placeholder="Entrez l'intitule de la collecte">
	                </div>
	                <div class="form-group">
	                	<label for="montant">Durée</label>
	                	<input type="number" name="duree" class="form-control">
	                </div>
	                <div class="form-group">
	                	<label for="cible">montant (Cible)</label>
	                	<input type="number" name="montant" class="form-control datepick">
	                </div>
	                <div class="modal-footer">
	                    <button type="submit" class="btn btn-primary">Enregistrer</button>
	                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
	                </div>
	            </form>
	        </div>
    </div>
</div>
