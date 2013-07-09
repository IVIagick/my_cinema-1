<?php
$current = $_SERVER['REQUEST_URI'];

 // Pour les listes
if(!isset($_POST['limit']) && !isset($_SESSION['limit']))
{
	$max = 25;
	$_SESSION['limit'] = $max;
}
else if(isset($_POST['limit']) && $_POST['limit'] != 0 )
{
	$_SESSION['limit'] = $_POST['limit'];
	$page = 0;
	$_GET['page'] = 0;
}
if(!isset($_GET['page']))
{
	$page = 0;
}
else
{
	$page = $_GET['page'];
}
if(!isset($_POST['tri']) && empty($_POST['tri']))
{
	if(isset($_GET['file']))
	{
		switch($_GET['file'])
		{
			case "ListFilms":
				$tri = "titre";
				break;
			case "ListAbo":
				$tri = "id_abo";
				break;
			case "ListUsers":
				$tri = "id_membre";
				break;
			case "ListMembres":
				$tri = "id_fiche_perso";
				break;
			case "ListDistrib":
				$tri = "id_distrib";
				break;
			case "ListGenre":
				$tri = "id_genre";
				break;
			case "ListReducs":
				$tri = "id_reduction";
				break;
		}
	}
}
else
{
	$tri = $_POST['tri'];
}


?>