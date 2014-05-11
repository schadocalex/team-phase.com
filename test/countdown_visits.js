//<![CDATA[

// Array to hold each digit's starting background-position Y value
var initialPosVisits = [0, -618, -1236, -1854, -2472, -3090, -3708, -4326, -4944, -5562];

// Starting number
// If no number in URL (?n=number), then get a random one
var theNumberVisits = (window.location.search == "") ? Number(getRandom()) : Number(window.location.search.substring(3));

// Initializing variables
var subStartVisits, subEndVisits;

// Splits each value into an array of digits
function splitToArrayVisits(input){
	var digitsVisits = new Array();
	for (var i = 0, c = input.length; i < c; i++){
		var subStartVisits = input.length - (i + 1),
		subEndVisits = input.length - i;
		digitsVisits[i] = input.substring(subStartVisits, subEndVisits);
	}
	return digitsVisits;
}

// Generates a good random number
function getRandom(){
	var numD = Math.floor(Math.random()*9) + 3;
	var num = '';
	for (var i = 0; i < numD; i++){
		num += Math.floor(Math.random()*9).toString();
	}
	return num
}

// Sets the correct digits on load
function initialDigitCheckVisits(initial){
	// Creates the right number of digits
	var countVisits = initial.toString().length;
	var bit = 1;
	for (var i = 0; i < countVisits; i++){
		$("#countdown-visits").prepend('<li id="dvisits' + i + '"></li>');
		if (bit != (countVisits) && bit % 3 == 0) $("#countdown-visits").prepend('<li class="seperator"></li>');
		bit++;
	}
	// Sets them to the right number
	var digitsVisits = splitToArrayVisits(initial.toString());
	for (var i = 0, c = digitsVisits.length; i < c; i++){
		$("#dvisits" + i).css({'background-position': '0 ' + initialPosVisits[digitsVisits[i]] + 'px'});
	}
}

// Start it up
initialDigitCheckVisits(theNumberVisits);

//]]>