<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Enregistrer une participation")){

            header('Location:index.php');

        }else{
            
            $idsaintescene = $_GET['id'];

             $fideles = $db->prepare("SELECT nom, prenom, sexe, profession, codeFidele, idfidele FROM fidele, personne, confirmation where fidele.personne_idpersonne = personne.idpersonne and fidele.lisible=1 and personne.lisible = 1 AND fidele.idfidele = confirmation.fidele_idfidele AND personne.lisible = 1 AND fidele.lisible = 1 AND confirmation.lisible = 1 AND idfidele NOT IN (SELECT fidele_idfidele FROM  fidelesaintescene where saintescene_idsaintescene = $idsaintescene) ORDER BY nom LIMIT 0, 50");
            $fideles->execute();

?>
    
<header class="panel-heading">
                        <!-- Module de recherche -->
                        <div class="form-group">
                           <input class="form-control" id="recherche" type="text" name="search" placeholder="Rechercher un fidèle">
                        </div> 
                    </header>  

                    <div id="result"> </div>
                     <div id="old_table" class="table-responsive">
                            <table class="table table-striped table-hover">
                                    <tbody>
                                        <tr>
                                            <th>#</th>
                                            <th><i class="icon_pin_alt"></i> Code</th>
                                            <th><i class="icon_profile"></i>Noms et prenoms</th>
                                            <th style="text-align: right;">Choix
                                            </th>
                                        </tr>
                                        <?php
                                            $n=0;
                                            while($liste=$fideles->fetch(PDO::FETCH_OBJ)){

                                            ?>
                                        <tr>
                                            <td><?php echo ++$n; ?></td>
                                            <td><?php echo $liste->codefidele; ?></td>
                                            <td><?php echo $liste->nom.' '.$liste->prenom; ?></td>
                                            <td style="text-align: right;">
                                                <div class="checkboxes">
                                                    <label class="label_check" for="checkbox-01">
                                                        <input name="choix[]" class="checkbox-default" value="<?php echo $liste->idfidele; ?> "  type="checkbox"/>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>



<?php

    }
}else{

    header('Location:login.php');
}
?>
<script type="text/javascript">
    $('#recherche').keyup(function(){

            $(":checkbox").attr('checked', false);
                            var txt = $(this).val();

                           // alert(txt);
                            if(txt != ''){                                
                                $.ajax({
                                    
                                    url:"searchParticipation.php?id=<?php echo $idsaintescene; ?>",
                                    method:"get",
                                    data:{search:txt},
                                    dataType:"text",
                                    success:function(data)
                                    {
                                        $('#old_table').hide();
                                        $('#result').html(data);
                                      //alert(txt);
                                    }
                                });

                            }else{
                               // alert(txt);
                                $('#result').html(txt);
                                $('#old_table').show();
                            }

                        });
</script>