<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="Assets/Style/admin.css">
</head>
<div class="blackBand">@Pwitter</div>
<header>
    <div class="form-group searchBar"><input class="form-control" id="searchTerm" data-from="person" data-user="<?= $_SESSION['currentUser']['auth']; ?>" type="text" placeholder="Rechercher un utilisateur" >
        <input class="form-control" id="searchCat" data-from="categorie" data-user="<?= $_SESSION['currentUser']['auth']; ?>" type="text" placeholder="rechercher une catégorie" >
    </div>

    <div style="float: right;margin-right:12.5%;padding-top:2.5%;">
        <button class="btn btn-primary" onClick="location.href='-index.php?userID=<?= $_SESSION['currentUser']['auth']; ?>&controller=frontProfil&action=frontPage'">Accueil</button>
        <button class="btn btn-primary" onClick="location.href='-index.php?userID=<?= $_SESSION['currentUser']['auth'];?>&controller=profil&action=showUserProfil'">Gestion du profil</button>
        <button class="btn btn-primary" onClick="location.href='-index.php?controller=auth&action=disconnect&userID=<?php echo $_SESSION['currentUser']['auth'];?>'">Deconnexion</button>
    </div>
</header>
<body>
<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 adminLeft">

    <div class="adminReturnBlock">
        <button class="btn btn-primary btn-block" onClick="location.href='-index.php?controller=frontProfil&action=frontPage&userID=<?php echo $userProfil['profil_info']['id_utilisateur']; ?>'"> Précedent</button>
        <button class="btn btn-primary btn-block" onClick="location.href='-index.php?controller=frontProfil&action=frontPage&userID=<?php echo $_SESSION['currentUser']['auth']; ?>'"> Revenir sur l'acceuil</button>
    </div>
</div>
<div class="adminCenter">

    <div style="margin-left:10%;">

        <?php if(isset($userProfil['profil_info']) && count($userProfil['profil_info']) >=1): ?>
            <h2>Profil de : <?php echo $userProfil['profil_info']['u_identifiant']; ?></h2>

        <div>

                <!-- image de profil -->
            <fieldset class="adminImg">
                <legend>Image de profil</legend>
                <?php
                if($userProfil['profil_info']['u_profil_img'] != "")
                {
                    $extension = explode(".", $userProfil['profil_info']['u_profil_img']);
                }
                if(isset($extension))
                {
                    echo "<img src='avatar/user_miniature_".$userProfil['profil_info']['id_utilisateur']."/".$userProfil['profil_info']['id_utilisateur']."_avatar_mini.".end($extension)."'></img>";
                }
                else
                {
                    echo "Pas d'image de profil";
                }
                ?>
            </fieldset>
            <fieldset>
                <?php if(is_array($userProfil)) :?>
                <legend>Description</legend>

                <!-- description -->
                <p id="userDescriptif"><?php echo $userProfil['profil_info']['u_profil_description'] !=null ? $userProfil['profil_info']['u_profil_description'] : "Pas de description"; ?></p>
            </fieldset>
                <!-- Nom + prenom -->
                <fieldset>
                    <div style="width: 30%">
                        <legend>Nom et Prénom</legend>
                        <P id="userRealName"><?php echo $userProfil['profil_info']['u_nom'] .' ' . $userProfil['profil_info']['u_prenom'] ; ?></P>
                    </div>
                </fieldset>

                <!-- Email -->
                <fieldset>
                    <legend>Email</legend>
                     <P><?php echo $userProfil['profil_info']['u_email']; ?></P>
                    <p id="errorMail"></p>
                </fieldset>

                <!-- Date de naissance -->
                <fieldset>
                    <legend>Date de naissance2 </legend>
                     <P id="userBirthDate"><?php echo date("d-m-Y", strtotime($userProfil['profil_info']['u_date_de_naissance'])); ?></P>
                </fieldset>
                <!-- Date d'inscription -->
                     <P id="userDateInscription">Inscrit le : <?php echo date("d F, Y", strtotime($userProfil['profil_info']['u_date_inscription'])); ?></P>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>

</div>
</body>
</html>









