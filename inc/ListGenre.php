<?php if(isset($_SESSION['id_job'])) { ?><span class="btn btn-primary add" onClick="addabo('add');" >Ajouter un genre</span> <?php } ?>
<?php if(isset($_SESSION['id_job'])) { 
// Suppression
if(!empty($_GET['del']) || (isset($_GET['del']) && $_GET['del'] == 0) )
{
	$verif = verifTable($bdd, "tp_genre", $_GET['del'] , "id_genre");
	if($verif == 1 && preg_match("/-/", $_GET['del']) == 0)
	{
			delTable($bdd, "tp_genre" , $_GET['del'], "id_genre");
			echo "<div class=\"alert alert-success\" >
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Succès :</strong> Le genre a été supprimé avec succès.
				</div>";
	}
	else
	{
		echo "<div class=\"alert alert-error\" >
   				<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    			<strong>Erreur :</strong> Ce genre n'existe pas.
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

	if(isset($_POST['nom']))
	{
		$nom= htmlentities($_POST['nom']);
		$verif = verifTable($bdd, "tp_genre", $_POST['nom'] , "nom");
		if($verif == 0)
		{
			addGenre($bdd, $nom);
			echo "<div class=\"alert alert-success\" >
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Succès :</strong> Le genre a été ajouté avec succès.
				</div>";
		}
		else
		{
			echo "<div class=\"alert alert-error\" >
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Erreur :</strong> Ce genre existe déjà.
				</div>";
		}
	}
	else
	{
		echo "<div class=\"alert alert-error\" >
   				<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    			<strong>Erreur :</strong> Vous n'avez pas indiquer de genre.
			</div>";
	}
}
}
$genre_list = getTable($bdd, "tp_genre", $page, $tri, $_SESSION['limit'] ); // Récupération de la liste de films

?>
			<?php if(isset($_SESSION['id_job'])) { ?><label for="nom">Genre :</label>
				<input type="text" name="nom" id="nom" placeholder="Entrez un genre" required value="<?php if(isset($_POST['nom'])){ echo $_POST['nom']; } ?>" >
			<br><input type="submit"  value="Ajouter" name="bouton-add">
		</form>
	</div><?php } ?>

<h5>Listes des genres</h5>
<div class="nb-result">
	<form method="POST">
		<label for="limit">Nombre de résulat par page :</label>
		<input type="number" id="limit" class="input-small" name="limit" placeholder="25" min="20" max="500" value="<?php if(isset($_SESSION['limit'])){ echo $_SESSION['limit']; } ?>">
		<button type="submit" class="btn"><i class="icon-ok"></i></button>
	</form>
</div>
<table>
	<tr>
		<th>Nom</th>
		<?php if(isset($_SESSION['id_job'])) { ?><th>Actions</th><?php } ?>
	</tr>
<?php

$i = 0;
foreach($genre_list as $val)
{
	if($i%2 == 0) { echo "<tr class=\"gris\">"; } else { echo "<tr>"; }
	echo "<td>";
	echo $val['nom'];
	echo "</td>";
	if(isset($_SESSION['id_job'])) { 
	echo "<td>";
	echo "<a href=\"#\" title=\"\"><i class='icon-pencil' ></i></a>
		<a href=\"#\" title=\"\"><i class='icon-list-alt unset-button'></i></a>
		<a href=\"index.php?file=ListGenre&amp;del=" . $val['id_genre'] . "\" title=\"\"><i class='icon-remove' ></i></a>";
	echo "</td>";
	}
	echo "</tr>";
	$i++;
}

$page_nb = round(countTable($bdd, "tp_genre") / $_SESSION['limit']);
?>
</table>

<div class="pagination">
  <ul>
<?php

if($page > 0) {
	$i = $page -1;
	echo "<li><a href=\"index.php?file=ListGenre&amp;page=0\">&laquo;</a></li>";
 	echo "<li><a href=\"index.php?file=ListGenre&amp;page=" . $i . "\">&lsaquo;</a></li>"; 
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
			echo "<li class=\"active\"><a href=\"index.php?file=ListGenre&amp;page=" . $i . "\">$i</a></li>";
		}
		else
		{
			echo "<li><a href=\"index.php?file=ListGenre&amp;page=" . $i . "\">$i</a></li>";
		}
	}
	else
	{
		if($i == $page + 3)
		{
			echo "<li class=\"disabled\"><a href=\"index.php?file=ListGenre&amp;page=" . $i . "\">...</a></li>";
		}
		else if($i == 2 && $page > 4)
		{
			echo "<li class=\"disabled\"><a href=\"index.php?file=ListGenre&amp;page=" . $i . "\">...</a></li>";
		}	
	}
	
}

if($page != $page_nb) 
{
	$i = $page +1;
	echo "<li><a href=\"index.php?file=ListGenre&amp;page=" . $i . "\">&rsaquo;</a></li>";
	echo "<li><a href=\"index.php?file=ListGenre&amp;page=$page_nb\">&raquo;</a></li>";
}
else
{
	echo "<li class=\"disabled\"><a href=\"index.php?file=ListGenre&amp;page=$page_nb\">&raquo;</a></li>";
}

?>
  </ul>
</div>