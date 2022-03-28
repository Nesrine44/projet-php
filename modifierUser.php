<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php'); 

        if(!has_Droit($idUser, "Modifier user")){

            header('Location:index.php');

        }else{

      		   $idUser=$_GET['id'];
                 $personne = null;
                 require_once('includes/connexionbd.php');

                 $SelectOne = $db->prepare("
                   SELECT
                       role.`nomRole` AS role,
                       userrole.`has_role` AS has_role,
                       personne.`nom` AS nom,
                       personne.`prenom` AS prenom,                
                       utilisateur.`login` AS login,
                       utilisateur.`password` AS password,
                       role.`idrole` AS idrole,
                       personne.`idpersonne` AS id
                   
                  FROM
                      personne, utilisateur, role, userrole
                  WHERE
                      userrole.role_idrole = role.idrole
                  AND
                      userrole.utilisateur_idutilisateur = utilisateur.idutilisateur 
                  AND
                      utilisateur.personne_idpersonne = personne.idpersonne 
                  AND 
                      utilisateur.lisible = true
                  AND 
                      utilisateur.`idutilisateur` = $idUser
                      "
                  );
                 $SelectOne->execute();
                 
                 $personne = $SelectOne->fetch(PDO::FETCH_OBJ);

                 $listeRole = $db->prepare('SELECT idrole, nomRole as nrole FROM role where lisible = true');
                 $listeRole->execute();
        }

    }else{

      header('Location:login.php');
    }
    //Liste des personnes a modifier;
        $listePersonne = null;

        $listePersonne = $db->prepare("SELECT idpersonne, nom, prenom from personne where lisible = true AND idpersonne NOT IN(select personne_idpersonne from utilisateur where lisible = true and idpersonne!='".$_GET['id']."')");
        $listePersonne->execute();

?>

    <section class="wrapper">
     

          <div class="row">
            <div class="col-lg-12">
              <ol class="breadcrumb">
                <li><i class="material-icons">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                <li><i class="material-icons">people</i><a class="afficher col-blue" href="listeUser.php"> Liste utilisateurs</a></li>
                <li><i class="material-icons">people</i><a href="#" class="col-blue"> Modifier User</a></li>
              </ol>
            </div>
          </div>
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading text-center h4">
                        Modifier un utilisateur
                    </header>
                    <div class="panel-body">
                        <div class="form">
                            <form class="form-validate form-horizontal" id="updateUser_form" method="POST" action="updateUser.php?id=<?php echo $idUser; ?>">
                            <div class="row clearfix inputTopSpace">
                                <div class="col-md-12">
                                  <label for="mdpconfirm">Nom Utilisateur: <span class="required">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons">person</i>
                                        </span>
                                        <div class="form-line">
                                            <input class="form-control" id="cUsername" type="text" minlength="5" name="username" placeholder="Nom Utilisateur:" value="<?php echo $personne->login; ?>"/>
                                        </div>
                                    </div>
                                </div> 
                            </div>

                          <div class="row clearfix inputTopSpace">
                               <div class="col-md-6">
                                <label for="mdp">Nouveau Mot de passe: <span class="required">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons">person</i>
                                        </span>
                                        <div class="form-line">
                                            <input class="form-control" id="cPassword" type="password" minlength="6" name="password" placeholder="Nouveau Mot de passe *" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                  <label for="mdpconfirm">Confirmation: <span class="required">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons">person</i>
                                        </span>
                                        <div class="form-line">
                                            <input class="form-control" id="cConfirmation" type="password" minlength="6" name="confirmation"  placeholder="Confirmation *"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row clearfix inputTopSpace">
                                <div class="col-sm-6 ">
                                  <label for="Nom">Personne: <span class="required">*</span></label>
                                    <select class="form-control show-tick" name="personne" id="cPersonne" >
                                               <option value="<?php echo $personne->id; ?>"><?php echo $personne->nom.' '.$personne->prenom; ?>                                
                                                </option>
                                              <?php
                                                while($liste = $listePersonne->fetch(PDO::FETCH_OBJ)){
                                                ?>
                                                    <option value="<?php echo $liste->idpersonne; ?>"><?php echo $liste->nom.' '.$liste->prenom; ?></option>
                                                <?php       
                                                    }
                                                ?>
                                    </select>
                                </div>
                                <div class="col-sm-6 ">
                                    <label for="Nom">Rôle: <span class="required">*</span></label>
                                    <select class="form-control show-tick" name="role" id="cRole" required>
                                                <?php
                                                while($listeR = $listeRole->fetch(PDO::FETCH_OBJ)){
                                            ?>
                                                <option value="<?php echo $listeR->idrole; ?>"   <?php if($listeR->idrole == $personne->idrole) echo 'selected';?>><?php echo $listeR->nrole; ?></option>
                                            <?php       
                                                }
                                            ?>
                                    </select>
                                </div>
                            </div>
                             <br>
                            <div class="form-group">
                              <div class="form-group">
                                    <div class="col-lg-offset-5 col-lg-10">
                                        <a class="btn btn-warning annuler" href="listeUser.php" title="Annuler l'opération">Annuler</a>&nbsp;
                                        <button id="modifier" class="btn btn-primary" id="modifier" name="modifier" type="submit">Mettre à jour</button>
                                        
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>                
    </section>

    <script>
      $('#chargement').hide();

             $('#updateUser_form').on('submit', function(e){
                e.preventDefault();
                 var $form = $(this); 

                 $form.find('#modifier').text('Chargement');
                 
                    $.post($form.attr('action'), $form.serializeArray())
                        .done(function(data, text, jqxhr){
                            alert('Utilisateur modifié avec succès!');
                          $('#main-content').load('listeUser.php');

                        })

                        .fail(function(jqxhr){

                            alert(jqxhr.responseText);
                        })

                        .always(function(){

                            $form.find('#modifier').text('Mettre à jour');
                        })
                });
            

               $('.annuler').on('click', function(e){

                    e.preventDefault();
                    var $link = $(this);
                    target = $link.attr('href');
                    if(window.confirm("Voulez-vous vraiment annuler?")){
                        $('.loader').show();
                        $('#main-content').load(target, function(){
                            $('.loader').hide();
                        });
                    }
                    
                }); 

         $('.afficher').on('click', function(af){

                            af.preventDefault();
                            $('.loader').show();
                           var $b = $(this);
                            url = $b.attr('href');
                           $('#main-content').load(url, function(){
                                $('.loader').hide();
                           });
                        });
             
        $(".select2").select2();
			  $('.loader').hide();

                       
           </script>              
