<?php
 
session_start();

    if(isset($_SESSION['login'])){

        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Editer role")){

            header('Location:index.php');

        }else{

          $selectModules = $db->prepare("SELECT * FROM modules");
          $selectModules->execute();

          $idRole=$_GET['id'];
          $_SESSION['role'] = $idRole;
          $p=0;

          while($module = $selectModules->fetch(PDO::FETCH_OBJ)){
              $idmodules = $module->idmodule;
              
             $SelectAll = $db->prepare("
                SELECT
                   droit.`iddroit` AS iddroit,
                   droit.`nomDroit` AS nomDroit,
                   droit.`description` AS description,
                   roledroit.`hasDroit` AS hasDroit,
                   roledroit.`idroleDroit` AS idroleDroit,
                   modules.`nomModule` AS modules_nomModule
                FROM
                   `droit` droit INNER JOIN `roledroit` roledroit ON droit.`iddroit` = roledroit.`droit_iddroit`
                   INNER JOIN `modules` modules ON droit.`modules_idmodule` = modules.`idmodule`
                WHERE modules_idmodule = $idmodules
                AND  role_idrole = $idRole"
              );
              $SelectAll->execute();

       ?>          

                <div class="row">
                    <div class="col-lg-12"> 
                     <section class="panel">
                        <header class="panel-heading">
                            <span style="font-size:1.4em; color:black;"><?php  echo $module->nommodule; ?></span>
                        </header>
                          <div class="panel-body">
                              <div class="row m-bot15">
                                <table class="table table-striped table-advance table-hover">
                                   <tbody>
                                      <tr>
                                         <th>#</th>
                                         <th><i class="icon_profile"></i> Droit</th>
                                         <th><i class="icon_pin_alt"></i> Description</th>
                                         <th><i class="icon_cogs"></i>Choix</th>
                                      </tr>
                                        <?php 
                                          $n = 0;
                                          while ($liste=$SelectAll->fetch(PDO::FETCH_OBJ)){
                                        ?>
                                        <tr>
                                          <td><?php echo ++$n; ?></td>
                                          <td><?php echo $liste->nomdroit; ?></td>
                                          <td><?php echo $liste->description; ?></td>
                                          <td>
                                            <div class="checkboxes">
                                              
                                                <input  type="checkbox" name="choix[]" id="checkAll<?php echo $n;echo $p; ?>" value="<?php echo $liste->iddroit; ?>" <?php if($liste->hasdroit) echo 'checked'; ?>
                                                   />
                                                   <label class="label_check" for="checkAll<?php echo $n;echo $p; ?>"></label>
                                            </div>
                                          </td>
                                        </tr>
                                        <?php } ?>
                                  </tbody>
                                </table>                                      
                              </div>                                      
                            </div>
                    </section>   
                          
                  </div>
                </div>
    
     
            
    <?php
          ++$p;
        }
        

    }

  }else{
        header('Location:login.php');
  }
?>

 <script>
    $(document).ready(function() {
             $(':checkbox').click(function() {

                 var $x = $(this);                      
                 var valeur;             
               // alert('ok');
                if(this.checked){ // si 'checkAll' est coché
                  valeur = true;
                }else{ // si on décoche 'checkAll'
                  valeur = false;
                }
                var url = "save.php?id="+$x.val()+"&hasDroit="+valeur;

                $.ajax(url, {
                  success: function(){
                            
                   },

                  error: function(){

                    alert('Une erreur est survenue!');
                  }
                });

              });
                       
      });
	  $('.loader').hide();
  </script>              