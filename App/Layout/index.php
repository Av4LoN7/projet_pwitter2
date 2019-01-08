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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="Assets/chat.css">
    <link rel="stylesheet" href="Assets/Style/indexFront.css">
</head>
<body>
<div class="blackBand">@Pwitter</div>
<header>

    <?php if(isset($error))
    {
        echo '<div class="alert alert-danger">'.$error.'</div>';
    }
    ?>
    <div class="form-group searchBar"><input class="form-control" id="searchTerm" data-from="person" data-user="<?= $_SESSION['currentUser']['auth']; ?>" type="text" placeholder="<?= _('Rechercher un utilisateur'); ?>" >
        <input class="form-control" id="searchCat" data-from="categorie" data-user="<?= $_SESSION['currentUser']['auth']; ?>" type="text" placeholder="<?= _('rechercher une catégorie'); ?>" >
    </div>

    <div style="float: right;margin-right:12.5%;padding-top:2.5%;">
        <button class="btn btn-primary" onClick="location.href='-index.php?userID=<?= $_SESSION['currentUser']['auth']; ?>&controller=frontProfil&action=frontPage'"><?= _('Accueil'); ?></button>
        <button class="btn btn-primary" onClick="location.href='-index.php?userID=<?= $_SESSION['currentUser']['auth'];?>&controller=profil&action=showUserProfil'"><?= _('Gestion du profil'); ?></button>
        <button class="btn btn-primary" onClick="location.href='-index.php?controller=auth&action=disconnect&userID=<?php echo $_SESSION['currentUser']['auth'];?>'"><?= _('Deconnexion'); ?></button>
    </div>
</header>
<div class="container-fluid">
    <div class="row">

        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <div class="leftBar">
                <div class="partialInfo">
                        <?php
                        if($profilPartial)
                        {
                            echo $profilPartial;
                        }

                        if(isset($aboPartial) && $pageId != $_SESSION['currentUser']['auth'] )
                        {
                            echo '<div class="navContainer">';
                            echo '<div class="navShortcut">';
                            echo '<div id="aboContent">'. $aboPartial . '</div>';
                            echo '<div id="aboContentError" class="alert alert-danger" style="display: none;"></div>';
                            echo '</div></div>';
                        }
                        ?>

            </div>
            <div class="catPlacement">
                <h4 style="text-align: center;"><?= _('catégorie suivis'); ?> : </h4>
                <?= $catPartial; ?>
            </div>
        </div>


        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 middleContent">
            <div id="page" data-value="<?php echo $pageId; ?>" style="display:none;"></div>
            <?php
                if(isset($pwitRender))
                {
                    if($_SESSION['currentUser']['auth'] == $pageId)
                    {
                        ?>
                        <div class="form-group2">
                            <form id="formPwitt" method="POST" action="" enctype="multipart/form-data">
                                <h3> <?= _('Ecrire un pwitt'); ?></h3>
                                <label for="pwittContent"> <?= _('Message'); ?></label>
                                <textarea class="form-control" id="textPwitt" name="pwittContent" placeholder="255 carractere maximum" maxlength="255"></textarea><br>
                                <!--<img id="blah" src="#" alt="your image" />-->
                                <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                                <input type="hidden" name="from" value="pwit" />
                                <input type="hidden" name="userID" value="<?= $pageId; ?>" />
                                <label for="file_prev"><?= _('Joindre une image'); ?></label>
                                <input class="form-control-file" type="file" name="file_img" id="file_prev"/>
                                <label for="tags" ><?= _('Ajouter une catégorie'); ?></label>
                                <input type="text" name="tags" placeholder="Tags" class="tm-input form-control"/>
                                <input id="submitPwitt" type="submit" name="submitPwitt" value="valider">
                            </form>
                            <div class="errorPwit btn btn-danger" style="display: none;"> <?= $error; ?></div>
                        </div>
                        <?php
                    }
                    echo '<div id="pwittContent">';
                    echo $pwitRender;
                    echo ' </div>';
                }
            if(isset($renderFollowing))
            {
                echo $renderFollowing;
            }
            if(isset($search))
            {
                echo $search;
            }
            if(isset($pwitByCatRender))
            {
                echo $pwitByCatRender;
            }
            if(isset($allFollow))
            {
                echo $allFollow;
                echo '<div class="messageView">';
                include_once(VIEWSPATH.'messagerieView.php');
                echo '</div>';
            }
            ?>
        </div>

        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <div style="width: 60%; float: left;height:auto;background-color:#ffffff; margin-top:5%; margin-bottom:1%;">
                <div><button style="display: block;" class="btn btn-primary btn-block" onClick="location.href='-index.php?controller=frontProfil&action=frontPage&option=showAbonner&userID=<?= $pageId; ?>'" ><?= _("Abonnement"); ?></button></div>
                <div> <button style="display: block;" class="btn btn-primary btn-block" onClick="location.href='-index.php?controller=frontProfil&action=frontPage&option=showAbonnement&userID=<?= $pageId; ?>'"><?= _('Abonnés'); ?></button></div>
                <?php if($_SESSION['currentUser']['auth'] != $pageId):?>
                    <div> <button style="display: block;" class="btn btn-primary btn-block privateMessage" data-user="<?= $_SESSION['currentUser']['auth']; ?>" data-userabo="<?= $pageId; ?>"><?= _('Messages'); ?></button></div>
                <?php else:?>
                <div> <button style="display: block;" class="btn btn-primary btn-block" onClick="location.href='-index.php?controller=frontProfil&action=frontPage&option=showMessagerie&userID=<?= $_SESSION['currentUser']['auth']; ?>'"><?= _('Messagerie'); ?></button></div>
                <?php endif; ?>
            </div>
            <div class="copyright">
                @pwitter <?= _('tout droit reservé'); ?>
            </div>
        </div>

</div>
</div>
<?php require_once(VIEWSPATH."footerView.php"); ?>
</body>
</html>

