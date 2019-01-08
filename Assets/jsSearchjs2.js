$(document).ready(function() {
    $('#searchTerm, #searchCat').keyup(function(e) {
        if(e.keyCode == 13)
        {
            var term = $(this).val();
            var errorBox = $("div#errorSearch");
            var from = this.getAttribute("data-from");
            var user = this.getAttribute("data-user");

            console.log(term, from);
            if(term.length >= 3)
            {
                window.location.href = '-index.php?controller=frontProfil&action=frontPage&option=search&term='+term+'&from='+from+'&userID='+user;
            }
            else
            {
                errorBox.css("display", "block");
                errorBox.html(" 3 lettres minimum");
            }
        }
    });
});