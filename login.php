
<?php
  
    require_once('includes/connexionDefault.php');
    try{
        /*

    $annee_encours = date('Y');
    $annee_suivante = 0;
    $annee = 2017;
    
    $year = $root->prepare("SELECT MAX(annee) as annee FROM base");
    $year->execute();

     $yearAll = $root->prepare("SELECT DISTINCT annee FROM base ORDER BY annee DESC");
     $yearAll->execute();
     $yearAllPlus=$yearAll->fetchAll(PDO::FETCH_OBJ);

    while ($p=$year->fetch(PDO::FETCH_OBJ)) {
        $annee_suivante = $p->annee;
    }

    if($annee_encours <= $annee_suivante){

        $annee = $annee_encours;

    }else{

        $annee = $annee_encours - 1;
    }

  

    */
    $message = "";

    $parametre = null;
    $conn = new PDO('mysql:host=localhost;dbname=paroise', 'root', '');
    $parametres = $conn->prepare("SELECT sigle, siege from parametre where idparametre = 1");
    $parametres->execute();

    while ($x=$parametres->fetch(PDO::FETCH_OBJ)) {
        
        $parametre = $x;
    }

    if(isset($_POST['submit'])){
        if($_POST['login'] && $_POST['password'] && $_POST['annee']){ 
                $conn = new PDO('mysql:host=localhost;dbname=paroise', 'church', 'church');
    $parametres = $conn->prepare("SELECT sigle, siege from parametre where idparametre = 1");
    $parametres->execute();
            
            session_start();

            $_SESSION['annee'] = $_POST['annee'];

            require_once('includes/connexionbd.php');  

            $login = addslashes($_POST['login']);
            $pass = md5(addslashes($_POST['password']));
            $select = $db->prepare("SELECT idutilisateur from utilisateur where login = '$login'and password = '$pass' and lisible = true limit 1");
            $select->execute();
            $result=$select->fetch(PDO::FETCH_OBJ);
            if ($result) {
                # code...
            
            $idLogin=$result->idutilisateur;
            //$idLogin = 0;

            // while($result = $select->fetch(PDO::FETCH_OBJ)){

            //     echo $idLogin  = $result->idutilisateur;

            // }


            if($idLogin){
                
                global $db;
                $insert = $db->prepare("INSERT INTO `connexion`(`dateConnexion`, `heureDebut`, `heureFin`, `lisible`, `utilisateur_idutilisateur`) VALUES (?,?,?,?,?)");
                    $insert->execute(array(date('Y-m-d'),date('H:i:s'), '', 1, $idLogin));

                // la requete qui permet de reourner l'identifiant de la derniere connexion
                $last = $db->query("SELECT last_insert_id() as idconnect from connexion");
                    $resultLast=$last->fetch(PDO::FETCH_OBJ);
                    
                    $_SESSION['idconnect']=$resultLast->idconnect;
                    $_SESSION['login'] = $idLogin;
                    $_SESSION['parametres'] = $parametre;


                    header('Location:index.php');
            }else{

                $message = "Vous n'avez pas tapé les bons paramètres";
            }
        }else{

                $message = "Vous n'avez pas tapé les bons paramètres";
            }
        }
    }   
}catch(Exception $ex){

    header('Location:404.php?error="0"');
}
   

?>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?php echo $parametre->sigle; ?>| <?php echo $parametre->siege; ?></title>
    <link rel="shortcut icon" href="img/bg-1.jpg">


    <!-- Google Fonts -->
    <link href="css/fonts.googleapis.com/css_family_Roboto_400_700&subset_latin_cyrillic_ext.css" rel="stylesheet" type="text/css">
    <link href="css/fonts.googleapis.comiconfamily_Material_Icons.css" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="css/style.css" rel="stylesheet">
</head>
<style type="text/css">

  /*pour l(image de fond du login*/
  .login-img3-body{
  background: url('img/bg-1.jpg') no-repeat center center fixed; 
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}
    
</style>

<body class="login-page login-img3-body">
    <div class="login-box">
        <br>
        <br>
        <div class="logo">
            <a href="javascript:void(0);" style="font-size:4em; color:white;  text-align: center; "><?php echo $parametre->sigle." ".$parametre->siege;?></a>
        </div>
        <div class="card">
            <div class="body">
                <form id="sign_in" method="POST">
                    <center><i class="msg material-icons">lock</i></center>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="login" placeholder="Nom d'utilisateur" required="" autofocus>
                        </div>
                    </div>
                    <br>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" name="password"  class="form-control" placeholder="Mot de passe" required />
                        </div>
                    </div><br>
                    <div class="input-group">
                         <span class="input-group-addon">
                            <i class="material-icons">check</i>
                        </span>
                           
                                <div class="form-line">
                                    <select class="form-control" name="annee" required>

                                        <option selected value="<?php echo date('Y');?>"><?php echo date('Y');?></option>
                                            <?php 
                                                $var = array();
                                                $cont=0;
                                                foreach ($yearAllPlus as $anne) {
                                                    $var[$cont]= $anne->annee;
                                                    $catrine = $var[$cont];
                                                        if($catrine >= date('Y') )
                                                        {

                                                        }
                                                        else {
                                                            echo '<option value="'.$catrine.'">'.$catrine.'</option>';
                                                        }
                                                        
                                                    $cont++;
                                                
                                                } 
                                                 
                                                
                                            ?>

                                    </select>
                                </div>
                    </div>
                    <br>
                    <div class="row" style='margin-bottom: -30px;'>
                        <div class="col-xs-12">
                            <button class="btn btn-block bg-blue waves-effect" type="submit" name="submit" style="font-size: 2rem;">Connexion</button>
                        </div>
                        <div class="col-xs-12">
                        <div style="color:red; font-weight:bold; font-size:1.2em; text-align:center;" class="col-xs-12"><?php if($message){echo $message;}  ?></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="plugins/node-waves/waves.js"></script>

    <!-- Validation Plugin Js -->
    <script src="plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/examples/sign-in.js"></script>
</body>

</html>