<?php

if(empty($_GET['id']) || verifTable($bdd, "tp_film", $_GET['id'] , "id_film") == 0) {  
      header('Location: index.php?file=404');
}

$id = abs(intval($_GET['id']));
$film_info = getInfo($bdd, "tp_film", "id_film", $id);
$genre = checkTable($bdd, "tp_genre", "nom" , $film_info['id_genre'], "id_genre");  
$distrib = checkTable($bdd, "tp_distrib", "nom" , $film_info['id_distrib'], "id_distrib"); 
$hist_avis =  getTableWhere($bdd, "tp_historique_membre" , "id_film", $id);

if(file_exists("upload/img/film" . $id . ".png"))
{
   $img = "<img src=\"upload/img/film" . $id . ".png\" alt=\"avatar\">";
}
else
{
   $img = "<img src=\"img/filmdefaut.png\" alt=\"avatar\">";
}
?><div class="sep"></div>
<div class="avis-bloc avis-film">
	<h5><?php echo $film_info['titre']; ?></h5>
	<div class="btn-group btn-film">
 		<button class="btn btn-primary" id="avis-right-btn" onClick="avisBloc('avis-right','avis-right2');">Informations</button>
 		<button class="btn" id="avis-right2-btn" onClick="avisBloc('avis-right2','avis-right');">Les avis</button>
	</div>
	<div class="avis-left">
		<?php echo $img; ?>
	</div>
	<div class="avis-right" id="avis-right">
		<div class="info-left">
			<p><strong>Date de sortie</strong></p>
			<p><strong>Durée du film :</strong></p>
			<p><strong>Date de visionnage :</strong></p>
			<p><strong>Avis sur le film :</strong></p>
			<p><strong>Année de production :</strong></p>
			<p><strong>Résumé :</strong></p>
		</div>
		<div class="info-right">
		<p> <?php echo date("j F Y", date_timestamp_get(date_create($film_info['date_debut_affiche']))); ?></p>
		<p> <?php echo $film_info['duree_min']; ?> minutes</p>
		<p><a href="index.php?file=search&amp;searchby=2&amp;text=<?php echo $genre; ?>&amp;search_bouton=1"><?php echo $genre; ?></a></p>
		<p><a href="index.php?file=search&amp;searchby=3&amp;text=<?php echo $distrib; ?>&amp;search_bouton=1"><?php echo $distrib; ?></a></p>
		<p> <?php echo $film_info['annee_prod']; ?></p>
		<p><div class="avis-em" id="bloc-em"><?php echo "<em id=\"avis-em\">‟" . $film_info['resum'] ."”</em>"; ?>
		</div></p>
		</div>
	</div>
	<div class="avis-right" id="avis-right2">
		<?php
			if(isset($hist_avis)){
				$count = 0;
		 foreach($hist_avis as $key => $value){
		$id_perso = checkTable($bdd, "tp_membre", "id_fiche_perso" , $value['id_membre'], "id_membre");
		if(!empty($value['avis'])){ ?>
		<div class="avis-em film-avis <?php if($count%2 == 1){ echo "white-avis";} ?>" id="bloc-em">
			<div class="info-avis">
				<strong>
					<?php if(isset($_SESSION['id_job'])) { $linkuser = ''; } else { $linkuser ='User';} ?>
				 <?php echo "<a href=\"index.php?file=Profil" . $linkuser . "&amp;id=" . $id_perso . "\" title=\"Accéder au profil\">" . checkTable($bdd, "tp_fiche_personne", "nom" , $id_perso, "id_perso") . "</a>";  ?></strong>
				<em><?php echo date("j F Y", date_timestamp_get(date_create())); ?></em>
			</div>
			<?php echo "<em id=\"avis-em\">‟" . $value['avis'] ."”</em>";
			$count++; ?>
		</div>
		<?php 
				} 
			}
			if($count == 0)
		{
			echo "<p id=\"com-em\">Aucun avis pour ce film</p>";
		}
		}
		else
		{
			echo "<p id=\"com-em\">Aucun avis pour ce film</p>";
		} ?>
	</div>
	<div style="clear:both"></div>
</div>