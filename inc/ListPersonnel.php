
<span class="btn btn-primary add" onClick="addabo('add');" >Ajouter un employé</span>
<?php 
// Suppression
if(!empty($_GET['del']) && isset($_SESSION['id_job']))
{
	$verif = verifTable($bdd, "tp_personnel", $_GET['del'] , "id_personnel");
	if($verif == 1 && preg_match("/-/", $_GET['del']) == 0)
	{
			$id_perso = checkTable($bdd, "tp_personnel", "id_fiche_perso", $_GET['del'] , "id_personnel");
			delTable($bdd, "tp_personnel" , $_GET['del'], "id_personnel");
			delTable($bdd, "tp_membre" , $id_perso, "id_fiche_perso");
			delTable($bdd, "tp_fiche_personne" , $id_perso, "id_perso");
			echo "<div class=\"alert alert-success\" >
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Succès :</strong> L'employé a été supprimé avec succès.
				</div>";
	}
	else
	{
		echo "<div class=\"alert alert-error\" >
   				<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    			<strong>Erreur :</strong> Ce employé n'existe pas.
			</div>";
	}
}
?>
<div class="contain" id="add" style="<?php if(isset($_POST['bouton-add'])){ echo "display:block"; } else { echo "display:none"; }?>">
		<form method="POST">

<?php

// Ajout
if(isset($_POST['bouton-add']))
{

	if(!empty($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['mail']) && isset($_POST['password']) && isset($_POST['id_job']) && isset($_POST['date_recrutement']))
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
				$id_job= abs(intval($_POST['id_job']));
				$pays= htmlentities($_POST['pays']);
				$horraire= htmlentities($_POST['horraire']);
				$date_recrutement= htmlentities($_POST['date_recrutement']);
				addMember($bdd, $nom, $prenom, $mail,$password, $date_naissance, $adresse, $ville, $pays, $cpostal);
				$id = maxTable($bdd, "tp_fiche_personne", "id_perso");
				addPersonnel($bdd, $id_job ,$id, $horraire, $date_recrutement);
				echo "<div class=\"alert alert-success\">
   					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    				<strong>Succès :</strong> L'employé a été ajouté avec succès.
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

$perso_nettoyage = getTableAll2($bdd, "tp_personnel", 0 , "id_job", "id_personnel", "tp_fiche_personne", "id_fiche_perso", "id_perso"); // Récupération du service nettoyage
$perso_projec = getTableAll2($bdd, "tp_personnel", 1 , "id_job", "id_personnel", "tp_fiche_personne", "id_fiche_perso", "id_perso"); // Récupération des projectionnistes
$perso_caisse = getTableAll2($bdd, "tp_personnel", 2 , "id_job", "id_personnel", "tp_fiche_personne", "id_fiche_perso", "id_perso"); // Récupération des caissieres
$perso_direct = getTableAll2($bdd, "tp_personnel", 3 , "id_job", "id_personnel", "tp_fiche_personne", "id_fiche_perso", "id_perso"); // Récupération des directeurs
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
			<label for="id_job">Métier : </label>
				<select id="id_job" name="id_job">
					<option value="0">Service Nettoyage</option>
					<option value="1">Projectionniste</option>
					<option value="2">Caissère</option>
					<option value="3">Directeur</option>
				</select>
			<br>
			<label for="horraire">Horraire : </label>
				<select id="horraire" name="horraire">
					<option value="am">Matin</option>
					<option value="pm">Aprés-midi</option>
					<option value="">Plein Temps</option>
				</select>
			<br>
			<label for="date_recrutement">Date de recrutement : </label>
				<input type="date" id="date_recrutement" name="date_recrutement" value="<?php if(isset($_POST['date_recrutement'])){ echo $_POST['date_recrutement']; } ?>"> 
			<br>
			<em>Champs obligatoires * </em>
			<input type="submit" value="Ajouter" name="bouton-add">
		</form>
	</div>
<h5>Listes des employés</h5>
<div class="nb-result">
	<form method="POST">
		<label for="limit">Nombre de résulat par page :</label>
		<input type="number" id="limit" class="input-small" name="limit" placeholder="25" min="20" max="500" value="<?php if(isset($_SESSION['limit'])){ echo $_SESSION['limit']; } ?>">
		<button type="submit" class="btn"><i class="icon-ok"></i></button>
	</form>
</div>

<table>
	<caption class="direct">Directeur(s)</caption>
	<tr>
		<th>Photo</th>
		<th>Nom</th>
		<th>Prenom</th>
		<th>Mail</th>
		<th>Ville</th>
		<th>Actions</th>
	</tr>
<?php

$i = 0;
foreach($perso_direct as $val)
{
	if(file_exists("upload/img/user" . $val['id_fiche_perso'] . ".png"))
	{
 		$img = "<img src=\"upload/img/user" . $val['id_fiche_perso'] . ".png\" alt=\"avatar\">";
	}
	else
	{
 		$img = "<img src=\"img/defautuser2.png\" alt=\"avatar\">";
	}
	if($i%2 == 0) { echo "<tr class=\"gris\">"; } else { echo "<tr>"; }
	echo "<td class=\"img-film\">";
	echo $img;
	echo "</td>";
	echo "<td>";
	echo $val['nom'];
	echo "</td>";
	echo "<td>";
	echo $val['prenom'];
	echo "</td>";
	echo "<td>";
	echo $val['email'];
	echo "</td>";
	echo "<td>";
	echo $val['ville'];
	echo "</td>";
	echo "<td class=\"actions\">";
	echo "<a href=\"index.php?file=ProfilPersonnel&amp;id=" . $val['id_fiche_perso'] . "\" title=\"Accéder au profil\"><i class='icon-list-alt'></i></a>"; 
	echo "<a href=\"index.php?file=ListPersonnel&amp;del=" . $val['id_personnel'] . "\" title=\"\"><i class='icon-remove' ></i></a>";
	echo "</td>";
	echo "</tr>";
	$i++;
}

?>
</table>

<table>
	<caption  class="caisse">Caissières</caption>
	<tr>
		<th>Photo</th>
		<th>Nom</th>
		<th>Prenom</th>
		<th>Mail</th>
		<th>Ville</th>
		<th>Actions</th>
	</tr>
<?php

$i = 0;
foreach($perso_caisse as $val)
{
		if(file_exists("upload/img/user" . $val['id_fiche_perso'] . ".png"))
	{
 		$img = "<img src=\"upload/img/user" . $val['id_fiche_perso'] . ".png\" alt=\"avatar\">";
	}
	else
	{
 		$img = "<img src=\"img/defautuser2.png\" alt=\"avatar\">";
	}
	if($i%2 == 0) { echo "<tr class=\"gris\">"; } else { echo "<tr>"; }
	echo "<td class=\"img-film\">";
	echo $img;
	echo "</td>";
	echo "<td>";
	echo $val['nom'];
	echo "</td>";
	echo "<td>";
	echo $val['prenom'];
	echo "</td>";
	echo "<td>";
	echo $val['email'];
	echo "</td>";
	echo "<td>";
	echo $val['ville'];
	echo "</td>";
	echo "<td class=\"actions\">";
	echo "<a href=\"index.php?file=ProfilPersonnel&amp;id=" . $val['id_fiche_perso'] . "\" title=\"Accéder au profil\"><i class='icon-list-alt'></i></a>"; 
	echo "<a href=\"index.php?file=ListPersonnel&amp;del=" . $val['id_personnel'] . "\" title=\"\"><i class='icon-remove' ></i></a>";
	echo "</td>";
	echo "</tr>";
	$i++;
}

?>
</table>

<table>
	<caption  class="projec">Projectionnistes</caption>
	<tr>
		<th>Photo</th>
		<th>Nom</th>
		<th>Prenom</th>
		<th>Mail</th>
		<th>Ville</th>
		<th>Actions</th>
	</tr>
<?php

$i = 0;
foreach($perso_projec as $val)
{
		if(file_exists("upload/img/user" . $val['id_fiche_perso'] . ".png"))
	{
 		$img = "<img src=\"upload/img/user" . $val['id_fiche_perso'] . ".png\" alt=\"avatar\">";
	}
	else
	{
 		$img = "<img src=\"img/defautuser2.png\" alt=\"avatar\">";
	}
	if($i%2 == 0) { echo "<tr class=\"gris\">"; } else { echo "<tr>"; }
	echo "<td class=\"img-film\">";
	echo $img;
	echo "</td>";
	echo "<td>";
	echo $val['nom'];
	echo "</td>";
	echo "<td>";
	echo $val['prenom'];
	echo "</td>";
	echo "<td>";
	echo $val['email'];
	echo "</td>";
	echo "<td>";
	echo $val['ville'];
	echo "</td>";
	echo "<td class=\"actions\">";
	echo "<a href=\"index.php?file=ProfilPersonnel&amp;id=" . $val['id_fiche_perso'] . "\" title=\"Accéder au profil\"><i class='icon-list-alt'></i></a>"; 
	echo "<a href=\"index.php?file=ListPersonnel&amp;del=" . $val['id_personnel'] . "\" title=\"\"><i class='icon-remove' ></i></a>";
	echo "</td>";
	echo "</tr>";
	$i++;
}

?>
</table>

<table>
	<caption  class="nettoyage">Service Nettoyage</caption>
	<tr>
		<th>Photo</th>
		<th>Nom</th>
		<th>Prenom</th>
		<th>Mail</th>
		<th>Ville</th>
		<th>Actions</th>
	</tr>
<?php

$i = 0;
foreach($perso_nettoyage as $val)
{
		if(file_exists("upload/img/user" . $val['id_fiche_perso'] . ".png"))
	{
 		$img = "<img src=\"upload/img/user" . $val['id_fiche_perso'] . ".png\" alt=\"avatar\">";
	}
	else
	{
 		$img = "<img src=\"img/defautuser2.png\" alt=\"avatar\">";
	}
	if($i%2 == 0) { echo "<tr class=\"gris\">"; } else { echo "<tr>"; }
	echo "<td class=\"img-film\">";
	echo $img;
	echo "</td>";
	echo "<td>";
	echo $val['nom'];
	echo "</td>";
	echo "<td>";
	echo $val['prenom'];
	echo "</td>";
	echo "<td>";
	echo $val['email'];
	echo "</td>";
	echo "<td>";
	echo $val['ville'];
	echo "</td>";
	echo "<td class=\"actions\">";
	echo "<a href=\"index.php?file=ProfilPersonnel&amp;id=" . $val['id_fiche_perso'] . "\" title=\"Accéder au profil\"><i class='icon-list-alt'></i></a>"; 
	echo "<a href=\"index.php?file=ListPersonnel&amp;del=" . $val['id_personnel'] . "\" title=\"\"><i class='icon-remove' ></i></a>";
	echo "</td>";
	echo "</tr>";
	$i++;
}

?>
</table>

<?php 
$page_nb = round(countTable($bdd, "tp_personnel") / $_SESSION['limit']);
?>
<div class="pagination">
  <ul>
<?php

if($page > 0) {
	$i = $page -1;
	echo "<li><a href=\"index.php?file=ListPersonnel&amp;page=0\">&laquo;</a></li>";
 	echo "<li><a href=\"index.php?file=ListPersonnel&amp;page=" . $i . "\">&lsaquo;</a></li>"; 
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
			echo "<li class=\"active\"><a href=\"index.php?file=ListPersonnel&amp;page=" . $i . "\">$i</a></li>";
		}
		else
		{
			echo "<li><a href=\"index.php?file=ListPersonnel&amp;page=" . $i . "\">$i</a></li>";
		}
	}
	else
	{
		if($i == $page + 3)
		{
			echo "<li class=\"disabled\"><a href=\"index.php?file=ListPersonnel&amp;page=" . $i . "\">...</a></li>";
		}
		else if($i == 2 && $page > 4)
		{
			echo "<li class=\"disabled\"><a href=\"index.php?file=ListPersonnel&amp;page=" . $i . "\">...</a></li>";
		}	
	}
	
}

if($page != $page_nb) 
{
	$i = $page +1;
	echo "<li><a href=\"index.php?file=ListPersonnel&amp;page=" . $i . "\">&rsaquo;</a></li>";
	echo "<li><a href=\"index.php?file=ListPersonnel&amp;page=$page_nb\">&raquo;</a></li>";
}
else
{
	echo "<li class=\"disabled\"><a href=\"index.php?file=ListPersonnel&amp;page=$page_nb\">&raquo;</a></li>";
}

?>
  </ul>
</div>
