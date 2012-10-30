//
//  Adapted from phpass --- https://github.com/rchouinard/phpass
//

function entropy_wolfram(str)
{
    var score=0

    // BaseScore
    score += str.length*4

    // LetterScore
    var lc_letters = (str.match(/[a-z]/g) || []).length;
    if (0 < lc_letters) {
	score += 2*(str.length - lc_letters)
    }
    var uc_letters = (str.match(/[A-Z]/g) || []).length;
    if (0 < uc_letters) {
	score += 2*(str.length - uc_letters)
    }

    // NumberScore
    var numbers = (str.match(/[0-9]/g) || []).length
    if (0 < numbers && numbers < str.length) {
	score += numbers*4
    }

    // SymbolScore
    var symbols = (str.match(/[^a-zA-Z0-9]/g) || []).length
    score += symbols*6

    // MiddleNumberOrSymbolScore
    //
    // The Wolfram algorithm actually only accounts for numbers, despite
    // what the rule name implies and others have documented.
    //
    // I've decided to account for both numbers and symbols as the rule
    // implies, and treat the Wolfram calculator as bugged. This will mean
    // that the calculations of this class and the Wolfram calculator may
    // not always match.
    //
    if (str.length > 2) {
      var middles = (str.substring(1,str.length-1).match(/[^a-zA-Z]/g) || []).length;
      score += middles*2
    }

    // All letters or all numbers
    if ((lc_letters+uc_letters) == str.length || numbers == str.length)
    {
	score -= str.length;
    }

    // RepeatTokenScore
    var bins=Object();
    for (var i=0; i<str.length; ++i) 
    {
	var ch = str.charAt(i);
	bins[ch] = (bins[ch]||0) + 1
    }

    var repeats = 0;

    for (var ch in bins) repeats += (bins[ch]-1);

    if (repeats > 0) 
    {
        score -= Math.floor(repeats / (str.length-repeats)) + 1;
//        score -= (repeats / (str.length-repeats)) + 1;
    }

    // ConsecutiveTokenScore(UPPER,LOWER,NUMBER)
    if (str.length > 2) {
        var matches = str.match(/([A-Z]{2,}|([a-z]{2,})|([0-9]{2,}))/g) || [];
	for (var i = 0; i<matches.length; ++i)
	{
	    score -= (matches[i].length-1)*2
	}
    }

    // Sequential
    var matches = str.toLowerCase().match(/([0-9]{3,}|[a-z]{3,})/g) || [];
    for (var k = 0; k<matches.length; ++k) 
    {
        var m=matches[k];
	var i=0;
	while (i < m.length) 
        {
  	    var up=1;   
	    while (i+up < m.length && m.charCodeAt(i) == m.charCodeAt(i+up) - up) 
	    { 
		++up; 
	    }
	    if (up > 2) 
	    {
                score -= (up-2)*2;
            }
	    i += up
	}
    }
    
    return score
}
