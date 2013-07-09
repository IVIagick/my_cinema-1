<?php session_start();
if(isset($_SESSION['mail'])) {
	header('Location: index.php');
}
require_once("inc/config.php");
require_once("inc/db.php");
require_once("inc/functions.php");

if(isset($_POST['bouton-connexion'])){
	if(isset($_POST['mail']) && isset($_POST['password']))
	{
		$password = MD5(SHA1($_POST['password']));
		if(Connexion($bdd, htmlentities($_POST['mail']), $password) == 1)
		{
			$_SESSION['mail'] =  htmlentities($_POST['mail']);
			$mail = checkTable($bdd, "tp_fiche_personne", "id_perso", $_POST['mail'] , "email");
			if(verifTable($bdd, "tp_personnel", $mail , "id_fiche_perso") == 1)
			{
				$id_job = checkTable($bdd, "tp_personnel", "id_job", $mail , "id_fiche_perso");
				if($id_job >= 2)
				{
					$_SESSION['id_job'] = $id_job;
				}

			}
			header('Location: index.php');
		}
		else
		{
			$error =  "<div class=\"alert alert-error\" >
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Erreur :</strong> Utilisateur ou mot de passe incorrect.
				</div>";
		}

	}
	else
	{
		$error = "<div class=\"alert alert-error\" >
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Erreur :</strong> Merci de remplir tous les champs.
				</div>";
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>My_cinema rivier_n - Connexion</title>
		<meta name="author" content="rivier_n">
		<meta charset="utf-8" />
		<link rel="icon" type="image/x-icon" href="img/favicon.ico" />
		<link rel="stylesheet" href="css/bootstrap.css" />
		<link rel="stylesheet" href="css/style.css" />
		<link rel="stylesheet" href="css/connexion.css" />
	</head>
	<body>
		<header><a href="index.php"><img src="img/logo.png" alt=""></a></header>
		<div class="searchbar">
		</div>
		<div class="menu ">
			<div class="sub-menu">
				<h5> Connexion à votre compte client</h5>
			</div>
			<?php if(isset($error)){ echo $error; } ?>
			<div class="body-complete">
				<form method="POST">
					<input type="text" id="mail" name="mail" required placeholder="Votre e-mail">
					<input type="password" id="password" name="password" required placeholder="Votre mot de passe">
					<input type="submit" id="bouton-connexion" class="btn btn-primary" name="bouton-connexion" value="Connexion">
				</form>
					<p id="where-id-p"><a href="#" id="where-id" onClick=" addabo('info-id')">Où trouver mes identifiants?</a></p>
					<div id="info-id" style="display:none;">
						<p>Pour vous connecter, vous devez être un client des cinemas "myCINEMA".
							Vous trouverez vos identifiants au dos de votre carte d'abonnement dans l'encadré "Se connecter sur le site myCINEMA".</p>
					</div>
			</div>
		</div>

			<script src="js/jquery.js"></script>
			<script type="text/javascript" src="js/bootstrap.js"></script>
			<script type="text/javascript" src="js/script.js"></script>