<?php
 
if(!isset($_SESSION['id_job'])) {
   header('Location: 404.php');
}
?>
<span class="btn btn-primary add" onClick="addabo('add');" >Ajouter une distribution</span>
<?php
// Suppression
if(!empty($_GET['del']) && isset($_SESSION['id_job']))
{
	$verif = verifTable($bdd, "tp_distrib", $_GET['del'] , "id_distrib");
	if($verif == 1 && preg_match("/-/", $_GET['del']) == 0)
	{
			delTable($bdd, "tp_distrib" , $_GET['del'], "id_distrib");
			echo "<div class=\"alert alert-success\" >
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Succès :</strong> La distribution a été supprimé avec succès.
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

?>
<div class="contain" id="add" style="<?php if(isset($_POST['bouton-add'])){ echo "display:block"; }else{ echo "display:none"; }?>">
		<form method="POST">

		<?php


// Ajout
if(isset($_POST['bouton-add']) && isset($_SESSION['id_job']))
{

	if(!empty($_POST['nom']) && isset($_POST['telephone']))
	{
		$nom= htmlentities($_POST['nom']);
		$verif = verifTable($bdd, "tp_distrib", $_POST['nom'] , "nom");
		if($verif == 0)
		{
			if(preg_match("/^[0-9]{10}$/",$_POST['telephone']))
			{
				$telephone= htmlentities($_POST['telephone']);
				$adresse= htmlentities($_POST['adresse']);
				$cpostal= htmlentities($_POST['cpostal']);
				$ville= htmlentities($_POST['ville']);
				$pays= htmlentities($_POST['pays']);
				addDistrib($bdd, $nom, $telephone, $adresse, $ville, $pays, $cpostal);
				echo "<div class=\"alert alert-success\">
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Succès :</strong> La distribution a été ajoutée avec succès.
				</div>";
			}
			else
			{
				echo "<div class=\"alert alert-error\">
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Erreur :</strong> Le téléphone n'est pas valide.
				</div>";
			}
		}
		else
		{
			echo "<div class=\"alert alert-error\" >
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Erreur :</strong> Cette distribution existe déjà.
				</div>";
		}
	}
	else
	{
		echo "<div class=\"alert alert-error\">
   				<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    			<strong>Erreur :</strong> Merci de remplir les champs obligatoires.
			</div>";
	}
}

$distrib_list = getTable($bdd, "tp_distrib", $page, $tri, $_SESSION['limit'] ); // Récupération de la liste de films

?>

			<label for="nom">Nom * :</label>
				<input type="text" name="nom" id="nom" placeholder="Entrez un nom" required value="<?php if(isset($_POST['nom'])){ echo $_POST['nom']; } ?>">
			<br>
			<label for="telephone">Telephone *: </label>
				<input type="tel" name="telephone" id="telephone" placeholder="Entrez un telephone" required  pattern="^[0-9]{10}$" value="<?php if(isset($_POST['telephone'])){ echo $_POST['telephone']; } ?>">
			<br>
			<label for="adresse">Adresse : </label>
				<input type="text" name="adresse" id="adresse" placeholder="Entrez une adresse" value="<?php if(isset($_POST['adresse'])){ echo $_POST['adresse']; } ?>">
			<br>
			<label for="cpostal">Code postal : </label>
				<input type="number" max="99999" min="00000" name="cpostal" id="cpostal" placeholder="Entrez un code postal" value="<?php if(isset($_POST['cpostal'])){ echo $_POST['cpostal']; } ?>">
			<br>
			<label for="ville">Ville : </label>
				<input type="text" name="ville" id="ville" placeholder="Entrez une ville" value="<?php if(isset($_POST['ville'])){ echo $_POST['ville']; } ?>">
			<br>
			<label for="pays">Pays : </label>
				<input type="text" name="pays" id="pays" placeholder="Entrez un pays" value="<?php if(isset($_POST['pays'])){ echo $_POST['pays']; } ?>">
			<br>
			<em>Champs obligatoires * </em>
			<input type="submit" value="Ajouter" name="bouton-add">
		</form>
	</div>

<h5>Listes des distributions</h5>
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
		<th>Telephone</th>
		<th>Adresse</th>
		<th>Code Postal</th>
		<th>Ville</th>
		<th>Pays</th>
		<th>Actions</th>
	</tr>
<?php

$i = 0;
foreach($distrib_list as $val)
{
	if($i%2 == 0) { echo "<tr class=\"gris\">"; } else { echo "<tr>"; }
	echo "<td>";
	echo $val['nom'];
	echo "</td>";
	echo "<td>";
	echo $val['telephone'];
	echo "</td>";
	echo "<td>";
	echo $val['adresse'];
	echo "</td>";
	echo "<td>";
	echo $val['cpostal'];
	echo "</td>";
	echo "<td>";
	echo $val['ville'];
	echo "</td>";
	echo "<td>";
	echo $val['pays'];
	echo "</td>";
	echo "<td>";
	echo "<a href=\"#\" title=\"\"><i class='icon-pencil' ></i></a>
		<a href=\"#\" title=\"\"><i class='icon-list-alt unset-button'></i></a>
		<a href=\"index.php?file=ListDistrib&amp;del=" . $val['id_distrib'] . "\" title=\"\"><i class='icon-remove' ></i></a>";
	echo "</td>";
	echo "</tr>";
	$i++;
}


$page_nb = round(countTable($bdd, "tp_distrib") / $_SESSION['limit']);
?>
</table>

<div class="pagination">
  <ul>
<?php

if($page > 0) {
	$i = $page -1;
	echo "<li><a href=\"index.php?file=ListDistrib&amp;page=0\">&laquo;</a></li>";
 	echo "<li><a href=\"index.php?file=ListDistrib&amp;page=" . $i . "\">&lsaquo;</a></li>"; 
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
			echo "<li class=\"active\"><a href=\"index.php?file=ListDistrib&amp;page=" . $i . "\">$i</a></li>";
		}
		else
		{
			echo "<li><a href=\"index.php?file=ListDistrib&amp;page=" . $i . "\">$i</a></li>";
		}
	}
	else
	{
		if($i == $page + 3)
		{
			echo "<li class=\"disabled\"><a href=\"index.php?file=ListDistrib&amp;page=" . $i . "\">...</a></li>";
		}
		else if($i == 2 && $page > 4)
		{
			echo "<li class=\"disabled\"><a href=\"index.php?file=ListDistrib&amp;page=" . $i . "\">...</a></li>";
		}	
	}
	
}

if($page != $page_nb) 
{
	$i = $page +1;
	echo "<li><a href=\"index.php?file=ListDistrib&amp;page=" . $i . "\">&rsaquo;</a></li>";
	echo "<li><a href=\"index.php?file=ListDistrib&amp;page=$page_nb\">&raquo;</a></li>";
}
else
{
	echo "<li class=\"disabled\"><a href=\"index.php?file=ListDistrib&amp;page=$page_nb\">&raquo;</a></li>";
}

?>
  </ul>
</div>