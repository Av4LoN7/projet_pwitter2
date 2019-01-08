<h3>Messagerie</h3>
<div style="width:50%;float:left;">
<?php

if(isset($allSub))
{
    foreach ($allSub as $key => $value)
    {
        echo "<div>";
        if($value['u_profil_img'] != null)
        {
            echo '<div> <img src="'.$value['miniature'].'"></div>';
        }
        echo '<a href="-index.php?controller=frontProfil&action=frontPage&userID='.$value['id_utilisateur'].'">'.$value['u_identifiant'].'  </a>';
        echo '<button class="btn btn-primary messageBox" data-abo="'.$value['id_utilisateur'].'" data-userID="'.$_SESSION['currentUser']['auth'].'">Message</button></div>';
    }
}
?>
</div>
