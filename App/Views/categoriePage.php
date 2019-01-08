<script type="text/javascript" src="Assets/jquery-3.3.1.js"></script>
<script type="text/javascript" src="Assets/max-favilli-tagmanager-b43646e/tagmanager.js"></script>
<script type="text/javascript" src="Assets/jsCatjs.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<?php
if(isset($catList['cat_sub']))
{
    $cat_sub = $catList['cat_sub'];
    unset($catList['cat_sub']);

    foreach($cat_sub as $key => $value)
    {
        ?>
        <script type="text/javascript">
            $(document).ready(function() {
                console.log('i[data-id_tags2=<?php echo $value['id_categorie']; ?>]');
                var tags = $('i[data-id_tags2=<?php echo $value['id_categorie']; ?>]');
                tags.removeClass("fa fa-bell");
                tags.addClass("fa fa-bell-slash");

            });
        </script>
        <?php
    }
}

if(isset($catTemp))
{
    for($i=0;$i < count($catTemp); $i++)
    {
        echo $catTemp[$i]['id_pwit']. '<br>';
        echo $catTemp[$i]['contenu'] . '<br>';

        for($j =0; $j < count($catTemp[$i][0]); $j++)
        {
            $idCat = explode(",", $catTemp[$i][0][$j]['idCat']);
            $titleCat = explode(",", $catTemp[$i][0][$j]['titleCat']);
        }
        for($k =0; $k < count($idCat); $k++)
        {
            echo '<a href="-index.php?controller=categorie&action=showPwitByCat&idCat='.$idCat[$k].'">#' .$titleCat[$k] . '<i data-name="subToTag" data-id_tags2="'.$idCat[$k].'" data-user="'.$_SESSION['currentUser']['auth'].'" class="fa fa-bell"></i></a> ';
            //echo "test";
            //echo '<a href=""><i data-name="subToTag" data-id_tags3="'.$idCat[$k].'" data-user="'.$_SESSION['currentUser']['auth'].'" class="fa fa-bell"></i></a> ';
        }
    }
}