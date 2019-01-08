<footer>
    <?php

    if(isset($_SESSION['currentUser']['convActiv'])) {
        ?>
        <script type="text/javascript">
            function getMessage(idUser, idAbo, path) {
                $.ajax({
                    url: '-index.php?controller=chat&action=getChatMessage',
                    method: "GET",
                    datatype: "JSON",
                    data: {
                        aboID: idAbo,
                        userID: idUser,
                    },
                    success: function (result) {
                        console.log(result);
                        $(path).css("display", "block");
                        $(path).html(result);
                    },
                    error: function (res) {
                        console.log(res);
                    }
                });
            }
        </script>
        <?php
        foreach ($_SESSION['currentUser']['convActiv'] as $key => $value) {
            echo "<div class='chat' id=chatBox" . $value['user'] . $value['abo'] . ">";
            echo "</div>";
            echo "<script> getMessage(" . $value['user'] . "," . $value['abo'] . "," . $value['path'] . ");</script>";
        }
    }
    ?>
</footer>
