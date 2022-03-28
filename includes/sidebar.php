
<?php  
     
// $logo="/var/www/vhosts/church.kamer-center.net/httpdocs/report/ICwt.jpeg";

    require_once('function_role.php'); 

    $idUser = $_SESSION['login'];
    $annee = $_SESSION['annee']; 
    $logo = $_SESSION['logo'];

 
    $logo=str_replace('\\', '/', $logo);
    $logo=explode('/', $logo);
    $logo1=$logo[4].'/'.$logo[5];    

    // $logo=explode('/', $logo);
    // $logo1=$logo[6].'/'.$logo[7];
    
    




    
?>
<section>
  

  
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info anna" style="background-image: url('<?php echo $logo1; ?>'); background-size: 300px 150px;">
                <div class="image">
                    <img src="images/<?php if($user->photo == ""){echo 'User';}else{echo $user->photo;}?>" width="70" height="70" />
                </div>
                <div class="info-container">
                    <div class="name col-teal" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <span class="font-bold "> <?php if($user){echo $user->nom;}?>   </span>                                           
                    </div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons bg-brown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right extended logout">
                            <div class="log-arrow-up"></div>
                            <li><a href="documentation.html" target="_blank"><i class="material-icons col-blue">line_weight</i> Documentation</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="deconnexion.php" class="deconnexion"><i class="material-icons col-red">lock</i>Déconnexion</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
 <!-- Menu -->
            <div class="menu themes_church_sidbar">
                <ul class="list">
                    <!-- <li class="header">NAVIGATION</li> -->
                    <li class="active sub" <?php if(!has_Droit($idUser, "Afficher fidele")){echo 'hidden';}else{echo "";} ?> >
                      <a class="" href="rechercheAvancee.php" title="faire une recherche approfondie">
                        <i class="material-icons">search</i>
                            <span>Réchercher</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle col-white">
                           <i class="material-icons col-light-green">personal_video</i>
                            <span class="col-white">Administration</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="sub"<?php if(!has_Droit($idUser, "creer utilisateur") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>>
                                <a href="nouveauUser.php"  class="col-white">
                                    Nouvel Utilisateur
                                </a>
                            </li>
                                
                            <li  class="sub" <?php if(!has_Droit($idUser, "consulter user")){echo 'hidden';}else{echo "";} ?>>
                                <a href="listeUser.php" class="col-white">
                                    Liste des Utilisateurs
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Editer role") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>>
                                <a href="editRole.php"  class="col-white">
                                    Editer role
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Editer parametres") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><a href="parametres.php" class="col-white">
                                    Paramètres
                                </a>                          
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle col-white">
                        <i class="material-icons col-cyan">group</i>
                            <span class="col-white">Fidèles</span>
                        </a>
                        <ul class="ml-menu">
                            <li  class="sub" <?php if(!has_Droit($idUser, "Creer un fidele") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?> ><a  href="nouveauFidele.php" title="Enregistrer un nouvel fidèle" class="col-white">
                                    <span class="col-white">Nouveau fidèle</span>
                                </a>
                            </li>
                                
                            <li  class="sub" <?php if(!has_Droit($idUser, "lister fidele")){echo 'hidden';}else{echo "";} ?> ><a  href="listeFideles.php" title="Liste de tous les fidèles" class="col-white">
                                    <span class="col-white">Liste des fidèles</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Creer un zone") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?> ><a  href="nouvelleZone.php" title="Créer une nouvelle zone" class="col-white">
                                    <span class="col-white">Nouvelle Zone</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "lister les zones")){echo 'hidden';}else{echo "";} ?> ><a  href="listeZones.php" title="Liste de toutes les zones" class="col-white">
                                    <span class="col-white">Liste des zones</span>
                                </a>                          
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle col-white">
                        <i class="material-icons col-light-green">local_hospital</i>
                            <span class="col-white">Santé</span>
                        </a>
                        <ul class="ml-menu">
                            <li  class="sub" <?php if(!has_Droit($idUser, "Creer un malade") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?> ><a  href="nouveauMalade.php" title="Enregistrement d'un nouveau malade" class="col-white">
                                    <span class="col-white">Nouveau malade</span>
                                </a>
                            </li>
                                
                            <li  class="sub" <?php if(!has_Droit($idUser, "Lister malade")){echo 'hidden';}else{echo "";} ?> ><a  href="listeMalades.php" title="liste des malades" class="col-white">
                                    <span class="col-white">Liste des malades</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Enregistrer guerison") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?> ><a  href="nouvelleguerison.php" title="Enregistrement d'une nouvelle guérison" class="col-white">
                                    <span class="col-white">Nouvelle guérison</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Lister guerison")){echo 'hidden';}else{echo "";} ?> ><a  href="listeMaladesGueris.php" title="liste des malades guéris" class="col-white">
                                    <span class="col-white">Liste des guérisons</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Enregistrer deces") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?> ><a  href="nouveauDeces.php" title="Enregistrement d'un nouveau décès" class="col-white">
                                    <span class="col-white">Nouveau décès</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Lister deces")){echo 'hidden';}else{echo "";} ?> ><a  href="listeMaladesDecedes.php" title="liste des malades décédés" class="col-white">
                                    <span class="col-white">Liste des décès</span>
                                </a>                          
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle col-white">
                        <i class="material-icons col-orange">group_work</i>
                            <span class="col-white">Groupes</span>
                        </a>
                        <ul class="ml-menu">
                            <li  class="sub" <?php if(!has_Droit($idUser, "Creer un groupe") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?> ><a  href="nouveauGroupe.php" title="Enregistrement d'un nouveau groupe" class="col-white">
                                    <span class="col-white">Nouveau groupe</span>
                                </a>
                            </li>
                                
                            <li  class="sub" <?php if(!has_Droit($idUser, "Lister groupe")){echo 'hidden';}else{echo "";} ?> ><a  href="listeGroupe.php" title="Liste de tous les groupes" class="col-white">
                                    <span class="col-white">Liste des groupes</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Inscrire a un groupe") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?> ><a  href="enregistrementUinique.php" title="Enregistrement d'un fidèle à un groupe" class="col-white">
                                    <span class="col-white">Ajouter à un groupe</span>
                                </a>                          
                            </li> 
                         
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle col-white">
                            <i class="material-icons col-pink">assignment</i>
                            <span class="col-white">Gestion Paroisse</span>
                        </a>
                        <ul class="ml-menu">
                            <li   class="sub" class="sub" <?php if(!has_Droit($idUser, "Lister groupe") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?> ><a  href="groupeAnciens.php" title="Voir les groupes des anciens" class="col-white">
                                    <span class="col-white">Groupes</span>
                                </a>
                            </li>
                                
                            <li  class="sub" <?php if(!has_Droit($idUser, "Inscrire a un groupe") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?> ><a  href="enregistrerAncienAgroupe.php" title="Enregistrer des anciens à un groupe" class="col-white">
                                    <span class="col-white">Enregistrement</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Enregistrer un conseil") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?> ><a  href="enregistrerUnConseil.php" title="Enregistrement d'un conseil" class="col-white">
                                    <span class="col-white">Enregistrer un conseil</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Assiduite a un conseil")){echo 'hidden';}else{echo "";} ?> ><a  href="assiduite.php" title="voir l'assiduité des anciens aux conseils" class="col-white">
                                    <span class="col-white">Assiduité</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Lister fidele")){echo 'hidden';}else{echo "";} ?> ><a href="listeAnciens.php" title="voir la liste des anciens" class="col-white">
                                    <span class="col-white">Liste d'anciens</span>
                                </a>                          
                            </li>

                            <li  class="sub" <?php if(!has_Droit($idUser, "Lister fidele")){echo 'hidden';}else{echo "";} ?> ><a  href="listeConseilles.php" title="voir la liste des Conseillés" class="col-white">
                                    <span class="col-white">Liste des conseillers</span>
                                </a>                          
                            </li>
                        </ul>
                    </li>

                     <!-- menu rajouter  -->
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle col-white">
                            <i class="material-icons col-orange">timelapse</i>
                            <span class="col-white">Collectes ou Dons</span>
                        </a>
                        <ul class="ml-menu">
                            <li  class="sub" <?php if(!has_Droit($idUser, "Enregistrer une participation")){echo 'hidden';}else{echo "";} ?> ><a  href="listedons.php" class="col-white">
                                    <span class="col-white">Liste des dons</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Enregistrer une participation")){echo 'hidden';}else{echo "";} ?> ><a  href="listecollectes.php" class="col-white">
                                    <span class="col-white">Liste des collectes</span>
                                </a>                          
                            </li>
                        </ul>
                    </li>
                    <!-- fin du menu rajouter -->





                    <li>
                        <a href="javascript:void(0);" class="menu-toggle col-white">
                            <i class="material-icons col-cyan">grain</i>
                            <span class="col-white">Activités</span>
                        </a>
                        <ul class="ml-menu">
                        <li  class="sub" <?php if(!has_Droit($idUser, "Creer activite") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?> ><a  href="nouvelleActivite.php" title="Enregistrement d'une nouvelle activité" class="col-white">
                                    <span class="col-white">Nouvelle activité</span>
                                </a>
                            </li>
                                
                            <li  class="sub" <?php if(!has_Droit($idUser, "Lister activites")){echo 'hidden';}else{echo "";} ?> ><a  href="listeActivites.php" title="Liste de toutes les activités" class="col-white">
                                    <span class="col-white">Liste d'activitées</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Enregistrer bapteme") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?> ><a  href="nouveauBapteme.php" title="Enregistrement d'un nouveau Baptisé" class="col-white">
                                    <span class="col-white">Nouveau baptisé</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Lister baptises")){echo 'hidden';}else{echo "";} ?> ><a  href="listeBaptises.php" title="Liste de tous les Baptisés" class="col-white">
                                    <span class="col-white">Liste des baptisés</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Enregistrer bapteme") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?> ><a  href="nouveauCommunian.php" title="Enregistrement d'un nouveau Communian" class="col-white">
                                    <span class="col-white">Nouveau communian</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Lister baptises")){echo 'hidden';}else{echo "";} ?> ><a  href="listeCommunians.php" title="Liste de tous les Communians" class="col-white">
                                    <span class="col-white">Liste des communians</span>
                                </a>                          
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle col-white">
                            <i class="material-icons col-light-green">timelapse</i>
                            <span class="col-white">Saintes Cène</span>
                        </a>
                        <ul class="ml-menu">
                        <li  class="sub" <?php if(!has_Droit($idUser, "Creer sainte cene") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?> ><a href="nouvelleSaintecene.php" class="col-white">
                                    <span class="col-white">Nouvelle sainte cène</span>
                                </a>
                            </li>
                                
                            <li  class="sub" <?php if(!has_Droit($idUser, "Enregistrer contribution") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?>><a  href="nouvelleContributionSainteCene.php" title="Enregistrement d'une nouvelle contribution" class="col-white">
                                    <span class="col-white">Nouvelle contribution</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Enregistrer une participation")){echo 'hidden';}else{echo "";} ?> ><a  href="saintecene.php" class="col-white">
                                    <span class="col-white">Liste des saintes cènes</span>
                                </a>                          
                            </li>
                            
                            <li  class="sub" <?php if(!has_Droit($idUser, "Enregistrer une participation")){echo 'hidden';}else{echo "";} ?> ><a  href="monBilan.php" class="col-white">
                                    <span class="col-white">Mon bilan</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Enregistrer une participation")){echo 'hidden';}else{echo "";} ?> ><a  href="bilanPeriodique1.php" class="col-white">
                                    <span class="col-white">Bilan période I</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Enregistrer une participation")){echo 'hidden';}else{echo "";} ?> ><a  href="bilanPeriodique2.php" class="col-white">
                                    <span class="col-white">Bilan période II</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Enregistrer une participation")){echo 'hidden';}else{echo "";} ?> ><a  href="bilanJournalier.php" class="col-white">
                                    <span class="col-white">Bilan journalier</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Consulter participation")){echo 'hidden';}else{echo "";} ?> >	<a  href="bilanGeneral.php" class="col-white">
                                    <span class="col-white">Bilan général</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Visualiser liste attente")){echo 'hidden';}else{echo "";} ?> ><a  href="listeAttente.php" class="col-white">
                                    <span class="col-white">Liste d'attentes</span>
                                </a>                          
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle col-white">
                            <i class="material-icons col-orange">supervisor_account</i>
                            <span class="col-white">Finances Génerales</span>
                        </a>
                        <ul class="ml-menu">
                            <li  class="sub" <?php if(!has_Droit($idUser, "Finances Generales")){echo 'hidden';}else{echo "";} ?> ><a  href="entreeFinanciere.php" title="Voir le collège pastoral" class="col-white">
                                    <span class="col-white">Entrées Financières</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Nouvelle depense")){echo 'hidden';}else{echo "";} ?> ><a  href="nouvelleDepense.php" class="col-white">
                                    <span class="col-white">Nouvelle Dépense</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Liste des depenses")){echo 'hidden';}else{echo "";} ?> ><a  href="listeDepense.php" class="col-white">
                                    <span class="col-white">Liste des dépences</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Finances Generales")){echo 'hidden';}else{echo "";} ?> ><a  href="creerProjet.php" title="Voir le collège pastoral" class="col-white">
                                    <span class="col-white">Nouveau projet</span>
                                </a>                          
                            </li>
                            <li  class="sub" <?php if(!has_Droit($idUser, "Finances Generales")){echo 'hidden';}else{echo "";} ?> ><a  href="listeProjet.php" title="Voir le collège pastoral" class="col-white">
                                    <span class="col-white">Liste des projets</span>
                                </a>                          
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle col-white">
                            <i class="material-icons col-orange">supervisor_account</i>
                            <span class="col-white">Colège pastoral</span>
                        </a>
                        <ul class="ml-menu">
                                <li  class="sub" <?php if(!has_Droit($idUser, "Afficher le college")){echo 'hidden';}else{echo "";} ?> ><a  href="collegePastoral.php" title="Voir le collège pastoral" class="col-white">
                                        <span class="col-white">Voir collège</span>
                                    </a>                          
                                </li>
                                <li  class="sub" <?php if(!has_Droit($idUser, "Creer un pasteur") || (date('Y') != $annee)){echo 'hidden';}else{echo "";} ?> ><a  href="nouveauPasteur.php" title="Enregistrement d'un nouveau pasteur" class="col-white">
                                        <span class="col-white">Nouveau pasteur</span>
                                    </a>                          
                                </li>
                                <li  class="sub" <?php if(!has_Droit($idUser, "Lister pasteurs")){echo 'hidden';}else{echo "";} ?> ><a  href="listePasteurs.php" title="Liste de tous les pasteurs" class="col-white">
                                        <span class="col-white">Liste des pasteurs</span>
                                    </a>                          
                                </li>
                        </ul>
                    </li>
                    
                    <li class="font-bold sub">
                        <a href="statistiques.php">
                        <i class="material-icons col-white">poll</i>
                            <span class="col-white">Statistiques</span>
                        </a>
                    </li>
                </ul>
            </div>
                    
                    
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
        <!-- Right Sidebar -->
        <aside id="rightsidebar" class="right-sidebar">
            <ul class="nav nav-tabs tab-nav-right" role="tablist">
                <li role="presentation" class="active"><a href="#skins" data-toggle="tab">THEMES</a></li>
               
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active in active" id="skins">
                    <ul class="demo-choose-skin">
                        <li data-theme="brown" class="active">
                            <div class="brown"></div>
                            <span>brown</span>
                        </li>
                        <li data-theme="cyan">
                            <div class="cyan"></div>
                            <span>cyan</span>
                        </li>
                        <li data-theme="red" >
                            <div class="red"></div>
                            <span>Red</span>
                        </li>
                        <li data-theme="pink">
                            <div class="pink"></div>
                            <span>Pink</span>
                        </li>
                        <li data-theme="purple">
                            <div class="purple"></div>
                            <span>Purple</span>
                        </li>
                        <li data-theme="deep-purple">
                            <div class="deep-purple"></div>
                            <span>Deep Purple</span>
                        </li>
                        <li data-theme="indigo">
                            <div class="indigo"></div>
                            <span>Indigo</span>
                        </li>
                        <li data-theme="blue">
                            <div class="blue"></div>
                            <span>Blue</span>
                        </li>
                        <li data-theme="light-blue">
                            <div class="light-blue"></div>
                            <span>Light Blue</span>
                        </li>
                        <li data-theme="cyan">
                            <div class="cyan"></div>
                            <span>Cyan</span>
                        </li>
                        <li data-theme="teal">
                            <div class="teal"></div>
                            <span>Teal</span>
                        </li>
                        <li data-theme="green">
                            <div class="green"></div>
                            <span>Green</span>
                        </li>
                        <li data-theme="light-green">
                            <div class="light-green"></div>
                            <span>Light Green</span>
                        </li>
                        <li data-theme="lime">
                            <div class="lime"></div>
                            <span>Lime</span>
                        </li>
                        <li data-theme="yellow">
                            <div class="yellow"></div>
                            <span>Yellow</span>
                        </li>
                        <li data-theme="amber">
                            <div class="amber"></div>
                            <span>Amber</span>
                        </li>
                        <li data-theme="orange">
                            <div class="orange"></div>
                            <span>Orange</span>
                        </li>
                        <li data-theme="deep-orange">
                            <div class="deep-orange"></div>
                            <span>Deep Orange</span>
                        </li>
                        <li data-theme="brown">
                            <div class="brown"></div>
                            <span>Brown</span>
                        </li>
                        <li data-theme="grey">
                            <div class="grey"></div>
                            <span>Grey</span>
                        </li>
                        <li data-theme="blue-grey">
                            <div class="blue-grey"></div>
                            <span>Blue Grey</span>
                        </li>
                        <li data-theme="black">
                            <div class="black"></div>
                            <span>Black</span>
                        </li>
                    </ul>
                </div>
              
            </div>
        </aside>
        <!-- #END# Right Sidebar -->
    </section>