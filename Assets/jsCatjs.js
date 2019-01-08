$(document).ready(function() {
    $("input.tm-input").tagsManager();

    /**
     * add tags on existant publication event
     */
    $(document).on('click', '.tagsAfter', {}, (function(e) {
        e.preventDefault();
        var idPwitt = this.getAttribute("data-idpwitt");
        var form = $("#addTagsAfter" + idPwitt);
        var tag = form.find($("input[type='hidden']")).val();
        console.log(idPwitt, tag);

        $.ajax({
            url:"-index.php?controller=categorie&action=addTagsAfter",
            method:"GET",
            data: {
                tags: tag,
                pwitt: idPwitt,
            },
            success:function(result)
            {
                console.log(result);
                // to do add tag dynamically
                location.reload();
            },
            error:function(res)
            {
                console.log(res);
            }
        });
    }));

    /**
     * remove tag on an exixstant publication
     */
    $("i[data-name='removeTags']").click(function(e) {
        e.preventDefault();
        //console.log("ok");
        var tagsID = this.getAttribute("data-id_tags");
        var pwittID = this.getAttribute("data-idPwitt");
        console.log(tagsID, pwittID);
        $.ajax({
            url:"-index.php?controller=categorie&action=removeTag",
            method: "GET",
            data: {
                tags: tagsID,
                pwitt: pwittID,
            },
            success:function(result)
            {
                console.log(result);
                // to do remove tag dynamically
                location.reload();
            },
            error:function(res)
            {
                console.log(res);
            }
        });
    });

    /**
     * sub to tag event
     */
    $("i[data-name='subToTag']").click(function(e) {
        e.preventDefault();
        var item = $(this);
        var tagsID = this.getAttribute("data-id_tags2");
        var user = this.getAttribute("data-user");
        var dataClass = this.getAttribute("class");
        if(dataClass === "fa fa-bell")
        {
            $.ajax({
                url:'-index.php?controller=categorie&action=subTo',
                method:"GET",
                data: {
                    tags: tagsID,
                    userID: user,
                },
                success:function(result)
                {
                    if(result.statut)
                    {
                        console.log(result.statut);
                    }
                    else
                    {
                        console.log(result);
                        item.removeClass("fa fa-bell");
                        item.addClass('fa fa-bell-slash');
                    }
                },
                error:function(res)
                {
                    console.log(res);
                }
            });
        }
        else
        {
            // sub to tag event
            $.ajax({
                url:'-index.php?controller=categorie&action=unSubTo',
                method:"GET",
                data: {
                    tags: tagsID,
                    userID: user,
                },
                success:function(result)
                {
                    if(result.statut)
                    {
                        console.log(result.statut);
                    }
                    else
                    {
                        console.log(result);
                        item.removeClass("fa fa-bell-slash");
                        item.addClass('fa fa-bell');
                    }

                },
                error:function(res)
                {
                    console.log(res);
                }
            });
        }
    });
});