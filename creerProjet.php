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
                    
                        Créer un Nouveau Projet
                    
                </div>
                <div class="body">
                    <form class="form-validate form-horizontal" id="form1" method="POST" action="saveProjet.php">   
                      
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                             <h2 class="card-inside-title">Intitulé</h2>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons text-primary">event</i> 
                                    </span>
                                    
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="intitule" type="text" name="intitule" required   placeholder="Intitulé">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <h2 class="card-inside-title">Responsable</h2>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons text-primary"></i> 
                                    </span>
                                
                                    <div class="form-line">
                                        <input  class="form-control" id="responsable" type="text" name="responsable" required placeholder="Responsable">
                                    </div>
                                    
                                 </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <h2 class="card-inside-title">Budget total</h2>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons text-primary"></i> 
                                    </span>
                                
                                    <div class="form-line">
                                        <input  class="form-control" id="budget" type="Number" name="budget" required placeholder="Budget">
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <h2 class="card-inside-title">Type</h2> 
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons text-primary"></i> 
                                    </span>
                                
                                    <div class="form-line">
                                        <input  class="form-control" id="type" type="text" name="type" required placeholder="Type">
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <h2 class="card-inside-title">Duréé</h2>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons text-primary"></i> 
                                    </span>
                                
                                    <div class="form-line">
                                        <input  class="form-control" id="duree" type="text" name="duree" required placeholder="Duréé">
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
            url: 'saveProjet.php',
            type: 'POST',
            contentType: false,
            processData: false,
            data: data,
            success: function(response) {
                $('#main-content').load("creerProjet.php");
               
            }
        });
        });
   
</script>

