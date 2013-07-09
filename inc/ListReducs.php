
<span class="btn btn-primary add" onClick="addabo('add');" >Ajouter une réduction</span>
<?php if(isset($_SESSION['id_job'])) { ?>
<?php
// Suppression
if(!empty($_GET['del']) && isset($_SESSION['id_job']))
{
	$verif = verifTable($bdd, "tp_reduction", $_GET['del'] , "id_reduction");
	if($verif == 1 && preg_match("/-/", $_GET['del']) == 0)
	{
			delTable($bdd, "tp_reduction" , $_GET['del'], "id_reduction");
			echo "<div class=\"alert alert-success\" >
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Succès :</strong> La réduction a été supprimée avec succès.
				</div>";
	}
	else
	{
		echo "<div class=\"alert alert-error\" >
   				<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    			<strong>Erreur :</strong> Cette réduction n'existe pas.
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

	if(isset($_POST['nom']) && isset($_POST['date_debut']) && isset($_POST['date_fin']) && isset($_POST['pourcentage_reduc']))
	{
		$nom = htmlentities($_POST['nom']);
		$verif = verifTable($bdd, "tp_reduction", $_POST['nom'] , "nom");
		if($verif == 0)
		{
			$date_debut = htmlentities($_POST['date_debut']);
			$date_fin = htmlentities($_POST['date_fin']);
			$pourcentage_reduc = htmlentities($_POST['pourcentage_reduc']);
			addReducs($bdd, $nom, $date_debut , $date_fin, $pourcentage_reduc);
			echo "<div class=\"alert alert-success\">
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Succès :</strong> La réduction a été ajoutée avec succès.
				</div>";
		}
		else
		{
			echo "<div class=\"alert alert-error\">
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Erreur :</strong> Cette reduction existe déjà.
				</div>";
		}
	}
	else
	{
		echo "<div class=\"alert alert-error\">
   				<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    			<strong>Erreur :</strong> Veuillez remplir tous les champs.
			</div>";
	}
}
}
$reducs_list = getTable($bdd, "tp_reduction", $page, $tri, $_SESSION['limit'] ); // Récupération de la liste de films

?>

			<?php if(isset($_SESSION['id_job'])) { ?><label for="nom">Nom de la réduction * :</label>
				<input type="text" name="nom" id="nom" placeholder="Entrez un nom" required value="<?php if(isset($_POST['nom'])){ echo $_POST['nom']; } ?>">
			<br>
			<label for="date_debut">Date de début *: </label>
				<input type="date"  id="date_debut" name="date_debut" value="<?php if(isset($_POST['date_debut'])){ echo $_POST['date_debut']; } ?>" required>
			<br>
			<label for="date_fin">Date de fin *: </label>
				<input type="date"  id="date_fin" name="date_fin" value="<?php if(isset($_POST['date_fin'])){ echo $_POST['date_fin']; } ?>" required>
			<br>
			<label for="pourcentage_reduc">Pourcentage de la réduction *:</label>
				<input type="number" name="pourcentage_reduc" id="pourcentage_reduc" placeholder="Exemple : 50 ( % )" required value="<?php if(isset($_POST['pourcentage_reduc'])){ echo $_POST['pourcentage_reduc']; } ?>">
			<br>
			<em>Champs obligatoires * </em>
			<input type="submit"  value="Ajouter" name="bouton-add">
		</form>
	</div>
	<?php } ?>

<h5>Listes des réductions</h5>
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
		<th>Date de début</th>
		<th>Date de fin</th>
		<th>Pourcentage</th>
		<?php if(isset($_SESSION['id_job'])) { ?><th>Actions</th><?php } ?>
	</tr>
<?php

$i = 0;
foreach($reducs_list as $val)
{
	if($i%2 == 0) { echo "<tr class=\"gris\">"; } else { echo "<tr>"; }
	echo "<td>";
	echo $val['nom'];
	echo "</td>";
	echo "<td>";
	echo $val['date_debut'];
	echo "</td>";
	echo "<td>";
	echo $val['date_fin'];
	echo "</td>";
	echo "<td>";
	echo $val['pourcentage_reduc'] . "%";
	echo "</td>";
	 if(isset($_SESSION['id_job'])) { 
	echo "<td>";
	echo "<a href=\"#\" title=\"\"><i class='icon-pencil' ></i></a>
		<a href=\"#\" title=\"\"><i class='icon-list-alt unset-button'></i></a>
		<a href=\"index.php?file=ListReducs&amp;del=" . $val['id_reduction'] . "\"><i class='icon-remove' ></i></a>";
	echo "</td>";
	}
	echo "</tr>";
	$i++;
}

$page_nb = round(countTable($bdd, "tp_reduction") / $_SESSION['limit']);
?>
</table>

<div class="pagination">
  <ul>
<?php

if($page > 0) {
	$i = $page -1;
	echo "<li><a href=\"index.php?file=ListReducs&amp;page=0\">&laquo;</a></li>";
 	echo "<li><a href=\"index.php?file=ListReducs&amp;page=" . $i . "\">&lsaquo;</a></li>"; 
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
			echo "<li class=\"active\"><a href=\"index.php?file=ListReducs&amp;page=" . $i . "\">$i</a></li>";
		}
		else
		{
			echo "<li><a href=\"index.php?file=ListReducs&amp;page=" . $i . "\">$i</a></li>";
		}
	}
	else
	{
		if($i == $page + 3)
		{
			echo "<li class=\"disabled\"><a href=\"index.php?file=ListReducs&amp;page=" . $i . "\">...</a></li>";
		}
		else if($i == 2 && $page > 4)
		{
			echo "<li class=\"disabled\"><a href=\"index.php?file=ListReducs&amp;page=" . $i . "\">...</a></li>";
		}	
	}
	
}

if($page != $page_nb) 
{
	$i = $page +1;
	echo "<li><a href=\"index.php?file=ListReducs&amp;page=" . $i . "\">&rsaquo;</a></li>";
	echo "<li><a href=\"index.php?file=ListReducs&amp;page=$page_nb\">&raquo;</a></li>";
}
else
{
	echo "<li class=\"disabled\"><a href=\"index.php?file=ListReducs&amp;page=$page_nb\">&raquo;</a></li>";
}

?>
  </ul>
</div>