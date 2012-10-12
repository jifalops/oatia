<?php
  require_once('../header.php');
  
  // this is sloppily taken from the javascript code at
  // http://www.csgnetwork.com/degreelenllavcalc.html
  // so that i can quickly put the values into an array

  $p1 = 111412.84;    // longitude calculation term 1
  $p2 = -93.5;      // longitude calculation term 2
  $p3 = 0.118;      // longitude calculation term 3

  for ($i=0; $i<=90; ++$i) {
    $rad = $i * ((2.0 * pi())/360);
    $longlen = ($p1 * cos($rad)) + ($p2 * cos(3 * $rad)) + ($p3 * cos(5 * $rad));
    $longmiles = $longlen * 3.280833333 / 5280;
    $lm = $longmiles * pow(10, 3);
    $lm = round($lm);
    $lm = $lm / pow(10, 3);
    echo "$lm<br />\n";
  }
