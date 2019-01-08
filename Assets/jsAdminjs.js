$(document).ready(function() {
    $(document).on("click", "#userListAdmin", {}, (function()
    {
        var user = this.getAttribute("data-userID");
        $.ajax({
            url:'-index.php?controller=admin$action=suppUserList',
            method:"GET",
            data:{
                userID:user,
            },

        })
    }))
})