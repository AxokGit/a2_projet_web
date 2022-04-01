var ID_internship=0;

$(document).ready(function(){

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    if (urlParams.get('page') == null){
        window.location.href = "/?page=1";
    }
    var page = parseInt(urlParams.get('page'));


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

    $(".close:eq(0)").click(function() {
        $(".modal").hide();
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

    function replaceUrlParam(url, paramName, paramValue)
    {
        if (paramValue == null) {
            paramValue = '';
        }
        var pattern = new RegExp('\\b('+paramName+'=).*?(&|#|$)');
        if (url.search(pattern)>=0) {
            return url.replace(pattern,'$1' + paramValue + '$2');
        }
        url = url.replace(/[?#]$/,'');
        return url + (url.indexOf('?')>0 ? '&' : '?') + paramName + '=' + paramValue;
    }

    function left(){
        if (page != null){
            if (page >= 2) {
                console.log("left");
                window.location.href = replaceUrlParam(window.location.href, "page", parseInt(urlParams.get('page'))-1);
            }
        }
    }
    $(".left").click( function() {
        left();
    });

    function right(){
        if (page != null){
            if (page >= 1) {
                console.log("right");
                window.location.href = replaceUrlParam(window.location.href, "page", parseInt(urlParams.get('page'))+1);
            }
        }
    }
    $(".right").click( function() {
        right();
    });
});