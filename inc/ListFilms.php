
<?php if(isset($_SESSION['id_job'])) { ?><span class="btn btn-primary add" onClick="addabo('add');" >Ajouter un film</span><?php } ?>
<?php if(isset($_SESSION['id_job'])) { ?>
<?php
// Suppression
if(!empty($_GET['del']))
{
	$verif = verifTable($bdd, "tp_film", $_GET['del'] , "id_film");
	if($verif == 1 && preg_match("/-/", $_GET['del']) == 0)
	{
			delTable($bdd, "tp_film" , $_GET['del'], "id_film");
			echo "<div class=\"alert alert-success\" >
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Succès :</strong> Le film a été supprimé avec succès.
				</div>";
	}
	else
	{
		echo "<div class=\"alert alert-error\" >
   				<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    			<strong>Erreur :</strong> Ce film n'existe pas.
			</div>";
	}
}
}
?>
<?php if(isset($_SESSION['id_job'])) { ?><div class="contain" id="add" style="<?php if(isset($_POST['bouton-add'])){ echo "display:block"; }else{ echo "display:none"; }?>">
		<form method="POST">

		<?php
		
		
// Ajout
if(isset($_POST['bouton-add']) && isset($_SESSION['id_job']))
{

	if(!empty($_POST['titre']) && isset($_POST['id_genre']) && isset($_POST['resum']) && !empty($_POST['date_debut']) && !empty($_POST['date_fin']) && isset($_POST['duree']) && !empty($_POST['annee_prod']))
	{
		$id_genre= abs(intval($_POST['id_genre']));
		$id_distrib= abs(intval($_POST['id_distrib']));
		$verif = verifTable($bdd, "tp_genre", $_POST['id_genre'] , "id_genre");
		if($verif == 1)
		{
			$verif2 = verifTable($bdd, "tp_distrib", $_POST['id_distrib'] , "id_distrib");
			if($verif2 == 1 && isset($_POST['id_distrib']))
			{
				$titre= htmlentities($_POST['titre']);
				$resum= htmlentities($_POST['resum']);
				$date_debut= htmlentities($_POST['date_debut']);
				$date_fin= htmlentities($_POST['date_fin']);
				$duree_min= abs(intval($_POST['duree']));
				$annee_prod= htmlentities($_POST['annee_prod']);
				addFilm($bdd, $id_genre, $id_distrib, $titre, $resum, $date_debut, $date_fin, $duree_min, $annee_prod);
				echo "<div class=\"alert alert-success\" >
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Succès :</strong> Le film a été ajouté avec succès.
				</div>";
			}
			else
			{
				echo "<div class=\"alert alert-error\" >
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Erreur :</strong> Cette distribution n'existe pas.
				</div>";
			}
		}
		else
		{
			echo "<div class=\"alert alert-error\" >
   				<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    			<strong>Erreur :</strong> Ce genre n'existe pas.
			</div>";
		}
	}
	else
	{
		echo "<div class=\"alert alert-error\" >
   				<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    			<strong>Erreur :</strong> Merci de remplir les champs obligatoires.
			</div>";
	}
}

}

$films_list = getTable($bdd, "tp_film", $page, $tri, $_SESSION['limit'] ); // Récupération de la liste de films
$counttable = countTable($bdd, "tp_genre");
$genre_list = getTable($bdd, "tp_genre", 0, "id_genre", $counttable ); // Récupération de la liste de genres
$countdistrib = countTable($bdd, "tp_distrib");
$distrib_list = getTable($bdd, "tp_distrib", 0, "id_distrib", $countdistrib ); // Récupération de la liste de distributions

?>	
			<?php if(isset($_SESSION['id_job'])) { ?><label for="titre">Titre du film * :</label>
				<input type="text" name="titre" id="titre" placeholder="Entrez un titre" required value="<?php if(isset($_POST['titre'])){ echo $_POST['titre']; } ?>">
			<br>
			<label for="resum">Résumé *: </label>
				<textarea rows="10" cols="80" id="resum" name="resum" placeholder="Résumé du film" required><?php if(isset($_POST['resum'])){ echo $_POST['resum']; } ?></textarea>
			<br>
			<label for="id_genre">Genre *: </label>
				<select name="id_genre" id="id_genre">
					<?php if(isset($_POST['id_genre'])){ echo "<option value=\"" . $_POST['id_genre'] . "\">" . checkTable($bdd, "tp_genre", "nom", $_POST['id_genre'] , "id_genre") . "</option>"; } ?>
					<?php foreach($genre_list as $val)
					{
						echo "<option value=\"" . $val['id_genre'] . "\">" . $val['nom'] . "</option>";
					} ?>
				</select>
			<br>
			<label for="id_distrib">Distribution: </label>
				<select id="id_distrib" name="id_distrib">
					<?php if(isset($_POST['id_distrib'])){ echo "<option value=\"" . $_POST['id_distrib'] . "\">" . checkTable($bdd, "tp_distrib", "nom", $_POST['id_distrib'] , "id_distrib") . "</option>"; } ?>
					<option value="">Non défini</option>
					<?php foreach($distrib_list as $val)
					{
						echo "<option value=\"" . $val['id_distrib'] . "\">" . $val['nom'] . "</option>";
					} ?>
				</select>
			<br>
			<label for="date_debut">Date de début d'affiche * : </label>
				<input type="date" id="date_debut" name="date_debut" value="<?php if(isset($_POST['date_debut'])){ echo $_POST['date_debut']; } ?>"> 
			<br>
			<label for="date_fin">Date de fin d'affiche * : </label>
				<input type="date" id="date_fin" name="date_fin" value="<?php if(isset($_POST['date_fin'])){ echo $_POST['date_fin']; } ?>"> 
			<br>
			<label for="duree">Duree du film : </label>
				<input type="number" min="0" id="duree" name="duree" placeholder="Durée du film ( minutes )" required value="<?php if(isset($_POST['duree'])){ echo $_POST['duree']; } ?>">
			<br>
			<label for="annee_prod">Annee de production * : </label>
				<input type="number" min="1880" id="annee_prod" max="2020" name="annee_prod" placeholder="Année de production" required value="<?php if(isset($_POST['annee_prod'])){ echo $_POST['annee_prod']; } ?>">
			<br>
			<em>Champs obligatoires * </em>
			<input type="submit"  value="Ajouter" name="bouton-add">
		</form>
	</div><?php } ?>


<h5>Listes des films</h5>
<div class="nb-result">
	<form method="POST">
		<label for="limit">Nombre de résulat par page :</label>
		<input type="number" id="limit" class="input-small" name="limit" placeholder="25" min="20" max="500" value="<?php if(isset($_SESSION['limit'])){ echo $_SESSION['limit']; } ?>">
		<button type="submit" class="btn"><i class="icon-ok"></i></button>
	</form>
</div>
<table>
	<tr>
		<th>Jaquette</th>
		<th>Titre</th>
		<th>Genre</th>
		<th>Distribution</th>
		<th>Durée</th>
		<th>Année</th>
		<th>Date de mise à l'affiche</th>
		<th>Date de fin</th>
		<th class="actions">Actions</th>
	</tr>
<?php

$i = 0;
foreach($films_list as $val)
{
	if(file_exists("upload/img/film" . $val['id_film'] . ".png"))
	{
 		$img = "<img src=\"upload/img/film" . $val['id_film'] . ".png\" alt=\"avatar\">";
	}
	else
	{
 		$img = "<img src=\"img/filmdefaut.png\" alt=\"avatar\">";
	}

	if($i%2 == 0) { echo "<tr class=\"gris\">"; } else { echo "<tr>"; }
	echo "<td class=\"img-film\">";
	echo $img;
	echo "</td>";
	echo "<td>";
	echo $val['titre'];
	echo "</td>";
	echo "<td>";
	echo checkTable($bdd, "tp_genre", "nom" , $val['id_genre'], "id_genre");
	echo "</td>";
	echo "<td>";
	if(!empty($val['id_distrib'])) { echo checkTable($bdd, "tp_distrib", "nom" , $val['id_distrib'], "id_distrib"); } else{ echo "-"; }	
	echo "</td>";
	echo "<td>";
	echo $val['duree_min'];
	echo "</td>";
	echo "<td>";
	echo $val['annee_prod'];
	echo "</td>";
	echo "<td>";
	echo $val['date_debut_affiche'];
	echo "</td>";
	echo "<td>";
	echo $val['date_fin_affiche'];
	echo "</td>";
	echo "<td>";
	echo "<a href=\"index.php?file=Film&amp;id=" . $val['id_film'] . "\" title=\"\"><i class='icon-list-alt unset-button'></i></a>";
	if(isset($_SESSION['id_job'])) { 
		echo "<a href=\"index.php?file=ListFilms&amp;del=" . $val['id_film'] . "\" title=\"\"><i class='icon-remove' ></i></a>";
		}
	echo "</td>";
	echo "</tr>";
	$i++;
}
$page_nb = round(countTable($bdd, "tp_film") / $_SESSION['limit']);
?>
</table>

<div class="pagination">
  <ul>
<?php

if($page > 0) {
	$i = $page -1;
	echo "<li><a href=\"index.php?file=ListFilms&amp;page=0\">&laquo;</a></li>";
 	echo "<li><a href=\"index.php?file=ListFilms&amp;page=" . $i . "\">&lsaquo;</a></li>"; 
}
else
{
	echo "<li class=\"disabled\"><a href=\"#\">&laquo;</a></li>";
}

for($i = 0; $i <= $page_nb; $i++)
{
	if($i == $page_nb || ($i > $page - 3 && $i < $page + 3) || $i <= 1)
	{
		if($i == $page)
		{
			echo "<li class=\"active\"><a href=\"index.php?file=ListFilms&amp;page=" . $i . "\">$i</a></li>";
		}
		else
		{
			echo "<li><a href=\"index.php?file=ListFilms&amp;page=" . $i . "\">$i</a></li>";
		}
	}
	else
	{
		if($i == $page + 3)
		{
			echo "<li class=\"disabled\"><a href=\"index.php?file=ListFilms&amp;page=" . $i . "\">...</a></li>";
		}
		else if($i == 2 && $page > 4)
		{
			echo "<li class=\"disabled\"><a href=\"index.php?file=ListFilms&amp;page=" . $i . "\">...</a></li>";
		}	
	}
	
}

if($page != $page_nb) 
{
	$i = $page +1;
	echo "<li><a href=\"index.php?file=ListFilms&amp;page=" . $i . "\">&rsaquo;</a></li>";
	echo "<li><a href=\"index.php?file=ListFilms&amp;page=$page_nb\">&raquo;</a></li>";
}
else
{
	echo "<li class=\"disabled\"><a href=\"index.php?file=ListFilms&amp;page=$page_nb\">&raquo;</a></li>";
}

?>
  </ul>
</div>