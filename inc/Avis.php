<?php

if(!isset($_SESSION['id_job'])) {
	header('Location: 404.php');
}
if(empty($_GET['avis']) || verifTable($bdd, "tp_historique_membre", $_GET['avis'] , "id") == 0 )
{
	header('Location: index.php?file=ListMembres');
}
$id_avis = abs(intval($_GET['avis']));
$films_list = getTableAll2($bdd, "tp_historique_membre", $id_avis , "id", "id", "tp_film", "id_film", "id_film");

if(isset($_POST['bouton-add-avis']))
{
	$_POST['add-avis'] = htmlentities($_POST['add-avis']);
	editavis($bdd, $_POST['add-avis'], $id_avis);
}

$films_list = getTableAll2($bdd, "tp_historique_membre", $id_avis , "id", "id", "tp_film", "id_film", "id_film");

if(file_exists("../upload/img/film" . $films_list[0]['id_film'] . ".png"))
{
   $img = "<img src=\"upload/img/film" . $films_list[0]['id_film'] . ".png\" alt=\"avatar\">";
}
else
{
   $img = "<img src=\"img/filmdefaut.png\" alt=\"avatar\">";
}

?><div class="sep"></div>
<div class="avis-bloc">
	<h5>Avis concernant le film <a href="index.php?file=Film&amp;id=<?php echo $films_list[0]['id_film']; ?>"><?php echo $films_list[0]['titre']; ?></a> par <a href="index.php?file=Profil&amp;id=<?php echo $films_list[0]['id_membre']; ?>"><?php echo checkTable($bdd, "tp_fiche_personne", "nom" , $films_list[0]['id_membre'], "id_perso"); ?></a></h5>
	<div class="avis-left">
		<?php echo $img; ?>
	</div>
	<div class="avis-right">
		<div class="info-left">
			<p><strong>Nom du membre :</strong></p>
			<p><strong>Film vu :</strong></p>
			<p><strong>Date de visionnage :</strong></p>
			<p><strong>Avis sur le film :</strong></p>
		</div>
		<div class="info-right">
		<p> <?php echo checkTable($bdd, "tp_fiche_personne", "nom" , $films_list[0]['id_membre'], "id_perso"); ?></p>
		<p><?php echo $films_list[0]['titre']; ?></p>
		<p><?php echo $films_list[0]['date']; ?></p>
		<p><div class="avis-em" id="bloc-em"  onClick="avis('bloc-text','avis-em');"><?php echo "<em id=\"avis-em\">‟" . $films_list[0]['avis'] ."”</em>"; ?>
			<div id="bloc-text" style="display:none">
				<form method="POST" id="form-avis">
				<textarea name="add-avis"  id="add-avis"  placeholder="Ajouter un avis"><?php if(isset($films_list[0]['avis'])) { echo $films_list[0]['avis']; }?></textarea>
				<input type="submit" class="btn btn-primary" name="bouton-add-avis" value="Modifier">
			</form>
			</div>
		</div></p>
		</div>
	</div>
	<div style="clear:both"></div>
</div>