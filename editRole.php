<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
       require_once('includes/function_role.php');
       
        if(!has_Droit($idUser, "Editer role")){
            header('Location:index.php');
        }else{

           //selection des roles 
           $selectRole = $db->prepare("SELECT * FROM role where lisible = true and nomrole != 'Administrateur' ORDER by idrole ");
           $selectRole->execute();

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
                    <li class="col-blue"><i class="material-icons">people</i><a href="#" class="col-blue"> Utilisateur</a></li>
                    <li class="col-blue"><i class="material-icons">border_color</i><a href="#" class="col-blue"> Edition des rôles</a></li>
                </ol>
            </div>
        </div>



        <div class="row card">
            <div class="col-lg-12">
                <section class="panel ">
                    <div class=class="row">
                        <div class="col-lg-12">
                            <header class="panel-heading text-center h4">
                                Edition des droits par rôle
                            </header>
                            <div class="panel-body">
                              <div class="form">
                                <form class="form-validate form-horizontal" id="form_role" method="get" action="">
                            <br>
                            <div class="row clearfix inputTopSpace">
                                <div class="col-sm-12 ">
                                  <label for="Nom">Choisir un rôle: <span class="required">*</span></label>
                                    <select class="form-control show-tick" name="role" id="cPersonne" required>
                                            <option selected="" disabled="">Choisir un rôle:</option>
                                                <?php
                                                    while($liste = $selectRole->fetch(PDO::FETCH_OBJ)){
                                                ?>
                                                    <option value="<?php echo $liste->idrole; ?>" class="choixRole"><?php echo $liste->nomrole; ?></option>
                                                <?php       
                                                    }
                                                ?>
                                            </select>
                                    </select>
                                </div>
                            </div>
                                    <div id="monTableau">
                                    	

                                    </div>
                                </form>
                              </div>
                            </div>
                        </div>   
                   </div> 
                </section>
            </div>
        </div>

        <script type="text/javascript">
        
            $(document).ready(function() {
                $('#chargement').hide();

            	$('.choixRole').on('click', function(e){
            		e.preventDefault();
            		var $a = $(this);
            		url = "selectRole.php?id="+$a.val();
            		
                    $('#monTableau').load(url)

            	});
        });


	$('.loader').hide();
        </script>
    </section>

