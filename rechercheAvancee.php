<?php
session_start();

if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login'];
    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');

    if(!has_Droit($idUser, "Afficher fidele")){

        header('Location:index.php');

    }else{
        $statut_paroisse = array();
        $statut_professionnel = array();
        $statut_matrimonial = array();
        $zonnage=array();

        $statutParoisials=$db->prepare("SELECT DISTINCT statut FROM fidele ORDER BY statut ");
        $statutParoisials->execute();

        while($x=$statutParoisials->fetch(PDO::FETCH_OBJ)){
            array_push($statut_paroisse, $x->statut);
        }

        $statut_professionnels=$db->prepare("SELECT DISTINCT statut_pro FROM personne ORDER BY statut_pro");
        $statut_professionnels->execute();

        while($y=$statut_professionnels->fetch(PDO::FETCH_OBJ)){
            array_push($statut_professionnel, $y->statut_pro);
        }

        $tatutMatrimonials=$db->prepare("SELECT DISTINCT situation_matri FROM personne ORDER BY situation_matri");
        $tatutMatrimonials->execute();

        while($z=$tatutMatrimonials->fetch(PDO::FETCH_OBJ)){
            array_push($statut_matrimonial, $z->situation_matri);
        }

        $zones=$db->prepare("SELECT DISTINCT nomzone FROM zone");
        $zones->execute();

        while($zone=$zones->fetch(PDO::FETCH_OBJ)){
            array_push($zonnage, $zone->nomzone);
        }
    }
}
?>



<section class="wrapper">

<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="col-blue"><i class="material-icons">home</i><a href="index.php" class="col-blue" > Accueil</a></li>
            <li class="col-blue"><i class="material-icons">filter</i> Recherche Avancée</li>
        </ol>
    </div>
</div>

<header class="col-lg-offset-5">
    <strong class="col-black h3">Choix du critère de recherche</strong>
</header><br>

<div>
<section class="panel bg-white">

<form class="form-validate form-horizontal" method="post" action="resultatRechercheAvance.php" id="form_find">

<div class="row">
    <div class="col-lg-12">
        <fieldset class="bordure">
            <legend><strong >Statut Paroissial</strong></legend>
            <div class="table-responsive  ">
                <table class="table table-striped table-advance table-hover col-sm-12">
                    <tbody>
                    <tr class="bg-teal col-white">
                        <?php
                        $n=0;
                        while( $n<count($statut_paroisse)){
                            ?>
                            <td style="text-align: center;">
                                <?php if ($statut_paroisse[$n]==NULL) {
                                          echo 'Autre';}
                                      else {echo $statut_paroisse[$n];} ?>
                            </td>
                            <?php
                            $n++;
                        }
                        ?>
                    </tr>
                    <tr>
                        <?php
                        $n=0;
                      
                        
                    
                    
                        while( $n<count($statut_paroisse)){
                            ?>
                            
                            <td style="text-align: center; overflow-wrap: hyphenate">
                                <div class="checkboxes">
                                    <label class="label_check" for="checkbox-01">
                                        <input type="checkbox" name="choix_statut_paroissial[]" value="<?php echo $statut_paroisse[$n]; ?> "  id="md_checkbox_01<?php echo $n;?>" class="filled-in chk-col-teal" <?php //if($n==0){echo "checked";}?> />
                                    <label for="md_checkbox_01<?php echo $n;?>" class="col-black"></label>
                                    </label>
                  
                                </div>
                            </td>
                            <?php
                            $n++;
                        }
                        ?>
                    </tr>
                    </tbody>
                </table>
            </div>
        </fieldset>
    </div>
</div><br>

<div class="row">
    <div class="col-lg-12">
        <fieldset class="bordure">
            <legend><strong >Statut Professionnel</strong></legend>
            <div class="table-responsive  ">
                <table class="table table-striped table-advance table-hover col-sm-12">
                    <tbody>
                    <tr class="bg-teal col-white">
                        <?php
                        $n=0;
                        while( $n<count($statut_professionnel)){
                            ?>
                            <td style="text-align: center;">
                                <?php if ($statut_professionnel[$n]==NULL) {
                                          echo 'Autre';}
                                      else {echo $statut_professionnel[$n];} ?></td>
                            <?php
                            $n++;
                        }
                        ?>
                    </tr>
                    <tr>
                        <?php
                        $n=0;
                        while( $n<count($statut_professionnel)){
                            ?>
                            <td style="text-align: center; overflow-wrap: hyphenate">
                                <div class="checkboxes">
                                    <label class="label_check" for="checkbox-01">
                                        <input type="checkbox" name="choix_statut_professionnel[]" value="<?php echo $statut_professionnel[$n]; ?> "   id="md_checkbox_02<?php echo $n;?>" class="filled-in chk-col-teal" <?php //if($n==0){echo "checked";}?> />
                                    <label for="md_checkbox_02<?php echo $n;?>"></label>
                                    </label>
                                </div>
                            </td>
                            <?php
                            $n++;
                        }
                        ?>
                    </tr>
                    </tbody>
                </table>
            </div>
        </fieldset>
    </div>
</div><br>

<div class="row">
    <div class="col-lg-12">
        <fieldset class="bordure">
            <legend><strong >Statut Matrimonial</strong></legend>
            <div class="table-responsive  ">
                <table class="table table-striped table-advance table-hover col-sm-12">
                    <tbody>
                    <tr class="bg-teal col-white">
                        <?php
                        $n=0;
                        while( $n<count($statut_matrimonial)){
                            ?>
                            <td style="text-align: center;">
                                <?php if ($statut_matrimonial[$n]==NULL) {
                                          echo 'Autre';}
                                      else {echo $statut_matrimonial[$n];} ?></td>
                            <?php
                            $n++;
                        }
                        ?>
                    </tr>
                    <tr>
                        <?php
                        $n=0;
                        while( $n<count($statut_matrimonial)){
                            ?>
                            <td style="text-align: center; overflow-wrap: hyphenate">
                                <div class="checkboxes">
                                   <label class="label_check" for="checkbox-01">
                                        <input type="checkbox" name="choix_statut_matrimonial[]" value="<?php echo $statut_matrimonial[$n]; ?> "   id="md_checkbox_03<?php echo $n;?>" class="filled-in chk-col-teal" <?php ///if($n==0){echo "checked";}?> />
                                    <label for="md_checkbox_03<?php echo $n;?>"></label>
                                    </label>
                                </div>
                            </td>
                            <?php
                            $n++;
                        }
                        ?>
                    </tr>
                    </tbody>
                </table>
            </div>
        </fieldset>
    </div>
</div><br>

<div class="row">
    <div class="col-lg-12">
        <fieldset class="bordure">
            <legend><strong >Zone</strong></legend>
            <div class="table-responsive  ">
                <table class="table table-striped table-advance table-hover col-lg-12 ">
                    <tbody>
                    <tr class="bg-teal col-white">
                        <?php
                        $n=0;
                        while( $n<count($zonnage)){
                            ?>
                            <td style="text-align: center;">
                                
                                <?php if ($zonnage[$n]==NULL) {
                                          echo 'Autre';}
                                      else {echo $zonnage[$n];} ?>
                            </td>
                            <?php
                            $n++;
                        }
                        ?>
                    </tr>
                    <tr>
                        <?php
                        $n=0;
                        while( $n<count($zonnage)){
                            ?>
                            <td style="text-align: center; overflow-wrap: hyphenate">
                                <div class="checkboxes">
                                    <label class="label_check" for="checkbox-01">
                                        <input type="checkbox" name="choix_zonne[]" value="<?php echo $zonnage[$n]; ?> "   id="md_checkbox_04<?php echo $n;?>" class="filled-in chk-col-teal" <?php //if($n==0){echo "checked";}?> />
                                    <label for="md_checkbox_04<?php echo $n;?>"></label>
                                    </label>
                                </div>
                            </td>
                            <?php
                            $n++;
                        }
                        ?>
                    </tr>
                    </tbody>
                </table>
            </div>
        </fieldset>
    </div>
</div><br>

<div class="row">

    <div class="col-lg-6">
        <fieldset class="bordure">
            <legend><strong>Sexe et age</strong></legend>
            <div class="table-responsive">
                <table class="table  table-striped table-advance table-hover col-lg-3 col-sm-12">
                    <tbody>
                    <tr>
                        <td class="h4 col-black">Sexe</td>
                        <td><input type="radio" name="sexe" value="MASCULIN" id="radio_30" class="decision_sexe with-gap radio-col-green" /><label for="radio_30" class="col-black">M </label></td>
                        <td> <input type="radio" name="sexe" value="FEMININ" id="radio_31" class="decision_sexe with-gap radio-col-pink"/><label for="radio_31" class="col-black">F</label></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="h4 col-black">Age par intervalle?</td>
                        <td><input type="radio" name="age" value="1" id="radio_32" class="decision_age with-gap radio-col-green" /><label for="radio_32" class="col-black">Oui </label></td>
                        <td><input type="radio" name="age" value="0" id="radio_33" class="decision_age with-gap radio-col-red" /><label for="radio_33" class="col-black">Non </label></td>
                        <td></td>
                    </tr>

                    <tr id="intervalle" style="display: none">
                        <td>Entre</td>
                        <td><input type="number" class="form-control" name="age_min" min="0"/></td>
                        <td>Et</td>
                        <td><input type="number" class="form-control" name="age_max" min="0"/></td>
                    </tr>

                    <tr id="non_intervalle" style="display: none">
                        <td class="h4 col-black">Renseigner l'âge</td>
                        <td><input type="number" name="age_mine" class="form-control" min="0"/></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </fieldset>
    </div>

    <div class="col-lg-6">
        <fieldset class="bordure">
            <legend><strong >Autres</strong></legend>
            <div class="table-responsive">
                <table class="table  table-striped table-advance table-hover col-lg-3 col-sm-12">
                    <tbody>
                    <tr>
                        <td class="h4 col-black">Baptisé(e)?</td>
                        <td><input type="radio" name="baptise" value="1" id="radio_34" class="with-gap radio-col-green" /><label for="radio_34" class="col-black">Oui</label></td>
                        <td><input type="radio" name="baptise" value="1" id="radio_35" class="with-gap radio-col-red" /><label for="radio_35" class="col-black">Nom</label></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="h4 col-black">Confirmé(e)?</td>
                        <td> <input type="radio" name="confirme" value="1" id="radio_36" class="with-gap radio-col-green" /><label for="radio_36" class="col-black">Oui</label></td>
                        <td><input type="radio" name="confirme" value="0" id="radio_37"  class="with-gap radio-col-red"/><label for="radio_37" class="col-black">Nom</label></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="h4 col-black">Malade?</td>
                        <td><input type="radio" name="malade" value="1" id="radio_38" class="with-gap radio-col-green" /><label for="radio_38" class="col-black">Oui</label></td>
                        <td><input type="radio" name="malade" value="0" id="radio_39" class="with-gap radio-col-red"/><label for="radio_39" class="col-black">Nom</label></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </fieldset>
    </div>
</div>

<br><div class="form-group">
    <div class="col-lg-offset-5 col-lg-10">
        <a class="btn btn-warning annuler">Annuler</a>
        <button class="btn btn-primary afficher" name="submit" type="submit">Rechercher</button>
    </div>
    <br>
</div>
<br>

</form>
</section>
</div>
</section>



<script>

    $('.decision_age').on('click', function(){

        $x = $(this).val();
        if($x == 1){

            $('#intervalle').show();
            $('#non_intervalle').hide();

        }else{

            $('#intervalle').hide();
            $('#non_intervalle').show();
        }
    });

    $('.annuler').on('click', function(e){

        e.preventDefault();
        var $link = $(this);
        target = $link.attr('href');
        if(window.confirm("Voulez-vous vraiment annuler?")){

            $('#main-content').load(target);
        }

    });

    $('#form_find').on('submit', function(e){

        e.preventDefault();
        $('.loader').show();

        var $form = $(this);
        $form.find('button').text('Traitement');

        url = $form.attr('action');


        $.post(url, $form.serializeArray())

            .done(function(data, text, jqxhr){


                $('#main-content').html(jqxhr.responseText);

            })
            .fail(function(jqxhr){
                alert(jqxhr.responseText);
            })
            .always(function(){
                $form.find('button').text('Enregistrer');
                $('.loader').hide();
            });

    });






    $('.loader').hide();

</script>
