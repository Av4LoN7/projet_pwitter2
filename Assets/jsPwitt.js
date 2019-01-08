$(document).ready(function() {
    // bind tagsManager plugin
    $("input.tm-input").tagsManager();

    /**
     * send new publication event
     */
    $('#formPwitt').on("submit", function(e){
        e.preventDefault();
        var content = $("#textPwitt").val();
        var userName = $("#submitPwitt").attr('data-userName');
        var formData = new FormData(this);

        var formID = $("#formPwitt");
        var user_id = formID.find($("input[name='userID']")).val();
        var tags = formID.find($("input[name='hidden-tags']")).val();

        console.log(tags);
       if(content.length <= 255 )
        {
            formData.append('tag', tags);
            formData.append('identifiant', userName);
            $.ajax({
                url: "-index.php?controller=pwitt&action=addPwitt",
                method: "POST",
                dataType: "JSON",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success:function(result)
                {
                   console.log(result);
                   if(result.error)
                   {
                       $("div.errorPwit").css("display","block").html(result.error);
                   }
                   else
                   {
                       $("#pwittContent").prepend(result);
                   }
                   // rebind tagsManager plugin if success
                   $("input.tm-input").tagsManager();
                },
                error:function(res)
                {
                    console.log(res);
                }
            });
        }
        else
        {
            print("votre pwit est trop long");
        }
    });


    /**
     * repwit event
     */
    $(document).on('click', '.repwitt', {}, (function() {
        var user_ID = this.getAttribute("data-userid");
        var pwittID = this.getAttribute("data-pwittid");
        var date_Repwit = this.getAttribute("data-date");
        var pageID = $("div#page").attr("data-value");
        console.log(user_ID, pwittID, date_Repwit, pageID);
        $.ajax({
            url:"-index.php?controller=pwitt&action=repwitt",
            method: "GET",
            dataType: "JSON",
            data: {
                userID: user_ID,
                dateOrigin: date_Repwit,
                idPwitt: pwittID,
                page: pageID,

            },
            success:function(result)
            {
                console.log(result);
                if(pageID == user_ID)
                {
                    $("#pwittContent").prepend(result);
                }
                else
                {

                    console.log("c\'est repwitter !");
                }
            },
            error:function(res)
            {
                console.log(res);
            }
        });
    }));

    /**
     * delete repwit event
     */
    $(document).on('click', '.repwittRemove', {}, (function() {
        var user = this.getAttribute("data-user_id");
        var dateRepwitt = this.getAttribute("data-date");
        var pwitt_id = this.getAttribute("data-idPwitt");
        console.log(user, dateRepwitt,pwitt_id);
        $.ajax({
            url: "-index.php?controller=pwitt&action=removeRepwitt",
            method: "GET",
            data:{
                userID: user,
                date: dateRepwitt,
                idPwitt: pwitt_id,
            },
            success:function(result)
            {
                console.log(result);

                $("div#repwitSingle"+pwitt_id).parent("div").remove();

            },
            error:function(res)
            {
                console.log(res);
            }
        })
    }));

    /**
     * delete pwit event
     */
    $(document).on('click', '.deletePwitt', {}, (function() {
        var user = this.getAttribute("data-userID");
        var pwitt_id = this.getAttribute("data-pwittID");
        $.ajax({
            url: "-index.php?controller=pwitt&action=deletePwitt",
            method: "GET",
            data:{
                userID: user,
                idPwitt: pwitt_id,
            },
            success:function(result)
            {
                console.log(result);
                $("div#pwittSingle"+pwitt_id).parent("div").remove();
            },
            error:function(res)
            {
                console.log(res);
            }
        })
    }));

    /**
     * like pwit event
     */
    $(document).on('click', '.like', {}, (function () {
        var buttonTarget = $(this);
        var user = this.getAttribute("data-userID");
        var id_Pwitt = this.getAttribute("data-idPwitt");
        var count = $(this).next("span.countlike");
        var val = parseInt(count.html());
            val = val + 1;

        $.ajax({
            url:"-index.php?controller=pwitt&action=likePwitt",
            method: "GET",
            data: {
                userID: user,
                idPwitt: id_Pwitt,
            },
            success:function(result)
            {
                console.log(result);
                var button = '<button class="dislike" data-idPwitt="'+id_Pwitt+'" data-userID="'+user+'">Ne plus aimer</button> ';
                buttonTarget.after(button);
                count.html(val);
                buttonTarget.remove();



            },
            error:function(res)
            {
                console.log(res);
            }
        });
    }));

    /**
     * dislike pwit event
     */
    $(document).on('click', '.dislike', {}, (function() {
        var buttonTarget = $(this);
        var user = this.getAttribute("data-userID");
        var id_Pwitt = this.getAttribute("data-idPwitt");
        var count = $(this).next("span.countlike");

        var val = parseInt(count.html()); // number of like
        val = val - 1;
        $.ajax({
            url: "-index.php?controller=pwitt&action=dislikePwitt",
            method: "GET",
            data: {
                userID: user,
                idPwitt: id_Pwitt,
            },
            success: function(result)
            {
                console.log(result);
                var button = '<button class="like" data-idPwitt="'+id_Pwitt+'" data-userID="'+user+'">Like</button> ';
                buttonTarget.after(button);

                count.html(val); // show new number of like
                buttonTarget.remove();
            },
            error:function(res)
            {
                console.log(res);
            }
        });
    }));

});
