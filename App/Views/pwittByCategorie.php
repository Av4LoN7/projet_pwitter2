<?php

if(count($pwitUser) >=1 && $pwitUser != false)
{
    echo '<h4>Liste des publication de la catégorie : </h4>';

    for($i = 0; $i < count($pwitUser);$i++)
    {
        echo '<div class="pwit">';

        echo '<div id="pwittSingle'.$pwitUser[$i]['id_pwit'].'" class="publication">';


        echo $pwitUser[$i]['p_auteur']." à écrit: <br>";
        echo $pwitUser[$i]['p_contenu'] . '<br>';
        if(isset($pwitUser[$i]['u_profil_img']))
        {
            echo "<img src=". $pwitUser[$i]['miniature'].">";
        }


        if(isset($pwitUser[$i]["catList"]))
        {
            $cat = explode(',',$pwitUser[$i]["catList"][0]['titleCat'] );
           for($j=0; $j < count($cat); $j++)
           {
               echo '# ' .$cat[$j]. ' ';
           }
        }

        echo  ' <br> Pwitter le : ' . $pwitUser[$i]['p_date'] . '<br>';
        echo "<button class='btn' onClick=showBoxComment(".$pwitUser[$i]['id_pwit'].")>commenter</button>";
        echo "<button class='repwitt btn' data-userID='".$_SESSION['currentUser']['auth']."' data-date='".$pwitUser[$i]['p_date']."' data-pwittID='".$pwitUser[$i]['id_pwit']."' data-page='".$_SESSION['currentUser']['auth']."'>Repwitt</button> ";
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

        if(isset($pwitUser[$i]['countLike']))
        {
            echo '<span class="countlike">'.$pwitUser[$i]['countLike'].'</span>';
        }
        else
        {
            echo '<span class="countlike">0</span>';
        }

        echo '</div></div>';

    }
}
else
{
    echo '<h2>Notification:</h2><div class="noContent"> Aucune publication à afficher</div>';
}
?>
