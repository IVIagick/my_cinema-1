<?php session_start();
/**
 * [Projet my_cinema]
 * @author [rivier_n]
 */

if(!isset($_SESSION['mail'])) {
	header('Location: connexion.php');
}

require_once("inc/config.php");
require_once("inc/db.php");
require_once("inc/functions.php");
require_once("inc/header.php");

$inc = array();
$included = false;

$folder = opendir("./inc/");
while($file = readdir($folder)) {
	$path = pathinfo($file);
	if($path['extension'] == "php" && $file != "config.php") {
		$inc[] = $path['filename'];
	}
}

foreach($inc as $val) {
	if(!isset($_GET['file'])) {
		include_once("inc/home.php");
		$included = true;
		break;
	}
	if (isset($_GET['file']) && $_GET['file'] != "index" && $_GET['file'] != "config" && $_GET['file'] != "header" && $_GET['file'] != "footer" && $_GET['file'] != "functions" && $_GET['file'] != "db") {
		if($_GET['file'] == $val) {
			include_once("inc/".$val.".php");
			$included = true;
			break;
		}
	}
}
if(!$included) {
	include_once("inc/404.php");
}

require_once("inc/footer.php");
bddclose($bdd);
?>
