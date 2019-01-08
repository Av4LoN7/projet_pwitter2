<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <script type="text/javascript" src="Assets/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="Assets/jsPwitt.js"></script>
    <script type="text/javascript" src="Assets/max-favilli-tagmanager-b43646e/tagmanager.js"></script>
    <script type="text/javascript" src="Assets/jsCatjs.js"></script>
    <script type="text/javascript" src="Assets/jsSearchjs2.js"></script>
    <script type="text/javascript" src="Assets/jsComjs.js"></script>
    <script type="text/javascript" src="Assets/jsChatjs.js"></script>
    <script type="text/javascript" src="Assets/jsAbojs.js"></script>
    <script type="text/javascript" src="Assets/jsAdminjs.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="Assets/Style/indexFront.css">
    <link rel="stylesheet" href="Assets/Style/admin.css">

</head>
<body>
<div class="blackBand">@Pwitter</div>
<header>

    <?php if(isset($error))
    {
        echo '<div class="alert alert-danger">'.$error.'</div>';
    }
    ?>
    <div class="form-group searchBar"><input class="form-control" id="searchTerm" data-from="person" data-user="<?= $_SESSION['currentUser']['auth']; ?>" type="text" placeholder="Rechercher un utilisateur" >
        <input class="form-control" id="searchCat" data-from="categorie" data-user="<?= $_SESSION['currentUser']['auth']; ?>" type="text" placeholder="rechercher une catégorie" >
    </div>

    <div style="float: right;margin-right:12.5%;padding-top:2.5%;">
        <button class="btn btn-primary" onClick="location.href='-index.php?userID=<?= $_SESSION['currentUser']['auth']; ?>&controller=frontProfil&action=frontPage'">Accueil</button>
        <button class="btn btn-primary" onClick="location.href='-index.php?userID=<?= $_SESSION['currentUser']['auth'];?>&controller=profil&action=showUserProfil'">Gestion du profil</button>
        <button class="btn btn-primary" onClick="location.href='-index.php?controller=auth&action=disconnect&userID=<?php echo $_SESSION['currentUser']['auth'];?>'">Deconnexion</button>
    </div>
</header>
<h3>Admin</h3>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <div class="leftBar">
                    <div class="partialInfo">
                       <button onClick="location.href='-index.php?controller=admin&action=suppUserList'" class="btn btn-warning btn-block">Supprimer utilisateur</button>
                        <button onClick="location.href='-index.php?controller=admin&action=suppUserList'" class="btn btn-warning btn-block">Modifier rang utilisateur</button>
                    </div>
                </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 rightContentAdmin">

            <?php
            if(isset($suppView))
            {
                echo $suppView;
            }
            ?>
        </div>
    </div>

    <?php include_once(VIEWSPATH."footerView.php"); ?>
</body>
</html>

