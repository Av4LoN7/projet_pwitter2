<?php
if(isset($followingList) && count($followingList) >= 1)
{
    foreach($followingList as $key => $value)
    {
        echo '<div class="aboRender">';

        echo '<div><img class="noImg" src="'.$value['miniature'].'"></div>';

        echo "<div class='followerViewOption'>";
        if(isset($value['following']) || isset($value['follower']) && $value['id_utilisateur'] != $_SESSION['currentUser']['auth'])
        {
            ?> <button class="btn btn-primary privateMessage" data-user="<?= $_SESSION["currentUser"]["auth"]; ?>" data-userabo="<?= $value["id_utilisateur"]; ?>">écrire une message privé</button><?php
        }
        ?><button class="btn btn-primary" onClick="location.href='-index.php?controller=frontProfil&action=frontPage&userID=<?= $value['id_utilisateur']; ?>'">profil</button><?php
        echo '</div>';
        echo '<div style="clear: both; margin-left:3vh;"><a href="-index.php?controller=frontProfil&action=frontPage&userID='.$value['id_utilisateur'].'">'.$value['u_identifiant'].'</a></div>';

        echo'</div>';
    }
}
else
{
    echo "<h2>Notification </h2><div class='noContent'>pas d'abonnement sur ce compte</div>";
}
