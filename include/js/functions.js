
function page_to_top() {
	$('html,body').animate({scrollTop: 0}, 'slow');
}

function page_to_content() {
	$('html,body').animate({scrollTop: 295}, 'slow');
}

$(document).ready(function() {
	$('.fancybox').fancybox();
});

function change_flag_form(id_select, id_img) {
	var flag_value = $('#' + id_select + ' option:selected')[0].text;
	console.log(flag_value);
	var flag = $('#' + id_img);
	var src = flag.attr("src");
	var adr = src.lastIndexOf('/');
	flag.attr("src", src.substring(0,adr+1) + flag_value + '.png');
}


(function($){
    $.dvjhGoUp = function(options){
        var opts = $.extend(true, {}, $.dvjhGoUp.defaults, options);
		
		$("<img/>", {
			id:opts.imgId,
			src:opts.imgSrc,
			css:{
				display:"none",
				position:"fixed",
				bottom:opts.bottom,
				right:opts.right,
				border:opts.imgBorder,
				cursor:opts.imgCursor
			},
			alt:opts.imgAlt,
			title:opts.imgTitle,
			click:function(){
				$("html, body").animate({scrollTop: "0px"}, opts.scrollDelay);
				return false;						
			}
		}).appendTo("body");
		
		$(window).bind("scroll", function() {
			var obj = $("#"+opts.imgId);
			
			if ($(this).scrollTop() > opts.scrollImg){ 
				obj.fadeIn(opts.imgFadeInDelay);
			} else { 
				obj.fadeOut(opts.imgFadeOutDelay);
			}
		});
    };
    
    $.dvjhGoUp.defaults = {
        bottom: "10px", // position fixed bottomRight
        right: "10px", // position fixed bottomRight
		imgId: "dvjhGoUp",
		imgSrc: "/img/icon/top.png", // exemple : "images/dvjhGoUp.png"
		imgAlt: "Go up.",
		imgTitle: "Go up.",
		imgBorder: "none",
		imgCursor: "pointer",
		imgFadeInDelay: 400, // 0.8s
		imgFadeOutDelay: 400, // 0.8s
		scrollImg: 200, // distance de scroll, en px, avant l'apparition ou la disparition de l'image
		scrollDelay: 1000 // durée de l'animation 2s
    };
})(jQuery);
	$.dvjhGoUp();