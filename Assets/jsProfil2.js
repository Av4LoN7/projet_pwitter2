$(document).ready(function() {

    /**
     * show modif bform
     */
    $("#modifDescription").on("click", function() {
        $("form#formModif").css("display","block");
       var focus = $("form#formModif").find("textarea");
        focus.focus();
    });

    /**
     * modify description event
     */
    $("form#formModif").on("submit", {}, (function(e) {
       e.preventDefault();
       var txt = $(this).find("textarea").val();
       var id_user = $(this).find("input[type=hidden]").val();
        console.log(id_user);
        console.log(txt);
        if(id_user != "")
        {
            $.ajax({
                url:'-index.php?controller=profil&action=addProfilDescription',
                method: "GET",
                dataType:"JSON",
                data: {
                    userID: id_user,
                    modif : txt,
                },
                success:function(result)
                {
                    console.log(result);
                    if(result.error)
                    {
                        $("p#userDescriptif").append('<div class="alert alert-danger">'+ result.error +'</div>');
                    }
                    else
                    {
                        $("p#userDescriptif").html(txt);

                        $("#text_areaDescription").val("");
                        $("form#formModif").css("display","none");
                        $("p#userDescriptif").append('<div class="alert alert-success"> Description modifier avec success ! </div>');
                    }
                },
                error:function(res)
                {

                    if(res.error)
                    {
                        $("p#userDescriptif").append('<div class="alert alert-danger">'+ res.error+'</div>');
                    }
                    console.log(res);

                }
            })
        }
    }));

    /**
     * delete description event
     */
    $(document).on("click", '#deleteDescript', {}, (function (e) {
        e.preventDefault();
        var user = this.getAttribute("data-user");
        if(user)
        {
            $.ajax({
                url: "-index.php?controller=profil&action=deleteDescription",
                method:"GET",
                dataType:"JSON",
                data:{
                    userID: user,
                },
                success: function(result)
                {
                    if(result.error)
                    {
                        $("p#userDescriptif").append('<div class="alert alert-danger">'+ result.error +'</div>');
                    }
                    else
                    {
                        $("p#userDescriptif").html("");
                        $("p#userDescriptif").append('<div class="alert alert-success"> Suppression effetuer avec succees</div>');
                    }
                },
                error:function(res)
                {
                    if(res.error)
                    {
                        $("p#userDescriptif").append('<div class="alert alert-danger">'+ res.error+'</div>');
                    }
                    else
                    {
                        $("p#userDescriptif").append('<div class="alert alert-danger">'+ res.responseText+'</div>');
                    }
                    console.log(res);
                }
            })
        }
    }))

    /**
     * change email event
     */
    $(document).on("click", '.sendNewMail', {}, (function() {

        var currentMail = $("fieldset#userFieldMail").find("p").html();
        var newMail = $("input#userMail").val();
        var id_user = this.getAttribute("data-user");

        if(id_user && currentMail && newMail)
        {
            $.ajax({
                url:'-index.php?controller=profil&action=changeMail',
                method: "GET",
                dataType: "JSON",
                data: {
                    cMail: currentMail,
                    nMail: newMail,
                    userId: id_user,
                },
                success:function(result)
                {
                    console.log(result);
                    if(result.success)
                    {
                        $("div#successMail").css("display", 'block');
                        $("div#successMail").html(result.success);
                        setTimeout(function(){
                            location.reload();
                        },2000);
                    }
                    else if(result.error)
                    {
                        $("div#errorMail").css("display", 'block');
                        $("div#errorMail").html(result.error);
                    }

                },
                error:function(res)
                {
                    console.log(res);
                    if(res.error)
                    {
                        $("div#errorMail").css("display", 'block');
                        $("div#errorMail").html(res.error);
                    }
                    else
                    {
                        $("div#errorMail").css("display", 'block');
                        $("div#errorMail").html(res);
                    }
                }
            });
        }
    }));

    /**
     * change pseudo event
     */
    $(document).on("click", '.sendNewPseudo', {}, (function() {
        var input = $("#userPseudo");
        var content = input.val();
        var user = input.attr("data-user");

        $.ajax({
            url:'-index.php?controller=profil&action=changePseudo',
            method: "GET",
            dataType: "JSON",
            data: {
                data: content,
                user_id: user,
            },
            success:function(result)
            {
                console.log(result);

                if(result.success)
                {
                    $("p#identifiantUser").html(content);
                    $("div#successPseudo").css("display","block");
                    $("div#successPseudo").html(result.success);

                }
                else if(result.error)
                {
                    $("div#errorPseudo").css("display","block");
                    $("div#errorPseudo").html(result.error);
                }
                input.val("");
            },
            error:function(res)
            {
                console.log(res);
                if(res.error)
                {
                    $("div#errorPseudo").css("display","block");
                    $("div#errorPseudo").html(res.error);
                }
                else
                {
                    $("div#errorPseudo").css("display","block");
                    $("div#errorPseudo").html(res);
                }
                    input.val("");
            }
        });

    }));

    /**
     * change lastname and firstname event
     */
    $(document).on("click", ".sendNewNames", {}, (function() {
        var user = this.getAttribute("data-user");
        var firstName = $("#userName").val();
        var lastName = $("#userLastName").val();

        console.log(user,firstName, lastName);

        $.ajax({
            url:'-index.php?controller=profil&action=changeUserRealNames',
            method:"GET",
            dataType: "JSON",
            data: {
                user_id: user,
                firstN : firstName,
                lastN: lastName,
            },
            success:function(result)
            {
                console.log(result);
                if(result.success)
                {
                    $("div#successName").css("display","block");
                    $("div#successName").html(result.success);
                    setTimeout(function(){
                        location.reload();
                    },1000);

                }
                else if(result.error)
                {
                    $("div#errorName").css("display","block");
                    $("div#errorName").html(result.error);
                }
            },
            error:function(res)
            {
                console.log(res);
                if(res.error)
                {
                    $("div#errorName").css("display","block");
                    $("div#errorName").html(res.error);
                }
                else
                {
                    $("div#errorName").css("display","block");
                    $("div#errorName").html(res);
                }
            }
        });
    }));

    /**
     * change birthday event
     */
    $(document).on("click", ".sendBirth",{}, (function(){
        var user = this.getAttribute("data-user");
        var content = $("#userBirth").val();
        console.log(content, user );

        $.ajax({
            url: '-index.php?controller=profil&action=changeBirth',
            method: "GET",
            dataType: "JSON",
            data:{
                user_id: user,
                date: content,
            },
            success:function(result)
            {
                if(result.success)
                {
                    $("#userBirth").val("");
                    $("div#successBirth").css("display","block");
                    $("div#successBirth").html(result.success);
                    setTimeout(function(){
                        location.reload();
                    },1000);
                }
                else if(result.error)
                {
                    $("div#errorBirth").css("display","block");
                    $("div#errorBirth").html(result.error);
                }
                console.log(result)

            },
            error:function(res)
            {
                if(res.error)
                {
                    $("div#errorBirth").css("display","block");
                    $("div#errorBirth").html(res.error);
                }
                console.log(res);
            }
        });
    }));

    // upload avatar script (user interface)
    $(document).on("submit","form#userAvatar", {}, function(e) {
        e.preventDefault();
       console.log(e);

        $.ajax({
            url:'-index.php?controller=profil&action=sendImage',
            method:"POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
        success:function(result)
        {
            console.log(result);
            if(result.miniature)
            {
                $(this).prepend("<p class='userAvatar'> <img src='" + result.miniature+"'></p>");

                // TO DO show new avatar upload dynamically
            }
            console.log($("p.userAvatar").html());
            location.reload();
        },
        error:function(res)
        {
            console.log(res);
        }
        });
    });

    // preview image before upload (not used)
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#file_prev").change(function() {
        readURL(this);
    });

    /**
     * delete avatar event
     */
    $(document).on("click", "#suppImg", {}, (function() {
        var user = this.getAttribute("data-user");
        $.ajax({
            url:'-index.php?controller=profil&action=suppAvatar',
            method:"GET",
            data: {
                userID :user,
            },
            success:function(result)
            {
                console.log(result);
                $("p.userAvatar").remove();
                //location.reload();
            },
            error:function(res)
            {
                console.log(res);
            }
        });
    }));

    $(document).on("click", "#suppUser", {}, (function() {
        console.log("ok");
        $(".confirmSupp").css("display", "block");
    }));

    $(document).on("click", ".reject", {}, (function() {
        var target = $(".confirmSupp");
        if( target.css("display") === "block")
        {
            target.css("display", "none");
        }
    }));
    $(document).on("click", ".accept", {}, (function() {
        var user = this.getAttribute("data-id");
        $.ajax({
            url:'-index.php?controller=admin&action=deleteProfil',
            method:"POST",
            dataType:"JSON",
            data:{
                user:user,
            },
            success:function(result)
            {
                console.log(result);
                if(result.success)
                {
                    console.log(result.success);
                    setTimeout(function(){
                        window.location.href='-index.php';
                    }, 5000);
                }
            },
            error:function(res)
            {
                console.log(res);
            }
        });
    }));
});
