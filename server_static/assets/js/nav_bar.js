$(document).ready(function(){
    $(".toggle").click(function() {
        if ($(".navigation").hasClass('reduced')){
            $(".navigation").removeClass('reduced');
            if ($( window ).width() >= 900){
                setTimeout(function(){
                $('.sous_li').css('display', 'none').css('display', 'block');
                    },300);
            } else {
                setTimeout(function(){
                    $('.sous_li').css('display', 'block').css('display', 'none');
                },400);
            }
        } else {
            $(".navigation").addClass('reduced');
            if ($( window ).width() >= 900){
                setTimeout(function(){
                    $('.sous_li').css('display', 'block').css('display', 'none');
                },400);
            } else {
                $('.sous_li').css('display', 'none').css('display', 'block');
            }
        }
        if ($(".main").hasClass('reduced')){
            $(".main").removeClass('reduced');
        } else {
            $(".main").addClass('reduced');
        }
    });
});
