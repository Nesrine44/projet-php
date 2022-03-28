
<?php
    session_start();

    if(isset($_SESSION['login'])){

        $idUser = $_SESSION['login'];

        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');
        if(!has_Droit($idUser, "Envoyer newsletter")){

            header('Location:index.php');

        }else{

            $message = "";
            $sendAll = false;
            $enabled = false;
            $data_exp = null;

            $fideles=$db->prepare("SELECT * FROM personne AS pers, fidele AS fil WHERE fil.lisible=1 AND pers.lisible=1 AND idpersonne=personne_idpersonne");
            $fideles->execute();

            $groupes = $db->prepare("SELECT nomgroupe, typegroupe, idgroupe, count(idfidele) AS nbrefidele from groupe, fidele, fidelegroupe WHERE groupe.idgroupe = fidelegroupe.groupe_idgroupe AND fidele.idfidele = fidelegroupe.fidele_idfidele AND fidele.lisible = 1 AND groupe.lisible = 1 AND fidelegroupe.lisible = 1 
                 GROUP BY idgroupe
                 ORDER BY nomgroupe ASC");
            $groupes->execute();

        }

    }else{

        header('Location:login.php');
    }

?>

     <section class="wrapper">
        
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="material-icons text-primary">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                <li><i class="material-icons text-primary">message</i><a href="#" class="col-blue"> Messages</a> </li>
               
              
                </ol>
            </div>
        </div>
        <div class="row col-xs-12">
            <div class="col-sm-12">
                <ol class="breadcrumb  col-lg-12" style="background-color: #394a59; height: 40px; width: 100%;">
                    <li><button class="contributions" value="meilleurs_contributions" title="Envoyer email a un ou plusieur personne"><i class="fa fa-bars"></i><b>Type Email</b></button></li>
                    <li><button class= "contributions" value="contributions_periodiques"><i class="icon_table"></i><b>Type Sms</b></button></li>                  
                </ol>
            </div>
        </div>

        <div class="row catrine">
            <div class="row">
                <div class="col-lg-6">
                    <p style="float: right;">
                        <button id="fideles_enregistres" type="button" class="btn btn-success waves-effect" style="margin-right: 20px;">Fideles enregistés</button>
                        <button id="groupes_paroissiaux" type="button "class="btn btn-success waves-effect" >Groupes paroissiaux</button>
                    </p>
                </div>
                <div class="col-lg-6"></div>
            </div>

            <!-- formulaire pour fidele -->
            <form class="form-validate form-horizontal" id="form-send-fidele" method="POST" action="sendMail.php">
                <div class="col-lg-7">
                        <section class="panel">      
                                <input class="form-control" id="recherche-fidele" type="text" name="searchFidele" placeholder="Rechercher un fidèle">

                                <div id="result-fidele"> </div>
                                <div id="old_table_fidele" class="table-responsive">
								  
                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                        <tbody>
                                            <tr>
                                                <th>Numéro</th>
                                                <th>Code</th>
                                                <th><i class="icon_profile"></i> Nom et prénom</th>
                                                <th>Email</th>
                                                <th style="text-align: right;"><div class="cochetou" style="text-align:right;width:82%; float:right;">
                                               
                                                <input class="checkAll" onclick="CocheTout(this)" id="checkAll"type="checkbox">
                                                <label class="catrin" for="checkAll">Cocher Tous</label> 
                                            </div></th>
                                            </tr>
                                        <?php

                                            $n=0;
                                            while ($liste=$fideles->fetch(PDO::FETCH_OBJ)){
                                        ?>
                                             <tr>
                                                    <td><?php echo ++$n;?></td>
                                                    <td><?php echo $liste->codefidele;?></td>
                                                    <td>
                                                        <a class ="link" <?php 
                                                            if(has_Droit($idUser, "Afficher fidele")){
                                                                echo 'href="afficherFidele.php?code='.$liste->idpersonne.'"';
                                                            }else{echo "";} ?>    ><?php echo $liste->nom.'  '.$liste->prenom; ?></a>
                                                    </td>
                                                    <td><?php echo $liste->email; ?></td>
                                                    <td style="text-align: right;">
                                                        <div class="checkboxes">
                                                           
                                                            <input name="choix[]" id="checkbox-01<?php echo($n); ?>" value="<?php echo $liste->email; ?> "  
                                                            type="checkbox"/>
                                                            <label class="label_check" for="checkbox-01<?php echo($n); ?>"></label>
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
                            $db=NULL;
                            ?>
                        </section>
                </div>
                <div class="col-lg-5">
                    <section class=" card panel">
                                <header class="panel-heading">
                                    <h4>Envoyer une newsLetter</h4>
                                </header>
                                <div class="panel-body">
                                    <div class="form">

                                        <div class="form-group">
                                            <label for="csujet-fidele" class="control-label col-lg-2">Objet: <span class="required">*</span></label>
                                            <div class="col-lg-10">
                                                <input class="form-line" id="csujet-fidele" name="sujet" minlength="2" type="text" required placeholder="Objet du message" />
                                            </div>
                                        </div>
                                        <br>

                                        <div class="form-group">
                                            <!-- <label for="cmessage" class="control-label col-lg-2">Message: </label>-->
                                            <div class="col-sm-12" style="border:1px solid black">
                                                <textarea class="form-control ckeditor" name="message" rows="6" required placeholder="Votre message"></textarea>
                                            </div>
                                        </div>
                                        <br>

                                        <div class="form-group">
                                            <div class="col-lg-offset-5 col-lg-8" >
                                            
                                                <input class="btn btn-primary send-fidele"  type="submit" name="submit" value="Envoyer" style="margin-left:20px;" /><span class="envoi_en_cours" style="margin-left: 10px; display: none;">Envoi en cours...</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                        </section>
                </div>
            </form>

                <!-- formulaire pour groupe -->
            <form class="form-validate form-horizontal" id="form-send-groupe" method="POST" action="sendMailGroupe.php" style="display: none;">
                <div class="col-lg-6">
                        <section class="panel">                                       
                                <input class="form-control" id="recherche-groupe" type="text" name="searchGroupe" placeholder="Rechercher un groupe">                                                     

                                <div id="result-groupe"> </div>
                                <div id="old_table_groupe">
                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                        <tbody>
                                            <tr>
                                                <th>Numéro</th>                                                
                                                <th>Nom du groupe</th>
                                                <th>Type</th>
                                                <th>Nombre de fidèles</th>
                                                <th style="text-align: right;"><div class="cochetou" style="text-align:right;width:82%; float:right;">
                                                
                                                <input class="checkAll" onclick="CocheTout(this)" type="checkbox" id="checkbox">
                                                <label for="checkbox"class="catrine">Cocher Tous</label> 
                                            </div></th>
                                            </tr>
                                        <?php

                                            $n=0;
                                            while ($groupe=$groupes->fetch(PDO::FETCH_OBJ)){
                                        ?>
                                             <tr>
                                                    <td><?php echo ++$n;?></td>
                                                    <td><?php echo $groupe->nomgroupe;?></td>
                                                    <td><?php echo $groupe->typegroupe;?></td>    
                                                    <td><?php echo $groupe->nbrefidele; ?></td>      
                                                    <td style="text-align: right;">
                                                        <div class="checkboxes">
                                                            
                                                            <input name="choix[]" id="checkbox-02<?php echo($n); ?>" value="<?php echo $groupe->idgroupe; ?> "  type="checkbox"/>
                                                            <label class="label_check" for="checkbox-02<?php echo($n); ?>">
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
                            $db=NULL;
                            ?>
                        </section>
                </div>
                <div class="col-lg-6">
                    <section class="card panel">

                                <header class="panel-heading">

                                    <h4>Envoyer une newsLetter</h4>

                                </header>

                                <div class="panel-body">
                                    <div class="form">

                                        <div class="form-group">
                                            <label for="csujet-groupe" class="control-label col-lg-2">Objet: <span class="required">*</span></label>
                                            <div class="col-lg-10">
                                                <input class="form-line" id="csujet-groupe" name="sujet" minlength="2" type="text" required placeholder="Objet du message" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <!-- <label for="cmessage" class="control-label col-lg-2">Message: </label>-->
                                            <div class="col-sm-12" style="border:1px solid black">
                                                <textarea class="form-control ckeditor" name="message" rows="6" placeholder="Contenu du message"></textarea>
                                            </div>
                                        </div>
                                        <br>

                                        <div class="form-group">
                                            <div class="col-lg-offset-5 col-lg-8" >
                                            
                                                <input class="btn btn-primary send-groupe"  type="submit" name="submit" value="Envoyer" style="margin-left:20px;" /><span class="envoi_en_cours" style="margin-left: 10px; display: none;">Envoi en cours...</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                    </section>
                </div>
                </form>
            </div>
            <div class="cat">
                <h4>Nous travaillons dure pour implementer cette partie mais elle est bientot prete.</h4>
            </div>
    </section>


    <script type="text/javascript">

        $('.contributions').on('click', function(e){
        e.preventDefault();
        var x = $(this).val();
        if(x == 'meilleurs_contributions'){
            $('.catrine').show();
            $('.cat').hide();
           
        }else if(x == 'contributions_periodiques'){
            $('.catrine').hide();
            $('.cat').show();
        }
      
    });
    </script>




<script type="text/javascript">

    $('#fideles_enregistres').on('click', function(e){
        e.preventDefault();
        var $fidele = $(this);
        var $groupe = $('#groupes_paroissiaux');

        $groupe.removeAttr('disabled', 'true');
        $groupe.removeClass('btn-default');
        $groupe.addClass('btn-success');
      
        $fidele.addClass('btn-default');
        $fidele.attr('disabled', 'true');

        $('#form-send-groupe').hide();
        $('#form-send-fidele').show();

    });

    $('#groupes_paroissiaux').on('click', function(e){

        e.preventDefault();
        var $groupe = $(this);
        var $fidele = $('#fideles_enregistres');

        $fidele.removeAttr('disabled', 'true');
        $fidele.removeClass('btn-default');
        $fidele.addClass('btn-success');        
        $groupe.addClass('btn-default');
        $groupe.attr('disabled', 'true');

        $('#form-send-fidele').hide();
        $('#form-send-groupe').show();
            
    });


    $('.checkAll').click(function() {                        
        var test='Cocher';
        var test1='Decocher';
        if(this.checked){ // si 'checkAll' est coché
            $(":checkbox").attr('checked', true);
            $('.catrin').html(test1);
            
        }else{ // si on décoche 'checkAll'

            $(":checkbox").attr('checked', false);
            $('.catrin').html(test);
                                   
        }
    });


    $('#form-send-fidele').on('submit', function(e){

        e.preventDefault();
        var $form = $(this);                                
                             
        url = $form.attr('action');

        $('.envoi_en_cours').show();
        $.post(url, $form.serializeArray())
 
            .done(function(data, text, jqxhr){  
                        
                alert('Message(s) envoyé(s) avec succès!');
                $('#main-content').load('newsLetter.php');
                        
            })
            .fail(function(jqxhr){

                alert(jqxhr.responseText);
            })
            .always(function(){

                $('.envoi_en_cours').hide();
            });
                                
    });

    $('#form-send-groupe').on('submit', function(e){

        e.preventDefault();
        var $form = $(this);                                
                             
        url = $form.attr('action');

        $('.envoi_en_cours').show();
        $.post(url, $form.serializeArray())
 
            .done(function(data, text, jqxhr){  
                        
                alert('Message(s) envoyé(s) avec succès!');
                $('#main-content').load('newsLetter.php');
                        
            })
            .fail(function(jqxhr){

                alert(jqxhr.responseText);
            })
            .always(function(){

                $('.envoi_en_cours').hide();
            });
                                
    });


    $('.link').on('click', function(e){

        e.preventDefault();
        var $link = $(this);
        target = $link.attr('href');

        $('#main-content').load(target);
                    
    });           


    $('#recherche-fidele').keyup(function(){

        var txt = $(this).val();

        if(txt != ''){   

            $.ajax({
                
                url:"searchNewsletter.php",
                method:"get",
                data:{searchFidele:txt},
                dataType:"text",
                success:function(data)
                {
                    $('#old_table_fidele').hide();
                    $('#result-fidele').html(data);                                   
                }
            });

        }else{

            $('#result-fidele').html(txt);
            $('#old_table_fidele').show();
        }

    });

$('#recherche-groupe').keyup(function(){

        var txt = $(this).val();

        if(txt != ''){   

            $.ajax({
                
                url:"searchNewsletterGroupe.php",
                method:"get",
                data:{searchGroupe:txt},
                dataType:"text",
                success:function(data)
                {
                    $('#old_table_groupe').hide();
                    $('#result-groupe').html(data);                                   
                }
            });

        }else{

            $('#result-groupe').html(txt);
            $('#old_table_groupe').show();
        }

    });
	$('.loader').hide();

</script>

