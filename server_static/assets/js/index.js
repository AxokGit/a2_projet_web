////////////////////////////////////////////////////
// [ Form Login ]
(function ($) {
    if (!window.navigator.onLine){
        console.log("offline");
        $(".info_message").show();
    }
    "use strict";
    var input = $('.validate-input .input-forms');
    $('.validate').on('submit',(function(){
        
        var check = true;
        for(var i=0; i<input.length; i++) {
            if(validate(input[i]) === false){
                showValidate(input[i]);
                check=false;
            }
        }

        if (check){
            $.ajax({
            type: 'POST',
            url: 'controller/Auth.php',
            data: {user: $(input[0]).val().trim(), pass: sha1($(input[1]).val().trim())},
            success: function(data, status, jqXHR) {
                console.log(data.trim());
                if (data.trim() == "true"){
                    location.href='/';
                } else {
                    $("#zone-login").addClass("shaking_error");
                    setTimeout(function() {
                        $("#zone-login").removeClass("shaking_error");
                    }, 1000);
                }
            }});
            
        }
        check = false;
        return check;
        
    }));

    function validate(input) {
        if($(input).val().trim() === ''){
                return false;
        }
    }

    $('.validate .input-forms').each(function(){
        $(this).focus(function(){
            hideValidate(this);
        });
    });

    function showValidate(input) {
        var thisAlert = $(input).parent();
        $(thisAlert).addClass('alert-validate');
    }

    function hideValidate(input) {
        var thisAlert = $(input).parent();
        $(thisAlert).removeClass('alert-validate');
    }
})(jQuery);

function httpGet(theUrl) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", theUrl, false);
    xmlHttp.send(null);
    return xmlHttp.responseText;
}

/////////////////////////////////////////////////
// [ Other ]

$('.hidden').delay(300).fadeIn(400);


if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('sw.js')
        .then(registration => {
          console.log(`Service Worker enregistré ! Ressource: ${registration.scope}`);
        })  
        .catch(err => {
          console.log(`Echec de l'enregistrement du Service Worker: ${err}`);
        });
    });
}