$(document).ready(function(){
    $("#li_parametres").delay(2000).addClass("hover");

    $("#button_edit_password").click(function() {
        $(".modal").show();
        $(".info_message").css("display", "none");
        for (let pas = 0; pas < 3; pas++) {
            $(input[pas]).val("");
        }
    });

    $(".close:eq(0)").click(function() {
        $(".modal").hide();
    });

    window.onclick = function(event) {
        if (event.target == document.getElementById("modal_edit_password")) {
            $(".modal").hide();
        }
    }

    //MODAL POST CHECK
    var input = $('.input_edit_password');
    $('.form_edit_password').on('submit',(function(){
        $.post(
            'controller/Edit_password.php',
            {
                actual_pass: sha1($(input[0]).val().trim()),
                new_pass: sha1($(input[1]).val().trim()),
                confirm_pass: sha1($(input[2]).val().trim())},
            function(data, status, jqXHR) {
                console.log(data.trim());
                if (data.trim() == "true"){
                    $(".info_message").html("Mot de passe modifié avec succès");
                    $(".info_message").css("background-color", "#90ee90");
                    $(".info_message").css("display", "block");
                    for (let pas = 0; pas < 3; pas++) {
                        $(input[pas]).val("");
                    }
                    setTimeout(function() { location.href='/controller/Disconnect.php'; }, 1000);
                    
                } else if (data.trim() == "new_not_match"){
                    $(".info_message").html("Le nouveau mot de passe ne correpond pas avec le champ confirmation");
                    $(".info_message").css("background-color", "#df8787");
                    $(".info_message").css("display", "block");
                } else if (data.trim() == "actual_not_match"){
                    $(".info_message").html("Le mot de passe actuel ne correpond pas");
                    $(".info_message").css("background-color", "#df8787");
                    $(".info_message").css("display", "block");
                }
            })
        return false;
        
    }));





});