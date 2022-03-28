<?php
session_start();

if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login'];
    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');

    if(!has_Droit($idUser, "Nouvelle depense")){
        header('Location:index.php');
    }else{
        $saintescenes=$db->prepare("SELECT idsaintescene, mois, annee
                            FROM saintescene
                            WHERE lisible=1
                            AND valide=0");
        $saintescenes->execute();

        $contributions = $db->prepare("SELECT * from contribution where lisible = 1");
        $contributions->execute();

        $traveau = $db->prepare("SELECT * from traveaux");
        $traveau->execute();

        $mois = date('m');
        if($mois < 10){
        	 $mois = str_replace(0, "", $mois);
        }
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
                <li class="col-blue"><i class="material-icons"> assistant</i><a class="afficher Accueil col-blue" title="Afficher toutes les Sainte Cène" href="saintecene.php">Sainte Cène</a></li>
                <li class="col-blue"><i class="material-icons">assistant</i> Nouvelle dépense</li>
            </ol>
        </div>
    </div>
    



<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header text-center h4">
                    
                        Enregistrement d'une nouvelle Dépense
                    
                </div>
                <div class="body">
                    <form class="form-validate form-horizontal" id="form1" method="POST" action="saveDepense.php">   
                      
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                             <h2 class="card-inside-title">Projet</h2>
                                <select class="form-control show-tick"  id="traveau" name="traveau" required>
                                          <option value="">-- Selectionner le projet --</option>
                                            <?php 

                                                while ($trav = $traveau->fetch(PDO::FETCH_OBJ)) {                       
                                            ?>
                                                    <option value="<?php echo $trav->intitule; ?>">
                                                        <?php echo $trav->intitule; ?>
                                                    </option>
                                            <?php            
                                                                    
                                                }
                                            ?>

                                  </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <h2 class="card-inside-title">Motif</h2>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons text-primary"></i> 
                                    </span>
                                
                                    <div class="form-line">
                                        <input  class="form-control" id="motif" type="text" name="motif" required placeholder="Le montant">
                                    </div>
                                    
                                 </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <h2 class="card-inside-title">Montant</h2>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons text-primary"></i> 
                                    </span>
                                
                                    <div class="form-line">
                                        <input  class="form-control" id="montant" type="number" name="montant" required placeholder="Le montant">
                                    </div>
                                    
                                 </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <h2 class="card-inside-title">Executant</h2>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons text-primary"></i> 
                                    </span>
                                
                                    <div class="form-line">
                                        <input  class="form-control" id="executant" type="text" name="executant" required placeholder="Executant">
                                    </div>
                                    
                                 </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <h2 class="card-inside-title">Date effectuée</h2>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons text-primary">event</i> 
                                    </span>
                                
                                    <div class="form-line">
                                        <input  class="form-control" id="date" type="date" name="date" required placeholder="Date">
                                    </div>
                                    
                                </div>
                            </div>

                        </div>
                            <div class="form-group">
                                <div class="col-lg-offset-5 col-lg-10">
                                    <a class="btn btn-warning waves-effect annuler" href="listeMalades.php">Annuler</a>
                                    <button type="submit" name="submit"class="btn btn-primary waves-effect">Enregistrer</button>
                                    <span class="envoi_en_cours" style="margin-left: 10px; display: none;">Enregistrement en cours...</span>
                                </div>
                            </div>
                        </div>                             
                    </form>
                </div>
            </div>
        </div>


    <section class="wrapper">
       
    </section>











<script type="text/javascript">
    //window.location.reload();
    $('#chargement').hide();

    $('#form1').on('submit', function(e){
        $('.envoi_en_cours').show();
        e.preventDefault();
        var $param = $(this);
        var formdata = (window.FormData) ? new FormData($param[0]) : null;
        var data = (formdata !== null) ? formdata : $param.serialize();
        console.log(data);
        $.ajax({
            url: 'saveDepense.php',
            type: 'POST',
            contentType: false,
            processData: false,
            data: data,
            success: function(response) {
                $('#main-content').load("nouvelleDepense.php");
               
            }
        });
        });
   
</script>

