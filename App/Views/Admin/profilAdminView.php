<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="Assets/Style/admin.css">
    <script type="text/javascript" src="Assets/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="Assets/jsProfil2.js"></script>
    <script type="text/javascript" src="Assets/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="Assets/jsAdmin.js"></script>
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
<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 adminLeft">
    <?php
    if(isset($error))
    {
        echo '<div class="alert alert-danger">'.$error.'</div>';
    }
    ?>
    <div class="adminReturnBlock">
        <button class="btn btn-primary btn-block" onClick="location.href='-index.php?controller=frontProfil&action=frontPage&userID=<?php echo $userProfil['profil_info']['id_utilisateur']; ?>'"> Retour</button>
        <button id="suppUser" class="btn btn-danger btn-block">Supprimer votre compte</button>
        <div class="confirmSupp" style="display:none;"><button class="accept" data-id="<?= $_SESSION['currentUser']['auth'];?>">OUI</button><button class="reject">NON</button></div>
        <?php
        if(isset($_SESSION['currentUser']['rang']) && $_SESSION['currentUser']['rang'] === 2)
        {
            ?><button onClick="location.href='-index.php?controller=frontProfil&action=admin'" class="btn btn-warning btn-block">Acceder à l'administration</button><?php
        }
        ?>
    </div>
</div>
<div class="adminCenter">
<h3>PROFIL</h3>
    <div style="margin-left:10%;">

        <!-- Image de profil -->
        <fieldset class="colorBox">
            <legend>Image de profil</legend>
            <div class="leftBoxInfo">
                <?php

                if($userProfil['profil_info']['u_profil_img'] != "")
                {
                    $extension = explode(".", $userProfil['profil_info']['u_profil_img']);
                }
                if(isset($extension))
                {
                    echo "<p class='userAvatar'><img src='avatar/user_miniature_".$userProfil['profil_info']['id_utilisateur']."/".$userProfil['profil_info']['id_utilisateur']."_avatar_mini.".end($extension)."'></img></p>";
                    echo '<button id="suppImg" data-user="'.$_SESSION['currentUser']['auth'].'">Supprimer</button>';
                }
                else
                {
                    echo '<p class="userAvatar"> Pas d\'image de profil</p>';
                }
                ?>
            </div>
            <!--modif image de profil-->
            <form id="userAvatar" method="post" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                    <input type="hidden" name="from" value="profil" />
                    <input type="hidden" name="userID" value="<?php echo $_SESSION['currentUser']['auth']; ?>" />
                    <input class="form-control-file" type="file" name="file_img" id="file_prev" required/>
                    <input class="btn btn-primary" type="submit" value="envoyer">
                </div>

            </form>
        </fieldset>

        <!--description-->
        <?php if(is_array($userProfil)) :?>
            <fieldset class="colorBox">
                <legend>Description</legend>
                <div class="leftBoxInfo">
                    <div id="userDescriptif"><?php echo $userProfil['profil_info']['u_profil_description']; ?></div>
                    <button id="deleteDescript" data-user="<?= $_SESSION['currentUser']['auth']; ?>">Supprimer description</button>
                </div>
                <form id="formModif" method="post" action="">
                    <div class="form-group" style="width:40%;margin-left:25%;">
                        <textarea class="form-control" id="text_areaDescription" placeholder="Modifier votre description" name="modif" maxlength="255"></textarea>
                        <input type="hidden" value="<?php echo $_SESSION['currentUser']['auth']; ?>"/>
                        <input class="btn btn-primary" type="submit" name="valider"/>
                         </div>
                </form>
            </fieldset>

            <!--Pseudo-->
            <fieldset class="colorBox">
                <legend>Votre speudo</legend>
                <div class="leftBoxInfo">
                    <P id="identifiantUser"><?php echo $userProfil['profil_info']['u_identifiant']; ?></P>
                </div>
                <input id="userPseudo" data-user="<?php echo $_SESSION['currentUser']['auth']; ?>" type="text"/> <button class="sendNewPseudo">Modifier</button>
                <div id="successPseudo" class="alert alert-success" style="display: none;"></div>
                <div id="errorPseudo" class="alert alert-danger" style="display: none;"></div>
            </fieldset>

            <!-- Nom + Prenom -->
            <fieldset class="colorBox">
                <legend>Votre nom et prénom</legend>
                <div class="leftBoxInfo">
                    <P id="userRealName"><?php echo $userProfil['profil_info']['u_nom'] .' ' . $userProfil['profil_info']['u_prenom'] ; ?></P>
                </div>
                <input id="userName" placeholder="Nom" type="text"/> <input id="userLastName" placeholder="Prénom" type="text"/>
                <button data-user="<?php echo $_SESSION['currentUser']['auth'];?>" class="sendNewNames">Modifier</button>
                <div id="successName" class="alert alert-success" style="display: none;"></div>
                <div id="errorName" class="alert alert-danger" style="display: none;"></div>
            </fieldset>

            <!-- email-->
            <fieldset id="userFieldMail" class="colorBox">
                <legend>Votre adresse email</legend>
                <div class="leftBoxInfo">
                    <P><?php echo $userProfil['profil_info']['u_email']; ?></P>
                </div>
                <input placeholder="email" id="userMail" type="email"/> <button class="sendNewMail" data-user="<?php echo $_SESSION['currentUser']['auth']; ?>">Modifier</button>
                <div id="errorMail" class="alert alert-danger" style="display:none;"></div>
                <div id="successMail" class="alert alert-success" style="display:none;"></div>
            </fieldset>

            <!-- date de naissance -->
            <fieldset class="colorBox">
                <legend>Date de naissance</legend>
                <div class="leftBoxInfo">
                    <P id="userBirthDate"><?php echo date("d-m-Y", strtotime($userProfil['profil_info']['u_date_de_naissance'])); ?></P>
                </div>
                <input id="userBirth" type="date"/> <button class="sendBirth" data-user="<?php echo $_SESSION['currentUser']['auth'];?>">Modifier</button>
                <div id="successBirth" class="alert alert-success" style="display: none;"></div>
                <div id="errorBirth" class="alert alert-danger" style="display: none;"></div>
            </fieldset>

            <!-- date inscription -->
            <fieldset>
                Inscrit le : <P id="userDateInscription"><?php echo date("d F, Y", strtotime($userProfil['profil_info']['u_date_inscription'])); ?></P>
            </fieldset>
        <?php endif; ?>


    </div>
</div>

<body>
</body>
</html>











