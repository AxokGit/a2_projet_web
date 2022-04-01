$(document).ready(function(){
    $("#li_gestion_entreprises").delay(2000).addClass("hover");


    $(".logo_add").click(function() {
        $(".modal").show();
        $(".title_modal").html("Ajout d'une entreprise");
        $("input[type='hidden']").attr("value","add");
        $("input[name='name']").attr("value","");
        $("input[name='activity_sector']").attr("value","");
        $("input[name='nb_intern_cesi']").attr("value","");
        $("input[name='email']").attr("value","");

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
        $(".form_add_edit").append('<input type="hidden" name="ID_company" value="'+ window.ID_company +'">');
        return true;
    }));




    $(".logo_edit").click(function() {
        $(".modal").show();
        window.ID_company = $(this).attr("ID_company");
        $(".title_modal").html("Modification d'une entreprise");
        $("input[type='hidden']").attr("value","edit");
        $("input[name='name']").attr("value",$(this).attr("name"));
        $("input[name='activity_sector']").attr("value",$(this).attr("activity_sector"));
        $("input[name='nb_intern_cesi']").attr("value",$(this).attr("nb_intern"));
        $("input[name='email']").attr("value",$(this).attr("email"));

        $("select[name='localisation'] option[value="+$(this).attr("localisation")+"]").prop('selected', true);
        $("select[name='note'] option[value="+$(this).attr("note")+"]").prop('selected', true);
        $("select[name='visibility'] option[value="+$(this).attr("visibility")+"]").prop('selected', true);
        $(".info_message").css("display", "none");
    });


    $(".logo_delete").click(function() {
        console.log($(this).attr("ID_company"));
        $.post(
            'controller/Manage_companies.php',
            {
                ID_company: $(this).attr("ID_company"),
                action: "delete"},
            function(data, status, jqXHR) {
                if (data.trim() == "false"){
                    $(".info_message").html("Cette entreprise ne peut pas supprimer car il existe des stages en lien avec elle.");
                    $(".info_message").css("background-color", "#df8787");
                    $(".info_message").css("display", "block");
                } else if (data.trim() == "true")
                    location.reload();
            }
            );
    });

    $(".logo_stat").click(function() {
        console.log($(this).attr("ID_company"));
        $("#modal_stat").show();
    });

    $("#close_stat").click(function() {
        $("#modal_stat").hide();
    });



    
});