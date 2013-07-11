<?php
 
if(!isset($_SESSION['id_job'])) 
{
      header('Location: index.php?file=404');
}

if (isset($_GET['id']))
{
   $id = $_GET['id'];
   $user = checkTable($bdd, "tp_personnel", "id_fiche_perso", $id , "id_fiche_perso");
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
$members_list = getTableAll2($bdd, "tp_personnel", $id , "id_fiche_perso", "id_personnel", "tp_fiche_personne", "id_fiche_perso", "id_perso"); // Récupération des infos users
$countjob = countTable($bdd, "tp_job");
$job_list = getTable($bdd, "tp_job", 0, "id_job", $countjob ); // Récupération de la liste d'abonnements
?>
<span class="btn btn-primary add" onClick="addabo('add');">Modifier l'employé</span>
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
            $pays= htmlentities($_POST['pays']);
            $id_job= abs(intval($_POST['id_job']));
            $horraire= htmlentities($_POST['horraire']);
            $date_recrutement= htmlentities($_POST['date_recrutement']);
            $id_perso = $members_list['0']['id_perso'];
            if(empty($error))
            {
               editMember($bdd, $nom, $prenom, $mail,$password, $date_naissance, $adresse, $ville, $pays, $cpostal,$id_perso);
               editPersonnel($bdd, $id_job ,$horraire, $date_recrutement, $id_perso);
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


$members_list = getTableAll2($bdd, "tp_personnel", $id , "id_fiche_perso", "id_personnel", "tp_fiche_personne", "id_fiche_perso", "id_perso"); // Récupération des infos users
$countjob = countTable($bdd, "tp_job");
$job_list = getTable($bdd, "tp_job", 0, "id_job", $countjob ); // Récupération de la liste d'abonnements
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
         <label for="id_job">Jobs : </label>
            <select id="id_job" name="id_job">
               <?php if(!empty($_POST['id_job'])) { echo "<option value=\"" . abs(intval($_POST['id_job'])) . "\">" . checkTable($bdd, "tp_job", "nom" , $_POST['id_job'], "id_job") . "</option>"; }?>
               <?php if(!empty($members_list['0']['id_job'])) { echo "<option value=\"" . $members_list['0']['id_job'] . "\">" . checkTable($bdd, "tp_job", "nom" , $members_list['0']['id_job'], "id_job") . "</option>"; } ?>
               <?php foreach($job_list as $val)
               {
                  if($val['id_job'] != $members_list['0']['id_job'])
                  {
                     echo "<option value=\"" . $val['id_job'] . "\">" . $val['nom'] . "</option>";
                  }
               } ?>
            </select>
         <br>
         <label for="horraire">Horaire : </label>
            <select id="horraire" name="horraire">
               <?php if(isset($_POST['horraire']) && $_POST['horraire'] == "am") { echo "<option value=\"" . abs(intval($_POST['horraire'])) . "\">Matin</option>"; }
               else if(isset($_POST['horraire']) && $_POST['horraire'] == "pm") { echo "<option value=\"" . abs(intval($_POST['horraire'])) . "\">Aprés Midi</option>"; }
               else { echo "<option value=\"pt\">Plein Temps</option>"; }?>
               <?php if($members_list['0']['horraire'] == "am") { echo "<option value=\"" . $members_list['0']['horraire'] . "\">Matin</option>"; }
               else if($members_list['0']['horraire'] == "am") { echo "<option value=\"" . $members_list['0']['horraire'] . "\">Aprés Midi</option>"; }
               else { echo "<option value=\"pt\">Plein Temps</option>"; } ?>
               <option value="am">Matin</option>
               <option value="pm">Aprés-midi</option>
               <option value="">Plein Temps</option>
            </select>
         <br>
         <label for="date_recrutement">Date de recrutement : </label>
            <input type="text" name="date_recrutement" id="date_recrutement" placeholder="Entrez une date de recrutement" value="<?php echo $members_list['0']['date_recrutement'];?>">
         <br>
         <em>Champs obligatoires * </em>
         <input type="submit" value="Modifier" name="bouton-add">
      </form>
   </div>
<?php

if(file_exists("upload/img/user" . $id . ".png"))
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
            $theme = '';
            if($members_list[0]['id_job'] == 3){$theme='direct';}
            else if($members_list[0]['id_job'] == 2) { $theme='caisse';}
            else if($members_list[0]['id_job'] == 1) { $theme='projec';}
            else if($members_list[0]['id_job'] == 0) { $theme='nettoyage';}

         echo "<tr>"; // Avatar
         echo "<th class=\"th\">";
         echo "Avatar : ";
         echo "</th>";
         echo "<td class=\"avatar-profil\">";
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

         echo "<tr>";
         echo "<th colspan=\"2\" class=\"th-profil\">";
         echo "Informations concernant son job <button class=\"btn btn-primary edit-hist\" onClick=\"addabo('add');\">Modifer</button>";
         echo "</th>";
         echo "</tr>";

         echo "<tr>"; // Métier
         echo "<th class=\"th\">";
         echo "Métier : ";
         echo "</th>";
         echo "<td class=\"theme-perso " . $theme . "\">";
         echo checkTable($bdd, "tp_job", "nom" , $members_list['0']['id_job'], "id_job");
         echo "</td>";
         echo "</tr>";

         echo "<tr>"; // Horaire
         echo "<th class=\"th\">";
         echo "Horaires : ";
         echo "</th>";
         echo "<td>";
         if($members_list['0']['horraire'] == 'am'){ echo "Matin"; } else if($members_list['0']['horraire'] == 'pm'){ echo "Aprés-Midi"; } else { echo "Plein temps"; };
         echo "</td>";
         echo "</tr>";

         echo "<tr>"; // Date de recrutement
         echo "<th class=\"th\">";
         echo "Date de recrutement : ";
         echo "</th>";
         echo "<td>";
         echo $members_list['0']['date_recrutement'];
         echo "</td>";
         echo "</tr>";

         echo "<tr>"; // Salaire
         echo "<th class=\"th\">";
         echo "Salaire : ";
         echo "</th>";
         echo "<td>";
         echo checkTable($bdd, "tp_job", "salaire" , $members_list['0']['id_job'], "id_job") . "€";
         echo "</td>";
         echo "</tr>";
?>
         </table>
   </div>
</div>
