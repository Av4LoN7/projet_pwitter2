<?php

if(isset($allUser))
{
    foreach( $allUser as $key => $value)
    {
        echo '<div class="adminUserList"> ' . $value['u_identifiant'];
        echo '  <button class="btn btn-danger" id="suppUser" data-user="'.$value['id_utilisateur'].'">supprimer</button>';
        echo '</div>';

    }
}