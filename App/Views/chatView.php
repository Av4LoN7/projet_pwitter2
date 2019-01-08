
<ul class="chatActive">
<?php
if(isset($convTemp['conv']) && $convTemp['conv'] != false)
{

    echo '<div style="position:relative;height:250px;"><div class="open"><a href="#" class="closeConv" data-user="'.$_SESSION['currentUser']['auth'].'" data-abo="'.$abo.'">x</a>:::::<a href="#" class="reducConv" data-user="'.$_SESSION['currentUser']['auth'].'" data-abo="'.$abo.'">-</a><br> ';

    foreach($convTemp['conv'] as $key2 => $value2 )
    {

                if($_SESSION['currentUser']['auth'] != $value2['sender'])
                {
                    echo "<li id='".$value2['id_message']."' class=\"right clearfix\"><span class=\"chat-img pull-right\">";
                    echo "<img src=\"http://placehold.it/50/55C1E7/fff&text=U\" alt=\"User Avatar\" class=\"img-circle\" />";
                }
                else
                {
                    echo "<li id='".$value2['id_message']."' class=\"left clearfix\"><span class=\"chat-img pull-left\">";
                    echo '<img src="http://placehold.it/50/FA6F57/fff&text=ME" alt="User Avatar" class="img-circle" />';

                }
                echo '<br>' . $value2['m_message']. '<br>';
                echo $value2['m_date_message']. '<br>';
                echo "</span></li>";

    }
    echo "</div></div>";
    echo "<div class='textBox'><textarea placeholder='test'></textarea>";
    echo "<input class='buttonSendMessage' id=".$_SESSION['currentUser']['auth'].$abo." type='button' data-user=".$_SESSION['currentUser']['auth']." data-abo=".$abo." value='envoyer'></div>";


}
else
{
    echo '<div style="position:relative;height:250px;"><div class="open"><a href="#" class="closeConv" data-user="'.$_SESSION['currentUser']['auth'].'" data-abo="'.$abo.'"  >x</a>:::::<a href="#" class="reducConv" data-user="'.$_SESSION['currentUser']['auth'].'" data-abo="'.$abo.'">-</a> ';

    echo "<div class='textBox'><textarea placeholder='text''></textarea>";
    echo "<input class='buttonSendMessage' id=".$_SESSION['currentUser']['auth'].$abo." type='button' data-user=".$_SESSION['currentUser']['auth']." data-abo='$abo' value='envoyer'></div>";
    echo "</div></div>";
}
?>
</ul>
