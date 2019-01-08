<?php
if(isset($error))
{
    echo $error;
}
        if(isset($_SESSION['messageBox']))
        {
            echo '<ul>';
            foreach ($_SESSION['messageBox'] as $key2 => $value2) {

                if ($_SESSION['currentUser']['auth'] != $value2['sender']) {

                    echo "<li id='" . $value2['id_message'] . "' class=\"right clearfix\"><span class=\"chat-img pull-right\">";
                } else {
                    echo "<li id='" . $value2['id_message'] . "' class=\"left clearfix\"><span class=\"chat-img pull-left\">";

                }
                echo $value2['u_identifiant']. ' à écrit :' . $value2['m_message'] . '<br>';
                echo $value2['m_date_message'] . ' ';
                echo "</span></li><br>";
            }
            echo "<div class='textBox2'><textarea placeholder='text'></textarea>";
            echo "<input class='SendMessageBox' id=" . $_SESSION['currentUser']['auth'] . $_SESSION['abo'] . " type='button' data-user=" . $_SESSION['currentUser']['auth'] . " data-abo=" . $_SESSION['abo'] . " value='envoyer'></div>";

            echo '</ul>';
        }
        elseif(isset($dialog))
        {
            echo '<ul>';
            foreach ($dialog as $key2 => $value2) {

                if ($_SESSION['currentUser']['auth'] != $value2['sender']) {
                    echo "<li id='" . $value2['id_message'] . "' class=\"right clearfix\"><span class=\"chat-img pull-right\">";
                } else {
                    echo "<li id='" . $value2['id_message'] . "' class=\"left clearfix\"><span class=\"chat-img pull-left\">";

                }
                echo '<br>' . $value2['m_message'] . '<br>';
                echo $value2['m_date_message'] . '<br>';
                echo "</span></li>";
            }
            echo $value2['u_identifiant']. ' à écrit :' . $value2['m_message'] . '<br>';
            echo "<div class='textBox2'><textarea placeholder='text'></textarea>";
            echo "<input class='SendMessageBox' id=" . $_SESSION['currentUser']['auth'] . $abo . " type='button' data-user=" . $_SESSION['currentUser']['auth'] . " data-abo=" . $abo . " value='envoyer'></div>";

            echo '</ul>';
        }
        elseif(isset($abo)) {
            echo "<div class='textBox2'><textarea placeholder='text'></textarea>";
            echo "<input class='SendMessageBox' id=" . $_SESSION['currentUser']['auth'] . $abo . " type='button' data-user=" . $_SESSION['currentUser']['auth'] . " data-abo=" . $abo . " value='envoyer'></div>";
        }
        ?>





