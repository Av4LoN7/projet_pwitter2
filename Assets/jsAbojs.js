$(document).ready(function() {
    /**
     * ajx call for sub and unsub action
     */
    $(document).on('click', 'button#subScribe', {},(function() {
        var page = $("div#page").attr('data-value');
        var userID = this.getAttribute('data-userID');
        var userSubID = this.getAttribute('data-subID');
        var type = this.getAttribute('data-type');

        console.log(userID, userSubID, page);

        $.ajax({
            url:"-index.php?controller=profil&action=sub",
            method: "GET",
            dataType:"JSON",
            data: {
                user_ID: userID,
                userSub_ID: userSubID,
                actionType: type,
            },
            success:function(result)
            {
                console.log(result);
                // On sub success
                if(result.result)
                {
                    $("div#aboContent").html(result.result);
                }
                else if(result.error)
                {
                    $("div#aboContentError").css("display", "block");
                    $("div#aboContentError").html(result.error);
                }
                // on unSub success
                if(result == "OK")
                {
                    $("button#subScribe").removeClass("btn btn-danger");
                    $("button#subScribe").addClass("btn btn-primary");
                    $("button#subScribe").text("S'abonner").fadeIn(200);
                    $("button#subScribe").attr("data-type", "sub");
                }

            },
            error:function(res)
            {
                console.log(res);
                if(res.error)
                {
                    $("div#aboContentError").css("display", "block");
                    $("div#aboContentError").html(res.error);
                }
                else
                {
                    $("div#aboContentError").css("display", "block");
                    $("div#aboContentError").html(res);
                }
            }
        });
    }));
});

