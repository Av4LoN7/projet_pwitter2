
<div id="resultSearch">
    <?php
    if(isset($searchResult['search']))
    {
        foreach($searchResult['search'] as $key => $value)
        {
            echo '<div class="aboRender">';
            echo '<img class="noImg" src="'.$value['miniature'].'">';
            echo '<ul class="userDetail">
                    <li>Nom : '.strtoupper(htmlspecialchars($value['u_nom'])).'</li>
                    <li>Prenom : '.utf8_decode($value["u_prenom"]).'</li>
                    <li>Pseudo : <a href="-index.php?controller=frontProfil&action=frontPage&userID='.$value['id_utilisateur'].'">'. $value['u_identifiant'].'</a></li>
                    </ul>';
            echo '</div>';
        }
    }
    elseif(isset($searchResult['searchCat']))
    {
        echo "<h2>Résultat pour la catégorie : ". $term."</h2>";
        foreach($searchResult['searchCat'] as $key => $value)
        {
            echo '<div class="noContent"><a href="-index.php?controller=frontProfil&action=frontPage&option=pwitByCat&idCat='.$value['id_categorie'].'&userID='.$_SESSION['currentUser']['auth'].'">' .$value['cat_titre'] . '</a></div>';
        }
    }
    else
    {
        echo "<h2>Notification : </h2><div class='noContent'>Aucun résultat trouvé</div>";
    }
    ?>
</div>