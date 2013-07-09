<?php 
if(!isset($_SESSION['id_job'])) {
	header('Location: 404.php');
}
?>
<span class="btn btn-primary add" onClick="addabo('add');" >Ajouter un membre</span>
<?php
// Suppression
if(!empty($_GET['del']) && isset($_SESSION['id_job']))
{
	$verif = verifTable($bdd, "tp_membre", $_GET['del'] , "id_membre");
	if($verif == 1 && preg_match("/-/", $_GET['del']) == 0)
	{
			delTable($bdd, "tp_membre" , $_GET['del'], "id_membre");
			echo "<div class=\"alert alert-success\" >
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Succès :</strong> Le membre a été supprimé avec succès.
				</div>";
	}
	else
	{
		echo "<div class=\"alert alert-error\" >
   				<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    			<strong>Erreur :</strong> Ce membre n'existe pas.
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

	if(!empty($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['mail']) && isset($_POST['password']))
	{
		$mail= htmlentities($_POST['mail']);
		$verif = verifTable($bdd, "tp_fiche_personne", $_POST['mail'] , "email");
		if($verif == 0)
		{
			if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/",$_POST['mail']))
			{
				$nom= htmlentities($_POST['nom']);
				$prenom= htmlentities($_POST['prenom']);
				$date_naissance= htmlentities($_POST['date_naissance']);
				$adresse= htmlentities($_POST['adresse']);
				$cpostal= htmlentities($_POST['cpostal']);
				$ville= htmlentities($_POST['ville']);
				$password = MD5(SHA1($_POST['password']));
				$abo= abs(intval($_POST['abo']));
				$pays= htmlentities($_POST['pays']);
				addMember($bdd, $nom, $prenom, $mail,$password, $date_naissance, $adresse, $ville, $pays, $cpostal);
				$id = maxTable($bdd, "tp_fiche_personne", "id_perso");
				addMemberAbo($bdd, $abo ,$id);
				echo "<div class=\"alert alert-success\">
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Succès :</strong> Le membre a été ajouté avec succès.
				</div>";
			}
			else
			{
				echo "<div class=\"alert alert-error\">
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Erreur :</strong> Le mail n'est pas valide.
				</div>";
			}
		}
		else
		{
			echo "<div class=\"alert alert-error\" >
   				<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    			<strong>Erreur :</strong> Cet e-mail est déjà utilisé.
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

$members_list = getTable($bdd, "tp_membre", $page, $tri, $_SESSION['limit'] ); // Récupération de la liste de films
$countabo = countTable($bdd, "tp_abonnement");
$abo_list = getTable($bdd, "tp_abonnement", 0, "id_abo", $countabo ); // Récupération de la liste d'abonnements

?>
			<label for="nom">Nom * :</label>
				<input type="text" name="nom" id="nom" placeholder="Entrez un nom" required value="<?php if(isset($_POST['nom'])){ echo $_POST['nom']; } ?>">
			<br>
			<label for="prenom">Prénom *: </label>
				<input type="text" name="prenom" id="prenom" placeholder="Entrez un prenom" required value="<?php if(isset($_POST['prenom'])){ echo $_POST['prenom']; } ?>">
			<br>
			<label for="mail">Mail *: </label>
				<input type="text" name="mail" id="mail" placeholder="Entrez un mail" required pattern="^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$" value="<?php if(isset($_POST['mail'])){ echo $_POST['mail']; } ?>">	
			<br>
			<label for="password">Password *: </label>
				<input type="password" name="password" id="password" placeholder="Entrez un password" required>	
			<br>
			<label for="date_naissance">Date de naissance : </label>
				<input type="date" id="date_naissance" name="date_naissance" value="<?php if(isset($_POST['date_naissance'])){ echo $_POST['date_naissance']; } ?>"> 
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
			<label for="abo">Abonnement: </label>
				<select id="abo" name="abo">
					<option value="-">Non défini</option>
					<?php foreach($abo_list as $val)
					{
						echo "<option value=\"" . $val['id_abo'] . "\">" . $val['nom'] . "</option>";
					} ?>
				</select>
			<br>
			<em>Champs obligatoires * </em>
			<input type="submit" value="Ajouter" name="bouton-add">
		</form>
	</div>
<?php if(!isset($_POST['search-membre'])){ ?>
<h5>Listes des membres</h5>
<div class="nb-result">
	<form method="POST">
		<label for="limit">Nombre de résulat par page :</label>
		<input type="number" id="limit" class="input-small" name="limit" placeholder="25" min="20" max="500" value="<?php if(isset($_SESSION['limit'])){ echo $_SESSION['limit']; } ?>">
		<button type="submit" class="btn"><i class="icon-ok"></i></button>
	</form>
</div>
<div class="nb-result search-membre">
	<form class="navbar-form pull-left search-bar" method="POST" action="">
  		<input class="span2" id="appendedInputButton2" name="search-membre" type="text" placeholder="Rechercher">
  		<button type="submit" class="btn recherche-btn" name="search-membre-bouton"><i class="icon-search"></i></button>
	</form>
</div>	
<table>
	<tr>
		<th>Nom</th>
		<th>Prenom</th>
		<th>Mail</th>
		<th>Ville</th>
		<th>Abonnement</th>
		<th>Fin de l'abonnement</th>
		<th>Actions</th>
	</tr>
<?php

$i = 0;
foreach($members_list as $val)
{
	$nbj_abo = checkTable($bdd, "tp_abonnement", "duree_abo" , $val['id_abo'], "id_abo"); // Durée de l'abonnement
	$date_abo = $val['date_abo']; // Date de l'abonnement
	$an=substr($date_abo,0,4); // Découpage de la date - annee
	$mois=substr($date_abo,5,2); // Découpage de la date - mois
	$jour=substr($date_abo,8,2); // Découpage de la date - jours
	$date_fin_abo = date("d-m-Y", mktime(0, 0, 0, $mois, $jour+$nbj_abo, $an)); // Date de fin de l'abonnement
	if($i%2 == 0) { echo "<tr class=\"gris\">"; } else { echo "<tr>"; }
	echo "<td>";
	echo checkTable($bdd, "tp_fiche_personne", "nom" , $val['id_fiche_perso'], "id_perso");
	echo "</td>";
	echo "<td>";
	echo checkTable($bdd, "tp_fiche_personne", "prenom" , $val['id_fiche_perso'], "id_perso");
	echo "</td>";
	echo "<td>";
	echo checkTable($bdd, "tp_fiche_personne", "email" , $val['id_fiche_perso'], "id_perso");
	echo "</td>";
	echo "<td>";
	echo checkTable($bdd, "tp_fiche_personne", "ville" , $val['id_fiche_perso'], "id_perso");
	echo "</td>";
	echo "<td>";
	echo checkTable($bdd, "tp_abonnement", "nom" , $val['id_abo'], "id_abo");
	echo "</td>";
	echo "<td>";
	echo $date_fin_abo;
	echo "</td>";
	echo "<td class=\"actions\">";
	echo "
		<a href=\"index.php?file=Profil&amp;id=" . $val['id_fiche_perso'] . "\" title=\"Accéder au profil\"><i class='icon-list-alt'></i></a>
		<a href=\"index.php?file=ListMembres&amp;del=" . $val['id_membre'] . "\" title=\"\"><i class='icon-remove' ></i></a>";
	echo "</td>";
	echo "</tr>";
	$i++;
}


$page_nb = round(countTable($bdd, "tp_membre") / $_SESSION['limit']);
?>
</table>

<div class="pagination">
  <ul>
<?php

if($page > 0) {
	$i = $page -1;
	echo "<li><a href=\"index.php?file=ListMembres&amp;page=0\">&laquo;</a></li>";
 	echo "<li><a href=\"index.php?file=ListMembres&amp;page=" . $i . "\">&lsaquo;</a></li>"; 
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
			echo "<li class=\"active\"><a href=\"index.php?file=ListMembres&amp;page=" . $i . "\">$i</a></li>";
		}
		else
		{
			echo "<li><a href=\"index.php?file=ListMembres&amp;page=" . $i . "\">$i</a></li>";
		}
	}
	else
	{
		if($i == $page + 3)
		{
			echo "<li class=\"disabled\"><a href=\"index.php?file=ListMembres&amp;page=" . $i . "\">...</a></li>";
		}
		else if($i == 2 && $page > 4)
		{
			echo "<li class=\"disabled\"><a href=\"index.php?file=ListMembres&amp;page=" . $i . "\">...</a></li>";
		}	
	}
	
}

if($page != $page_nb) 
{
	$i = $page +1;
	echo "<li><a href=\"index.php?file=ListMembres&amp;page=" . $i . "\">&rsaquo;</a></li>";
	echo "<li><a href=\"index.php?file=ListMembres&amp;page=$page_nb\">&raquo;</a></li>";
}
else
{
	echo "<li class=\"disabled\"><a href=\"index.php?file=ListMembres&amp;page=$page_nb\">&raquo;</a></li>";
}

?>
  </ul>
</div>

<?php } else {
	echo "<h5>Résultat de votre recherche</h5>";
	$user = checkTableLikeT($bdd, "tp_fiche_personne", "*", $_POST['search-membre'] , "nom");
	$user2 = checkTableLikeT($bdd, "tp_fiche_personne", "*", $_POST['search-membre'] , "prenom");
	if(count($user2) > 0 || count($user) > 0)
	{
		
		if(count($user) > 0)
		{
			echo "<div class='user-left'>";
			echo "<h6>Recherche par nom</h6>";
			foreach($user as $nom){
				// Récupération avatar
				if(file_exists("../upload/img/user" . $nom['id_perso'] . ".png"))
				{
  					$img = "<img src=\"upload/img/user" . $nom['id_perso'] . ".png\" alt=\"avatar\">";
				}
				else
				{
				   $img = "<img src=\"img/defautuser.png\" alt=\"avatar\">";
				}
				?>
				<a href="index.php?file=Profil&amp;id=<?php echo checkTable($bdd, "tp_membre", "id_membre", $nom['id_perso'] , "id_fiche_perso"); ?>" class="usersearch">	
						<?php echo $img; ?>
						<?php echo "<p><strong>Nom</strong>: " . $nom['nom'] . "</p>";
						echo "<p><strong>Prenom</strong> : " . $nom['prenom'] . "</p>";?>
				</a>
				<?php
			}
			echo "</div>";

		}

		if(count($user2) > 0)
		{
			echo "<div class='user-left'>";
			echo "<h6>Recherche par prenom</h6>";
			foreach($user2 as $prenom){
				// Récupération avatar
				if(file_exists("../upload/img/user" . $prenom['id_perso'] . ".png"))
				{
  					$img = "<img src=\"upload/img/user" . $prenom['id_perso'] . ".png\" alt=\"avatar\">";
				}
				else
				{
				   $img = "<img src=\"img/defautuser.png\" alt=\"avatar\">";
				}
				?>
				<a href="index.php?file=Profil&amp;id=<?php echo checkTable($bdd, "tp_membre", "id_membre", $prenom['id_perso'] , "id_fiche_perso"); ?>" class="usersearch">	
						<?php echo $img; ?>
						<?php echo "<p><strong>Nom</strong>: " . $prenom['nom'] . "</p>";
						echo "<p><strong>Prenom</strong> : " . $prenom['prenom'] . "</p>";?>
				</a>
				<?php
			}
			echo "</div>";
			

		}
		echo "<div style=\"clear:both\"></div>";
	}
	else
	{
			echo "<p class=\"result\"><span class=\"empty\">Aucun résulat trouvé</span></p>";
			echo "<p class=\"result\"><a href=\"index.php?file=ListMembres\" >retour à la page des membres</a></p>";
	}
	$members_list = getTable($bdd, "tp_membre", $page, $tri, $_SESSION['limit'] ); // Récupération de la liste de films
	$countabo = countTable($bdd, "tp_abonnement");
	$abo_list = getTable($bdd, "tp_abonnement", 0, "id_abo", $countabo ); // Récupération de la liste d'abonnements

}?>