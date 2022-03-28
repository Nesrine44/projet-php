
<?php
	$message = "";
	$error = $_GET['error'];

	switch ($error) {
		case 0:
			$message = "Erreur d'authentification, veuillez contacter l'administrateur!";
			break;
		case 1:
			$message = "Impossible de se connecter à la base de données, veuillez contacter l'administrateur!";
			break;
		
		default:
			$message = "Une erreur est survenue, veuillez contacter l'administrateur!";
			break;
	}
	

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">
    <meta name="author" content="GeeksLabs">
    <meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>404 | CHRCH</title>

    <!-- Bootstrap CSS -->    
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- bootstrap theme -->
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <!--external css-->
    <!-- font icon -->
    <link href="css/elegant-icons-style.css" rel="stylesheet" />
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>

  <body>
<div class="page-404">
    <p class="text-404">404</p>

    <h2>Oups!</h2>
    <p><?php echo $message; ?><br><a href="index.php">Retour à l'accueil</a></p>
  </div>

  </body>
</html>
