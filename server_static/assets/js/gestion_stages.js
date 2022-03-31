$(document).ready(function(){
    $("#li_gestion_stages").delay(2000).addClass("hover");

    $(".logo_add").click(function() {
        $(".modal").show();
        $(".title_modal").html("Ajout d'un stage");
        $("input[type='hidden']").attr("value","edit");
        $("input[name='name_internship']").attr("value",$(this).attr("name_internship"));
        $("input[name='description_internship']").attr("value",$(this).attr("description_internship"));
        $("input[name='duration_internship']").attr("value",$(this).attr("duration_internship"));
        $("input[name='remuneration_internship']").attr("value",$(this).attr("remuneration_internship"));
        $("input[name='offer_date_internship']").attr("value",$(this).attr("offer_date_internship"));
        $("input[name='place_number_internship']").attr("value",$(this).attr("place_number_internship"));
        $("input[name='competences_internship']").attr("value",$(this).attr("competences_internship"));
        $("select[name='name_promotion'] option[value="+$(this).attr("name_promotion")+"]").prop('selected', true);
        $("select[name='localisation'] option[value="+$(this).attr("localisation")+"]").prop('selected', true);
        $("select[name='company'] option[value="+$(this).attr("company")+"]").prop('selected', true);
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
        $(".form_add_edit").append('<input type="hidden" name="ID_internship" value="'+ window.ID_internship +'">');
        return true;
    }));


    $(".logo_edit").click(function() {
        $("#modal_add_edit").show();
        window.ID_internship = $(this).attr("ID_internship");
        $(".title_modal").html("Modification d'un stage");
        $("input[type='hidden']").attr("value","edit");
        $("input[name='name_internship']").attr("value",$(this).attr("name_internship"));
        $("input[name='description_internship']").attr("value",$(this).attr("description_internship"));
        $("input[name='duration_internship']").attr("value",$(this).attr("duration_internship"));
        $("input[name='remuneration_internship']").attr("value",$(this).attr("remuneration_internship"));
        $("input[name='offer_date_internship']").attr("value",$(this).attr("offer_date_internship"));
        $("input[name='place_number_internship']").attr("value",$(this).attr("place_number_internship"));
        $("input[name='competences_internship']").attr("value",$(this).attr("competences_internship"));
        $("select[name='name_promotion'] option[value="+$(this).attr("name_promotion")+"]").prop('selected', true);
        $("select[name='localisation'] option[value="+$(this).attr("localisation")+"]").prop('selected', true);
        $("select[name='company'] option[value="+$(this).attr("company")+"]").prop('selected', true);
        $(".info_message").css("display", "none");
    });

    $(".logo_delete").click(function() {
        console.log($(this).attr("ID_internship"));
        $.post(
            'controller/Manage_internship.php',
            {
                ID_internship: $(this).attr("ID_internship"),
                action: "delete"},
            function(data, status, jqXHR) {
                if (data.trim() == "false"){
                    $(".info_message").html("Ce stage ne peut pas être supprimé car il existe des candidatures en lien avec lui.");
                    $(".info_message").css("background-color", "#df8787");
                    $(".info_message").css("display", "block");
                } else if (data.trim() == "true")
                    location.reload();
            }
            );
    });    
});