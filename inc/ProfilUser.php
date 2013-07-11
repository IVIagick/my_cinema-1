<?php
 
if(!empty($_GET['id'])){
   
   $id = $_GET['id'];
   $user = checkTable($bdd, "tp_membre", "id_fiche_perso", $id , "id_fiche_perso");
   if ( empty($user) )
   {
      header("Location: index.php?page=404");
         exit;
   }
}
else
{
      header("Location: index.php?page=404");
         exit; 
}
$members_list = getTableAll2($bdd, "tp_membre", $id , "id_fiche_perso", "id_membre", "tp_fiche_personne", "id_fiche_perso", "id_perso"); // Récupération des infos users
$countfilms = countTable($bdd, "tp_historique_membre");
$films_list = getTable($bdd, "tp_historique_membre WHERE id_membre =" . checkTable($bdd, "tp_membre", "id_membre" , $_GET['id'], "id_fiche_perso"), 0, "date DESC", $countfilms ); // Récupération de la liste de films
$countfilmshist = countTable($bdd, "tp_film");
$films_hist = getTable($bdd, "tp_film", 0, "titre", $countfilmshist); // Récupération de la liste de films

if(file_exists("../upload/img/user" . $id . ".png"))
{
   $img = "<img src=\"upload/img/user" . $id . ".png\" alt=\"avatar\">";
}
else
{
   $img = "<img src=\"img/defautuser.png\" alt=\"avatar\">";
}
?>
<div class="bloc_page_profil">
            <div class="container profil_title_bg profil" id="titleprofil">
            <div class="title_profil">
               <h5>Profil de <?php echo $members_list['0']['nom'] . " " . $members_list['0']['prenom']; ?></h5>
            </div>
   </div>
   <div class="container profil_ctn" id="ctnprofil">
         <table class="profil-list">
            <tr class="th">
               <th colspan="2" class="th-profil">Informations concernant <?php echo $user; ?></th>
            </tr>
            <?php

         echo "<tr>"; // Avatar
         echo "<th class=\"th\">";
         echo "Avatar : ";
         echo "</th>";
         echo "<td>";
         echo $img ;
         echo "</td>";
         echo "</tr>";

         echo "<tr>"; // Nom Prenom
         echo "<th class=\"th\">";
         echo "Nom : ";
         echo "</th>";
         echo "<td>";
         echo $members_list['0']['nom'] . " " . $members_list['0']['prenom'] ;
         echo "</td>";
         echo "</tr>";

         echo "<tr>"; // Dernier film vu
         echo "<th class=\"th\">";
         echo "Dernier film vu : ";
         echo "</th>";
         echo "<td>";
         echo maxTableHisto($bdd, $id);
         echo "</td>";
         echo "</tr>";

         echo "<tr>";
         echo "<th colspan=\"2\" class=\"th-profil\">";
         echo "Films vus par le membre";
         echo "</th>";
         echo "</tr>";

         foreach($films_list as $val){
            $date_film = $val['date'];
            $an=substr($date_film,0,4); // Découpage de la date - annee
            $mois=substr($date_film,5,2); // Découpage de la date - mois
            $jour=substr($date_film,8,2); // Découpage de la date - jours
            $date_film = $jour . "/" . $mois . "/" . $an;
            
            echo "<tr>"; // films
            echo "<th class=\"th\">";
            echo $date_film;
            echo "</th>";
            echo "<td class=\"td-film\">";
            echo checkTable($bdd, "tp_film", "titre" , $val['id_film'], "id_film");
            if(!empty($val['avis'])){
               if(strlen($val['avis']) < 70)
               {
                  echo "<em class=\"empty\"> ‟" . htmlentities($val['avis']) ."”</em>"; 
               }
               else
               {
                  echo "<em class=\"empty\"> ‟" . substr(htmlentities($val['avis']),0,70) ."...”</em>"; 
               }
            }
            echo "</td>";
            echo "</tr>";
         }

      ?>
         </table>
   </div>
</div>
