$(document).ready(function() {
    /**
     *  tableau pour interval
     */
    var chatActiveBox = [];

    /**
     * event binding for user tchat process
     */
    $(document).on('click', '.privateMessage', {}, (function() {
        var idAbo = this.getAttribute("data-userabo");
        var idUser = this.getAttribute("data-user");
        var path = "div#chatBox"+idUser+idAbo+"";
            getMessage(idUser, idAbo, path);
    }));

    /**
     * interval process for open tchat after reloading page
     */
    $(window).on("load", function() {
        $("ul.chatActive").each(function(){
            var container = $(this).parent().attr("id");
            var path = "div#"+container;
            var user = $(this).find("a.closeConv").attr("data-user");
            var abo = $(this).find("a.closeConv").attr("data-abo");

            chatInterval = setInterval(function()
            {
                getLastMessage(user,abo,path)
            }, 5000);
            chatActiveBox.push({id: abo, func: chatInterval, element: this});
        });
    });

    /**
     * get data from user and send it to php process sendMessage for tchat
     */
    $(document).on("click","input[type=button].buttonSendMessage", {},( function(e)
    {
        e.preventDefault();
        //console.log("ok");
        //var stranger = $(this).attr("data-stranger");
        var user = $(this).attr("data-user");
        var abo = $(this).attr("data-abo");
        var path = "div#chatBox"+user+abo;
        var content = $(path).find("textarea").val();
        var errorBox = $("div#errorChat"+abo);
        var focus = $(path).find("textarea");

        console.log(user, abo, content, path);
        $.each(chatActiveBox, function(index, val)
        {
            if(val.id == abo)
            {
                clearInterval(val.func);
            }
        });
        $.ajax({
            url: '-index.php?controller=chat&action=sendMessage',
            method: "GET",
            data: {
                userID: user,
                aboID: abo,
                data: content,
            },
            success:function(result)
            {
                console.log(result);
                if(result.error)
                {
                    errorBox.css("display", "block");
                    errorBox.html(result.error);
                }
                else
                {
                    focus.focus();
                    getLastMessage(user,abo,path);
                }
            },
            error:function(res)
            {
                console.log(res);
                if(res.error)
                {
                    errorBox.css("display", "block");
                    errorBox.html(res.error);
                }
            }
        });

        /**
         * interval process for open tchat
         * @type {number}
         */
        chatInterval = setInterval(function()
        {
            getLastMessage(user,abo,path)
        }, 5000);

        chatActiveBox.push({id: abo, func: chatInterval, element: this});

    }));

    /**
     * get all tchat message between user and follower/following person
     * @param idUser
     * @param idAbo
     * @param path
     * @return html/JSON
     */
    function getMessage(idUser,idAbo, path)
    {
        var errorBox = $("div#errorChat"+idAbo);
        var focus = $(path).find("textarea");
        var container = "<div class='chat' id='chatBox"+idUser+idAbo+"'></div>";

        console.log(path);
        $.ajax({
            url: '-index.php?controller=chat&action=getChatMessage',
            method: "GET",

            data: {
                aboID : idAbo,
                userID: idUser,
            },
            success: function(result)
            {
               console.log(result);
               if(result.error)
               {
                    errorBox.css("display", "block");
                    errorBox.html(result.error);
               }
               else
               {
                   $("footer").append(container);
                   $(path).html(result);

                   focus.focus();

                   chatInterval = setInterval(function()
                   {
                       getLastMessage(idUser,idAbo,path)
                   }, 5000);
                   chatActiveBox.push({id: idAbo, func: chatInterval, element: this});
               }
            },
            error:function(res)
            {
                console.log(res);
                if(res.error)
                {
                    errorBox.css("display", "block");
                    errorBox.html(res.error);
                }
            }
        });
    }

    /**
     * get last insert tchat message from a open tchat function call in interval
     * @param idUser
     * @param idAbo
     * @param path
     * @return html/JSON
     */
    function getLastMessage(idUser,idAbo, path )
    {
        var box = $(path).find($("ul.chatActive"));
        var lastLi = $(box).find("li").last();
        var lastMess = $(lastLi).attr("id");
        var errorBox = $("div#errorChat"+idAbo);
        console.log(box,lastLi,lastMess);

        $.ajax({
            url: '-index.php?controller=chat&action=getLastChatMessage',
            method: "GET",
            data: {
                aboID : idAbo,
                userID: idUser,
                lastId: lastMess,
            },
            dataType:"JSON",
            success: function(result)
            {
                if(result.error)
                {
                    errorBox.css("display", "none");
                    errorBox.html(result.error);
                }
                else
                {
                    var txt = "";
                    for(var i in result)
                    {
                        var response = result[i];
                        if(response.id_conv != lastMess)
                        {
                            if(response.id_utilisateur == idAbo)
                            {
                                txt += "<li id='"+response.id_message+"' class=\"right clearfix\"><span class=\"chat-img pull-right\">";
                                txt += "<img src=\"http://placehold.it/50/55C1E7/fff&text=U\" alt=\"User Avatar\" class=\"img-circle\" />";
                            }
                            else
                            {
                                txt += "<li id='"+response.id_message+"' class=\"left clearfix\"><span class=\"chat-img pull-left\">";
                                txt += '<img src="http://placehold.it/50/FA6F57/fff&text=ME" alt="User Avatar" class="img-circle" />'
                            }

                            txt += response.m_message + "<br>";
                            txt += response.m_date_message + "<br>";
                            txt += "</span></li>";

                            $(lastLi).append(txt);
                        }
                    }
                }
            },
            error:function(res)
            {
                console.log(res);

                if(res.error)
                {
                    errorBox.css("display", "none");
                    errorBox.html(res.error);
                }
            }
        });
    }

    /**
     * close tchat tab
     */
    $(document).on("click", '.closeConv', {}, (function(e) {
        e.preventDefault();
        var user = this.getAttribute("data-user");
        var abo = this.getAttribute("data-abo");
        var chatBox = user+abo;
        $.ajax({
            url: "-index.php?controller=chat&action=closeConvers",
            method: "GET",
            data: {
                aboID: abo,
            },
            success:function (result)
            {
                console.log(result);
                $("div#chatBox"+chatBox).css("display", "none");
                $.each(chatActiveBox, function(index, val)
                {
                    if(val.id == abo)
                    {
                        clearInterval(val.func);
                    }
                });
            },
            error:function (res)
            {
                console.log(res);
            }
        });
    }));


    /**
     * reduce tchat tab
     */

    $(document).on("click", '.reducConv', {}, (function(e) {
        e.preventDefault();
        console.log(this);

        var user = this.getAttribute("data-user");
        var abo = this.getAttribute("data-abo");
        var chatBox = "div#chatBox"+user+abo;
        //var cssValue = $(chatBox).css("height");
        var divChild = $(chatBox).find("div");
        $(divChild).each(function() {
            if ($(this).attr("class") === 'open')
            {
                $(chatBox).find("div.textBox").css("display", "none");
                $(this).addClass("reduc");
                $(this).removeClass("open");
            }
            else if($(this).attr("class") === "reduc")
            {
                $(this).addClass("open");
                $(this).removeClass("reduc");
                $(chatBox).find("div.textBox").css("display", "block");
            }
        });
    }));

    $(document).on("click", "button.messageBox", {}, (function() {
        var abo = this.getAttribute("data-abo");
        var user = this.getAttribute("data-userID");
        $.ajax({
            url:'-index.php?controller=frontProfil&action=showMessagerieBox',
            method:"GET",
            dataType: "JSON",
            data: {
                aboID : abo,
                userID: user,
            },
            success:function(result)
            {
                $("div.messageView").html(result);
            },
            error:function(res)
            {
                console.log(res);
            }
        });
    }));
    $(document).on("click", "button.SendMessageBox", {},(function()
    {
        var user = this.getAttribute("data-user");
        var abo = this.getAttribute("data-abo");
    }))
});

