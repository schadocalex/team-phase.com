//<![CDATA[
				 
// Array to hold each digit's starting background-position Y value
var initialPosHours = [0, -618, -1236, -1854, -2472, -3090, -3708, -4326, -4944, -5562];
// Amination frames
var animationFramesHours = 5;
// Frame shift
var frameShiftHours = 103;
 
// Starting time
var timeStartHours = getTimeStringHours();

// Pace of counting in milliseconds
var paceHours = 1000;
 
// Initializing variables
var digitsOldHours = [], digitsNewHours = [], subStartHours, subEndHours;
 
// Function that controls counting
function doCountHours(timeThenHours){
	var timeNowHours = getTimeStringHours();
	digitCheckHours(timeThenHours,timeNowHours);
	setTimeout(function(){
		doCountHours(timeNowHours);
	}, paceHours);
}
 
// This checks the old count value vs. new value, to determine how many digits
// have changed and need to be animated.
function digitCheckHours(x,y){
	var digitsOldHours = splitToArrayHours(x),
	digitsNewHours = splitToArrayHours(y);
	for (var i = 0, c = digitsNewHours.length; i < c; i++){
		if (digitsNewHours[i] != digitsOldHours[i]){
			animateDigitHours(i, digitsOldHours[i], digitsNewHours[i]);
		}
	}
}
 
// Animation function
function animateDigitHours(n, oldDigit, newDigit){
	speed = 80;
	// Get the initial Y value of background position to begin animate
	var pos = initialPosHours[oldDigit];
	// Each animation is 5 frames long, and 103px down the background image.
	// We delay each frame according to the speed we determined above.
	for (var k = 0; k < animationFramesHours; k++){
		pos = pos - frameShiftHours;
		if (k == (animationFramesHours - 1)){
			$("#hours" + n).delay(speed).animate({'background-position': '0 ' + pos + 'px'}, 0, function(){
				// At end of animation, shift position to new digit.
				$("#hours" + n).css({'background-position': '0 ' + initialPosHours[newDigit] + 'px'}, 0);
			});
		}
		else{
			$("#hours" + n).delay(speed).animate({'background-position': '0 ' + pos + 'px'}, 0);
		}
	}
}
 
// Splits each value into an array of digits
function splitToArrayHours(input){
	var digits = new Array();
	for (var i = 0, c = input.length; i < c; i++){
		subStartHours = input.length - (i + 1);
		subEndHours = input.length - i;
		digits[i] = input.substring(subStartHours, subEndHours);
	}
	return digits;
}
 
// Sets the correct digits on load
function initialDigitCheckHours(initial){
	var digits = splitToArrayHours(initial.toString());
	for (var i = 0, c = digits.length; i < c; i++){
		$("#hours" + i).css({'background-position': '0 ' + initialPosHours[digits[i]] + 'px'});
	}
}

// Returns time as hhmmss
function getTimeStringHours(){
	var currentDate = new Date();
	var hours = currentDate.getHours();
	hours = (hours < 10) ? '0' + hours : hours.toString();
	var minutes = currentDate.getMinutes();
	minutes = (minutes < 10) ? '0' + minutes : minutes.toString();
	var seconds = currentDate.getSeconds();
	seconds = (seconds < 10) ? '0' + seconds : seconds.toString();
	var time = hours + minutes + seconds;
	return time;
}
 
// Start it up

// Hours
initialDigitCheckHours(timeStartHours);
doCountHours(timeStartHours);
 
//]]>