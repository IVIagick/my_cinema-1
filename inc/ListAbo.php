<?php 
if(!isset($_SESSION['id_job'])) {
	header('Location: 404.php');
}
?>
	<span class="btn btn-primary add" onClick="addabo('add');" >Ajouter un abonnement</span>
	<?php 
	// Suppression
	if(!empty($_GET['del']) && isset($_SESSION['id_job']))
	{
		$verif = verifTable($bdd, "tp_abonnement", $_GET['del'] , "id_abo");
		if($verif == 1 && preg_match("/-/", $_GET['del']) == 0)
		{
			delTable($bdd, "tp_abonnement" , $_GET['del'], "id_abo");
			echo "<div class=\"alert alert-success\" >
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Succès :</strong> L'abonnement a été supprimé avec succès.
				</div>";
		}
		else
		{
			echo "<div class=\"alert alert-error\" >
   				<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    			<strong>Erreur :</strong> Cet abonnement n'existe pas.
			</div>";
		}
	}
	?>
	<div class="contain" id="add" style="<?php if(isset($_POST['bouton-add'])){ echo "display:block"; }else{ echo "display:none"; }?>">
		<form method="POST">

		<?php
		// Ajout
if(isset($_POST['bouton-add']) && isset($_SESSION['id_job']))
{

	if(isset($_POST['nom']) && isset($_POST['resum']) && isset($_POST['prix']) && isset($_POST['duree']) )
	{
		$nom= htmlentities($_POST['nom']);
		$verif = verifTable($bdd, "tp_abonnement", $nom , "nom");
		if($verif == 0)
		{	
			$resum= htmlentities($_POST['resum']);
			$prix= abs(intval($_POST['prix']));
			$duree= abs(intval($_POST['duree']));
			addAbo($bdd, $nom, $resum, $prix, $duree);
			echo "<div class=\"alert alert-success\" >
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Succès :</strong> L'abonnement a été ajouté avec succès.
				</div>";
		}
		else
		{
			echo "<div class=\"alert alert-error\" >
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Erreur :</strong> Cet abonnement existe déjà.
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

$abo_list = getTable($bdd, "tp_abonnement", $page, $tri, $_SESSION['limit'] ); // Récupération de la liste des abonnements

?>

			<label for="nom">Nom * :</label>
				<input type="text" name="nom" id="nom" placeholder="Entrez un nom" required value="<?php if(isset($_POST['nom'])){ echo htmlentities($_POST['nom']); } ?>" >
			<br>
			<label for="resum">Résumé *: </label>
				<textarea rows="10" cols="80" id="resum" name="resum" placeholder="Résumé du film" required ><?php if(isset($_POST['resum'])){ echo htmlentities($_POST['resum']); } ?></textarea>
			<br>
			<label for="prix">Prix * : </label>
				<input type="number" min="0" id="prix" max="500" name="prix" placeholder="Prix (€)" required value="<?php if(isset($_POST['prix'])){ echo htmlentities($_POST['prix']); } ?>"> 
			<br>
			<label for="duree">Durée * : </label>
				<input type="number" min="0" id="duree" max="500" name="duree" placeholder="Durée (jours)" required value="<?php if(isset($_POST['duree'])){ echo htmlentities($_POST['duree']); } ?>">
			<br>
			<em>Champs obligatoires * </em>
			<input type="submit" value="Ajouter" name="bouton-add">
		</form>
	</div>

<h5>Listes des abonnements</h5>
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
		<th>Resum</th>
		<th>Prix</th>
		<th>Durée Abonnement</th>
		<th>Actions</th>
	</tr>
<?php

$i = 0;
foreach($abo_list as $val)
{
	if($i%2 == 0) { echo "<tr class=\"gris\">"; } else { echo "<tr>"; }
	echo "<td>";
	echo $val['nom'];
	echo "</td>";
	echo "<td>";
	echo $val['resum'];
	echo "</td>";
	echo "<td>";
	echo $val['prix'] . " €";
	echo "</td>";
	echo "<td>";
	echo $val['duree_abo'];
	echo "</td>";
	echo "<td>";
	echo "<a href=\"#\" title=\"\"><i class='icon-pencil' ></i></a>
		<a href=\"#\" title=\"\"><i class='icon-list-alt unset-button'></i></a>
		<a href=\"index.php?file=ListAbo&amp;del=" . $val['id_abo'] . "\" title=\"\"><i class='icon-remove' ></i></a>";
	echo "</td>";
	echo "</tr>";
	$i++;
}

$page_nb = round(countTable($bdd, "tp_abonnement") / $_SESSION['limit']);
?>
</table>

<div class="pagination">
  <ul>
<?php

if($page > 0) {
	$i = $page -1;
	echo "<li><a href=\"index.php?file=ListAbo&amp;page=0\">&laquo;</a></li>";
 	echo "<li><a href=\"index.php?file=ListAbo&amp;page=" . $i . "\">&lsaquo;</a></li>"; 
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
			echo "<li class=\"active\"><a href=\"index.php?file=ListAbo&amp;page=" . $i . "\">$i</a></li>";
		}
		else
		{
			echo "<li><a href=\"index.php?file=ListAbo&amp;page=" . $i . "\">$i</a></li>";
		}
	}
	else
	{
		if($i == $page + 3)
		{
			echo "<li class=\"disabled\"><a href=\"index.php?file=ListAbo&amp;page=" . $i . "\">...</a></li>";
		}
		else if($i == 2 && $page > 4)
		{
			echo "<li class=\"disabled\"><a href=\"index.php?file=ListAbo&amp;page=" . $i . "\">...</a></li>";
		}	
	}
	
}

if($page != $page_nb) 
{
	$i = $page +1;
	echo "<li><a href=\"index.php?file=ListAbo&amp;page=" . $i . "\">&rsaquo;</a></li>";
	echo "<li><a href=\"index.php?file=ListAbo&amp;page=$page_nb\">&raquo;</a></li>";
}
else
{
	echo "<li class=\"disabled\"><a href=\"index.php?file=ListAbo&amp;page=$page_nb\">&raquo;</a></li>";
}

?>
  </ul>
</div>