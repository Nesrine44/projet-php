<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        $annee = $_SESSION['annee'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Afficher groupe")){

            header('Location:index.php');

        }else{

        $idLogin = $_SESSION['login'];

            if(!isset($_GET['id'])){

                $idgroupe = $_SESSION['id'];

            }else{

                $idgroupe=$_GET['id'];
                $_SESSION['id'] = $idgroupe;
            }

            $groupe = null;

            $selectGroupe=$db->prepare("SELECT nomGroupe FROM groupe WHERE idgroupe=$idgroupe;");
            $selectGroupe->execute();

            while($x=$selectGroupe->fetch(PDO::FETCH_OBJ)){
                $groupe=$x;
            }

       
            $selectMembre = $db->prepare("SELECT
                                             personne.`idpersonne` AS idpersonne,
                                             personne.`nom` AS nom,
                                             personne.`prenom` AS prenom,
                                             fidele.`codeFidele` AS codeFidele,
                                             fidele.`idfidele` AS idfidele,
                                             groupe.`nomGroupe` AS nomGroupe,
                                             fidelegroupe.`date_inscription` AS date_inscription,
                                             groupe.`idgroupe` AS idgroupe,
                                             groupe.`typeGroupe` AS typeGroupe
                                        FROM
                                             `fidele` fidele INNER JOIN `fidelegroupe` fidelegroupe ON fidele.`idfidele` = fidelegroupe.`fidele_idfidele`
                                             INNER JOIN `groupe` groupe ON fidelegroupe.`groupe_idgroupe` = groupe.`idgroupe`
                                             INNER JOIN `personne` personne ON fidele.`personne_idpersonne` = personne.`idpersonne`
                                        AND groupe.lisible = true
                                        AND fidele.lisible = true
                                        AND personne.lisible = true
                                        AND fidelegroupe.lisible = true
                                        AND fidelegroupe.groupe_idgroupe = $idgroupe
                                        ORDER BY nom ASC");
            $selectMembre->execute();

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
                                 <li> <i class="material-icons text-primary">group_add</i> <a href="#"class="col-blue">Liste Membre</a></li>
                                <li style="float: right;">
                        
                                <a class="" href="report/imprimer_param.php?file=liste_groupes_membres&param=<?php echo $idgroupe;  ?>" title="Imprimer la liste des membres du groupe" target="_blank"><i class="material-icons">print</i> Imprimer</a>
                               </li>
							</ol>
						</div>
                    </div>
                    


                
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="card">

                                <div class="header text-center h4">
                                        
                                Liste des membres du groupes "<?php echo $groupe->nomgroupe; ?>"
                                      
                                        
                                </div>
                               
                            
                            <div id="old_table" class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable tableau_dynamique">
                                    <thead>
                                        <tr>
                                            <th><i class="material-icons iconposition">confirmation_number</i>Numéro</th>
                                            <th><i class="material-icons iconposition">code</i>Code fidèle</th>
                                            <th><i class="material-icons iconposition">people</i>Noms et prenom</th>
                                            <th><i class="material-icons iconposition">event</i>Date d'inscription</th>
                                            <th><i class="material-icons iconposition">settings</i> Action</th>
                                        </tr>
                                    </thead>
                                        <tfoot>
                                            <tr>
                                                    <th><i class="material-icons iconposition">confirmation_number</i>Numéro</th>
                                                    <th><i class="material-icons iconposition">code</i>Code fidèle</th>
                                                    <th><i class="material-icons iconposition">people</i>Noms et prenom</th>
                                                    <th><i class="material-icons iconposition">event</i>Date d'inscription</th>
                                                    <th><i class="material-icons iconposition">settings</i> Action</th>
                                            </tr>
                                        
                                        </tfoot>
                                    <tbody>
                                    <?php
                                    $n=0;
                                    while($liste=$selectMembre->fetch(PDO::FETCH_OBJ)){                                       
                                        ?>

                                        <tr>
                                            <td><?php echo ++$n; ; ?></td>
                                            <td><?php echo $liste->codefidele; ?></td>
                                            <td><?php echo $liste->nom.' '.$liste->prenom; ?></td>
                                            <td><?php echo $liste->date_inscription; ?></td>
                                            <td width="10%">
                                               
                                                    <a class="col-blue afficher" href="afficherFidele.php?code=<?php echo $liste->idpersonne; ?>" title="Visualiser" <?php if(!has_Droit($idUser, "Afficher fidele")){echo 'disabled';}else{echo "";} ?>><i class="material-icons">loupe</i></a>
                                                    <a class="col-red" href="supprimerMembreGroupe.php?fidele=<?php echo $liste->idfidele;?>&amp;groupe=<?php echo $liste->idgroupe; ?>" title="Supprimer" <?php if(!has_Droit($idUser, "Inscrire a un groupe") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><i class="material-icons">delete</i></a>
                                              
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                </div>
                          
                            <div align="center">
                                <a class="btn btn-success" href="report/imprimer_param.php?file=liste_groupes_membres&param=<?php echo $idgroupe;  ?>" title="Imprimer la liste des membres du groupe" target="_blank"><i class="fa fa-print"></i> Imprimer</a>
                            </div>
                    </div>                    
                </section>
            </div>
        </div>
    </section>


<script>

    $('.col-red').on('click', function(e){

         e.preventDefault();

         var $a = $(this);
         var url = $a.attr('href');
         if(window.confirm('Voulez-vous supprimer ce fidèle de ce groupe?')){
            $.ajax(url, {

                success: function(){
                    $a.parents('tr').remove();
                 },

                error: function(){

                   alert("Une erreur est survenue lors de la suppresion du fidèle du groupe");
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
