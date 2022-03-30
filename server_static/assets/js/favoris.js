$(document).ready(function(){
    $("#li_favoris").delay(2000).addClass("hover");

    $(".heart1, .heart2").click(function(event) {
        var id = $(this).prop('id').split('_')[0];
        if ($("#"+id+"_1").hasClass("heart-hidden")){ //enlever coeur rouge /enlever des favoris
            $.post(
                'controller/AddRemoveWishlist.php',
                {action: "remove", ID_internship: id},
                function(data, status, jqXHR) {
                    if (data.trim() == "remove_ok"){
                        window.location.href = "/favoris.php";
                    }
            });
        }
    });
});