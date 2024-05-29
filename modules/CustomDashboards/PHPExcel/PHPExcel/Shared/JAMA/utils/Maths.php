<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

/**
 *    @package JAMA
 *
 *    Pythagorean Theorem:
 *
 *    a = 3
 *    b = 4
 *    r = sqrt(square(a) + square(b))
 *    r = 5
 *
 *    r = sqrt(a^2 + b^2) without under/overflow.
 */
function hypo($a, $b)
{
    if (abs($b) < abs($a)) {
        $r = $b / $a;
        $r = abs($a) * sqrt(1 + $r * $r);
    } else {
        if ($b != 0) {
            $r = $a / $b;
            $r = abs($b) * sqrt(1 + $r * $r);
        } else {
            $r = 0;
        }
    }
    return $r;
}

?>