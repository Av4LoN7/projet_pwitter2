<?php

if(count($pwitUser) >=1 && $pwitUser != false)
{
    for($i = 0; $i < count($pwitUser);$i++)
    {
        echo '<div class="pwit">';
        if(isset($pwitUser[$i]['date_origin']))
        {
            echo '<div id="repwitSingle'.$pwitUser[$i]['id_pwit'].'" class="publication">';
        }
        else
        {
            echo '<div id="pwittSingle'.$pwitUser[$i]['id_pwit'].'" class="publication">';
        }

        echo '<img class="noImg" src="'.$pwitUser[$i]['u_profil_img'].'">';
        echo "<div class='pwitData'>". $pwitUser[$i]['p_auteur']." à écrit: <br>";
        echo $pwitUser[$i]['p_contenu'] . '<br>';
        if($pwitUser[$i]['p_img'] != null)
        {
            echo "<img src=". $pwitUser[$i]['miniature_pwit']."></div>";
        }
        else
        {
            echo '<img src=""></div>';
        }


        if(isset($pwitUser[$i]['titre_categories']) && $pwitUser[$i]['titre_categories'] != null )
        {
            $tags = explode(',', $pwitUser[$i]['titre_categories']);
            $id_tags = explode(',', $pwitUser[$i]['id_categories']);

            for($e=0; $e < count($tags); $e++)
            {
                echo '<a class="linkToCategorie" data-idCat="'.$id_tags[$e].'" href="-index.php?controller=categorie&action=showPwitByCat&tags='.$id_tags[$e].'" style="text-decoration:none;">#'.$tags[$e].' </a> ';
                if($_SESSION['currentUser']['auth'] == $pageId)
                {
                    echo '<a href=""><i data-name="removeTags" data-id_tags="'.$id_tags[$e].'" data-idPwitt="'.$pwitUser[$i]["id_pwit"].'" class="fa fa-trash"></i></a> ';
                }

                echo '<a href=""><i data-name="subToTag" data-id_tags2="'.$id_tags[$e].'" data-user="'.$_SESSION['currentUser']['auth'].'" class="fa fa-bell"></i></a> ';


            }
            echo "<br>";
        }

        if(isset($pwitUser[$i]['date_origin']))
        {
            $date = $pwitUser[$i]['p_date'] != null ? $pwitUser[$i]['p_date'] : $pwitUser[$i]['date'];

            echo ' repwitter le:' . $date. '<br>';
            echo "<button class='btn showBoxComment' data-com=".$pwitUser[$i]['id_pwit'].">commenter</button>";

            if( $pageId == $_SESSION['currentUser']['auth'])
            {
                echo'<button class="repwittRemove btn btn-danger" data-date="'.$date.'" data-idPwitt="'.$pwitUser[$i]['id_pwit'].'" data-user_id="'.$_SESSION['currentUser']['auth'].'">Retirer repwitte</button> ';
            }
            else
            {
                echo "<button class='repwitt btn btn-primary' data-userID='".$_SESSION['currentUser']['auth']."' data-date='".$pwitUser[$i]['p_date']."' data-pwittID='".$pwitUser[$i]['id_pwit']."' data-page='".$_SESSION['page']."'>Repwitt</button> ";
                if(isset($pwitUser[$i]['userLike']) && $pwitUser[$i]['userLike'] == false)
                {
                    echo '<button class="like btn btn-primary" data-idPwitt="'.$pwitUser[$i]['id_pwit'].'" data-userID="'.$_SESSION['currentUser']['auth'].'">Like</button>';
                }
                else
                {
                    echo '<button class="dislike btn btn-danger" data-idPwitt="'.$pwitUser[$i]['id_pwit'].'" data-userID="'.$_SESSION['currentUser']['auth'].'">Ne plus aimer</button>';
                }
            }
        }
        else
        {
            echo  ' Pwitter le : ' . $pwitUser[$i]['p_date'] . '<br>';
            echo "<button class='btn showBoxComment' data-com=".$pwitUser[$i]['id_pwit'].">commenter</button>";
            echo "<button class='repwitt btn' data-userID='".$_SESSION['currentUser']['auth']."' data-date='".$pwitUser[$i]['p_date']."' data-pwittID='".$pwitUser[$i]['id_pwit']."' data-page='".$pageId."'>Repwitt</button> ";
            if($pwitUser[$i]['id_utilisateur'] == $_SESSION['currentUser']['auth'])
            {
                echo'<button class="deletePwitt btn btn-danger" data-userID="'.$_SESSION['currentUser']['auth'].'" data-pwittID="'.$pwitUser[$i]['id_pwit'].'">effacer pwitt</button> ';
            }
            if(isset($pwitUser[$i]['userLike']) && $pwitUser[$i]['userLike'] == 0)
            {
                echo '<button class="like btn btn-primary" data-idPwitt="'.$pwitUser[$i]['id_pwit'].'" data-userID="'.$_SESSION['currentUser']['auth'].'">Like</button> ';
            }
            else
            {
                echo '<button class="dislike btn btn-primary" data-idPwitt="'.$pwitUser[$i]['id_pwit'].'" data-userID="'.$_SESSION['currentUser']['auth'].'">Ne plus aimer</button>';
            }
        }
        if(isset($pwitUser[$i]['countLike']))
        {
            echo '<span class="countlike">'.$pwitUser[$i]['countLike'].'</span>';
        }
        else
        {
            echo '<span class="countlike">0</span>';
        }

        if($pwitUser[$i]['id_utilisateur'] == $_SESSION['currentUser']['auth'])
        {
            ?>
            <form id="addTagsAfter<?php echo $pwitUser[$i]['id_pwit'];?>" method="GET">
                <input type="text" name="tags" placeholder="Ajouter Un Tag" class="tm-input"/>
                <input class="tagsAfter" data-idPwitt="<?php echo $pwitUser[$i]['id_pwit']; ?>" type="submit" value="valider"/>
            </form>
            <?php
        }
        // commentaire
        if(isset($pwitUser[$i]['commentaire']) && $pwitUser[$i]['commentaire'] != false)
        {
            for($d=0; $d < count($pwitUser[$i]['commentaire']); $d++)
            {

                echo '<div class="comm">';
                if($pwitUser[$i]['pwitOwner'] || $pwitUser[$i]['commentaire']['isOwner'] == true)
                {
                    echo '<button class="deleteCom" data-idCom="'.$pwitUser[$i]['commentaire'][$d]['id_commentaire'].'" data-idPwit="'.$pwitUser[$i]['commentaire'][$d]['id_pwit'].'">X</button>';
                }
                echo $pwitUser[$i]['commentaire'][$d]['u_identifiant'] . "a écrit : ";
                echo $pwitUser[$i]['commentaire'][$d]['c_contenu'];
                if(file_exists($pwitUser[$i]['commentaire'][$d]['c_img']))
                {
                    echo '<img src="'.$pwitUser[$i]['commentaire'][$d]['miniature'].'"/><br>';
                }
                echo "</div>";
            }
        }

        // espace commentaire
        ?>

        <form id="adCom<?php echo$pwitUser[$i]['id_pwit'];?>" class="addCom" method="POST" enctype="multipart/form-data">
            <textarea name="data" placeholder="Ecrire un commentaire"></textarea>
            <input type="hidden" name="pwitID" value="<?php echo $pwitUser[$i]['id_pwit'];?>">
            <input type="hidden" name="from" value="comm">
            <input type="hidden" name="userID" value="<?php echo $_SESSION['currentUser']['auth']; ?>">
            <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
            <input type="file" name="file_img" id="file_prev"/>
            <input type="submit" value="valider" />
        </form>

        <?php
        echo '</div></div>';

    }
}
else
{
    echo '<div class="notifBox"><h2>Notification:</h2><div class="noContent"> Aucune publication à afficher</div></div>';
}
?>



