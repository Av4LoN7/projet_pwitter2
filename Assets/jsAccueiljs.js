$(document).ready(function() {
    $("button.subscribe").on("click", function() {
        if( $("div#formSub").css("display") == "none" )
        {
            $("div#formSub").css("display", "block");
            $(this).html("Refermer");
        }
        else
        {
            $("div#formSub").css("display", "none");
            $(this).html("Pas encore inscrit ?");
        }
    });
    $("form.subscription").on("submit", function(e)
    {
        console.log("test");
        e.preventDefault();
        var password = $(this).find("input[name='password']").val();
        var name = $(this).find("input[name='nom']").val();
        var prenom = $(this).find("input[name='prenom']").val();
        var birth = $(this).find("input[name='birthday']").val();
        var email = $(this).find("input[name='email']").val();
        var pseudo = $(this).find("input[name='pseudo']").val();

        $.ajax({
            url:"-index.php?controller=sub&action=subscribe",
            method:"POST",
            dataType: "JSON",
            data:
                {
                    password : password,
                    nom: name,
                    prenom:prenom,
                    birthday:birth,
                    email:email,
                    pseudo:pseudo,
                },
            cache:false,
            success:function(result)
            {
                if(result.success)
                {
                    var response = '<div id="successSub" class="alert alert-success" style="width:20%; margin: auto;">Felicitation !  <br>vous pouvez à present vous connecté </div>';
                    $("div#formSub").prepend(response);
                   $("form.subscription")[0].reset();

                }
                else if(result.error)
                {
                    console.log(result.error);
                    var response = '<div id="errorSub" class="alert alert-danger" style="width:20%; margin: auto;">'+result.error+'</div>';
                    $("div#formSub").prepend(response);
                   setTimeout( function() {
                       $("div#errorSub").fadeOut(10000);
                       // $("div#errorSub").remove();
                   }, 5000);
                    //$(response).fadeOut(10000).remove();
                }
            },
            error:function(res)
            {
                console.log(res);
            }
        });
    });

});