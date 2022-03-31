$(document).ready(function(){
    $("#li_gestion_pilotes").delay(2000).addClass("hover");

    $(".logo_add").click(function() {
        $(".modal").show();
        $(".info_message").css("display", "none");
    });

    $(".close:eq(0)").click(function() {
        $(".modal").hide();
    });

    window.onclick = function(event) {
        if (event.target == document.getElementById("modal_add_edit")) {
            $(".modal").hide();
        }
    }

    $('.form_add_edit').on('submit',(function(){
        $(".form_postuler").append('<input type="hidden" name="ID_internship" value="'+ window.ID_internship +'">');
        return true;
    }));


    $(".logo_delete").click(function() {
        console.log($(this).attr("ID_user"));
        $.post(
            'controller/Manage_pilots.php',
            {
                ID_user: $(this).attr("ID_user"),
                action: "delete"},
            function(data, status, jqXHR) {
                if (data.trim() == "false"){
                    $(".info_message").html("Ce pilote ne peut pas supprimer car il existe des candidatures en lien avec lui.");
                    $(".info_message").css("background-color", "#df8787");
                    $(".info_message").css("display", "block");
                } else if (data.trim() == "true")
                    location.reload();
            }
            );
    });
});