var ID_internship=0;

$(document).ready(function(){
    $("#li_stages").delay(2000).addClass("hover");

    $(".heart1, .heart2").click(function(event) {
        var id = $(this).prop('id').split('_')[0];
        if ($("#"+id+"_1").hasClass("heart-hidden")){ //enlever coeur rouge /enlever des favoris
            $.post(
                'controller/AddRemoveWishlist.php',
                {action: "remove", ID_internship: id},
                function(data, status, jqXHR) {
                    if (data.trim() == "remove_ok"){
                        $("#"+id+"_1").removeClass("heart-hidden");
                        $("#"+id+"_2").addClass("heart-hidden");
                    }
            });
        } else {                                //cocher coeur gris /ajouter aux favoris
            $.post(
                'controller/AddRemoveWishlist.php',
                {action: "add", ID_internship: id},
                function(data, status, jqXHR) {
                    if (data.trim() == "add_ok"){
                        $("#"+id+"_2").removeClass("heart-hidden");
                        $("#"+id+"_1").addClass("heart-hidden");
                    }
            });
        }
    });

    $(".button_postuler").click(function() {
        window.ID_internship = $(this).attr("id_internship");
        $(".title_modal").html("Postuler pour " + $(this).attr("name_internship"));
        $(".info_message").hide();
        $(".modal").show();
    });

    window.onclick = function(event) {
        if (event.target == document.getElementById("modal_postuler")) {
            $(".modal").hide();
        }
    }

    $('.form_postuler').on('submit',(function(){
        check = true;
        if ($('#cv').get(0).files.length === 0 || $('#lm').get(0).files.length === 0) { check = false; }
        if (check) {$(".form_postuler").append('<input type="hidden" name="ID_internship" value="'+ window.ID_internship +'">');}
        return check;
    }));
});