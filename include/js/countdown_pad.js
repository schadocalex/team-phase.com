//<![CDATA[

// Array to hold each digit's starting background-position Y value
var initialPosPad = [0, -300, -600, -900, -1200, -1500, -1800, -2100, -2400, -2700];
// Amination frames
var animationFramesPad = 5;
// Frame shift
var frameShiftPad = 50;

// Starting number
var theNumberPad = 0;

// Increment
var incrementPad = 17;
// Pace of counting in milliseconds
var pacePad = 200;

// Initializing variables
var digitsOldPad = [], digitsNewPad = [], subStartPad, subEndPad, xPad, yPad;

// Function that controls counting
function doCountPad(){
	// In this example, we're padding the numbers
	var x = pad(theNumberPad.toString());
	theNumberPad += incrementPad;
	var y = pad(theNumberPad.toString());
	digitCheckPad(x,y);
}

// This checks the old count value vs. new value, to determine how many digits
// have changed and need to be animated.
function digitCheckPad(xPad,yPad){
	if (yPad.length > xPad.length) addDigitPad(yPad.length);
	var digitsOldPad = splitToArrayPad(xPad),
	digitsNewPad = splitToArrayPad(yPad);
	for (var i = 0, c = digitsNewPad.length; i < c; i++){
		if (digitsNewPad[i] != digitsOldPad[i]){
			animateDigitPad(i, digitsOldPad[i], digitsNewPad[i]);
		}
	}
}

// Animation function
function animateDigitPad(nPad, oldDigitPad, newDigitPad){
	// I want three different animations speeds based on the digit,
	// because the pace and increment is so high. If it was counting
	// slower, just one speed would do.
	// 1: Changes so fast is just like a blur
	// 2: You can see complete animation, barely
	// 3: Nice and slow
	var speedPad;
	switch (nPad){
		case 0:
			speedPad = pacePad/8;
			break;
		case 1:
			speedPad = pacePad/4;
			break;
		default:
			speedPad = pacePad/2;
			break;
	}
	// Cap on slowest animation can go
	speedPad = (speedPad > 100) ? 100 : speedPad;
	// Get the initial Y value of background position to begin animation
	var posPad = initialPosPad[oldDigitPad];
	// Each animation is 5 frames long, and 103px down the background image.
	// We delay each frame according to the speed we determined above.
	for (var k = 0; k < animationFramesPad; k++){
		posPad = posPad - frameShiftPad;
		if (k == (animationFramesPad - 1)){
			$("#pad" + nPad).delay(speedPad).animate({'background-position': '0 ' + posPad + 'px'}, 0, function(){
				// At end of animation, shift position to new digit.
				$("#pad" + nPad).css({'background-position': '0 ' + initialPosPad[newDigitPad] + 'px'}, 0);
			});
		}
		else{
			$("#pad" + nPad).delay(speedPad).animate({'background-position': '0 ' + posPad + 'px'}, 0);
		}
	}
}

// Splits each value into an array of digits
function splitToArrayPad(input){
	var digitsPad = new Array();
	for (var i = 0, c = input.length; i < c; i++){
		var subStartPad = input.length - (i + 1),
		subEndPad = input.length - i;
		digitsPad[i] = input.substring(subStartPad, subEndPad);
	}
	return digitsPad;
}

// Adds new digit
function addDigitPad(lenPad){
	var liPad = Number(lenPad) - 1;
	if (liPad % 3 == 0) $("#countdown-pad").prepend('<li class="seperator"></li>');
	$("#countdown-pad").prepend('<li id="pad' + liPad + '"></li>');
	$("#pad" + liPad).css({'background-position': '0 ' + initialPosPad[1] + 'px'});
}

// Sets the correct digits on load
function initialDigitCheckPad(initialPad){
	// Creates the right number of digits
	// In this example, we're padding the numbers
	var paddedPad = pad(initialPad.toString());
	var countPad = paddedPad.length;
	var bitPad = 1;
	for (var i = 0; i < countPad; i++){
		$("#countdown-pad").prepend('<li id="pad' + i + '"></li>');
		if (bitPad != (countPad) && bitPad % 3 == 0) 
			$("#countdown-pad").prepend('<li class="seperator"></li>');
		bitPad++;
	}
	// Sets them to the right number
	var digitsPad = splitToArrayPad(initialPad.toString());
	for (var i = 0, c = digitsPad.length; i < c; i++){
		$("#pad" + i).css({'background-position': '0 ' + initialPosPad[digitsPad[i]] + 'px'});
	}
}

// Generates a good random number
// http://www.electrictoolbox.com/pad-number-zeroes-javascript/
function pad(str, length) {
    var size = 11; // For 72 billion
    while (str.length < size) {
        str = '0' + str;
    }
    return str;
}

// Start it up
initialDigitCheckPad(theNumberPad);
setInterval(doCountPad, pacePad);

//]]>