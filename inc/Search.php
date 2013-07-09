<?php
if(isset($_POST['bouton-search']))
{
	if(isset($_POST['searchby']) && $_POST['searchby'] > 0 && $_POST['searchby'] < 5)
	{
		if($_POST['searchby'] == 1)
		{
			echo "<h5>Résultat(s) correspondant(s) à votre recherche par nom</h5>";
			$search = htmlentities($_POST['text']);
			$films_search = getFilmsSearch($bdd, $search, "titre");
			?>
			<table>
				<tr>
					<th>Titre</th>
					<th>Genre</th>
					<th>Distribution</th>
					<th>Durée</th>
					<th>Année</th>
					<th>Date de mise à l'affiche</th>
					<th>Date de fin</th>
					<th>Actions</th>
				</tr>
			<?php
			if(count($films_search) != 0)
			{
			$i = 0;
			foreach($films_search as $val)
			{
				if($i%2 == 0) { echo "<tr class=\"gris\">"; } else { echo "<tr>"; }
				echo "<td>";
				echo $val['titre'];
				echo "</td>";
				echo "<td>";
				echo checkTable($bdd, "tp_genre", "nom" , $val['id_genre'], "id_genre");
				echo "</td>";
				echo "<td>";
				if($val['id_distrib'] != NULL){ echo checkTable($bdd, "tp_distrib", "nom" , $val['id_distrib'], "id_distrib");} else {echo "<span class=\"empty\">-</span>";}
				echo "</td>";
				echo "<td>";
				echo $val['duree_min'];
				echo "</td>";
				echo "<td>";
				echo $val['annee_prod'];
				echo "</td>";
				echo "<td class=\"datetab\">";
				echo $val['date_debut_affiche'];
				echo "</td>";
				echo "<td class=\"datetab\">";
				echo $val['date_fin_affiche'];
				echo "</td>";
				echo "<td class=\"actions\">";
				echo "<a href=\"#\" title=\"\"><i class='icon-pencil' ></i></a>
					<a href=\"#\" title=\"\"><i class='icon-list-alt unset-button'></i></a>
					<a href=\"index.php?file=ListFilms&amp;del=" . $val['id_film'] . "\" title=\"\"><i class='icon-remove' ></i></a>";
				echo "</td>";
				echo "</tr>";
				$i++;
			}
		}
		else
		{
			echo "<tr>";
			echo "<td colspan=\"8\">";
			echo "<span class=\"empty\">Aucun résulat trouvé</span>";
			echo "</td>";
			echo "</tr>";
		}

			?>
			</table>
		<?php
		}
		else if ($_POST['searchby'] == 2)
		{
			echo "<h5>Résultat(s) correspondant(s) à votre recherche par genre</h5>";
			$search = htmlentities($_POST['text']);
			$films_search = getTableAll($bdd, "tp_film", $search , "nom", "titre", "tp_genre", "id_genre", "id_genre");	
			?>
			<table>
				<tr>
					<th>Titre</th>
					<th>Genre</th>
					<th>Distribution</th>
					<th>Durée</th>
					<th>Année</th>
					<th>Date de mise à l'affiche</th>
					<th>Date de fin</th>
					<th>Actions</th>
				</tr>
			<?php
			if(count($films_search) != 0)
			{
			$i = 0;
			foreach($films_search as $val)
			{
				if($i%2 == 0) { echo "<tr class=\"gris\">"; } else { echo "<tr>"; }
				echo "<td>";
				echo $val['titre'];
				echo "</td>";
				echo "<td>";
				echo checkTable($bdd, "tp_genre", "nom" , $val['id_genre'], "id_genre");
				echo "</td>";
				echo "<td>";
				if($val['id_distrib'] != NULL){ echo checkTable($bdd, "tp_distrib", "nom" , $val['id_distrib'], "id_distrib");} else {echo "<span class=\"empty\">-</span>";}
				echo "</td>";
				echo "<td>";
				echo $val['duree_min'];
				echo "</td>";
				echo "<td>";
				echo $val['annee_prod'];
				echo "</td>";
				echo "<td class=\"datetab\">";
				echo $val['date_debut_affiche'];
				echo "</td >";
				echo "<td class=\"datetab\">";
				echo $val['date_fin_affiche'];
				echo "</td>";
				echo "<td class=\"actions\">";
				echo "<a href=\"#\" title=\"\"><i class='icon-pencil' ></i></a>
					<a href=\"#\" title=\"\"><i class='icon-list-alt unset-button'></i></a>
					<a href=\"index.php?file=ListFilms&amp;del=" . $val['id_film'] . "\" title=\"\"><i class='icon-remove' ></i></a>";
				echo "</td>";
				echo "</tr>";
				$i++;
			}
		}
		else
		{
			echo "<tr>";
			echo "<td colspan=\"8\">";
			echo "<span class=\"empty\">Aucun résulat trouvé</span>";
			echo "</td>";
			echo "</tr>";
		}

			?>
			</table>
		<?php
		}
		else if ($_POST['searchby'] == 3)
		{
			echo "<h5>Résultat(s) correspondant(s) à votre recherche par distribution</h5>";
			$search = htmlentities($_POST['text']);
			$films_search = getTableAll($bdd, "tp_film", $search , "nom", "titre", "tp_distrib", "id_distrib", "id_distrib");	
			?>
			<table>
				<tr>
					<th>Titre</th>
					<th>Genre</th>
					<th>Distribution</th>
					<th>Durée</th>
					<th>Année</th>
					<th>Date de mise à l'affiche</th>
					<th>Date de fin</th>
					<th>Actions</th>
				</tr>
			<?php
			if(count($films_search) != 0)
			{
			$i = 0;
			foreach($films_search as $val)
			{
				if($i%2 == 0) { echo "<tr class=\"gris\">"; } else { echo "<tr>"; }
				echo "<td>";
				echo $val['titre'];
				echo "</td>";
				echo "<td>";
				echo checkTable($bdd, "tp_genre", "nom" , $val['id_genre'], "id_genre");
				echo "</td>";
				echo "<td>";
				if($val['id_distrib'] != NULL){ echo checkTable($bdd, "tp_distrib", "nom" , $val['id_distrib'], "id_distrib");} else {echo "<span class=\"empty\">-</span>";}
				echo "</td>";
				echo "<td>";
				echo $val['duree_min'];
				echo "</td>";
				echo "<td>";
				echo $val['annee_prod'];
				echo "</td>";
				echo "<td class=\"datetab\">";
				echo $val['date_debut_affiche'];
				echo "</td>";
				echo "<td class=\"datetab\">";
				echo $val['date_fin_affiche'];
				echo "</td>";
				echo "<td class=\"actions\">";
				echo "<a href=\"#\" title=\"\"><i class='icon-pencil' ></i></a>
					<a href=\"#\" title=\"\"><i class='icon-list-alt unset-button'></i></a>
					<a href=\"index.php?file=ListFilms&amp;del=" . $val['id_film'] . "\" title=\"\"><i class='icon-remove' ></i></a>";
				echo "</td>";
				echo "</tr>";
				$i++;
			}
		}
		else
		{
			echo "<tr>";
			echo "<td colspan=\"8\">";
			echo "<span class=\"empty\">Aucun résulat trouvé</span>";
			echo "</td>";
			echo "</tr>";
		}

			?>
			</table>
		<?php
		}
		else if ($_POST['searchby'] == 4)
		{
			echo "<h5>Résultat(s) correspondant(s) à votre recherche par date de diffusion</h5>";
			$search = htmlentities($_POST['text']);
			$countfilms = countTable($bdd, "tp_film");	
			$films_search = getTable($bdd, "tp_film" , 0 , "date_debut_affiche", $countfilms);
			?>
			<table>
				<tr>
					<th>Titre</th>
					<th>Genre</th>
					<th>Distribution</th>
					<th>Durée</th>
					<th>Année</th>
					<th>Date de mise à l'affiche</th>
					<th>Date de fin</th>
					<th>Actions</th>
				</tr>
			<?php
			if(count($films_search) != 0)
			{
			$i = 0;
			$searchfilm = htmlentities($_POST['date']);
			foreach($films_search as $val)
			{
				
				if(substr($val['date_debut_affiche'],0,10) < $searchfilm && substr($val['date_fin_affiche'],0,10) > $searchfilm){
				if($i%2 == 0) { echo "<tr class=\"gris\">"; } else { echo "<tr>"; }
				echo "<td>";
				echo $val['titre'];
				echo "</td>";
				echo "<td>";
				echo checkTable($bdd, "tp_genre", "nom" , $val['id_genre'], "id_genre");
				echo "</td>";
				echo "<td>";
				if($val['id_distrib'] != NULL){ echo checkTable($bdd, "tp_distrib", "nom" , $val['id_distrib'], "id_distrib");} else {echo "<span class=\"empty\">-</span>";}
				echo "</td>";
				echo "<td>";
				echo $val['duree_min'];
				echo "</td>";
				echo "<td>";
				echo $val['annee_prod'];
				echo "</td>";
				echo "<td class=\"datetab\">";
				echo $val['date_debut_affiche'];
				echo "</td>";
				echo "<td class=\"datetab\">";
				echo $val['date_fin_affiche'];
				echo "</td>";
				echo "<td class=\"actions\">";
				echo "<a href=\"#\" title=\"\"><i class='icon-pencil' ></i></a>
					<a href=\"#\" title=\"\"><i class='icon-list-alt unset-button'></i></a>
					<a href=\"index.php?file=ListFilms&amp;del=" . $val['id_film'] . "\" title=\"\"><i class='icon-remove' ></i></a>";
				echo "</td>";
				echo "</tr>";
				$i++;
			}
			}
		}
		else
		{
			echo "<tr>";
			echo "<td colspan=\"8\">";
			echo "<span class=\"empty\">Aucun résulat trouvé</span>";
			echo "</td>";
			echo "</tr>";
		}

			?>
			</table>
		<?php
		}
	}
	else
	{
		echo "<h5>Résultat(s) correspondant(s) à votre recherche par nom</h5>";
			$search = htmlentities($_POST['text']);
			$films_search = getFilmsSearch($bdd, $search, "titre");
			?>
			<table>
				<tr>
					<th>Titre</th>
					<th>Genre</th>
					<th>Distribution</th>
					<th>Durée</th>
					<th>Année</th>
					<th>Date de mise à l'affiche</th>
					<th>Date de fin</th>
					<th>Actions</th>
				</tr>
			<?php
			if(count($films_search) != 0)
			{
			$i = 0;
			foreach($films_search as $val)
			{
				if($i%2 == 0) { echo "<tr class=\"gris\">"; } else { echo "<tr>"; }
				echo "<td>";
				echo $val['titre'];
				echo "</td>";
				echo "<td>";
				echo checkTable($bdd, "tp_genre", "nom" , $val['id_genre'], "id_genre");
				echo "</td>";
				echo "<td>";
				if($val['id_distrib'] != NULL){ echo checkTable($bdd, "tp_distrib", "nom" , $val['id_distrib'], "id_distrib");} else {echo "<span class=\"empty\">-</span>";}
				echo "</td>";
				echo "<td>";
				echo $val['duree_min'];
				echo "</td>";
				echo "<td>";
				echo $val['annee_prod'];
				echo "</td>";
				echo "<td class=\"datetab\">";
				echo $val['date_debut_affiche'];
				echo "</td>";
				echo "<td class=\"datetab\">";
				echo $val['date_fin_affiche'];
				echo "</td>";
				echo "<td class=\"actions\">";
				echo "<a href=\"#\" title=\"\"><i class='icon-pencil' ></i></a>
					<a href=\"#\" title=\"\"><i class='icon-list-alt unset-button'></i></a>
					<a href=\"index.php?file=ListFilms&amp;del=" . $val['id_film'] . "\" title=\"\"><i class='icon-remove' ></i></a>";
				echo "</td>";
				echo "</tr>";
				$i++;
			}
		}
		else
		{
			echo "<tr>";
			echo "<td colspan=\"8\">";
			echo "<span class=\"empty\">Aucun résulat trouvé</span>";
			echo "</td>";
			echo "</tr>";
		}

			?>
			</table>
		<?php
		
		echo "<h5>Résultat(s) correspondant(s) à votre recherche par genre</h5>";
			$search2 = htmlentities($_POST['text']);
			$films_search = getTableAll($bdd, "tp_film", $search2 , "nom", "titre", "tp_genre", "id_genre", "id_genre");	
			?>
			<table>
				<tr>
					<th>Titre</th>
					<th>Genre</th>
					<th>Distribution</th>
					<th>Durée</th>
					<th>Année</th>
					<th>Date de mise à l'affiche</th>
					<th>Date de fin</th>
					<th>Actions</th>
				</tr>
			<?php
			if(count($films_search) != 0)
			{
			$i = 0;
			foreach($films_search as $val)
			{
				if($i%2 == 0) { echo "<tr class=\"gris\">"; } else { echo "<tr>"; }
				echo "<td>";
				echo $val['titre'];
				echo "</td>";
				echo "<td>";
				echo checkTable($bdd, "tp_genre", "nom" , $val['id_genre'], "id_genre");
				echo "</td>";
				echo "<td>";
				if($val['id_distrib'] != NULL){ echo checkTable($bdd, "tp_distrib", "nom" , $val['id_distrib'], "id_distrib");} else {echo "<span class=\"empty\">-</span>";}
				echo "</td>";
				echo "<td>";
				echo $val['duree_min'];
				echo "</td>";
				echo "<td>";
				echo $val['annee_prod'];
				echo "</td>";
				echo "<td class=\"datetab\">";
				echo $val['date_debut_affiche'];
				echo "</td >";
				echo "<td class=\"datetab\">";
				echo $val['date_fin_affiche'];
				echo "</td>";
				echo "<td class=\"actions\">";
				echo "<a href=\"#\" title=\"\"><i class='icon-pencil' ></i></a>
					<a href=\"#\" title=\"\"><i class='icon-list-alt unset-button'></i></a>
					<a href=\"index.php?file=ListFilms&amp;del=" . $val['id_film'] . "\" title=\"\"><i class='icon-remove' ></i></a>";
				echo "</td>";
				echo "</tr>";
				$i++;
			}
		}
		else
		{
			echo "<tr>";
			echo "<td colspan=\"8\">";
			echo "<span class=\"empty\">Aucun résulat trouvé</span>";
			echo "</td>";
			echo "</tr>";
		}

			?>
			</table>
		<?php

		echo "<h5>Résultat(s) correspondant(s) à votre recherche par distribution</h5>";
			$search3 = htmlentities($_POST['text']);
			$films_search = getTableAll($bdd, "tp_film", $search3 , "nom", "titre", "tp_distrib", "id_distrib", "id_distrib");	
			?>
			<table>
				<tr>
					<th>Titre</th>
					<th>Genre</th>
					<th>Distribution</th>
					<th>Durée</th>
					<th>Année</th>
					<th>Date de mise à l'affiche</th>
					<th>Date de fin</th>
					<th>Actions</th>
				</tr>
			<?php
			if(count($films_search) != 0)
			{
			$i = 0;
			foreach($films_search as $val)
			{
				if($i%2 == 0) { echo "<tr class=\"gris\">"; } else { echo "<tr>"; }
				echo "<td>";
				echo $val['titre'];
				echo "</td>";
				echo "<td>";
				echo checkTable($bdd, "tp_genre", "nom" , $val['id_genre'], "id_genre");
				echo "</td>";
				echo "<td>";
				if($val['id_distrib'] != NULL){ echo checkTable($bdd, "tp_distrib", "nom" , $val['id_distrib'], "id_distrib");} else {echo "<span class=\"empty\">-</span>";}
				echo "</td>";
				echo "<td>";
				echo $val['duree_min'];
				echo "</td>";
				echo "<td>";
				echo $val['annee_prod'];
				echo "</td>";
				echo "<td class=\"datetab\">";
				echo $val['date_debut_affiche'];
				echo "</td>";
				echo "<td class=\"datetab\">";
				echo $val['date_fin_affiche'];
				echo "</td>";
				echo "<td class=\"actions\">";
				echo "<a href=\"#\" title=\"\"><i class='icon-pencil' ></i></a>
					<a href=\"#\" title=\"\"><i class='icon-list-alt unset-button'></i></a>
					<a href=\"index.php?file=ListFilms&amp;del=" . $val['id_film'] . "\" title=\"\"><i class='icon-remove' ></i></a>";
				echo "</td>";
				echo "</tr>";
				$i++;
			}
		}
		else
		{
			echo "<tr>";
			echo "<td colspan=\"8\">";
			echo "<span class=\"empty\">Aucun résulat trouvé</span>";
			echo "</td>";
			echo "</tr>";
		}

			?>
			</table>
		<?php
	}
}
else
{
	header('Location: index.php');
}

?>