<?php
function password_random()
{
    $password='';
    $ch = rand(32,126);
    $n = rand(0,80);

    while (strlen($password) < $n) {
      switch(rand(0,5)) {
      case 0: $ch = rand(ord('A'),ord('Z')); break;
      case 1: $ch = rand(ord('a'),ord('z')); break;
      case 2: $ch = rand(ord('0'),ord('9')); break;
      case 3: $ch = rand(32,126); break; // random printable char
      case 4: $ch = 32+(($ch-32+1) % (126 - 32)); break; // next printable char
      case 5: break;
      }
      $password .= chr($ch);
    }
    return $password;
}
