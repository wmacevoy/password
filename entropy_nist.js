//
//  Adapted from phpass --- https://github.com/rchouinard/phpass
//

function entropy_nist(str)
{
    var score=0

    // First character is 4 bits of entropy
    if (str.length > 0) score += 4;

    // The next seven characters are 2 bits of entropy
    if (str.length > 1) score += 2*Math.min(str.length-1,7)

    // Characters 9 through 20 are 1.5 bits of entropy
    if (str.length > 8) score += 1.5*Math.min(str.length-8,12)

    // Characters 21 and beyond are 1 bit of entropy
    if (str.length > 20) score +=  1*(str.length-20)

    // Bonus of 6 bits if upper, lower, and non-alpha characters are used
    if (str.match(/[A-Z]/) && str.match(/[a-z]/) && str.match(/[^a-zA-Z]/)) score += 6

    return score
}
