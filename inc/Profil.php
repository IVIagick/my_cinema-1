<?php
 
if(!isset($_SESSION['id_job'])) {
   if(!empty($_GET['id']) && $_SESSION['id'] != $_GET['id'])
   {
      header('Location: index.php?file=404');
   }
}

if (isset($_GET['id']))
{
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
$countabo = countTable($bdd, "tp_abonnement");
$abo_list = getTable($bdd, "tp_abonnement", 0, "id_abo", $countabo ); // Récupération de la liste d'abonnements
$countfilms = countTable($bdd, "tp_historique_membre");
$films_list = getTable($bdd, "tp_historique_membre WHERE id_membre =" . checkTable($bdd, "tp_membre", "id_membre" , $_GET['id'], "id_fiche_perso"), 0, "date DESC", $countfilms ); // Récupération de la liste de films
?>
<span class="btn btn-primary add" onClick="addabo('add');">Modifier le membre</span>
<div class="contain" id="add" style="<?php if(isset($_POST['bouton-add'])){ echo "display:block"; }else{ echo "display:none"; }?>">
      <form method="POST">
<?php
// Modification
if(isset($_POST['bouton-add']))
{

   if(!empty($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['mail']))
   {
      $mail= htmlentities($_POST['mail']);
      $verif = verifTable($bdd, "tp_fiche_personne", $mail , "email");
      if($verif == 0 || $mail == $members_list['0']['email'])
      {
         if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/",$_POST['mail']))
         {
            $nom= htmlentities($_POST['nom']);
            $prenom= htmlentities($_POST['prenom']);
            $date_naissance= htmlentities($_POST['date_naissance']);
            $adresse= htmlentities($_POST['adresse']);
            $cpostal= htmlentities($_POST['cpostal']);
            if(!empty($_POST['password'])) { $password = MD5(SHA1($_POST['password'])); } else { $password = $members_list['0']['password'];};
            $ville= htmlentities($_POST['ville']);
            if(!empty($_POST['abo'])){ if($date_abo == "0000-00-00 00:00:00" || $date_abo == ""){
               echo "<div class=\"alert alert-error\">
                  <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                  <strong>Erreur :</strong> Merci d'indiquer une date de début d'abonnement.
                </div>";
                $error = true;
               }
            }
            $pays= htmlentities($_POST['pays']);
            $id_perso = $members_list['0']['id_perso'];
            if(empty($_SESSION['id_job'])) {
               $abo = $members_list['0']['id_abo'];
               $date_abo = $members_list['0']['date_abo'];
            }
            else
            {
               $abo= abs(intval($_POST['abo']));
               $date_abo= htmlentities($_POST['date_abo']);
            }
            if(empty($error))
            {

                editMember($bdd, $nom, $prenom, $mail,$password, $date_naissance, $adresse, $ville, $pays, $cpostal,$id_perso);
                editMemberAbo($bdd, $abo ,$id_perso, $date_abo);
                if(isset($_SESSION['id_job'])) { 
                echo "<div class=\"alert alert-success\">
                      <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                   <strong>Succès :</strong> Le membre a été modifié avec succès.
                </div>";
               }
               else
               {
                  echo "<div class=\"alert alert-success\">
                      <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                   <strong>Succès :</strong> Votre compte a été modifié avec succès.
                </div>";
               }
             }
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

// Modification historique film
if(isset($_POST['bouton-edit']))
{
   if(isset($_POST['oldname']) && isset($_POST['filmname']) && isset($_POST['datefilm']))
   {
      $film_name= htmlentities($_POST['filmname']);
      $verif = verifTable($bdd, "tp_film", $film_name , "id_film");
      if($verif == 1)
      {
            $old_name= htmlentities($_POST['oldname']);
            $date_film= htmlentities($_POST['datefilm']);
            if($date_film == "0000-00-00 00:00:00" || $date_film == ""){
               $error =  "<div class=\"alert alert-error\">
                  <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                  <strong>Erreur :</strong> Merci d'indiquer une date.
                </div>";
            }
            if(empty($error))
            {
                editHistoFilm($bdd, $old_name,$film_name,$date_film);
                $error = "<div class=\"alert alert-success\">
                      <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                   <strong>Succès :</strong> L'historique a été modifié avec succès.
                </div>";
             }
      }
      else
      {
         $error = "<div class=\"alert alert-error\" >
               <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
            <strong>Erreur :</strong> Ce film n'existe pas.
         </div>";
      }
   }
   else
   {
      $error = "<div class=\"alert alert-error\">
               <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
            <strong>Erreur :</strong> Merci de remplir les champs obligatoires.
         </div>";
   }
}

// Suppression
if(!empty($_GET['del']))
{
   $verif = verifTable($bdd, "tp_historique_membre", $_GET['del'] , "id");
   if($verif == 1 && preg_match("/-/", $_GET['del']) == 0)
   {
         delTable($bdd, "tp_historique_membre" , $_GET['del'], "id");
         $error_del = "<div class=\"alert alert-success\" >
                  <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
               <strong>Succès :</strong> Cet historique a été supprimé avec succès.
            </div>";
   }
   else
   {
      $error_del = "<div class=\"alert alert-error\">
               <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
            <strong>Erreur :</strong> Cet historique n'existe pas.
         </div>";
   }
}

// Ajout
if(isset($_POST['bouton-addfilm']))
{

   if(isset($_POST['film-name']) && !empty($_POST['date-film']))
   {
         $film_name = htmlentities($_POST['film-name']);
         $date_film = htmlentities($_POST['date-film']);
         $avis = htmlentities($_POST['avis']);

         $verif = verifTable($bdd, "tp_film", $film_name , "id_film");
          if($verif == 1)
         {
            addHisto($bdd,checkTable($bdd, "tp_membre", "id_membre" , $_GET['id'], "id_fiche_perso"), $film_name, $date_film,$avis);
            $error_add = "<div class=\"alert alert-success\">
                  <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
               <strong>Succès :</strong> Le film a été ajoutée avec succès.
             </div>";
          }
         else
         {
            $error_add = "<div class=\"alert alert-error\">
               <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
            <strong>Erreur :</strong> Ce film n'existe pas.
            </div>";
         }
   }
   else
   {
      $error_add =  "<div class=\"alert alert-error\">
               <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
            <strong>Erreur :</strong> Veuillez remplir tous les champs.
         </div>";
   }
}

$members_list = getTableAll2($bdd, "tp_membre", $id , "id_fiche_perso", "id_membre", "tp_fiche_personne", "id_fiche_perso", "id_perso"); // Récupération des infos users
$countabo = countTable($bdd, "tp_abonnement");
$abo_list = getTable($bdd, "tp_abonnement", 0, "id_abo", $countabo ); // Récupération de la liste d'abonnements
$countfilms = countTable($bdd, "tp_historique_membre");
$films_list = getTable($bdd, "tp_historique_membre WHERE id_membre =" . checkTable($bdd, "tp_membre", "id_membre" , $_GET['id'], "id_fiche_perso"), 0, "date DESC", $countfilms ); // Récupération de la liste de films
$countfilmshist = countTable($bdd, "tp_film");
$films_hist = getTable($bdd, "tp_film", 0, "titre", $countfilmshist); // Récupération de la liste de films
?>
         <label for="nom">Nom :</label>
            <input type="text" name="nom" id="nom" placeholder="Entrez un nom" required value="<?php echo $members_list['0']['nom'];?>">
         <br>
         <label for="prenom">Prénom : </label>
            <input type="text" name="prenom" id="prenom" placeholder="Entrez un prenom" required value="<?php echo $members_list['0']['prenom'];?>">
         <br>
         <label for="mail">Mail : </label>
            <input type="text" name="mail" id="mail" placeholder="Entrez un mail" required pattern="^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$" value="<?php echo $members_list['0']['email'];?>">  
         <br>
         <label for="password">Password *: </label>
            <input type="password" name="password" id="password" placeholder="Entrez un password" > 
         <br>
         <label for="date_naissance">Date de naissance : </label>
            <input type="text" id="date_naissance" name="date_naissance" value="<?php echo $members_list['0']['date_naissance'];?>"> 
         <br>
         <label for="adresse">Adresse : </label>
            <input type="text" name="adresse" id="adresse" placeholder="Entrez une adresse" value="<?php echo $members_list['0']['adresse'];?>">
         <br>
         <label for="cpostal">Code postal : </label>
            <input type="number" max="99999" min="00000" name="cpostal" id="cpostal" placeholder="Entrez un code postal" value="<?php echo $members_list['0']['cpostal'];?>">
         <br>
         <label for="ville">Ville : </label>
            <input type="text" name="ville" id="ville" placeholder="Entrez une ville" value="<?php echo $members_list['0']['ville'];?>">
         <br>
         <label for="pays">Pays : </label>
            <input type="text" name="pays" id="pays" placeholder="Entrez un pays" value="<?php echo $members_list['0']['pays'];?>">
         <br>
         <?php if(isset($_SESSION['id_job'])) { ?>
         <label for="abo">Abonnement: </label>
            <select id="abo" name="abo">
               <?php if(!empty($_POST['abo'])) { echo "<option value=\"" . abs(intval($_POST['abo'])) . "\">" . checkTable($bdd, "tp_abonnement", "nom" , $_POST['abo'], "id_abo") . "</option>"; }?>
               <?php if(!empty($members_list['0']['id_abo'])) { echo "<option value=\"" . $members_list['0']['id_abo'] . "\">" . checkTable($bdd, "tp_abonnement", "nom" , $members_list['0']['id_abo'], "id_abo") . "</option>"; } ?>
               <option value="">Non défini</option>
               <?php foreach($abo_list as $val)
               {
                  if($val['id_abo'] != $members_list['0']['id_abo'])
                  {
                     echo "<option value=\"" . $val['id_abo'] . "\">" . $val['nom'] . "</option>";
                  }
               } ?>
            </select>
         <br>
         <label for="date_abo">Début d'abonnement *</label>
         <input type="text" name="date_abo" id="date_abo" placeholder="Date de début de l'abonnement" value="<?php echo $members_list['0']['date_abo'];?>">
         <?php } ?>
         <em>Champs obligatoires * </em>
         <input type="submit" value="Modifier" name="bouton-add">
      </form>
   </div>
<?php

if(file_exists("../upload/img/user" . $id . ".png"))
{
   $img = "<img src=\"upload/img/user" . $id . ".png\" alt=\"avatar\">";
}
else
{
   $img = "<img src=\"img/defautuser.png\" alt=\"avatar\">";
}

if(isset($error_del)){echo $error_del;}
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

         $nbj_abo = checkTable($bdd, "tp_abonnement", "duree_abo" , $members_list['0']['id_abo'], "id_abo"); // Durée de l'abonnement
         $date_abo = $members_list['0']['date_abo']; // Date de l'abonnement
         $an=substr($date_abo,0,4); // Découpage de la date - annee
         $mois=substr($date_abo,5,2); // Découpage de la date - mois
         $jour=substr($date_abo,8,2); // Découpage de la date - jours
         if($an != 0000 && $mois != 00 && $jour != 00)
         {
            $date_fin_abo = date("d-m-Y", mktime(0, 0, 0, $mois, $jour+$nbj_abo, $an)); // Date de fin de l'abonnement
         }
         else
         {
            $date_fin_abo = "";
         }

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

         echo "<tr>"; // Mail
         echo "<th class=\"th\">";
         echo "Mail : ";
         echo "</th>";
         echo "<td>";
         echo stripslashes($members_list['0']['email']) ;
         echo "</td>";
         echo "</tr>";

         echo "<tr>"; // Date de naissance
         echo "<th class=\"th\">";
         echo "Date de naissance : ";
         echo "</th>";
         echo "<td>";
         echo stripslashes($members_list['0']['date_naissance']) ;
         echo "</td>";
         echo "</tr>";

         echo "<tr>"; // Adresse
         echo "<th class=\"th\">";
         echo "Adresse : ";
         echo "</th>";
         echo "<td>";
         echo stripslashes($members_list['0']['adresse']) ;
         echo "</td>";
         echo "</tr>";

         echo "<tr>"; // Code postal
         echo "<th class=\"th\">";
         echo "Code postal : ";
         echo "</th>";
         echo "<td>";
         echo stripslashes($members_list['0']['cpostal']) ;
         echo "</td>";
         echo "</tr>";

         echo "<tr>"; // Ville
         echo "<th class=\"th\">";
         echo "Ville : ";
         echo "</th>";
         echo "<td>";
         echo stripslashes($members_list['0']['ville']) ;
         echo "</td>";
         echo "</tr>";

         echo "<tr>"; // Pays
         echo "<th class=\"th\">";
         echo "Pays : ";
         echo "</th>";
         echo "<td>";
         echo stripslashes($members_list['0']['pays']) ;
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
         echo "Abonnement du membre <button class=\"btn btn-primary edit-hist\" onClick=\"addabo('add');\">Modifer</button>";
         echo "</th>";
         echo "</tr>";

         echo "<tr>"; // Abonnement
         echo "<th class=\"th\">";
         echo "Abonnement : ";
         echo "</th>";
         echo "<td>";
         echo checkTable($bdd, "tp_abonnement", "nom" , $members_list['0']['id_abo'], "id_abo");
         echo "</td>";
         echo "</tr>";

         echo "<tr>"; // Fin Abonnement
         echo "<th class=\"th\">";
         echo "Date de fin d'abonnement : ";
         echo "</th>";
         echo "<td>";
         echo $date_fin_abo;
         echo "</td>";
         echo "</tr>";

         echo "<tr>";
         echo "<th colspan=\"2\" class=\"th-profil\">";
         echo "Films vus par le membre";
         echo "</th>";
         echo "</tr>";

         echo "<tr id=\"edit-histo\">";
         echo "<th>";
         if(isset($error_add)){echo $error_add;}
         echo "<form class=\"td-edit\" method=\"POST\">
                  <select name=\"film-name\" >";
                  foreach($films_hist as $val){
                     echo "<option class=\"select-table\" value=\"" . $val['id_film'] . "\">" . $val['titre'] . "</option>";
                            }
                echo  "</select>
                  <input type=\"date\" name=\"date-film\">
                  <textarea name=\"avis\" placeholder=\"Ajouter un avis...\"></textarea>
                  <input type=\"submit\" class=\"btn btn-success\" name=\"bouton-addfilm\" value=\"Ajouter\">
               </form>";
         echo "</th>";
         echo "<th>";
         if(isset($error)){echo $error;}
         echo "<form class=\"td-edit\" method=\"POST\">
         <label>Remplacer</label>
                  <select name=\"oldname\">";
                  foreach($films_list as $val){
                     echo "<option  value=\"" . $val['id'] . "\">". substr($val['date'],0,10) . " - " . checkTable($bdd, "tp_film", "titre" , $val['id_film'], "id_film") . "</option>";
                            }
                echo  "</select>
                 <label>par</label>
                <select name=\"filmname\" >";
                  foreach($films_hist as $val){
                     echo "<option class=\"select-table\" value=\"" . $val['id_film'] . "\">" . $val['titre'] . "</option>";
                            }
                echo  "</select>
                <input type=\"date\" name=\"datefilm\">
                <input type=\"submit\" class=\"btn btn-primary\" name=\"bouton-edit\" value=\"Modifier\">
               </form>";
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
            echo "<td class=\"td-film\" onMouseOver=\"suppfilm('film". $val['id'] . "');suppfilm('avis". $val['id'] . "')\" onMouseOut=\"suppfilm2('film". $val['id'] . "');suppfilm2('avis". $val['id'] . "')\">";
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
            echo "<a href=\"index.php?file=Avis&amp;avis=" . $val['id'] . "\" style=\"visibility:hidden\" class=\"btn btn-primary avis-hist\" id=\"avis" . $val['id'] . "\" >Afficher</button>";
            echo "<a href=\"index.php?file=Profil&amp;id=" . $_GET['id'] . "&amp;del=" . $val['id']. "\" style=\"visibility:hidden\" class=\"btn btn-danger edit-hist\" id=\"film" . $val['id'] . "\" >Supprimer</button>";
            echo "</td>";
            echo "</tr>";
         }

      ?>
         </table>
   </div>
</div>
