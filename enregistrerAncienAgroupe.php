<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Inscrire a un groupe")){

            header('Location:index.php');

        }else{            

            $select = $db->prepare("SELECT nom, prenom, codefidele, idfidele FROM fidele, personne where fidele.personne_idpersonne = personne.idpersonne AND personne.lisible=1 AND fidele.lisible = 1 AND statut = 'ancien' ORDER BY nom ASC ");
            $select->execute();

            $selectGroupe = $db->prepare("SELECT * FROM groupe where lisible = 1 AND typegroupe ='anciens' ORDER BY nomgroupe ASC");
            $selectGroupe->execute();

            function is_in_group($idfidele, $idgroupe){
                global $db;


                $selectE = $db->prepare("SELECT idfidelegroupe from fidelegroupe where fidelegroupe.lisible=1 AND fidele_idfidele = $idfidele AND groupe_idgroupe = $idgroupe");
                $selectE->execute();

                if($selectE->fetch(PDO::FETCH_OBJ)){
                    return true;
                }else{

                    return false;
                }
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
                     <li> <i class="material-icons text-primary">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                    <li> <i class="material-icons text-primary">school</i><a href="#" class="col-blue"> Conseils des Anciens</a></li>
                    <li> <i class="material-icons text-primary">save</i><a href="#" class="col-blue">Enregistrement</a></li>
                </ol>
            </div>
        </div>
        
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="card">

                    <div class="header text-center h4">
                            
                         Enregistrmement d'un ancien à un groupe d'anciens
                            
                            
                    </div>

                     <div class="body">

                        
                            <form class="form-validate form-horizontal" id="form-ancienGoupe" method="POST" enctype="multipart/form-data" action="">
                                
                                <div class="table-responsive">
                                 <table class="table table-bordered table-striped table-hover js-basic-example dataTable tableau_dynamique">
                                    <thead>
                                        <tr>
                                            <th><i class="material-icons iconposition">confirmation_number</i>Numéro</th>
                                            <th><i class="material-icons iconposition">code</i>Code</th>
                                            <th><i class="material-icons iconposition">people</i>Noms et prenoms</th>
                                            <?php 
                                                while($listeG=$selectGroupe->fetch(PDO::FETCH_OBJ)){
                                            ?>
                                                <th style="text-align:center;"><?php echo $listeG->nomgroupe; ?></th>
                                            <?php
                                                } 
                                            ?>                                        
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th><i class="material-icons iconposition">confirmation_number</i>Numéro</th>
                                            <th><i class="material-icons iconposition">code</i>Code</th>
                                            <th><i class="material-icons iconposition">people</i>Noms et prenoms</th>
                                            <?php 
                                                while($listeG=$selectGroupe->fetch(PDO::FETCH_OBJ)){
                                            ?>
                                                <th style="text-align:center;"><?php echo $listeG->nomgroupe; ?></th>
                                            <?php
                                                } 
                                            ?>      
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                    $n = 0;
                                    while($liste=$select->fetch(PDO::FETCH_OBJ)){
                                        
                                        ?>
                                        <tr>
                                            <td><?php echo ++$n;?></td>
                                            <td><?php echo $liste->codefidele ;?></td>
                                            <td><?php echo $liste->nom.' '.$liste->prenom;?></td>
                                        <?php  
                                                $selectGroupe->execute();
                                                while ($listeG=$selectGroupe->fetch(PDO::FETCH_OBJ)) {
                                        ?>
                                            <td>
                                                <div class="checkboxes" style="text-align:center;">
                                                    <label class="label_check" for="checkbox-01">
                                                        <input name="choix[]" class="magazine" id="checkbox-01" value="<?php echo 'fidele='.$liste->idfidele.'&amp;groupe='.$listeG->idgroupe; ?>" type="checkbox" <?php if(is_in_group($liste->idfidele, $listeG->idgroupe)){ echo 'checked';} ?> />
                                                    </label>
                                                </div>
                                            </td>
                                        <?php
                                                }

                                        ?>                                            
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>    
                                <?php
                                $db=NULL;
                                ?>                                
                            </form>

                        </div>
                    </div>
                           
                </section>
            </div>
        </div>

        <script type="text/javascript">
                           
                $(':checkbox').click(function() {

                 var $x = $(this);                      
                 var valeur;             
               // alert('ok');
                if(this.checked){ // si 'checkAll' est coché
                  valeur = true;
                }else{ // si on décoche 'checkAll'
                  valeur = false;
                }
                var url = "saveMultiple.php?"+$x.val()+"&valeur="+valeur;
                $('#modifiertext').html("Traitement...");

                $.ajax(url, {
                  success: function(){
                            
                   },

                  error: function(){

                    alert('Une erreur est survenue lors de la suppresion du groupe');
                  }
                });
                $('#modifiertext').html("");

              });

           
         $(".tableau_dynamique").DataTable();
     $('.loader').hide();

        </script>

    </section>
