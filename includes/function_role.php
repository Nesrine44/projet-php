
<?php
require_once('connexionbd.php');

has_Droit(34, 'Liste des depenses');

	function has_Droit($idUser, $nomRole){
 
    global $db;
    $val = false;
    $s = $db->prepare("
      SELECT DISTINCT
            roledroit.`hasDroit` AS hasdroit
      FROM
           `droit` droit INNER JOIN `roledroit` roledroit ON droit.`iddroit` = roledroit.`droit_iddroit`
           INNER JOIN `role` role ON roledroit.`role_idrole` = role.`idrole`
           INNER JOIN `userrole` userrole ON role.`idrole` = userrole.`role_idrole`
           INNER JOIN `utilisateur` utilisateur ON userrole.`utilisateur_idutilisateur` = utilisateur.`idutilisateur`
      WHERE
           utilisateur.idutilisateur = $idUser AND droit.nomDroit = '$nomRole'

      ");
    $s->execute();

    while($result = $s->fetch(PDO::FETCH_OBJ)){

      $val = $result->hasdroit;
    }

    if($val==0){
      $se = $db->prepare("SELECT DISTINCT * from userrole where utilisateur_idutilisateur = $idUser");
      $se->execute();
      while($res = $se->fetch(PDO::FETCH_OBJ)){

        $val = $res->role_idrole;
      }
    }


    return $val;
  }

  function pagination($pageCourante, $nbDePage, $page){

  
?>
  <div class="text-center">
      
      <ul class="pagination">
      
        <li><a <?php if($pageCourante == 1){ echo "disabled";}?> class="btn item" href="<?php echo $page; ?>.php?p=<?php if($pageCourante != 1) { echo $pageCourante - 1; }else{echo $pageCourante; } ?>">&laquo</a>
         </li>

        <?php
           $nb=10;
                            if($nbDePage<=$nb){
                                            for($i=1; $i<=$nbDePage; $i++){
                                                if( $i == $pageCourante){
                                                    ?>
                                                    <li class="active"><a class="item" href="<?php echo $page; ?>.php?p=<?php echo $i ?>"><?php echo $i ?></a></li>
                                                <?php
                                                }else{
                                                    ?>
                                                    <li><a class="item" href="<?php echo $page; ?>.php?p=<?php echo $i ?>"><?php echo $i ?></a></li>
                                                <?php
                                                }
                                            }
                                        }else{                                           
                                            for($i=1; $i<=$nb/2; $i++){
                                                if( $i == $pageCourante){
                                                    ?>
                                                    <li class="active"><a class="item" href="<?php echo $page; ?>.php?p=<?php echo $i ?>"><?php echo $i ?></a></li>
                                                <?php
                                                }else{
                                                    ?>
                                                    <li><a class="item" href="<?php echo $page; ?>.php?p=<?php echo $i ?>"><?php echo $i ?></a></li>
                                                <?php
                                                }
                                            }


                                            ?>

                                            <li class="<?php if($pageCourante>$nb/2 AND $pageCourante<$nbDePage-($nb/2-1))
                                                echo 'active'; ?>"><a class="item"><?php echo "..." ?></a></li>

                                            <?php
                                            for($i=$nbDePage-($nb/2-1); $i<=$nbDePage; $i++){
                                                if( $i == $pageCourante){
                                                    ?>
                                                    <li class="active"><a class="item" href="<?php echo $page; ?>.php?p=<?php echo $i ?>"><?php echo $i ?></a></li>
                                                <?php
                                                }else{
                                                    ?>
                                                    <li><a class="item" href="<?php echo $page; ?>.php?p=<?php echo $i ?>"><?php echo $i ?></a></li>
                                                <?php
                                                }
                                            }
                                        }
                                        ?>

                                        <li><a <?php if($pageCourante == $nbDePage || $nbDePage ==0 ){ echo "disabled";}?> class="item btn" href="<?php echo $page; ?>.php?p=<?php if($pageCourante != $nbDePage) { echo $pageCourante + 1; }else{echo $pageCourante; } ?>">&raquo</a> <br/>
                                            <label id="modifiertext" style="font-style:italic;"></label>
                                        </li>
                                    </ul>
                                </div> 
<?php
  }

  ?>
 