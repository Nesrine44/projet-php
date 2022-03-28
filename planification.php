<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Planification")){

            header('Location:index.php');

        }else{

            $selectServices = $db->prepare("SELECT * FROM personne where lisible = true");
            $selectServices->execute();

        }

    }else{
        header('Location:login.php');
    }
?>

 
    <section class="wrapper">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="index.php">Accueil</a></li>
                    <li><i class="icon_document_alt"></i>Conseil des anciens</li>
                    <li><i class="fa fa-files-o"></i>Planification</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        Planification des services des anciens
                    </header>
                                        <div class="panel-body">
                        <div class="form">
                            <form class="form-validate form-horizontal" id="feedback_form" method="POST" enctype="multipart/form-data" action="">

                                <div class="form-group">
                                    <label for="cmois" class="control-label col-lg-2">Mois</label>
                                    <div class="col-lg-8">
                                        <select class="form-control" name="mois">
                                        	<option disabled selected>Selectionner un mois</option>
                                            <option value="Janvier" id="janvier">Janvier</option>
                                            <option value="Février" id="Fevrier">Février</option>
                                            <option value="Mars" id="Mars">Mars</option>
                                            <option value="Avril" id="Avril">Avril</option>
                                            <option value="Mai" id="Mai">Mail</option>
                                            <option value="Juin" id="Juin">Juin</option>
                                            <option value="Juillet" id="Juillet">Juillet</option>
                                            <option value="Aout" id="Aout">Aout</option>
                                            <option value="Septembre" id="Septembre">Septembre</option>
                                            <option value="Octobre" id="Octobre">Octobre</option>
                                            <option value="Novembre" id="Novembre">Novembre</option>
                                            <option value="Décenbre" id="Decembre">Décembre</option>
                                        </select>
                                    </div>
                                    <div>
                                    	<input class="btn btn-primary" id="planifier" type="Submit" name="planifier" value="Visualiser"/>
                                    </div>
                                </div>
                                <?php 
                                	while ($allServices = $selectServices->fetch(PDO::FETCH_OBJ)) {
                                		
                                ?>
                                		
	                                <div class="form-group">
	                                	<table class="table table-striped table-advance table-hover">
		                                	<thead style="text-align:center; font-size:1.2em; font-weight:bold;">
		                                		<?php echo $allServices->nom; ?>
		                                	</thead>
		                                </table>
	                                	
	                                </div>
	                            <?php
		                            }
	                             ?>
                            </form>

                </section>
            </div>
        </div>
    </section>
 
<script type="text/javascript">
    $('.loader').hide();
     $( ".datepicker" ).datepicker({

                  changeMonth: true,
                  changeYear: true,
                  dateFormat: 'yy-mm-dd'
                });
</script>