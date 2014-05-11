var slider_id = [1, 2, 3];
var nb_slides = slider_id.length;

slider_act_id = 0;
var slider_anim = false;
var duration_anim_slider = 500;

function echanger_img(id1, id2) {
	$('#slider_img_'+id1).fadeOut(duration_anim_slider,function() {
		$('#slider_img_'+id2).fadeIn(duration_anim_slider, function() { slider_anim = false; });
	});
	$('#title_slider_'+id1).fadeOut(duration_anim_slider,function() {
		$('#title_slider_'+id2).fadeIn(duration_anim_slider);
	});
	// $('#slider_img2_'+id2).fadeOut(duration_anim_slider,function() {
		// $('#slider_img2_'+id1).fadeIn(duration_anim_slider);
	// });
	
	// buttons
	$('#slider_button_on_'+id1).fadeOut(0,function() {
		$('#slider_button_off_'+id1).fadeIn(0);
	});
	$('#slider_button_off_'+id2).fadeOut(0,function() {
		$('#slider_button_on_'+id2).fadeIn(0);
	});
	last_slide_animation = getTime();
}

function slider_left_arrow() {
	var slider_next_id = slider_act_id - 1;
	if(slider_next_id < 0)
		slider_next_id = nb_slides - 1;
		
	slider_select(slider_id[slider_next_id]);
}

function slider_right_arrow() {
	var slider_next_id = slider_act_id + 1;
	if(slider_next_id > nb_slides - 1)
		slider_next_id = 0;
		
	slider_select(slider_id[slider_next_id]);
}

function slider_select(id) {
	if(!slider_anim) {
		if(slider_id[id-1] != undefined && id != slider_id[slider_act_id]) {
			slider_anim = true;
			echanger_img(slider_id[slider_act_id], id);
			slider_act_id = id - 1;
		}
	}
}

// Slide auto
var NB_SEC_SLIDES = 5.5;

function getTime() {
	return new Date().getTime();
}

function slider_auto() {
	if(last_slide_animation + NB_SEC_SLIDES*1000 < getTime())
	{
		slider_right_arrow();
	}
	setTimeout(slider_auto, 500);
}
var last_slide_animation = getTime();
slider_auto();