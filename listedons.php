<?php
session_start();

if (isset($_SESSION['login'])){
	$iduser = $_SESSION['login'];
	require_once('includes/connexionbd.php');
	require_once('includes/function_role.php');

	if (!has_droit($iduser, 'Consulter participation')){
		header('location:index.php');
	} else {
		$selectAllFidels= $db->prepare("SELECT fidele.`idfidele` AS id_fidele, 
                                                 fidele.`codeFidele` AS codefidele,
                                                 personne.`nom` AS nom,
                                                 personne.`prenom` AS prenom
                                            FROM
                                                `personne` personne 
                                            INNER JOIN `fidele` fidele ON personne.`idpersonne` = fidele.`personne_idpersonne`
                                            AND fidele.lisible = true
                                            AND personne.lisible = true                                           ORDER BY nom");	
                $selectAllFidels->execute();

        $listeDons = $db->prepare("SELECT dons.`iddons` AS id_dons, dons.`montant` AS montant, dons.`datedons` AS date_dons,  personne.`nom` AS nom, personne.`prenom` AS prenom FROM `dons` dons INNER JOIN `fidele` fidele  ON dons.`idfidele` = fidele.`idfidele` INNER JOIN  `personne` personne ON personne.`idpersonne` = fidele.`personne_idpersonne`");
        $listeDons->execute();
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
				<li class="col-blue"><i class="material-icons">assistant</i>Liste de dons</li>

				<li style="float: right;">
					<a class="afficher col-blue" href="#" data-toggle="modal" data-target="#modaldon" title="Nouveau don"><i class="material-icons">plus_one</i>Nouveau don</a>
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
							<span style="font-size: 1.5em;">Liste des dons pour l'année <?php echo $_SESSION['annee']; ?></span>
						</header>
						<div id="old_table" class="table-responsive">
							<table class="table table-bordered table-striped table-hover tableau_dynamique">
								<thead>
									<tr>
                                        <th><i class="material-icons iconposition">confirmation_number</i>Numéro</th>
                                        <th><i class="material-icons iconposition">people</i>Montant</th>
                                        <th><i class="material-icons iconposition">ev_station</i>Date</th>
                                        <th><i class="material-icons iconposition">ev_station</i>Donateur</th>
                                        <th><i class="material-icons iconposition">settings</i>Action</th>
                                    </tr>
								</thead>
								<tfoot>
                                    <tr>
                                        <th><i class="material-icons iconposition">confirmation_number</i>Numéro</th>
                                        <th><i class="material-icons iconposition">code</i>Montant</th>
                                        <th><i class="material-icons iconposition">people</i>Date</th>
                                        <th><i class="material-icons iconposition">ev_station</i>Donateur</th>
                                        <th><i class="material-icons iconposition">settings</i>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>    
                                    <?php
                                    $n=0;
                                    while($liste=$listeDons->fetch(PDO::FETCH_OBJ)){
                                        ?>
                                        <tr>
                                            <td><?php echo ++$n; ?></td>
                                            <td><?php echo $liste->montant; ?></td>
                                            <td><?php echo $liste->date_dons; ?></td>
                                            <td><?php echo $liste->nom.' '.$liste->prenom; ?></td>
                                            <td width="15%">
                                                <a class="col-green afficher" href="modifierFidele.php?id=<?php echo $liste->id_dons; ?>" title="Modifier" <?php if(!has_Droit($iduser, "Modifier une collecte") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">border_color</i>
                                                </a>
                                                <a class="col-red" href="supprimerFidele.php?code=<?php echo $liste->id_dons; ?>" title="Supprimer" <?php if(!has_Droit($iduser, "Supprimer collecte") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">delete</i>
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

<div class="modal" id="modaldon">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h4>Ajouter un Don</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
	        <div class="modal-body">
	            <form action="savedons.php" method="POST">
	                <div class="form-group">
	                    <label for="montant">Montant</label>
	                    <input type="number" name="montant" class="form-control" placeholder="Entrez le montant du don">
	                </div>
	                <div class="form-group">
	                	<label for="montant">Date</label>
	                	<input type="text" name="datedons" class="form-control datepick">
	                </div>
	                <div class="form-group">
	                	<label for="montant">Donateur</label>
	                	 <select class="form-control show-tick"   id="selectfidele" name="idfidele" required>
                            <option value="">-- Selectionner --</option>
                            <?php 
                                    while ($fidele = $selectAllFidels->fetch(PDO::FETCH_OBJ)) {                       
                                ?>
                                    <option value="<?php echo $fidele->id_fidele; ?>">
                                        <?php echo $fidele->codefidele.' : '.$fidele->nom.' '.$fidele->prenom; ?>
                                    </option>
                                <?php                   
                                    }
                            ?>         
                        </select>
	                </div>
	                <div class="modal-footer">
	                    <button type="submit" class="btn btn-primary">Enregistrer</button>
	                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
	                </div>
	            </form>
	        </div>
    </div>
</div>

<script>
	$('.datepick').datepicker();
</script>