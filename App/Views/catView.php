<?php

if(isset($catSub) && $catSub != false)
{
    echo '<div class="categorieLike">';
    foreach($catSub as $key => $value)
    {

        ?>
        <script type="text/javascript">
            $(document).ready(function() {
                var tags = $('i[data-id_tags2=<?php echo $value['id_categorie']; ?>]');
                tags.removeClass("fa fa-bell");
                tags.addClass("fa fa-bell-slash");

            });
        </script>
        <?php
        echo '<a href="-index.php?controller=frontProfil&action=frontPage&option=search&term='.$value['cat_titre'].'&from=categorie&userID='.$_SESSION['currentUser']['auth'].'"># '.$value['cat_titre'].'</a><br>';
    }
    echo '</div>';
}

?>