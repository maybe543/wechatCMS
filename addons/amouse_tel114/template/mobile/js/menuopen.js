$(function () {  
	$('.sr_more').click(function (event) {  
		event.stopPropagation();
		$('#menu').toggle();  
       });  
	$(document).click(function (event) { $('#menu').hide() });  
		$('#menu').click(function (event) { $(this).toggle() });  
})
function closeErrors() { 
return true; 
} 
window.onerror=closeErrors; 

$(document).ready(function () {
    $('#activator').click(function () {
        $('#sbg').fadeIn('fast', function () {
            $('#sharebox').animate({ 'bottom': '0' }, 300);
        });
    });
    $('#boxclose').click(function () {
        $('#sharebox').animate({ 'bottom': '-200px' }, 300, function () {
            $('#sbg').fadeOut('fast');
        });
    });
});
$(document).ready(function() {
    $("#activator").click(function(e) {
        e.stopPropagation();
        $("div.sharebox,div.sbg").removeClass("hide");
    });
    $(document).click(function() {
        if (!$("div.sharebox,div.sbg").hasClass("hide")) {
            $("div.sharebox,div.sbg").addClass("hide");
			$('#sbg').fadeOut('fast');
        }
    });
});

$(document).ready(function () {
    $(function () {
        $('.btn').click(function (event) {
            event.stopPropagation();
            $('.bg').fadeIn('fast', function () {
                $('.boxtext').animate({ 'top': '45px' }, 500);
            });
        });
    });
    $('.boxclose').click(function () {
        $('.boxtext').animate({ 'top': '-500px' }, 500, function () {
            $('.bg').fadeOut('fast');
        });
    });
    $(document).bind("click",function(e){
        var target  = $(e.target);
        if(target.closest(".boxtext").length == 0){
            $('.boxtext').animate({ 'top': '-500px' }, 500, function () {
                $('.bg').fadeOut('fast');
            });
        }
    });
});