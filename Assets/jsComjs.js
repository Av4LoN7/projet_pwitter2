$(document).ready(function() {
    /**
     * showBoxComment event
     */
    $("button.showBoxComment").on("click", function() {
        var id = this.getAttribute("data-com");
        if($("#adCom"+id).css("display") == "none")
        {
            $("#adCom"+id).css("display", "block");
        }
        else
        {
            $("#adCom"+id).css("display", "none")
        }
    });

    /**
     * add commentary ajax process
     */
    $("form.addCom").on("submit", function(e) {
        e.preventDefault();
        var idPwit = $(this).find("input[name='pwitID']").val();
        var userID = $(this).find("input[name='userID']").val();
        var form = $("#adCom"+idPwit+".addCom");

        $.ajax({
            url: "-index.php?controller=commentaire&action=addCommentaire",
            method: "POST",
            dataType: "JSON",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success:function(result)
            {
                console.log(result);
                if(result.error)
                {
                    $("div#errorCom").css("display", "block");
                    $("div#errorCom").html(result.error);
                }
                else
                {
                    // add last comment dynamicly
                    for( var i in result)
                    {
                        console.log(result[i]);
                        var response = "<div class='comm'>";
                        response += '<button class="deleteCom" data-idCom="'+ result[i][0].id_commentaire +'" data-idPwit="'+idPwit+'">X</button>';
                        response += result[i][0].u_identifiant;
                        response += result[i][0].c_contenu;
                        response += "</div>";
                    }
                    $(response).insertBefore(form);
                    $("form#adCom"+userID).find("textarea").val("");
                }
            },
            error: function(res)
            {
                console.log(res);
                if(res.error)
                {
                    $("div#errorCom").css("display", "block");
                    $("div#errorCom").html(res.error);
                }
                else
                {
                    $("div#errorCom").css("display", "block");
                    $("div#errorCom").html(res.error);
                }
            }
        });
    });

    /**
     * delete com event
     */
    $(document).on("click", ".deleteCom", {}, (function(e) {
        e.preventDefault();
        var idCom = this.getAttribute("data-idCom");
        var idPwit = this.getAttribute("data-idPwit");
        var container = $(this).parent();
        var boxSuccess = "<div class='alert alert-success'>";
        var boxError = "<div class='alert alert-danger'>";

        $.ajax({
            url:'-index.php?controller=commentaire&action=deleteCom',
            method: "GET",
            dataType:"JSON",
            data:{
                comID: idCom,
                pwitID: idPwit,
            },
            success:function(result)
            {
                if(result.success)
                {
                    boxSuccess += result.success;
                    boxSuccess += "</div>";
                    $(container).append(boxSuccess);
                    // remove com success
                    setTimeout(function()
                    {
                        $(container).css("display", "none");
                    },2000);
                }
                if(result.error)
                {
                    boxError += result.error;
                    boxError += "</div>";
                    $(container).append(boxError);
                    // remove com error
                    setTimeout(function()
                    {
                        $(container).css("display", "none");
                    },2000);
                }
                console.log(result);
            },
            error: function(res)
            {
                if(res.error)
                {
                    boxError += res.error;
                    boxError += "</div>";
                    $(container).append(boxError);
                }
                console.log(res);
            }
        })
    }));
});