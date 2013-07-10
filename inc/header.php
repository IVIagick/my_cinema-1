<?php
/*if(isset($_POST['search_bouton']))
{
	if(isset($_POST['date'])){ $date_search = $_POST['date']; }
	$link_search = "?file=Search&amp;searchby=" . $_POST['searchby'] . "&amp;text=" . $_POST['text'];
	header('Location: index.php' . $link_search);
}*/
?>
<!DOCTYPE html>
<html>
	<head>
		<title>My_cinema rivier_n</title>
		<meta name="author" content="rivier_n">
		<meta charset="utf-8" />
		<link rel="icon" type="image/x-icon" href="img/favicon.ico" />
		<link rel="stylesheet" href="css/bootstrap.css" />
		<link rel="stylesheet" href="css/style.css" />
	</head>
	<body>
		<header><a href="index.php"><img src="img/logo.png" alt=""></a></header>
		<div class="searchbar">
			<div class="container">
				<a href="index.php?file=Profil&amp;id=<?php echo $_SESSION['id']; ?>" class="logout">Mon profil</a> - <a href="logout.php" class="logout">Deconnexion</a>
				<form class="navbar-form pull-left search-bar" method="GET">
					<em style="float:left;">Rechercher un film : </em>
					<select name="searchby" onclick="dateSearch(this.value);" onchange="dateSearch(this.value);" onkeyup="dateSearch(this.value);">
						<option value="0">Non défini</option>
						<option value="1">Nom</option>
						<option value="2">Genre</option>
						<option value="3">Distribution</option>
						<option value="4">Date de projection</option>
					</select>
  					<input class="span2" id="appendedInputButton" name="text" type="text" placeholder="Rechercher">
  					<input class="span2" id="datesearch" name="date" type="date" style="display:none">
  					<input type="hidden" name="file" value="search">
  					<button type="submit" class="btn recherche-btn" name="search_bouton" value="ok"><i class="icon-search"></i></button>
				</form>	
			</div>
		</div>
		<div class="menu container">
			<div class="sub-menu">
				<ul class="nav">
					<li id="l-li" <?php if((isset($_GET['file']) && ($_GET['file'] == 'home' || $_GET['file'] == '')) || empty($_GET['file']) || !isset($_GET['file'])){ echo "class=\"active\""; }?> ><a href="index.php?file=home">Accueil</a></li>
					<li <?php if(isset($_GET['file']) && $_GET['file'] == 'ListFilms'){ echo "class=\"active\""; }?>><a href="index.php?file=ListFilms">Les Films</a></li>
					<?php if(isset($_SESSION['id_job'])) { ?><li <?php if(isset($_GET['file']) && $_GET['file'] == 'ListAbo'){ echo "class=\"active\""; }?>><a href="index.php?file=ListAbo">Les Abonnements</a></li><?php } ?>
					<li <?php if(isset($_GET['file']) && $_GET['file'] == 'ListReducs'){ echo "class=\"active\""; }?>><a href="index.php?file=ListReducs">Les Reductions</a></li>
					<?php if(isset($_SESSION['id_job'])) { ?><li <?php if(isset($_GET['file']) && $_GET['file'] == 'ListDistrib'){ echo "class=\"active\""; }?>><a href="index.php?file=ListDistrib">Les Distributions</a></li><?php } ?>
					<li <?php if(isset($_GET['file']) && $_GET['file'] == 'ListGenre'){ echo "class=\"active\""; }?>><a href="index.php?file=ListGenre">Les Genres</a></li>
					<li id="r-li" <?php if(isset($_GET['file']) && $_GET['file'] == 'ListMembres'){ echo "class=\"active\""; }?>><a href="index.php?file=ListMembres">Les Membres</a></li>
				</ul>
			</div>
			<div class="body-complete">
	