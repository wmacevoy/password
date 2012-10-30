<?php

function entropy_wolfram($str)
{
  $score=0;
  $strlen=strlen($str);

  // helper function to count number of 
  // matches in a string
  $rx_count = function($rx,$str) { 
    preg_match_all($rx,$str,$matches);
    return $matches ? count($matches[0]) : 0;
  };


  // BaseScore
  $score += $strlen*4;

  // LetterScore
  $lc_letters = $rx_count('/[a-z]/',$str);
  if (0 < $lc_letters) {
    $score += 2*($strlen - $lc_letters);
  }
  $uc_letters = $rx_count('/[A-Z]/',$str);
  if (0 < $uc_letters) {
    $score += 2*($strlen - $uc_letters);
  }

  // NumberScore
  $numbers = $rx_count('/[0-9]/',$str);
  if (0 < $numbers && $numbers < $strlen) {
    $score += $numbers*4;
  }

  // SymbolScore
  $symbols = $rx_count('/[^a-zA-Z0-9]/',$str);
  $score += $symbols*6;

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
  if ($strlen > 2) 
  {
    $middles = $rx_count('/[^a-zA-Z]/',substr($str,1,$strlen-2));
    $score += $middles*2;
  }

  // All letters or all numbers
  if (($lc_letters+$uc_letters) == $strlen || $numbers == $strlen)
  {
    $score -= $strlen;
  }

  // RepeatTokenScore
  $bins=array();
  for ($i=0; $i<$strlen; ++$i) 
  {
    $ch = substr($str,$i,1);
    if (array_key_exists($ch,$bins)) {
      ++$bins[$ch];
    } else {
      $bins[$ch]=1;
    }
  }

  $repeats = 0;

  foreach ($bins as $counts) $repeats += ($counts-1);

  if ($repeats > 0) 
  {
    $score -= floor($repeats / ($strlen-$repeats)) + 1;
  }

  // ConsecutiveTokenScore(UPPER,LOWER,NUMBER)
  if ($strlen > 2) 
  {
    preg_match_all('/([A-Z]{2,}|([a-z]{2,})|([0-9]{2,}))/',$str,$matches);
    if ($matches) 
    {
      foreach ($matches[0] as $match)
      {
	$score -= (strlen($match)-1)*2;
      }
    }
  }

  // Sequential
  preg_match_all('/([0-9]{3,}|[a-z]{3,})/',strtolower($str),$matches);
  if ($matches) 
  {
    foreach ($matches[0] as $match) 
    {
      $i=0;
      $n=strlen($match);

      while ($i < $n)
      {
	$up=1;   
	while ($i+$up < $n && ord(substr($match,$i,1)) == ord(substr($match,$i+$up,1)) - $up) 
        { 
	  ++$up; 
	}
	if ($up > 2) 
	{
	  $score -= ($up-2)*2;
	}
	$i += $up;
      }
    }
  }
  return $score;
}
