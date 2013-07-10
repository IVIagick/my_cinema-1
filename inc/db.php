<?php


if($bdd = mysqli_connect($sql_host, $sql_user, $sql_pass, $sql_db)) {
}
else {
	echo "Impossible de se connecter a la base de donnees";
	exit;

}
if (!mysqli_set_charset($bdd, "utf8")) {
    printf("Erreur lors du chargement du jeu de caractères utf8 : %s\n", mysqli_error($bdd));
    exit;
}

function bddclose($bdd) {
	mysqli_close($bdd);
	return;
}

function getTable($bdd, $table , $page = 0, $tri, $max = 20) {
	$tri = mysqli_real_escape_string($bdd, $tri);
	$table = mysqli_real_escape_string($bdd, $table);
	$page = abs(intval($page));
	$max = abs(intval($max));
	$page *= $max;
	$resultat = mysqli_query($bdd, "SELECT * FROM $table ORDER BY $tri LIMIT $page, $max");
	$tabres = array();
	while($row = mysqli_fetch_assoc($resultat))
	{
   		$tabres[] = $row;
	}
	mysqli_free_result($resultat);
	return $tabres;
}

function getTableWhere($bdd, $table , $name_where, $name) {
	$table = mysqli_real_escape_string($bdd, $table);
	$name_where = mysqli_real_escape_string($bdd, $name_where);
	$name = mysqli_real_escape_string($bdd, $name);
	$resultat = mysqli_query($bdd, "SELECT * FROM $table WHERE $name_where = \"$name\"");
	$tabres = array();
	while($row = mysqli_fetch_assoc($resultat))
	{
   		$tabres[] = $row;
	}
	mysqli_free_result($resultat);
	return $tabres;
}

function getTableAll($bdd, $table, $id , $name_where, $tri, $join, $tabjoin1, $tabjoin2) {
	$resultat = mysqli_query($bdd, "SELECT * FROM $table LEFT JOIN $join ON $table.$tabjoin1 = $join.$tabjoin2 WHERE $name_where LIKE \"%$id%\" ORDER BY $tri ");
	$tabres = array();
	while($row = mysqli_fetch_assoc($resultat))
	{
   		$tabres[] = $row;
	}
	mysqli_free_result($resultat);
	return $tabres;
}

function getTableAll2($bdd, $table, $id , $name_where, $tri, $join, $tabjoin1, $tabjoin2) {
	$resultat = mysqli_query($bdd, "SELECT * FROM $table LEFT JOIN $join ON $table.$tabjoin1 = $join.$tabjoin2 WHERE $name_where = \"$id\"");
	$tabres = array();
	while($row = mysqli_fetch_assoc($resultat))
	{
   		$tabres[] = $row;
	}
	mysqli_free_result($resultat);
	return $tabres;
}

function checkTable($bdd, $table, $name, $id , $name_where)
{
	$resultat = mysqli_query($bdd, "SELECT $name FROM $table WHERE $name_where = \"$id\" LIMIT 1");
	$row = mysqli_fetch_assoc($resultat);
	mysqli_free_result($resultat);
	return $row[$name];
}


function getInfo($bdd, $table, $name_where, $name) {
	$resultat = mysqli_query($bdd, "SELECT * FROM $table WHERE $name_where = \"$name\"");
	$row = mysqli_fetch_assoc($resultat);
	mysqli_free_result($resultat);
	return $row;
}

function checkTableLike($bdd, $table, $name, $id , $name_where)
{
	$resultat = mysqli_query($bdd, "SELECT $name FROM $table WHERE $name_where LIKE \"$id\"");
	$row = mysqli_fetch_assoc($resultat);
	mysqli_free_result($resultat);
	return $row[$name];
}

function checkTableLikeT($bdd, $table, $name, $id , $name_where)
{
	$resultat = mysqli_query($bdd, "SELECT $name FROM $table WHERE $name_where LIKE \"%$id%\"");
	$tab = array();
	while($row = mysqli_fetch_assoc($resultat))
	{
		$tab[] = $row;
	}
	mysqli_free_result($resultat);
	return $tab;
}

function delTable($bdd, $table , $id, $name_where) {
	$id = abs(intval($id));
	mysqli_query($bdd, "DELETE FROM $table WHERE $name_where =$id");
}

function verifTable($bdd, $table, $id , $name_where)
{
	$table = mysqli_real_escape_string($bdd, $table);
	$name = mysqli_real_escape_string($bdd, $name_where);
	if(preg_match("/id/", $id)){ $id = abs(intval($id)); }
	$resultat = mysqli_query($bdd, "SELECT COUNT($name_where) AS res FROM $table WHERE $name_where = \"$id\" LIMIT 1");
	$row = mysqli_fetch_assoc($resultat);
	mysqli_free_result($resultat);
	return $row['res'];
}


function countTable($bdd, $table) 
{
	$table = mysqli_real_escape_string($bdd, $table);
	$res = mysqli_query($bdd, "SELECT count(*) AS res FROM $table");
	$donnees = mysqli_fetch_assoc($res);
	mysqli_free_result($res);
	return $donnees['res'];
}

function maxTable($bdd, $table, $name_where) 
{
	$table = mysqli_real_escape_string($bdd, $table);
	$res = mysqli_query($bdd, "SELECT max($name_where) AS res FROM $table");
	$donnees = mysqli_fetch_assoc($res);
	mysqli_free_result($res);
	return $donnees['res'];
}

// Films //

function addFilm($bdd, $id_genre, $id_distrib, $titre, $resum, $date_debut, $date_fin, $duree_min, $annee_prod){
	$id = maxTable($bdd, "tp_film", "id_film") + 1;
	$req = mysqli_prepare($bdd, 'INSERT INTO tp_film(id_film,id_genre, id_distrib, titre, resum,date_debut_affiche, date_fin_affiche, duree_min,annee_prod) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?)');
	mysqli_stmt_bind_param($req, "iiissssii",$id, $id_genre, $id_distrib, $titre, $resum, $date_debut, $date_fin, $duree_min,$annee_prod );
	mysqli_stmt_execute($req);
}

function getFilmsSearch($bdd, $search, $type){
	$search = mysqli_real_escape_string($bdd, $search);
	$resultat = mysqli_query($bdd, "SELECT * FROM tp_film WHERE $type LIKE '%$search%' ");
	$tabres = array();
	while($row = mysqli_fetch_assoc($resultat))
	{
   		$tabres[] = $row;
	}
	mysqli_free_result($resultat);
	return $tabres;
}

// Abonnements //

function addAbo($bdd, $nom, $resum, $prix, $duree){
	$id = maxTable($bdd, "tp_abonnement", "id_abo") + 1;
	$req = mysqli_prepare($bdd, 'INSERT INTO tp_abonnement(id_abo, nom, resum, prix, duree_abo) VALUES (?,?,?, ?, ?)');
	mysqli_stmt_bind_param($req, "issii",$id, $nom, $resum, $prix, $duree );
	mysqli_stmt_execute($req);
}

// Genre //

function addGenre($bdd, $nom){
	$id = maxTable($bdd, "tp_genre", "id_genre") + 1;
	$req = mysqli_prepare($bdd, 'INSERT INTO tp_genre(id_genre, nom) VALUES (?,?)');
	mysqli_stmt_bind_param($req, "is",$id, $nom);
	mysqli_stmt_execute($req);
}

// Reducs //

function addReducs($bdd, $nom, $date_debut , $date_fin, $pourcentage_reduc){
	$id = maxTable($bdd, "tp_reduction", "id_reduction") + 1;
	$req = mysqli_prepare($bdd, 'INSERT INTO tp_reduction(id_reduction, nom, date_debut, date_fin, pourcentage_reduc) VALUES (?,?,?,?,?)');
	mysqli_stmt_bind_param($req, "isssi",$id, $nom , $date_debut, $date_fin, $pourcentage_reduc);
	mysqli_stmt_execute($req);
}

// Membres

function addMember($bdd, $nom, $prenom, $mail,$password, $date_naissance, $adresse, $ville, $pays, $cpostal){
	$id = maxTable($bdd, "tp_fiche_personne", "id_perso") + 1;
	$req = mysqli_prepare($bdd, 'INSERT INTO tp_fiche_personne(id_perso,nom, prenom, date_naissance, email,password, adresse,cpostal, ville, pays) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?)');
	mysqli_stmt_bind_param($req, "issssssiss",$id, $nom, $prenom, $date_naissance, $mail, $password,$adresse, $cpostal, $ville, $pays );
	mysqli_stmt_execute($req);
}

function addMemberAbo($bdd, $abo ,$id_fiche_perso){
	$id = maxTable($bdd, "tp_membre", "id_membre") + 1;
	$req = mysqli_prepare($bdd, 'INSERT INTO tp_membre(id_membre,id_fiche_perso,id_abo, date_abo, date_inscription) VALUES (?,?, ?, NOW(), NOW())');
	mysqli_stmt_bind_param($req, "iii",$id, $id_fiche_perso, $abo);
	mysqli_stmt_execute($req);
}


function editMember($bdd, $nom, $prenom, $mail,$password, $date_naissance, $adresse, $ville, $pays, $cpostal,$id){
	$req = mysqli_prepare($bdd, 'UPDATE tp_fiche_personne SET nom = ?, prenom = ?, date_naissance = ?, email = ? , password = ? ,adresse = ?,cpostal = ?, ville = ?, pays = ? WHERE id_perso = ?');
	mysqli_stmt_bind_param($req, "ssssssissi", $nom, $prenom, $date_naissance, $mail, $password, $adresse, $cpostal, $ville, $pays, $id );
	mysqli_stmt_execute($req);
}

function editMemberAbo($bdd, $abo ,$id_fiche_perso, $date_abo){
	$req = mysqli_prepare($bdd, 'UPDATE tp_membre SET  id_abo = ?, date_abo = ? WHERE id_fiche_perso = ?');
	mysqli_stmt_bind_param($req, "isi", $abo, $date_abo, $id_fiche_perso);
	mysqli_stmt_execute($req);
}

//Histo Film
function editHistoFilm($bdd, $old_name,$film_name,$date_film){
	$req = mysqli_prepare($bdd, 'UPDATE tp_historique_membre SET  id_film = ?, date = ? WHERE id = ?');
	mysqli_stmt_bind_param($req, "isi", $film_name, $date_film, $old_name);
	mysqli_stmt_execute($req);
}

function editavis($bdd, $avis, $id){
	$req = mysqli_prepare($bdd, 'UPDATE tp_historique_membre SET  avis = ? WHERE id = ?');
	mysqli_stmt_bind_param($req, "si", $avis, $id);
	mysqli_stmt_execute($req);
}

function addHisto($bdd,$id_membre, $film_name, $date_film,$avis){
	$req = mysqli_prepare($bdd, 'INSERT INTO tp_historique_membre(id_membre, id_film, date, avis) VALUES (?, ? , ?,?)');
	mysqli_stmt_bind_param($req, "iiss",$id_membre, $film_name, $date_film,$avis);
	mysqli_stmt_execute($req);
}

function maxTableHisto($bdd, $id_user) 
{
	$res = mysqli_query($bdd, "SELECT MAX(date) AS date_film FROM tp_historique_membre WHERE id_membre = $id_user");
	$donnees = mysqli_fetch_assoc($res);
	mysqli_free_result($res);
	$resultat = $donnees['date_film'];

	$result = mysqli_query($bdd, "SELECT titre FROM tp_film LEFT JOIN tp_historique_membre ON tp_film.id_film = tp_historique_membre.id_film WHERE tp_historique_membre.date = \"$resultat\"");
	$donnee = mysqli_fetch_assoc($result);
	mysqli_free_result($result);
	return $donnee['titre'];
}
// Distributions

function addDistrib($bdd, $nom, $telephone, $adresse, $ville, $pays, $cpostal){
	$id = maxTable($bdd, "tp_distrib", "id_distrib") + 1;
	$req = mysqli_prepare($bdd, 'INSERT INTO tp_distrib(id_distrib,nom, telephone, adresse, cpostal, ville, pays) VALUES (?,?, ?, ?, ?, ?, ?)');
	mysqli_stmt_bind_param($req, "isisiss",$id, $nom, $telephone, $adresse, $cpostal, $ville,$pays );
	mysqli_stmt_execute($req);
}

// connexion
function Connexion($bdd, $mail, $password)
{
	$mail = mysqli_real_escape_string($bdd,$mail);
	$password = mysqli_real_escape_string($bdd,$password);
	$resultat = mysqli_query($bdd, "SELECT COUNT(*) AS res FROM tp_fiche_personne WHERE email = \"$mail\" AND password = \"$password\"");
	$row = mysqli_fetch_assoc($resultat);
	mysqli_free_result($resultat);
	return $row['res'];
}
?>