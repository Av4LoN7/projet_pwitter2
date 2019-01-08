<?php
//check if pageID is inside our aboList

if(isset($aboId) && in_array($pageId, $aboId))
{
    echo '<div><button class="btn btn-danger btn-block" id="subScribe" data-userID="'.$_SESSION['currentUser']['auth'].'" data-subID="'.$pageId.'" data-type="unSub">Se désabonner</button></div>';

}
elseif(isset($aboId) && !in_array($pageId, $aboId) && $pageId != $_SESSION['currentUser']['auth'])
{
    echo '<div><button class="btn btn-primary btn-block" id="subScribe" data-userID="'.$_SESSION['currentUser']['auth'].'" data-subID="'.$pageId.'" data-type="sub">S\'abonner</button></div>';
}
if($pageId != $_SESSION['currentUser']['auth'])
{
    ?>
    <div><button class="btn btn-primary btn-block" onClick="location.href='-index.php?controller=profil&action=showUserProfil&userID=<?= $pageId;?>'" >Profil</button></div>

   <div> <button class="btn btn-primary btn-block" onClick="location.href='-index.php?controller=frontProfil&action=frontPage&userID=<?= $_SESSION['currentUser']['auth']; ?>'">Revenir sur l'accueil </button></div>
<?php
}

?>


