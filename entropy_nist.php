<?php

//
//  Adapted from phpass --- https://github.com/rchouinard/phpass
//

function entropy_nist($str)
{
  $score=0;
  $strlen=strlen($str);
    
  // First character is 4 bits of entropy
  if ($strlen > 0) { $score += 4; }
  
  // The next seven characters are 2 bits of entropy
  if ($strlen > 1) { $score += 2*min($strlen-1,7); }

  // Characters 9 through 20 are 1.5 bits of entropy
  if ($strlen > 8) { $score += 1.5*min($strlen-8,12); }

  // Characters 21 and beyond are 1 bit of entropy
  if ($strlen > 20) { $score +=  1*($strlen-20); }

  // Bonus of 6 bits if upper, lower, and non-alpha characters are used
  if (preg_match('/[A-Z]/',$str) && preg_match('/[a-z]/',$str) && preg_match('/[^a-zA-Z]/',$str)) { $score += 6; }

  return $score;
}
