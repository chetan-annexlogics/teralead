<?php
//headerNop

if (!defined("PHPEXCEL_ROOT")) {
    define("PHPEXCEL_ROOT", dirname(__FILE__) . "/../../");
    require PHPEXCEL_ROOT . "PHPExcel/Autoloader.php";
}
require_once PHPEXCEL_ROOT . "PHPExcel/Shared/trend/trendClass.php";
define("LOG_GAMMA_X_MAX_VALUE", 2.55E+305);
define("XMININ", 2.23E-308);
define("EPS", 2.22E-16);
define("SQRT2PI", 2.506628274631);
/**
 * PHPExcel_Calculation_Statistical
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @category    PHPExcel
 * @package        PHPExcel_Calculation
 * @copyright    Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license        http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version        ##VERSION##, ##DATE##
 */
class PHPExcel_Calculation_Statistical
{
    private static $logBetaCacheP = 0;
    private static $logBetaCacheQ = 0;
    private static $logBetaCacheResult = 0;
    /**
     * logGamma function
     *
     * @version 1.1
     * @author Jaco van Kooten
     *
     * Original author was Jaco van Kooten. Ported to PHP by Paul Meagher.
     *
     * The natural logarithm of the gamma function. <br />
     * Based on public domain NETLIB (Fortran) code by W. J. Cody and L. Stoltz <br />
     * Applied Mathematics Division <br />
     * Argonne National Laboratory <br />
     * Argonne, IL 60439 <br />
     * <p>
     * References:
     * <ol>
     * <li>W. J. Cody and K. E. Hillstrom, 'Chebyshev Approximations for the Natural
     *     Logarithm of the Gamma Function,' Math. Comp. 21, 1967, pp. 198-203.</li>
     * <li>K. E. Hillstrom, ANL/AMD Program ANLC366S, DGAMMA/DLGAMA, May, 1969.</li>
     * <li>Hart, Et. Al., Computer Approximations, Wiley and sons, New York, 1968.</li>
     * </ol>
     * </p>
     * <p>
     * From the original documentation:
     * </p>
     * <p>
     * This routine calculates the LOG(GAMMA) function for a positive real argument X.
     * Computation is based on an algorithm outlined in references 1 and 2.
     * The program uses rational functions that theoretically approximate LOG(GAMMA)
     * to at least 18 significant decimal digits. The approximation for X > 12 is from
     * reference 3, while approximations for X < 12.0 are similar to those in reference
     * 1, but are unpublished. The accuracy achieved depends on the arithmetic system,
     * the compiler, the intrinsic functions, and proper selection of the
     * machine-dependent constants.
     * </p>
     * <p>
     * Error returns: <br />
     * The program returns the value XINF for X .LE. 0.0 or when overflow would occur.
     * The computation is believed to be free of underflow and overflow.
     * </p>
     * @return MAX_VALUE for x < 0.0 or when overflow would occur, i.e. x > 2.55E305
     */
    private static $logGammaCacheResult = 0;
    private static $logGammaCacheX = 0;
    private static function checkTrendArrays(&$array1, &$array2)
    {
        if (!is_array($array1)) {
            $array1 = array($array1);
        }
        if (!is_array($array2)) {
            $array2 = array($array2);
        }
        $array1 = PHPExcel_Calculation_Functions::flattenArray($array1);
        $array2 = PHPExcel_Calculation_Functions::flattenArray($array2);
        foreach ($array1 as $key => $value) {
            if (is_bool($value) || is_string($value) || is_null($value)) {
                unset($array1[$key]);
                unset($array2[$key]);
            }
        }
        foreach ($array2 as $key => $value) {
            if (is_bool($value) || is_string($value) || is_null($value)) {
                unset($array1[$key]);
                unset($array2[$key]);
            }
        }
        $array1 = array_merge($array1);
        $array2 = array_merge($array2);
        return true;
    }
    /**
     * Beta function.
     *
     * @author Jaco van Kooten
     *
     * @param p require p>0
     * @param q require q>0
     * @return 0 if p<=0, q<=0 or p+q>2.55E305 to avoid errors and over/underflow
     */
    private static function beta($p, $q)
    {
        if ($p <= 0 || $q <= 0 || LOG_GAMMA_X_MAX_VALUE < $p + $q) {
            return 0;
        }
        return exp(self::logBeta($p, $q));
    }
    /**
     * Incomplete beta function
     *
     * @author Jaco van Kooten
     * @author Paul Meagher
     *
     * The computation is based on formulas from Numerical Recipes, Chapter 6.4 (W.H. Press et al, 1992).
     * @param x require 0<=x<=1
     * @param p require p>0
     * @param q require q>0
     * @return 0 if x<0, p<=0, q<=0 or p+q>2.55E305 and 1 if x>1 to avoid errors and over/underflow
     */
    private static function incompleteBeta($x, $p, $q)
    {
        if ($x <= 0) {
            return 0;
        }
        if (1 <= $x) {
            return 1;
        }
        if ($p <= 0 || $q <= 0 || LOG_GAMMA_X_MAX_VALUE < $p + $q) {
            return 0;
        }
        $beta_gam = exp(0 - self::logBeta($p, $q) + $p * log($x) + $q * log(1 - $x));
        if ($x < ($p + 1) / ($p + $q + 2)) {
            return $beta_gam * self::betaFraction($x, $p, $q) / $p;
        }
        return 1 - $beta_gam * self::betaFraction(1 - $x, $q, $p) / $q;
    }
    /**
     * The natural logarithm of the beta function.
     *
     * @param p require p>0
     * @param q require q>0
     * @return 0 if p<=0, q<=0 or p+q>2.55E305 to avoid errors and over/underflow
     * @author Jaco van Kooten
     */
    private static function logBeta($p, $q)
    {
        if ($p != self::$logBetaCacheP || $q != self::$logBetaCacheQ) {
            self::$logBetaCacheP = $p;
            self::$logBetaCacheQ = $q;
            if ($p <= 0 || $q <= 0 || LOG_GAMMA_X_MAX_VALUE < $p + $q) {
                self::$logBetaCacheResult = 0;
            } else {
                self::$logBetaCacheResult = self::logGamma($p) + self::logGamma($q) - self::logGamma($p + $q);
            }
        }
        return self::$logBetaCacheResult;
    }
    /**
     * Evaluates of continued fraction part of incomplete beta function.
     * Based on an idea from Numerical Recipes (W.H. Press et al, 1992).
     * @author Jaco van Kooten
     */
    private static function betaFraction($x, $p, $q)
    {
        $c = 1;
        $sum_pq = $p + $q;
        $p_plus = $p + 1;
        $p_minus = $p - 1;
        $h = 1 - $sum_pq * $x / $p_plus;
        if (abs($h) < XMININ) {
            $h = XMININ;
        }
        $h = 1 / $h;
        $frac = $h;
        $m = 1;
        for ($delta = 0; $m <= MAX_ITERATIONS && PRECISION < abs($delta - 1); $m++) {
            $m2 = 2 * $m;
            $d = $m * ($q - $m) * $x / (($p_minus + $m2) * ($p + $m2));
            $h = 1 + $d * $h;
            if (abs($h) < XMININ) {
                $h = XMININ;
            }
            $h = 1 / $h;
            $c = 1 + $d / $c;
            if (abs($c) < XMININ) {
                $c = XMININ;
            }
            $frac *= $h * $c;
            $d = (0 - ($p + $m)) * ($sum_pq + $m) * $x / (($p + $m2) * ($p_plus + $m2));
            $h = 1 + $d * $h;
            if (abs($h) < XMININ) {
                $h = XMININ;
            }
            $h = 1 / $h;
            $c = 1 + $d / $c;
            if (abs($c) < XMININ) {
                $c = XMININ;
            }
            $delta = $h * $c;
            $frac *= $delta;
        }
        return $frac;
    }
    private static function logGamma($x)
    {
        static $lg_d1 = -0.57721566490153;
        static $lg_d2 = 0.42278433509847;
        static $lg_d4 = 1.7917594692281;
        static $lg_p1 = array(4.9452353592967, 201.81126208568, 2290.8383738313, 11319.672059034, 28557.246356716, 38484.962284438, 26377.487876242, 7225.8139797003);
        static $lg_p2 = array(4.9746078455689, 542.4138599891101, 15506.938649784, 184793.29044456, 1088204.7694688, 3338152.967987, 5106661.6789274, 3074109.0548505);
        static $lg_p4 = array(14745.021660599, 2426813.3694867, 121475557.40451, 2663432449.631, 29403789566.346, 170266573776.54, 492612579337.74, 560625185622.4);
        static $lg_q1 = array(67.48212550303801, 1113.3323938572, 7738.7570569354, 27639.870744033, 54993.102062262, 61611.22180066, 36351.275915019, 8785.536302431001);
        static $lg_q2 = array(183.03283993706, 7765.049321445, 133190.38279661, 1136705.821322, 5267964.1174379, 13467014.543111, 17827365.303533, 9533095.5918444);
        static $lg_q4 = array(2690.5301758709, 639388.56543001, 41355999.302414, 1120872109.6161, 14886137286.788, 101680358627.24, 341747634550.74, 446315818741.97);
        static $lg_c = array(-0.001910444077728, 0.0008417138778129501, -0.0005952379913043, 0.00079365079350035, -0.0027777777777777, 0.083333333333333, 0.0057083835261);
        static $lg_frtbig = 2.25E+76;
        static $pnt68 = 0.6796875;
        if ($x == self::$logGammaCacheX) {
            return self::$logGammaCacheResult;
        }
        $y = $x;
        if (0 < $y && $y <= LOG_GAMMA_X_MAX_VALUE) {
            if ($y <= EPS) {
                $res = 0 - log(y);
            } else {
                if ($y <= 1.5) {
                    if ($y < $pnt68) {
                        $corr = 0 - log($y);
                        $xm1 = $y;
                    } else {
                        $corr = 0;
                        $xm1 = $y - 1;
                    }
                    if ($y <= 0.5 || $pnt68 <= $y) {
                        $xden = 1;
                        $xnum = 0;
                        for ($i = 0; $i < 8; $i++) {
                            $xnum = $xnum * $xm1 + $lg_p1[$i];
                            $xden = $xden * $xm1 + $lg_q1[$i];
                        }
                        $res = $corr + $xm1 * ($lg_d1 + $xm1 * $xnum / $xden);
                    } else {
                        $xm2 = $y - 1;
                        $xden = 1;
                        $xnum = 0;
                        for ($i = 0; $i < 8; $i++) {
                            $xnum = $xnum * $xm2 + $lg_p2[$i];
                            $xden = $xden * $xm2 + $lg_q2[$i];
                        }
                        $res = $corr + $xm2 * ($lg_d2 + $xm2 * $xnum / $xden);
                    }
                } else {
                    if ($y <= 4) {
                        $xm2 = $y - 2;
                        $xden = 1;
                        $xnum = 0;
                        for ($i = 0; $i < 8; $i++) {
                            $xnum = $xnum * $xm2 + $lg_p2[$i];
                            $xden = $xden * $xm2 + $lg_q2[$i];
                        }
                        $res = $xm2 * ($lg_d2 + $xm2 * $xnum / $xden);
                    } else {
                        if ($y <= 12) {
                            $xm4 = $y - 4;
                            $xden = -1;
                            $xnum = 0;
                            for ($i = 0; $i < 8; $i++) {
                                $xnum = $xnum * $xm4 + $lg_p4[$i];
                                $xden = $xden * $xm4 + $lg_q4[$i];
                            }
                            $res = $lg_d4 + $xm4 * $xnum / $xden;
                        } else {
                            $res = 0;
                            if ($y <= $lg_frtbig) {
                                $res = $lg_c[6];
                                $ysq = $y * $y;
                                for ($i = 0; $i < 6; $i++) {
                                    $res = $res / $ysq + $lg_c[$i];
                                }
                                $res /= $y;
                                $corr = log($y);
                                $res = $res + log(SQRT2PI) - 0.5 * $corr;
                                $res += $y * ($corr - 1);
                            }
                        }
                    }
                }
            }
        } else {
            $res = MAX_VALUE;
        }
        self::$logGammaCacheX = $x;
        self::$logGammaCacheResult = $res;
        return $res;
    }
    private static function incompleteGamma($a, $x)
    {
        static $max = 32;
        $summer = 0;
        for ($n = 0; $n <= $max; $n++) {
            $divisor = $a;
            for ($i = 1; $i <= $n; $i++) {
                $divisor *= $a + $i;
            }
            $summer += pow($x, $n) / $divisor;
        }
        return pow($x, $a) * exp(0 - $x) * $summer;
    }
    private static function gamma($data)
    {
        if ($data == 0) {
            return 0;
        }
        static $p0 = 1.00000000019;
        static $p = array("1" => 76.180091729471, "2" => -86.505320329417, "3" => 24.014098240831, "4" => -1.2317395724502, "5" => 0.0012086509738662, "6" => -5.395239384953E-6);
        $y = $x = $data;
        $tmp = $x + 5.5;
        $tmp -= ($x + 0.5) * log($tmp);
        $summer = $p0;
        for ($j = 1; $j <= 6; $j++) {
            $summer += $p[$j] / ++$y;
        }
        return exp(0 - $tmp + log(SQRT2PI * $summer / $x));
    }
    private static function inverseNcdf($p)
    {
        static $a = array("1" => -39.696830286654, "2" => 220.94609842452, "3" => -275.92851044697, "4" => 138.35775186727, "5" => -30.664798066147, "6" => 2.5066282774592);
        static $b = array("1" => -54.476098798224, "2" => 161.58583685804, "3" => -155.69897985989, "4" => 66.80131188772, "5" => -13.280681552886);
        static $c = array("1" => -0.0077848940024303, "2" => -0.32239645804114, "3" => -2.4007582771618, "4" => -2.5497325393437, "5" => 4.374664141465, "6" => 2.9381639826988);
        static $d = array("1" => 0.0077846957090415, "2" => 0.32246712907004, "3" => 2.445134137143, "4" => 3.7544086619074);
        $p_low = 0.02425;
        $p_high = 1 - $p_low;
        if (0 < $p && $p < $p_low) {
            $q = sqrt(-2 * log($p));
            return ((((($c[1] * $q + $c[2]) * $q + $c[3]) * $q + $c[4]) * $q + $c[5]) * $q + $c[6]) / (((($d[1] * $q + $d[2]) * $q + $d[3]) * $q + $d[4]) * $q + 1);
        }
        if ($p_low <= $p && $p <= $p_high) {
            $q = $p - 0.5;
            $r = $q * $q;
            return ((((($a[1] * $r + $a[2]) * $r + $a[3]) * $r + $a[4]) * $r + $a[5]) * $r + $a[6]) * $q / ((((($b[1] * $r + $b[2]) * $r + $b[3]) * $r + $b[4]) * $r + $b[5]) * $r + 1);
        }
        if ($p_high < $p && $p < 1) {
            $q = sqrt(-2 * log(1 - $p));
            return (0 - ((((($c[1] * $q + $c[2]) * $q + $c[3]) * $q + $c[4]) * $q + $c[5]) * $q + $c[6])) / (((($d[1] * $q + $d[2]) * $q + $d[3]) * $q + $d[4]) * $q + 1);
        }
        return PHPExcel_Calculation_Functions::NULL();
    }
    private static function inverseNcdf2($prob)
    {
        $a1 = 2.50662823884;
        $a2 = -18.61500062529;
        $a3 = 41.39119773534;
        $a4 = -25.44106049637;
        $b1 = -8.4735109309;
        $b2 = 23.08336743743;
        $b3 = -21.06224101826;
        $b4 = 3.13082909833;
        $c1 = 0.337475482272615;
        $c2 = 0.976169019091719;
        $c3 = 0.160797971491821;
        $c4 = 0.0276438810333863;
        $c5 = 0.0038405729373609;
        $c6 = 0.0003951896511919;
        $c7 = 3.21767881768E-5;
        $c8 = 2.888167364E-7;
        $c9 = 3.960315187E-7;
        $y = $prob - 0.5;
        if (abs($y) < 0.42) {
            $z = $y * $y;
            $z = $y * ((($a4 * $z + $a3) * $z + $a2) * $z + $a1) / (((($b4 * $z + $b3) * $z + $b2) * $z + $b1) * $z + 1);
        } else {
            if (0 < $y) {
                $z = log(0 - log(1 - $prob));
            } else {
                $z = log(0 - log($prob));
            }
            $z = $c1 + $z * ($c2 + $z * ($c3 + $z * ($c4 + $z * ($c5 + $z * ($c6 + $z * ($c7 + $z * ($c8 + $z * $c9)))))));
            if ($y < 0) {
                $z = 0 - $z;
            }
        }
        return $z;
    }
    private static function inverseNcdf3($p)
    {
        $split1 = 0.425;
        $split2 = 5;
        $const1 = 0.180625;
        $const2 = 1.6;
        $a0 = 3.38713287279637;
        $a1 = 133.141667891784;
        $a2 = 1971.59095030655;
        $a3 = 13731.6937655095;
        $a4 = 45921.9539315499;
        $a5 = 67265.77092700871;
        $a6 = 33430.5755835881;
        $a7 = 2509.08092873012;
        $b1 = 42.3133307016009;
        $b2 = 687.187007492058;
        $b3 = 5394.19602142475;
        $b4 = 21213.7943015866;
        $b5 = 39307.8958000927;
        $b6 = 28729.0857357219;
        $b7 = 5226.49527885285;
        $c0 = 1.42343711074968;
        $c1 = 4.63033784615655;
        $c2 = 5.76949722146069;
        $c3 = 3.6478483247632;
        $c4 = 1.27045825245237;
        $c5 = 0.241780725177451;
        $c6 = 0.0227238449892692;
        $c7 = 0.000774545014278341;
        $d1 = 2.05319162663776;
        $d2 = 1.6763848301838;
        $d3 = 0.6897673349851;
        $d4 = 0.14810397642748;
        $d5 = 0.0151986665636165;
        $d6 = 0.000547593808499535;
        $d7 = 1.05075007164442E-9;
        $e0 = 6.6579046435011;
        $e1 = 5.46378491116411;
        $e2 = 1.78482653991729;
        $e3 = 0.296560571828505;
        $e4 = 0.0265321895265761;
        $e5 = 0.00124266094738808;
        $e6 = 2.71155556874349E-5;
        $e7 = 2.01033439929229E-7;
        $f1 = 0.599832206555888;
        $f2 = 0.136929880922736;
        $f3 = 0.0148753612908506;
        $f4 = 0.000786869131145613;
        $f5 = 1.84631831751005E-5;
        $f6 = 1.42151175831645E-7;
        $f7 = 2.04426310338994E-15;
        $q = $p - 0.5;
        if (abs($q) <= split1) {
            $R = $const1 - $q * $q;
            $z = $q * ((((((($a7 * $R + $a6) * $R + $a5) * $R + $a4) * $R + $a3) * $R + $a2) * $R + $a1) * $R + $a0) / ((((((($b7 * $R + $b6) * $R + $b5) * $R + $b4) * $R + $b3) * $R + $b2) * $R + $b1) * $R + 1);
        } else {
            if ($q < 0) {
                $R = $p;
            } else {
                $R = 1 - $p;
            }
            $R = pow(0 - log($R), 2);
            if ($R <= $split2) {
                $R = $R - $const2;
                $z = ((((((($c7 * $R + $c6) * $R + $c5) * $R + $c4) * $R + $c3) * $R + $c2) * $R + $c1) * $R + $c0) / ((((((($d7 * $R + $d6) * $R + $d5) * $R + $d4) * $R + $d3) * $R + $d2) * $R + $d1) * $R + 1);
            } else {
                $R = $R - $split2;
                $z = ((((((($e7 * $R + $e6) * $R + $e5) * $R + $e4) * $R + $e3) * $R + $e2) * $R + $e1) * $R + $e0) / ((((((($f7 * $R + $f6) * $R + $f5) * $R + $f4) * $R + $f3) * $R + $f2) * $R + $f1) * $R + 1);
            }
            if ($q < 0) {
                $z = 0 - $z;
            }
        }
        return $z;
    }
    /**
     * AVEDEV
     *
     * Returns the average of the absolute deviations of data points from their mean.
     * AVEDEV is a measure of the variability in a data set.
     *
     * Excel Function:
     *        AVEDEV(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    float
     */
    public static function AVEDEV()
    {
        $aArgs = PHPExcel_Calculation_Functions::flattenArrayIndexed(func_get_args());
        $returnValue = NULL;
        $aMean = self::AVERAGE($aArgs);
        if ($aMean != PHPExcel_Calculation_Functions::DIV0()) {
            $aCount = 0;
            foreach ($aArgs as $k => $arg) {
                if (is_bool($arg) && (!PHPExcel_Calculation_Functions::isCellValue($k) || PHPExcel_Calculation_Functions::getCompatibilityMode() == PHPExcel_Calculation_Functions::COMPATIBILITY_OPENOFFICE)) {
                    $arg = (int) $arg;
                }
                if (is_numeric($arg) && !is_string($arg)) {
                    if (is_null($returnValue)) {
                        $returnValue = abs($arg - $aMean);
                    } else {
                        $returnValue += abs($arg - $aMean);
                    }
                    $aCount++;
                }
            }
            if ($aCount == 0) {
                return PHPExcel_Calculation_Functions::DIV0();
            }
            return $returnValue / $aCount;
        } else {
            return PHPExcel_Calculation_Functions::NaN();
        }
    }
    /**
     * AVERAGE
     *
     * Returns the average (arithmetic mean) of the arguments
     *
     * Excel Function:
     *        AVERAGE(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    float
     */
    public static function AVERAGE()
    {
        $returnValue = $aCount = 0;
        foreach (PHPExcel_Calculation_Functions::flattenArrayIndexed(func_get_args()) as $k => $arg) {
            if (is_bool($arg) && (!PHPExcel_Calculation_Functions::isCellValue($k) || PHPExcel_Calculation_Functions::getCompatibilityMode() == PHPExcel_Calculation_Functions::COMPATIBILITY_OPENOFFICE)) {
                $arg = (int) $arg;
            }
            if (is_numeric($arg) && !is_string($arg)) {
                if (is_null($returnValue)) {
                    $returnValue = $arg;
                } else {
                    $returnValue += $arg;
                }
                $aCount++;
            }
        }
        if (0 < $aCount) {
            return $returnValue / $aCount;
        }
        return PHPExcel_Calculation_Functions::DIV0();
    }
    /**
     * AVERAGEA
     *
     * Returns the average of its arguments, including numbers, text, and logical values
     *
     * Excel Function:
     *        AVERAGEA(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    float
     */
    public static function AVERAGEA()
    {
        $returnValue = NULL;
        $aCount = 0;
        foreach (PHPExcel_Calculation_Functions::flattenArrayIndexed(func_get_args()) as $k => $arg) {
            if (!is_bool($arg) || PHPExcel_Calculation_Functions::isMatrixValue($k)) {
                if (is_numeric($arg) || is_bool($arg) || is_string($arg) && $arg != "") {
                    if (is_bool($arg)) {
                        $arg = (int) $arg;
                    } else {
                        if (is_string($arg)) {
                            $arg = 0;
                        }
                    }
                    if (is_null($returnValue)) {
                        $returnValue = $arg;
                    } else {
                        $returnValue += $arg;
                    }
                    $aCount++;
                }
            }
        }
        if (0 < $aCount) {
            return $returnValue / $aCount;
        }
        return PHPExcel_Calculation_Functions::DIV0();
    }
    /**
     * AVERAGEIF
     *
     * Returns the average value from a range of cells that contain numbers within the list of arguments
     *
     * Excel Function:
     *        AVERAGEIF(value1[,value2[, ...]],condition)
     *
     * @access    public
     * @category Mathematical and Trigonometric Functions
     * @param    mixed        $arg,...        Data values
     * @param    string        $condition        The criteria that defines which cells will be checked.
     * @param    mixed[]        $averageArgs    Data values
     * @return    float
     */
    public static function AVERAGEIF($aArgs, $condition, $averageArgs = array())
    {
        $returnValue = 0;
        $aArgs = PHPExcel_Calculation_Functions::flattenArray($aArgs);
        $averageArgs = PHPExcel_Calculation_Functions::flattenArray($averageArgs);
        if (empty($averageArgs)) {
            $averageArgs = $aArgs;
        }
        $condition = PHPExcel_Calculation_Functions::ifCondition($condition);
        $aCount = 0;
        foreach ($aArgs as $key => $arg) {
            if (!is_numeric($arg)) {
                $arg = PHPExcel_Calculation::wrapResult(strtoupper($arg));
            }
            $testCondition = "=" . $arg . $condition;
            if (PHPExcel_Calculation::getInstance()->_calculateFormulaValue($testCondition) && (is_null($returnValue) || $returnValue < $arg)) {
                $returnValue += $arg;
                $aCount++;
            }
        }
        if (0 < $aCount) {
            return $returnValue / $aCount;
        }
        return PHPExcel_Calculation_Functions::DIV0();
    }
    /**
     * BETADIST
     *
     * Returns the beta distribution.
     *
     * @param    float        $value            Value at which you want to evaluate the distribution
     * @param    float        $alpha            Parameter to the distribution
     * @param    float        $beta            Parameter to the distribution
     * @param    boolean        $cumulative
     * @return    float
     *
     */
    public static function BETADIST($value, $alpha, $beta, $rMin = 0, $rMax = 1)
    {
        $value = PHPExcel_Calculation_Functions::flattenSingleValue($value);
        $alpha = PHPExcel_Calculation_Functions::flattenSingleValue($alpha);
        $beta = PHPExcel_Calculation_Functions::flattenSingleValue($beta);
        $rMin = PHPExcel_Calculation_Functions::flattenSingleValue($rMin);
        $rMax = PHPExcel_Calculation_Functions::flattenSingleValue($rMax);
        if (is_numeric($value) && is_numeric($alpha) && is_numeric($beta) && is_numeric($rMin) && is_numeric($rMax)) {
            if ($value < $rMin || $rMax < $value || $alpha <= 0 || $beta <= 0 || $rMin == $rMax) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            if ($rMax < $rMin) {
                $tmp = $rMin;
                $rMin = $rMax;
                $rMax = $tmp;
            }
            $value -= $rMin;
            $value /= $rMax - $rMin;
            return self::incompleteBeta($value, $alpha, $beta);
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * BETAINV
     *
     * Returns the inverse of the beta distribution.
     *
     * @param    float        $probability    Probability at which you want to evaluate the distribution
     * @param    float        $alpha            Parameter to the distribution
     * @param    float        $beta            Parameter to the distribution
     * @param    float        $rMin            Minimum value
     * @param    float        $rMax            Maximum value
     * @param    boolean        $cumulative
     * @return    float
     *
     */
    public static function BETAINV($probability, $alpha, $beta, $rMin = 0, $rMax = 1)
    {
        $probability = PHPExcel_Calculation_Functions::flattenSingleValue($probability);
        $alpha = PHPExcel_Calculation_Functions::flattenSingleValue($alpha);
        $beta = PHPExcel_Calculation_Functions::flattenSingleValue($beta);
        $rMin = PHPExcel_Calculation_Functions::flattenSingleValue($rMin);
        $rMax = PHPExcel_Calculation_Functions::flattenSingleValue($rMax);
        if (is_numeric($probability) && is_numeric($alpha) && is_numeric($beta) && is_numeric($rMin) && is_numeric($rMax)) {
            if ($alpha <= 0 || $beta <= 0 || $rMin == $rMax || $probability <= 0 || 1 < $probability) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            if ($rMax < $rMin) {
                $tmp = $rMin;
                $rMin = $rMax;
                $rMax = $tmp;
            }
            $a = 0;
            $b = 2;
            $i = 0;
            while (PRECISION < $b - $a && $i++ < MAX_ITERATIONS) {
                $guess = ($a + $b) / 2;
                $result = self::BETADIST($guess, $alpha, $beta);
                if ($result == $probability || $result == 0) {
                    $b = $a;
                } else {
                    if ($probability < $result) {
                        $b = $guess;
                    } else {
                        $a = $guess;
                    }
                }
            }
            if ($i == MAX_ITERATIONS) {
                return PHPExcel_Calculation_Functions::NA();
            }
            return round($rMin + $guess * ($rMax - $rMin), 12);
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * BINOMDIST
     *
     * Returns the individual term binomial distribution probability. Use BINOMDIST in problems with
     *        a fixed number of tests or trials, when the outcomes of any trial are only success or failure,
     *        when trials are independent, and when the probability of success is constant throughout the
     *        experiment. For example, BINOMDIST can calculate the probability that two of the next three
     *        babies born are male.
     *
     * @param    float        $value            Number of successes in trials
     * @param    float        $trials            Number of trials
     * @param    float        $probability    Probability of success on each trial
     * @param    boolean        $cumulative
     * @return    float
     *
     * @todo    Cumulative distribution function
     *
     */
    public static function BINOMDIST($value, $trials, $probability, $cumulative)
    {
        $value = floor(PHPExcel_Calculation_Functions::flattenSingleValue($value));
        $trials = floor(PHPExcel_Calculation_Functions::flattenSingleValue($trials));
        $probability = PHPExcel_Calculation_Functions::flattenSingleValue($probability);
        if (is_numeric($value) && is_numeric($trials) && is_numeric($probability)) {
            if ($value < 0 || $trials < $value) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            if ($probability < 0 || 1 < $probability) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            if (is_numeric($cumulative) || is_bool($cumulative)) {
                if ($cumulative) {
                    $summer = 0;
                    for ($i = 0; $i <= $value; $i++) {
                        $summer += PHPExcel_Calculation_MathTrig::COMBIN($trials, $i) * pow($probability, $i) * pow(1 - $probability, $trials - $i);
                    }
                    return $summer;
                }
                return PHPExcel_Calculation_MathTrig::COMBIN($trials, $value) * pow($probability, $value) * pow(1 - $probability, $trials - $value);
            }
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * CHIDIST
     *
     * Returns the one-tailed probability of the chi-squared distribution.
     *
     * @param    float        $value            Value for the function
     * @param    float        $degrees        degrees of freedom
     * @return    float
     */
    public static function CHIDIST($value, $degrees)
    {
        $value = PHPExcel_Calculation_Functions::flattenSingleValue($value);
        $degrees = floor(PHPExcel_Calculation_Functions::flattenSingleValue($degrees));
        if (is_numeric($value) && is_numeric($degrees)) {
            if ($degrees < 1) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            if ($value < 0) {
                if (PHPExcel_Calculation_Functions::getCompatibilityMode() == PHPExcel_Calculation_Functions::COMPATIBILITY_GNUMERIC) {
                    return 1;
                }
                return PHPExcel_Calculation_Functions::NaN();
            }
            return 1 - self::incompleteGamma($degrees / 2, $value / 2) / self::gamma($degrees / 2);
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * CHIINV
     *
     * Returns the one-tailed probability of the chi-squared distribution.
     *
     * @param    float        $probability    Probability for the function
     * @param    float        $degrees        degrees of freedom
     * @return    float
     */
    public static function CHIINV($probability, $degrees)
    {
        $probability = PHPExcel_Calculation_Functions::flattenSingleValue($probability);
        $degrees = floor(PHPExcel_Calculation_Functions::flattenSingleValue($degrees));
        if (is_numeric($probability) && is_numeric($degrees)) {
            $xLo = 100;
            $xHi = 0;
            $x = $xNew = 1;
            $dx = 1;
            $i = 0;
            while (PRECISION < abs($dx) && $i++ < MAX_ITERATIONS) {
                $result = self::CHIDIST($x, $degrees);
                $error = $result - $probability;
                if ($error == 0) {
                    $dx = 0;
                } else {
                    if ($error < 0) {
                        $xLo = $x;
                    } else {
                        $xHi = $x;
                    }
                }
                if ($result != 0) {
                    $dx = $error / $result;
                    $xNew = $x - $dx;
                }
                if ($xNew < $xLo || $xHi < $xNew || $result == 0) {
                    $xNew = ($xLo + $xHi) / 2;
                    $dx = $xNew - $x;
                }
                $x = $xNew;
            }
            if ($i == MAX_ITERATIONS) {
                return PHPExcel_Calculation_Functions::NA();
            }
            return round($x, 12);
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * CONFIDENCE
     *
     * Returns the confidence interval for a population mean
     *
     * @param    float        $alpha
     * @param    float        $stdDev        Standard Deviation
     * @param    float        $size
     * @return    float
     *
     */
    public static function CONFIDENCE($alpha, $stdDev, $size)
    {
        $alpha = PHPExcel_Calculation_Functions::flattenSingleValue($alpha);
        $stdDev = PHPExcel_Calculation_Functions::flattenSingleValue($stdDev);
        $size = floor(PHPExcel_Calculation_Functions::flattenSingleValue($size));
        if (is_numeric($alpha) && is_numeric($stdDev) && is_numeric($size)) {
            if ($alpha <= 0 || 1 <= $alpha) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            if ($stdDev <= 0 || $size < 1) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            return self::NORMSINV(1 - $alpha / 2) * $stdDev / sqrt($size);
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * CORREL
     *
     * Returns covariance, the average of the products of deviations for each data point pair.
     *
     * @param    array of mixed        Data Series Y
     * @param    array of mixed        Data Series X
     * @return    float
     */
    public static function CORREL($yValues, $xValues = NULL)
    {
        if (is_null($xValues) || !is_array($yValues) || !is_array($xValues)) {
            return PHPExcel_Calculation_Functions::VALUE();
        }
        if (!self::checkTrendArrays($yValues, $xValues)) {
            return PHPExcel_Calculation_Functions::VALUE();
        }
        $yValueCount = count($yValues);
        $xValueCount = count($xValues);
        if ($yValueCount == 0 || $yValueCount != $xValueCount) {
            return PHPExcel_Calculation_Functions::NA();
        }
        if ($yValueCount == 1) {
            return PHPExcel_Calculation_Functions::DIV0();
        }
        $bestFitLinear = trendClass::calculate(trendClass::TREND_LINEAR, $yValues, $xValues);
        return $bestFitLinear->getCorrelation();
    }
    /**
     * COUNT
     *
     * Counts the number of cells that contain numbers within the list of arguments
     *
     * Excel Function:
     *        COUNT(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    int
     */
    public static function COUNT()
    {
        $returnValue = 0;
        $aArgs = PHPExcel_Calculation_Functions::flattenArrayIndexed(func_get_args());
        foreach ($aArgs as $k => $arg) {
            if (is_bool($arg) && (!PHPExcel_Calculation_Functions::isCellValue($k) || PHPExcel_Calculation_Functions::getCompatibilityMode() == PHPExcel_Calculation_Functions::COMPATIBILITY_OPENOFFICE)) {
                $arg = (int) $arg;
            }
            if (is_numeric($arg) && !is_string($arg)) {
                $returnValue++;
            }
        }
        return $returnValue;
    }
    /**
     * COUNTA
     *
     * Counts the number of cells that are not empty within the list of arguments
     *
     * Excel Function:
     *        COUNTA(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    int
     */
    public static function COUNTA()
    {
        $returnValue = 0;
        $aArgs = PHPExcel_Calculation_Functions::flattenArray(func_get_args());
        foreach ($aArgs as $arg) {
            if (is_numeric($arg) || is_bool($arg) || is_string($arg) && $arg != "") {
                $returnValue++;
            }
        }
        return $returnValue;
    }
    /**
     * COUNTBLANK
     *
     * Counts the number of empty cells within the list of arguments
     *
     * Excel Function:
     *        COUNTBLANK(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    int
     */
    public static function COUNTBLANK()
    {
        $returnValue = 0;
        $aArgs = PHPExcel_Calculation_Functions::flattenArray(func_get_args());
        foreach ($aArgs as $arg) {
            if (is_null($arg) || is_string($arg) && $arg == "") {
                $returnValue++;
            }
        }
        return $returnValue;
    }
    /**
     * COUNTIF
     *
     * Counts the number of cells that contain numbers within the list of arguments
     *
     * Excel Function:
     *        COUNTIF(value1[,value2[, ...]],condition)
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @param    string        $condition        The criteria that defines which cells will be counted.
     * @return    int
     */
    public static function COUNTIF($aArgs, $condition)
    {
        $returnValue = 0;
        $aArgs = PHPExcel_Calculation_Functions::flattenArray($aArgs);
        $condition = PHPExcel_Calculation_Functions::ifCondition($condition);
        foreach ($aArgs as $arg) {
            if (!is_numeric($arg)) {
                $arg = PHPExcel_Calculation::wrapResult(strtoupper($arg));
            }
            $testCondition = "=" . $arg . $condition;
            if (PHPExcel_Calculation::getInstance()->_calculateFormulaValue($testCondition)) {
                $returnValue++;
            }
        }
        return $returnValue;
    }
    /**
     * COVAR
     *
     * Returns covariance, the average of the products of deviations for each data point pair.
     *
     * @param    array of mixed        Data Series Y
     * @param    array of mixed        Data Series X
     * @return    float
     */
    public static function COVAR($yValues, $xValues)
    {
        if (!self::checkTrendArrays($yValues, $xValues)) {
            return PHPExcel_Calculation_Functions::VALUE();
        }
        $yValueCount = count($yValues);
        $xValueCount = count($xValues);
        if ($yValueCount == 0 || $yValueCount != $xValueCount) {
            return PHPExcel_Calculation_Functions::NA();
        }
        if ($yValueCount == 1) {
            return PHPExcel_Calculation_Functions::DIV0();
        }
        $bestFitLinear = trendClass::calculate(trendClass::TREND_LINEAR, $yValues, $xValues);
        return $bestFitLinear->getCovariance();
    }
    /**
     * CRITBINOM
     *
     * Returns the smallest value for which the cumulative binomial distribution is greater
     *        than or equal to a criterion value
     *
     * See http://support.microsoft.com/kb/828117/ for details of the algorithm used
     *
     * @param    float        $trials            number of Bernoulli trials
     * @param    float        $probability    probability of a success on each trial
     * @param    float        $alpha            criterion value
     * @return    int
     *
     * @todo    Warning. This implementation differs from the algorithm detailed on the MS
     *            web site in that $CumPGuessMinus1 = $CumPGuess - 1 rather than $CumPGuess - $PGuess
     *            This eliminates a potential endless loop error, but may have an adverse affect on the
     *            accuracy of the function (although all my tests have so far returned correct results).
     *
     */
    public static function CRITBINOM($trials, $probability, $alpha)
    {
        $trials = floor(PHPExcel_Calculation_Functions::flattenSingleValue($trials));
        $probability = PHPExcel_Calculation_Functions::flattenSingleValue($probability);
        $alpha = PHPExcel_Calculation_Functions::flattenSingleValue($alpha);
        if (is_numeric($trials) && is_numeric($probability) && is_numeric($alpha)) {
            if ($trials < 0) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            if ($probability < 0 || 1 < $probability) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            if ($alpha < 0 || 1 < $alpha) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            if ($alpha <= 0.5) {
                $t = sqrt(log(1 / ($alpha * $alpha)));
                $trialsApprox = 0 - ($t + (2.515517 + 0.802853 * $t + 0.010328 * $t * $t) / (1 + 1.432788 * $t + 0.189269 * $t * $t + 0.001308 * $t * $t * $t));
            } else {
                $t = sqrt(log(1 / pow(1 - $alpha, 2)));
                $trialsApprox = $t - (2.515517 + 0.802853 * $t + 0.010328 * $t * $t) / (1 + 1.432788 * $t + 0.189269 * $t * $t + 0.001308 * $t * $t * $t);
            }
            $Guess = floor($trials * $probability + $trialsApprox * sqrt($trials * $probability * (1 - $probability)));
            if ($Guess < 0) {
                $Guess = 0;
            } else {
                if ($trials < $Guess) {
                    $Guess = $trials;
                }
            }
            $TotalUnscaledProbability = $UnscaledPGuess = $UnscaledCumPGuess = 0;
            $EssentiallyZero = 9.999999999999999E-12;
            $m = floor($trials * $probability);
            $TotalUnscaledProbability++;
            if ($m == $Guess) {
                $UnscaledPGuess++;
            }
            if ($m <= $Guess) {
                $UnscaledCumPGuess++;
            }
            $PreviousValue = 1;
            $Done = false;
            for ($k = $m + 1; !$Done && $k <= $trials; $k++) {
                $CurrentValue = $PreviousValue * ($trials - $k + 1) * $probability / ($k * (1 - $probability));
                $TotalUnscaledProbability += $CurrentValue;
                if ($k == $Guess) {
                    $UnscaledPGuess += $CurrentValue;
                }
                if ($k <= $Guess) {
                    $UnscaledCumPGuess += $CurrentValue;
                }
                if ($CurrentValue <= $EssentiallyZero) {
                    $Done = true;
                }
                $PreviousValue = $CurrentValue;
            }
            $PreviousValue = 1;
            $Done = false;
            for ($k = $m - 1; !$Done && 0 <= $k; $k--) {
                $CurrentValue = $PreviousValue * $k + 1 * (1 - $probability) / (($trials - $k) * $probability);
                $TotalUnscaledProbability += $CurrentValue;
                if ($k == $Guess) {
                    $UnscaledPGuess += $CurrentValue;
                }
                if ($k <= $Guess) {
                    $UnscaledCumPGuess += $CurrentValue;
                }
                if ($CurrentValue <= $EssentiallyZero) {
                    $Done = true;
                }
                $PreviousValue = $CurrentValue;
            }
            $PGuess = $UnscaledPGuess / $TotalUnscaledProbability;
            $CumPGuess = $UnscaledCumPGuess / $TotalUnscaledProbability;
            $CumPGuessMinus1 = $CumPGuess - 1;
            while (true) {
                if ($CumPGuessMinus1 < $alpha && $alpha <= $CumPGuess) {
                    return $Guess;
                }
                if ($CumPGuessMinus1 < $alpha && $CumPGuess < $alpha) {
                    $PGuessPlus1 = $PGuess * ($trials - $Guess) * $probability / $Guess / (1 - $probability);
                    $CumPGuessMinus1 = $CumPGuess;
                    $CumPGuess = $CumPGuess + $PGuessPlus1;
                    $PGuess = $PGuessPlus1;
                    $Guess++;
                } else {
                    if ($alpha <= $CumPGuessMinus1 && $alpha <= $CumPGuess) {
                        $PGuessMinus1 = $PGuess * $Guess * (1 - $probability) / ($trials - $Guess + 1) / $probability;
                        $CumPGuess = $CumPGuessMinus1;
                        $CumPGuessMinus1 = $CumPGuessMinus1 - $PGuess;
                        $PGuess = $PGuessMinus1;
                        $Guess--;
                    }
                }
            }
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * DEVSQ
     *
     * Returns the sum of squares of deviations of data points from their sample mean.
     *
     * Excel Function:
     *        DEVSQ(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    float
     */
    public static function DEVSQ()
    {
        $aArgs = PHPExcel_Calculation_Functions::flattenArrayIndexed(func_get_args());
        $returnValue = NULL;
        $aMean = self::AVERAGE($aArgs);
        if ($aMean != PHPExcel_Calculation_Functions::DIV0()) {
            $aCount = -1;
            foreach ($aArgs as $k => $arg) {
                if (is_bool($arg) && (!PHPExcel_Calculation_Functions::isCellValue($k) || PHPExcel_Calculation_Functions::getCompatibilityMode() == PHPExcel_Calculation_Functions::COMPATIBILITY_OPENOFFICE)) {
                    $arg = (int) $arg;
                }
                if (is_numeric($arg) && !is_string($arg)) {
                    if (is_null($returnValue)) {
                        $returnValue = pow($arg - $aMean, 2);
                    } else {
                        $returnValue += pow($arg - $aMean, 2);
                    }
                    $aCount++;
                }
            }
            if (is_null($returnValue)) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            return $returnValue;
        } else {
            return self::NA();
        }
    }
    /**
     * EXPONDIST
     *
     *    Returns the exponential distribution. Use EXPONDIST to model the time between events,
     *        such as how long an automated bank teller takes to deliver cash. For example, you can
     *        use EXPONDIST to determine the probability that the process takes at most 1 minute.
     *
     * @param    float        $value            Value of the function
     * @param    float        $lambda            The parameter value
     * @param    boolean        $cumulative
     * @return    float
     */
    public static function EXPONDIST($value, $lambda, $cumulative)
    {
        $value = PHPExcel_Calculation_Functions::flattenSingleValue($value);
        $lambda = PHPExcel_Calculation_Functions::flattenSingleValue($lambda);
        $cumulative = PHPExcel_Calculation_Functions::flattenSingleValue($cumulative);
        if (is_numeric($value) && is_numeric($lambda)) {
            if ($value < 0 || $lambda < 0) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            if (is_numeric($cumulative) || is_bool($cumulative)) {
                if ($cumulative) {
                    return 1 - exp(0 - $value * $lambda);
                }
                return $lambda * exp(0 - $value * $lambda);
            }
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * FISHER
     *
     * Returns the Fisher transformation at x. This transformation produces a function that
     *        is normally distributed rather than skewed. Use this function to perform hypothesis
     *        testing on the correlation coefficient.
     *
     * @param    float        $value
     * @return    float
     */
    public static function FISHER($value)
    {
        $value = PHPExcel_Calculation_Functions::flattenSingleValue($value);
        if (is_numeric($value)) {
            if ($value <= -1 || 1 <= $value) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            return 0.5 * log((1 + $value) / (1 - $value));
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * FISHERINV
     *
     * Returns the inverse of the Fisher transformation. Use this transformation when
     *        analyzing correlations between ranges or arrays of data. If y = FISHER(x), then
     *        FISHERINV(y) = x.
     *
     * @param    float        $value
     * @return    float
     */
    public static function FISHERINV($value)
    {
        $value = PHPExcel_Calculation_Functions::flattenSingleValue($value);
        if (is_numeric($value)) {
            return (exp(2 * $value) - 1) / (exp(2 * $value) + 1);
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * FORECAST
     *
     * Calculates, or predicts, a future value by using existing values. The predicted value is a y-value for a given x-value.
     *
     * @param    float                Value of X for which we want to find Y
     * @param    array of mixed        Data Series Y
     * @param    array of mixed        Data Series X
     * @return    float
     */
    public static function FORECAST($xValue, $yValues, $xValues)
    {
        $xValue = PHPExcel_Calculation_Functions::flattenSingleValue($xValue);
        if (!is_numeric($xValue)) {
            return PHPExcel_Calculation_Functions::VALUE();
        }
        if (!self::checkTrendArrays($yValues, $xValues)) {
            return PHPExcel_Calculation_Functions::VALUE();
        }
        $yValueCount = count($yValues);
        $xValueCount = count($xValues);
        if ($yValueCount == 0 || $yValueCount != $xValueCount) {
            return PHPExcel_Calculation_Functions::NA();
        }
        if ($yValueCount == 1) {
            return PHPExcel_Calculation_Functions::DIV0();
        }
        $bestFitLinear = trendClass::calculate(trendClass::TREND_LINEAR, $yValues, $xValues);
        return $bestFitLinear->getValueOfYForX($xValue);
    }
    /**
     * GAMMADIST
     *
     * Returns the gamma distribution.
     *
     * @param    float        $value            Value at which you want to evaluate the distribution
     * @param    float        $a                Parameter to the distribution
     * @param    float        $b                Parameter to the distribution
     * @param    boolean        $cumulative
     * @return    float
     *
     */
    public static function GAMMADIST($value, $a, $b, $cumulative)
    {
        $value = PHPExcel_Calculation_Functions::flattenSingleValue($value);
        $a = PHPExcel_Calculation_Functions::flattenSingleValue($a);
        $b = PHPExcel_Calculation_Functions::flattenSingleValue($b);
        if (is_numeric($value) && is_numeric($a) && is_numeric($b)) {
            if ($value < 0 || $a <= 0 || $b <= 0) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            if (is_numeric($cumulative) || is_bool($cumulative)) {
                if ($cumulative) {
                    return self::incompleteGamma($a, $value / $b) / self::gamma($a);
                }
                return 1 / (pow($b, $a) * self::gamma($a)) * pow($value, $a - 1) * exp(0 - $value / $b);
            }
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * GAMMAINV
     *
     * Returns the inverse of the beta distribution.
     *
     * @param    float        $probability    Probability at which you want to evaluate the distribution
     * @param    float        $alpha            Parameter to the distribution
     * @param    float        $beta            Parameter to the distribution
     * @return    float
     *
     */
    public static function GAMMAINV($probability, $alpha, $beta)
    {
        $probability = PHPExcel_Calculation_Functions::flattenSingleValue($probability);
        $alpha = PHPExcel_Calculation_Functions::flattenSingleValue($alpha);
        $beta = PHPExcel_Calculation_Functions::flattenSingleValue($beta);
        if (is_numeric($probability) && is_numeric($alpha) && is_numeric($beta)) {
            if ($alpha <= 0 || $beta <= 0 || $probability < 0 || 1 < $probability) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            $xLo = 0;
            $xHi = $alpha * $beta * 5;
            $x = $xNew = 1;
            $error = $pdf = 0;
            $dx = 1024;
            $i = 0;
            while (PRECISION < abs($dx) && $i++ < MAX_ITERATIONS) {
                $error = self::GAMMADIST($x, $alpha, $beta, true) - $probability;
                if ($error < 0) {
                    $xLo = $x;
                } else {
                    $xHi = $x;
                }
                $pdf = self::GAMMADIST($x, $alpha, $beta, false);
                if ($pdf != 0) {
                    $dx = $error / $pdf;
                    $xNew = $x - $dx;
                }
                if ($xNew < $xLo || $xHi < $xNew || $pdf == 0) {
                    $xNew = ($xLo + $xHi) / 2;
                    $dx = $xNew - $x;
                }
                $x = $xNew;
            }
            if ($i == MAX_ITERATIONS) {
                return PHPExcel_Calculation_Functions::NA();
            }
            return $x;
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * GAMMALN
     *
     * Returns the natural logarithm of the gamma function.
     *
     * @param    float        $value
     * @return    float
     */
    public static function GAMMALN($value)
    {
        $value = PHPExcel_Calculation_Functions::flattenSingleValue($value);
        if (is_numeric($value)) {
            if ($value <= 0) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            return log(self::gamma($value));
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * GEOMEAN
     *
     * Returns the geometric mean of an array or range of positive data. For example, you
     *        can use GEOMEAN to calculate average growth rate given compound interest with
     *        variable rates.
     *
     * Excel Function:
     *        GEOMEAN(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    float
     */
    public static function GEOMEAN()
    {
        $aArgs = PHPExcel_Calculation_Functions::flattenArray(func_get_args());
        $aMean = PHPExcel_Calculation_MathTrig::PRODUCT($aArgs);
        if (is_numeric($aMean) && 0 < $aMean) {
            $aCount = self::COUNT($aArgs);
            if (0 < self::MIN($aArgs)) {
                return pow($aMean, 1 / $aCount);
            }
        }
        return PHPExcel_Calculation_Functions::NaN();
    }
    /**
     * GROWTH
     *
     * Returns values along a predicted emponential trend
     *
     * @param    array of mixed        Data Series Y
     * @param    array of mixed        Data Series X
     * @param    array of mixed        Values of X for which we want to find Y
     * @param    boolean                A logical value specifying whether to force the intersect to equal 0.
     * @return    array of float
     */
    public static function GROWTH($yValues, $xValues = array(), $newValues = array(), $const = true)
    {
        $yValues = PHPExcel_Calculation_Functions::flattenArray($yValues);
        $xValues = PHPExcel_Calculation_Functions::flattenArray($xValues);
        $newValues = PHPExcel_Calculation_Functions::flattenArray($newValues);
        $const = is_null($const) ? true : (bool) PHPExcel_Calculation_Functions::flattenSingleValue($const);
        $bestFitExponential = trendClass::calculate(trendClass::TREND_EXPONENTIAL, $yValues, $xValues, $const);
        if (empty($newValues)) {
            $newValues = $bestFitExponential->getXValues();
        }
        $returnArray = array();
        foreach ($newValues as $xValue) {
            $returnArray[0][] = $bestFitExponential->getValueOfYForX($xValue);
        }
        return $returnArray;
    }
    /**
     * HARMEAN
     *
     * Returns the harmonic mean of a data set. The harmonic mean is the reciprocal of the
     *        arithmetic mean of reciprocals.
     *
     * Excel Function:
     *        HARMEAN(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    float
     */
    public static function HARMEAN()
    {
        $returnValue = PHPExcel_Calculation_Functions::NA();
        $aArgs = PHPExcel_Calculation_Functions::flattenArray(func_get_args());
        if (self::MIN($aArgs) < 0) {
            return PHPExcel_Calculation_Functions::NaN();
        }
        $aCount = 0;
        foreach ($aArgs as $arg) {
            if (is_numeric($arg) && !is_string($arg)) {
                if ($arg <= 0) {
                    return PHPExcel_Calculation_Functions::NaN();
                }
                if (is_null($returnValue)) {
                    $returnValue = 1 / $arg;
                } else {
                    $returnValue += 1 / $arg;
                }
                $aCount++;
            }
        }
        if (0 < $aCount) {
            return 1 / ($returnValue / $aCount);
        }
        return $returnValue;
    }
    /**
     * HYPGEOMDIST
     *
     * Returns the hypergeometric distribution. HYPGEOMDIST returns the probability of a given number of
     * sample successes, given the sample size, population successes, and population size.
     *
     * @param    float        $sampleSuccesses        Number of successes in the sample
     * @param    float        $sampleNumber            Size of the sample
     * @param    float        $populationSuccesses    Number of successes in the population
     * @param    float        $populationNumber        Population size
     * @return    float
     *
     */
    public static function HYPGEOMDIST($sampleSuccesses, $sampleNumber, $populationSuccesses, $populationNumber)
    {
        $sampleSuccesses = floor(PHPExcel_Calculation_Functions::flattenSingleValue($sampleSuccesses));
        $sampleNumber = floor(PHPExcel_Calculation_Functions::flattenSingleValue($sampleNumber));
        $populationSuccesses = floor(PHPExcel_Calculation_Functions::flattenSingleValue($populationSuccesses));
        $populationNumber = floor(PHPExcel_Calculation_Functions::flattenSingleValue($populationNumber));
        if (is_numeric($sampleSuccesses) && is_numeric($sampleNumber) && is_numeric($populationSuccesses) && is_numeric($populationNumber)) {
            if ($sampleSuccesses < 0 || $sampleNumber < $sampleSuccesses || $populationSuccesses < $sampleSuccesses) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            if ($sampleNumber <= 0 || $populationNumber < $sampleNumber) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            if ($populationSuccesses <= 0 || $populationNumber < $populationSuccesses) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            return PHPExcel_Calculation_MathTrig::COMBIN($populationSuccesses, $sampleSuccesses) * PHPExcel_Calculation_MathTrig::COMBIN($populationNumber - $populationSuccesses, $sampleNumber - $sampleSuccesses) / PHPExcel_Calculation_MathTrig::COMBIN($populationNumber, $sampleNumber);
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * INTERCEPT
     *
     * Calculates the point at which a line will intersect the y-axis by using existing x-values and y-values.
     *
     * @param    array of mixed        Data Series Y
     * @param    array of mixed        Data Series X
     * @return    float
     */
    public static function INTERCEPT($yValues, $xValues)
    {
        if (!self::checkTrendArrays($yValues, $xValues)) {
            return PHPExcel_Calculation_Functions::VALUE();
        }
        $yValueCount = count($yValues);
        $xValueCount = count($xValues);
        if ($yValueCount == 0 || $yValueCount != $xValueCount) {
            return PHPExcel_Calculation_Functions::NA();
        }
        if ($yValueCount == 1) {
            return PHPExcel_Calculation_Functions::DIV0();
        }
        $bestFitLinear = trendClass::calculate(trendClass::TREND_LINEAR, $yValues, $xValues);
        return $bestFitLinear->getIntersect();
    }
    /**
     * KURT
     *
     * Returns the kurtosis of a data set. Kurtosis characterizes the relative peakedness
     * or flatness of a distribution compared with the normal distribution. Positive
     * kurtosis indicates a relatively peaked distribution. Negative kurtosis indicates a
     * relatively flat distribution.
     *
     * @param    array    Data Series
     * @return    float
     */
    public static function KURT()
    {
        $aArgs = PHPExcel_Calculation_Functions::flattenArrayIndexed(func_get_args());
        $mean = self::AVERAGE($aArgs);
        $stdDev = self::STDEV($aArgs);
        if (0 < $stdDev) {
            $count = $summer = 0;
            foreach ($aArgs as $k => $arg) {
                if (!is_bool($arg) || PHPExcel_Calculation_Functions::isMatrixValue($k)) {
                    if (is_numeric($arg) && !is_string($arg)) {
                        $summer += pow(($arg - $mean) / $stdDev, 4);
                        $count++;
                    }
                }
            }
            if (3 < $count) {
                return $summer * ($count * ($count + 1)) / (($count - 1) * ($count - 2) * ($count - 3)) - 3 * pow($count - 1, 2) / (($count - 2) * ($count - 3));
            }
        }
        return PHPExcel_Calculation_Functions::DIV0();
    }
    /**
     * LARGE
     *
     * Returns the nth largest value in a data set. You can use this function to
     *        select a value based on its relative standing.
     *
     * Excel Function:
     *        LARGE(value1[,value2[, ...]],entry)
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @param    int            $entry            Position (ordered from the largest) in the array or range of data to return
     * @return    float
     *
     */
    public static function LARGE()
    {
        $aArgs = PHPExcel_Calculation_Functions::flattenArray(func_get_args());
        $entry = floor(array_pop($aArgs));
        if (is_numeric($entry) && !is_string($entry)) {
            $mArgs = array();
            foreach ($aArgs as $arg) {
                if (is_numeric($arg) && !is_string($arg)) {
                    $mArgs[] = $arg;
                }
            }
            $count = self::COUNT($mArgs);
            $entry = floor(--$entry);
            if ($entry < 0 || $count <= $entry || $count == 0) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            rsort($mArgs);
            return $mArgs[$entry];
        } else {
            return PHPExcel_Calculation_Functions::VALUE();
        }
    }
    /**
     * LINEST
     *
     * Calculates the statistics for a line by using the "least squares" method to calculate a straight line that best fits your data,
     *        and then returns an array that describes the line.
     *
     * @param    array of mixed        Data Series Y
     * @param    array of mixed        Data Series X
     * @param    boolean                A logical value specifying whether to force the intersect to equal 0.
     * @param    boolean                A logical value specifying whether to return additional regression statistics.
     * @return    array
     */
    public static function LINEST($yValues, $xValues = NULL, $const = true, $stats = false)
    {
        $const = is_null($const) ? true : (bool) PHPExcel_Calculation_Functions::flattenSingleValue($const);
        $stats = is_null($stats) ? false : (bool) PHPExcel_Calculation_Functions::flattenSingleValue($stats);
        if (is_null($xValues)) {
            $xValues = range(1, count(PHPExcel_Calculation_Functions::flattenArray($yValues)));
        }
        if (!self::checkTrendArrays($yValues, $xValues)) {
            return PHPExcel_Calculation_Functions::VALUE();
        }
        $yValueCount = count($yValues);
        $xValueCount = count($xValues);
        if ($yValueCount == 0 || $yValueCount != $xValueCount) {
            return PHPExcel_Calculation_Functions::NA();
        }
        if ($yValueCount == 1) {
            return 0;
        }
        $bestFitLinear = trendClass::calculate(trendClass::TREND_LINEAR, $yValues, $xValues, $const);
        if ($stats) {
            return array(array($bestFitLinear->getSlope(), $bestFitLinear->getSlopeSE(), $bestFitLinear->getGoodnessOfFit(), $bestFitLinear->getF(), $bestFitLinear->getSSRegression()), array($bestFitLinear->getIntersect(), $bestFitLinear->getIntersectSE(), $bestFitLinear->getStdevOfResiduals(), $bestFitLinear->getDFResiduals(), $bestFitLinear->getSSResiduals()));
        }
        return array($bestFitLinear->getSlope(), $bestFitLinear->getIntersect());
    }
    /**
     * LOGEST
     *
     * Calculates an exponential curve that best fits the X and Y data series,
     *        and then returns an array that describes the line.
     *
     * @param    array of mixed        Data Series Y
     * @param    array of mixed        Data Series X
     * @param    boolean                A logical value specifying whether to force the intersect to equal 0.
     * @param    boolean                A logical value specifying whether to return additional regression statistics.
     * @return    array
     */
    public static function LOGEST($yValues, $xValues = NULL, $const = true, $stats = false)
    {
        $const = is_null($const) ? true : (bool) PHPExcel_Calculation_Functions::flattenSingleValue($const);
        $stats = is_null($stats) ? false : (bool) PHPExcel_Calculation_Functions::flattenSingleValue($stats);
        if (is_null($xValues)) {
            $xValues = range(1, count(PHPExcel_Calculation_Functions::flattenArray($yValues)));
        }
        if (!self::checkTrendArrays($yValues, $xValues)) {
            return PHPExcel_Calculation_Functions::VALUE();
        }
        $yValueCount = count($yValues);
        $xValueCount = count($xValues);
        foreach ($yValues as $value) {
            if ($value <= 0) {
                return PHPExcel_Calculation_Functions::NaN();
            }
        }
        if ($yValueCount == 0 || $yValueCount != $xValueCount) {
            return PHPExcel_Calculation_Functions::NA();
        }
        if ($yValueCount == 1) {
            return 1;
        }
        $bestFitExponential = trendClass::calculate(trendClass::TREND_EXPONENTIAL, $yValues, $xValues, $const);
        if ($stats) {
            return array(array($bestFitExponential->getSlope(), $bestFitExponential->getSlopeSE(), $bestFitExponential->getGoodnessOfFit(), $bestFitExponential->getF(), $bestFitExponential->getSSRegression()), array($bestFitExponential->getIntersect(), $bestFitExponential->getIntersectSE(), $bestFitExponential->getStdevOfResiduals(), $bestFitExponential->getDFResiduals(), $bestFitExponential->getSSResiduals()));
        }
        return array($bestFitExponential->getSlope(), $bestFitExponential->getIntersect());
    }
    /**
     * LOGINV
     *
     * Returns the inverse of the normal cumulative distribution
     *
     * @param    float        $probability
     * @param    float        $mean
     * @param    float        $stdDev
     * @return    float
     *
     * @todo    Try implementing P J Acklam's refinement algorithm for greater
     *            accuracy if I can get my head round the mathematics
     *            (as described at) http://home.online.no/~pjacklam/notes/invnorm/
     */
    public static function LOGINV($probability, $mean, $stdDev)
    {
        $probability = PHPExcel_Calculation_Functions::flattenSingleValue($probability);
        $mean = PHPExcel_Calculation_Functions::flattenSingleValue($mean);
        $stdDev = PHPExcel_Calculation_Functions::flattenSingleValue($stdDev);
        if (is_numeric($probability) && is_numeric($mean) && is_numeric($stdDev)) {
            if ($probability < 0 || 1 < $probability || $stdDev <= 0) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            return exp($mean + $stdDev * self::NORMSINV($probability));
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * LOGNORMDIST
     *
     * Returns the cumulative lognormal distribution of x, where ln(x) is normally distributed
     * with parameters mean and standard_dev.
     *
     * @param    float        $value
     * @param    float        $mean
     * @param    float        $stdDev
     * @return    float
     */
    public static function LOGNORMDIST($value, $mean, $stdDev)
    {
        $value = PHPExcel_Calculation_Functions::flattenSingleValue($value);
        $mean = PHPExcel_Calculation_Functions::flattenSingleValue($mean);
        $stdDev = PHPExcel_Calculation_Functions::flattenSingleValue($stdDev);
        if (is_numeric($value) && is_numeric($mean) && is_numeric($stdDev)) {
            if ($value <= 0 || $stdDev <= 0) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            return self::NORMSDIST((log($value) - $mean) / $stdDev);
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * MAX
     *
     * MAX returns the value of the element of the values passed that has the highest value,
     *        with negative numbers considered smaller than positive numbers.
     *
     * Excel Function:
     *        MAX(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    float
     */
    public static function MAX()
    {
        $returnValue = NULL;
        $aArgs = PHPExcel_Calculation_Functions::flattenArray(func_get_args());
        foreach ($aArgs as $arg) {
            if (is_numeric($arg) && !is_string($arg) && (is_null($returnValue) || $returnValue < $arg)) {
                $returnValue = $arg;
            }
        }
        if (is_null($returnValue)) {
            return 0;
        }
        return $returnValue;
    }
    /**
     * MAXA
     *
     * Returns the greatest value in a list of arguments, including numbers, text, and logical values
     *
     * Excel Function:
     *        MAXA(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    float
     */
    public static function MAXA()
    {
        $returnValue = NULL;
        $aArgs = PHPExcel_Calculation_Functions::flattenArray(func_get_args());
        foreach ($aArgs as $arg) {
            if (is_numeric($arg) || is_bool($arg) || is_string($arg) && $arg != "") {
                if (is_bool($arg)) {
                    $arg = (int) $arg;
                } else {
                    if (is_string($arg)) {
                        $arg = 0;
                    }
                }
                if (is_null($returnValue) || $returnValue < $arg) {
                    $returnValue = $arg;
                }
            }
        }
        if (is_null($returnValue)) {
            return 0;
        }
        return $returnValue;
    }
    /**
     * MAXIF
     *
     * Counts the maximum value within a range of cells that contain numbers within the list of arguments
     *
     * Excel Function:
     *        MAXIF(value1[,value2[, ...]],condition)
     *
     * @access    public
     * @category Mathematical and Trigonometric Functions
     * @param    mixed        $arg,...        Data values
     * @param    string        $condition        The criteria that defines which cells will be checked.
     * @return    float
     */
    public static function MAXIF($aArgs, $condition, $sumArgs = array())
    {
        $returnValue = NULL;
        $aArgs = PHPExcel_Calculation_Functions::flattenArray($aArgs);
        $sumArgs = PHPExcel_Calculation_Functions::flattenArray($sumArgs);
        if (empty($sumArgs)) {
            $sumArgs = $aArgs;
        }
        $condition = PHPExcel_Calculation_Functions::ifCondition($condition);
        foreach ($aArgs as $key => $arg) {
            if (!is_numeric($arg)) {
                $arg = PHPExcel_Calculation::wrapResult(strtoupper($arg));
            }
            $testCondition = "=" . $arg . $condition;
            if (PHPExcel_Calculation::getInstance()->_calculateFormulaValue($testCondition) && (is_null($returnValue) || $returnValue < $arg)) {
                $returnValue = $arg;
            }
        }
        return $returnValue;
    }
    /**
     * MEDIAN
     *
     * Returns the median of the given numbers. The median is the number in the middle of a set of numbers.
     *
     * Excel Function:
     *        MEDIAN(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    float
     */
    public static function MEDIAN()
    {
        $returnValue = PHPExcel_Calculation_Functions::NaN();
        $mArgs = array();
        $aArgs = PHPExcel_Calculation_Functions::flattenArray(func_get_args());
        foreach ($aArgs as $arg) {
            if (is_numeric($arg) && !is_string($arg)) {
                $mArgs[] = $arg;
            }
        }
        $mValueCount = count($mArgs);
        if (0 < $mValueCount) {
            sort($mArgs, SORT_NUMERIC);
            $mValueCount = $mValueCount / 2;
            if ($mValueCount == floor($mValueCount)) {
                $returnValue = ($mArgs[$mValueCount--] + $mArgs[$mValueCount]) / 2;
            } else {
                $mValueCount = floor($mValueCount);
                $returnValue = $mArgs[$mValueCount];
            }
        }
        return $returnValue;
    }
    /**
     * MIN
     *
     * MIN returns the value of the element of the values passed that has the smallest value,
     *        with negative numbers considered smaller than positive numbers.
     *
     * Excel Function:
     *        MIN(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    float
     */
    public static function MIN()
    {
        $returnValue = NULL;
        $aArgs = PHPExcel_Calculation_Functions::flattenArray(func_get_args());
        foreach ($aArgs as $arg) {
            if (is_numeric($arg) && !is_string($arg) && (is_null($returnValue) || $arg < $returnValue)) {
                $returnValue = $arg;
            }
        }
        if (is_null($returnValue)) {
            return 0;
        }
        return $returnValue;
    }
    /**
     * MINA
     *
     * Returns the smallest value in a list of arguments, including numbers, text, and logical values
     *
     * Excel Function:
     *        MINA(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    float
     */
    public static function MINA()
    {
        $returnValue = NULL;
        $aArgs = PHPExcel_Calculation_Functions::flattenArray(func_get_args());
        foreach ($aArgs as $arg) {
            if (is_numeric($arg) || is_bool($arg) || is_string($arg) && $arg != "") {
                if (is_bool($arg)) {
                    $arg = (int) $arg;
                } else {
                    if (is_string($arg)) {
                        $arg = 0;
                    }
                }
                if (is_null($returnValue) || $arg < $returnValue) {
                    $returnValue = $arg;
                }
            }
        }
        if (is_null($returnValue)) {
            return 0;
        }
        return $returnValue;
    }
    /**
     * MINIF
     *
     * Returns the minimum value within a range of cells that contain numbers within the list of arguments
     *
     * Excel Function:
     *        MINIF(value1[,value2[, ...]],condition)
     *
     * @access    public
     * @category Mathematical and Trigonometric Functions
     * @param    mixed        $arg,...        Data values
     * @param    string        $condition        The criteria that defines which cells will be checked.
     * @return    float
     */
    public static function MINIF($aArgs, $condition, $sumArgs = array())
    {
        $returnValue = NULL;
        $aArgs = PHPExcel_Calculation_Functions::flattenArray($aArgs);
        $sumArgs = PHPExcel_Calculation_Functions::flattenArray($sumArgs);
        if (empty($sumArgs)) {
            $sumArgs = $aArgs;
        }
        $condition = PHPExcel_Calculation_Functions::ifCondition($condition);
        foreach ($aArgs as $key => $arg) {
            if (!is_numeric($arg)) {
                $arg = PHPExcel_Calculation::wrapResult(strtoupper($arg));
            }
            $testCondition = "=" . $arg . $condition;
            if (PHPExcel_Calculation::getInstance()->_calculateFormulaValue($testCondition) && (is_null($returnValue) || $arg < $returnValue)) {
                $returnValue = $arg;
            }
        }
        return $returnValue;
    }
    private static function modeCalc($data)
    {
        $frequencyArray = array();
        foreach ($data as $datum) {
            $found = false;
            foreach ($frequencyArray as $key => $value) {
                if ((string) $value["value"] == (string) $datum) {
                    $frequencyArray[$key]["frequency"]++;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $frequencyArray[] = array("value" => $datum, "frequency" => 1);
            }
        }
        foreach ($frequencyArray as $key => $value) {
            $frequencyList[$key] = $value["frequency"];
            $valueList[$key] = $value["value"];
        }
        array_multisort($frequencyList, SORT_DESC, $valueList, SORT_ASC, SORT_NUMERIC, $frequencyArray);
        if ($frequencyArray[0]["frequency"] == 1) {
            return PHPExcel_Calculation_Functions::NA();
        }
        return $frequencyArray[0]["value"];
    }
    /**
     * MODE
     *
     * Returns the most frequently occurring, or repetitive, value in an array or range of data
     *
     * Excel Function:
     *        MODE(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    float
     */
    public static function MODE()
    {
        $returnValue = PHPExcel_Calculation_Functions::NA();
        $aArgs = PHPExcel_Calculation_Functions::flattenArray(func_get_args());
        $mArgs = array();
        foreach ($aArgs as $arg) {
            if (is_numeric($arg) && !is_string($arg)) {
                $mArgs[] = $arg;
            }
        }
        if (!empty($mArgs)) {
            return self::modeCalc($mArgs);
        }
        return $returnValue;
    }
    /**
     * NEGBINOMDIST
     *
     * Returns the negative binomial distribution. NEGBINOMDIST returns the probability that
     *        there will be number_f failures before the number_s-th success, when the constant
     *        probability of a success is probability_s. This function is similar to the binomial
     *        distribution, except that the number of successes is fixed, and the number of trials is
     *        variable. Like the binomial, trials are assumed to be independent.
     *
     * @param    float        $failures        Number of Failures
     * @param    float        $successes        Threshold number of Successes
     * @param    float        $probability    Probability of success on each trial
     * @return    float
     *
     */
    public static function NEGBINOMDIST($failures, $successes, $probability)
    {
        $failures = floor(PHPExcel_Calculation_Functions::flattenSingleValue($failures));
        $successes = floor(PHPExcel_Calculation_Functions::flattenSingleValue($successes));
        $probability = PHPExcel_Calculation_Functions::flattenSingleValue($probability);
        if (is_numeric($failures) && is_numeric($successes) && is_numeric($probability)) {
            if ($failures < 0 || $successes < 1) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            if ($probability < 0 || 1 < $probability) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            if (PHPExcel_Calculation_Functions::getCompatibilityMode() == PHPExcel_Calculation_Functions::COMPATIBILITY_GNUMERIC && $failures + $successes - 1 <= 0) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            return PHPExcel_Calculation_MathTrig::COMBIN($failures + $successes - 1, $successes - 1) * pow($probability, $successes) * pow(1 - $probability, $failures);
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * NORMDIST
     *
     * Returns the normal distribution for the specified mean and standard deviation. This
     * function has a very wide range of applications in statistics, including hypothesis
     * testing.
     *
     * @param    float        $value
     * @param    float        $mean        Mean Value
     * @param    float        $stdDev        Standard Deviation
     * @param    boolean        $cumulative
     * @return    float
     *
     */
    public static function NORMDIST($value, $mean, $stdDev, $cumulative)
    {
        $value = PHPExcel_Calculation_Functions::flattenSingleValue($value);
        $mean = PHPExcel_Calculation_Functions::flattenSingleValue($mean);
        $stdDev = PHPExcel_Calculation_Functions::flattenSingleValue($stdDev);
        if (is_numeric($value) && is_numeric($mean) && is_numeric($stdDev)) {
            if ($stdDev < 0) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            if (is_numeric($cumulative) || is_bool($cumulative)) {
                if ($cumulative) {
                    return 0.5 * (1 + PHPExcel_Calculation_Engineering::erfVal(($value - $mean) / ($stdDev * sqrt(2))));
                }
                return 1 / (SQRT2PI * $stdDev) * exp(0 - pow($value - $mean, 2) / (2 * $stdDev * $stdDev));
            }
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * NORMINV
     *
     * Returns the inverse of the normal cumulative distribution for the specified mean and standard deviation.
     *
     * @param    float        $value
     * @param    float        $mean        Mean Value
     * @param    float        $stdDev        Standard Deviation
     * @return    float
     *
     */
    public static function NORMINV($probability, $mean, $stdDev)
    {
        $probability = PHPExcel_Calculation_Functions::flattenSingleValue($probability);
        $mean = PHPExcel_Calculation_Functions::flattenSingleValue($mean);
        $stdDev = PHPExcel_Calculation_Functions::flattenSingleValue($stdDev);
        if (is_numeric($probability) && is_numeric($mean) && is_numeric($stdDev)) {
            if ($probability < 0 || 1 < $probability) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            if ($stdDev < 0) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            return self::inverseNcdf($probability) * $stdDev + $mean;
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * NORMSDIST
     *
     * Returns the standard normal cumulative distribution function. The distribution has
     * a mean of 0 (zero) and a standard deviation of one. Use this function in place of a
     * table of standard normal curve areas.
     *
     * @param    float        $value
     * @return    float
     */
    public static function NORMSDIST($value)
    {
        $value = PHPExcel_Calculation_Functions::flattenSingleValue($value);
        return self::NORMDIST($value, 0, 1, true);
    }
    /**
     * NORMSINV
     *
     * Returns the inverse of the standard normal cumulative distribution
     *
     * @param    float        $value
     * @return    float
     */
    public static function NORMSINV($value)
    {
        return self::NORMINV($value, 0, 1);
    }
    /**
     * PERCENTILE
     *
     * Returns the nth percentile of values in a range..
     *
     * Excel Function:
     *        PERCENTILE(value1[,value2[, ...]],entry)
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @param    float        $entry            Percentile value in the range 0..1, inclusive.
     * @return    float
     */
    public static function PERCENTILE()
    {
        $aArgs = PHPExcel_Calculation_Functions::flattenArray(func_get_args());
        $entry = array_pop($aArgs);
        if (is_numeric($entry) && !is_string($entry)) {
            if ($entry < 0 || 1 < $entry) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            $mArgs = array();
            foreach ($aArgs as $arg) {
                if (is_numeric($arg) && !is_string($arg)) {
                    $mArgs[] = $arg;
                }
            }
            $mValueCount = count($mArgs);
            if (0 < $mValueCount) {
                sort($mArgs);
                $count = self::COUNT($mArgs);
                $index = $entry * ($count - 1);
                $iBase = floor($index);
                if ($index == $iBase) {
                    return $mArgs[$index];
                }
                $iNext = $iBase + 1;
                $iProportion = $index - $iBase;
                return $mArgs[$iBase] + ($mArgs[$iNext] - $mArgs[$iBase]) * $iProportion;
            }
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * PERCENTRANK
     *
     * Returns the rank of a value in a data set as a percentage of the data set.
     *
     * @param    array of number        An array of, or a reference to, a list of numbers.
     * @param    number                The number whose rank you want to find.
     * @param    number                The number of significant digits for the returned percentage value.
     * @return    float
     */
    public static function PERCENTRANK($valueSet, $value, $significance = 3)
    {
        $valueSet = PHPExcel_Calculation_Functions::flattenArray($valueSet);
        $value = PHPExcel_Calculation_Functions::flattenSingleValue($value);
        $significance = is_null($significance) ? 3 : (int) PHPExcel_Calculation_Functions::flattenSingleValue($significance);
        foreach ($valueSet as $key => $valueEntry) {
            if (!is_numeric($valueEntry)) {
                unset($valueSet[$key]);
            }
        }
        sort($valueSet, SORT_NUMERIC);
        $valueCount = count($valueSet);
        if ($valueCount == 0) {
            return PHPExcel_Calculation_Functions::NaN();
        }
        $valueAdjustor = $valueCount - 1;
        if ($value < $valueSet[0] || $valueSet[$valueAdjustor] < $value) {
            return PHPExcel_Calculation_Functions::NA();
        }
        $pos = array_search($value, $valueSet);
        if ($pos === false) {
            $pos = 0;
            $testValue = $valueSet[0];
            while ($testValue < $value) {
                $testValue = $valueSet[++$pos];
            }
            $pos--;
            $pos += ($value - $valueSet[$pos]) / ($testValue - $valueSet[$pos]);
        }
        return round($pos / $valueAdjustor, $significance);
    }
    /**
     * PERMUT
     *
     * Returns the number of permutations for a given number of objects that can be
     *        selected from number objects. A permutation is any set or subset of objects or
     *        events where internal order is significant. Permutations are different from
     *        combinations, for which the internal order is not significant. Use this function
     *        for lottery-style probability calculations.
     *
     * @param    int        $numObjs    Number of different objects
     * @param    int        $numInSet    Number of objects in each permutation
     * @return    int        Number of permutations
     */
    public static function PERMUT($numObjs, $numInSet)
    {
        $numObjs = PHPExcel_Calculation_Functions::flattenSingleValue($numObjs);
        $numInSet = PHPExcel_Calculation_Functions::flattenSingleValue($numInSet);
        if (is_numeric($numObjs) && is_numeric($numInSet)) {
            $numInSet = floor($numInSet);
            if ($numObjs < $numInSet) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            return round(PHPExcel_Calculation_MathTrig::FACT($numObjs) / PHPExcel_Calculation_MathTrig::FACT($numObjs - $numInSet));
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * POISSON
     *
     * Returns the Poisson distribution. A common application of the Poisson distribution
     * is predicting the number of events over a specific time, such as the number of
     * cars arriving at a toll plaza in 1 minute.
     *
     * @param    float        $value
     * @param    float        $mean        Mean Value
     * @param    boolean        $cumulative
     * @return    float
     *
     */
    public static function POISSON($value, $mean, $cumulative)
    {
        $value = PHPExcel_Calculation_Functions::flattenSingleValue($value);
        $mean = PHPExcel_Calculation_Functions::flattenSingleValue($mean);
        if (is_numeric($value) && is_numeric($mean)) {
            if ($value < 0 || $mean <= 0) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            if (is_numeric($cumulative) || is_bool($cumulative)) {
                if ($cumulative) {
                    $summer = 0;
                    for ($i = 0; $i <= floor($value); $i++) {
                        $summer += pow($mean, $i) / PHPExcel_Calculation_MathTrig::FACT($i);
                    }
                    return exp(0 - $mean) * $summer;
                }
                return exp(0 - $mean) * pow($mean, $value) / PHPExcel_Calculation_MathTrig::FACT($value);
            }
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * QUARTILE
     *
     * Returns the quartile of a data set.
     *
     * Excel Function:
     *        QUARTILE(value1[,value2[, ...]],entry)
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @param    int            $entry            Quartile value in the range 1..3, inclusive.
     * @return    float
     */
    public static function QUARTILE()
    {
        $aArgs = PHPExcel_Calculation_Functions::flattenArray(func_get_args());
        $entry = floor(array_pop($aArgs));
        if (is_numeric($entry) && !is_string($entry)) {
            $entry /= 4;
            if ($entry < 0 || 1 < $entry) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            return self::PERCENTILE($aArgs, $entry);
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * RANK
     *
     * Returns the rank of a number in a list of numbers.
     *
     * @param    number                The number whose rank you want to find.
     * @param    array of number        An array of, or a reference to, a list of numbers.
     * @param    mixed                Order to sort the values in the value set
     * @return    float
     */
    public static function RANK($value, $valueSet, $order = 0)
    {
        $value = PHPExcel_Calculation_Functions::flattenSingleValue($value);
        $valueSet = PHPExcel_Calculation_Functions::flattenArray($valueSet);
        $order = is_null($order) ? 0 : (int) PHPExcel_Calculation_Functions::flattenSingleValue($order);
        foreach ($valueSet as $key => $valueEntry) {
            if (!is_numeric($valueEntry)) {
                unset($valueSet[$key]);
            }
        }
        if ($order == 0) {
            rsort($valueSet, SORT_NUMERIC);
        } else {
            sort($valueSet, SORT_NUMERIC);
        }
        $pos = array_search($value, $valueSet);
        if ($pos === false) {
            return PHPExcel_Calculation_Functions::NA();
        }
        return ++$pos;
    }
    /**
     * RSQ
     *
     * Returns the square of the Pearson product moment correlation coefficient through data points in known_y's and known_x's.
     *
     * @param    array of mixed        Data Series Y
     * @param    array of mixed        Data Series X
     * @return    float
     */
    public static function RSQ($yValues, $xValues)
    {
        if (!self::checkTrendArrays($yValues, $xValues)) {
            return PHPExcel_Calculation_Functions::VALUE();
        }
        $yValueCount = count($yValues);
        $xValueCount = count($xValues);
        if ($yValueCount == 0 || $yValueCount != $xValueCount) {
            return PHPExcel_Calculation_Functions::NA();
        }
        if ($yValueCount == 1) {
            return PHPExcel_Calculation_Functions::DIV0();
        }
        $bestFitLinear = trendClass::calculate(trendClass::TREND_LINEAR, $yValues, $xValues);
        return $bestFitLinear->getGoodnessOfFit();
    }
    /**
     * SKEW
     *
     * Returns the skewness of a distribution. Skewness characterizes the degree of asymmetry
     * of a distribution around its mean. Positive skewness indicates a distribution with an
     * asymmetric tail extending toward more positive values. Negative skewness indicates a
     * distribution with an asymmetric tail extending toward more negative values.
     *
     * @param    array    Data Series
     * @return    float
     */
    public static function SKEW()
    {
        $aArgs = PHPExcel_Calculation_Functions::flattenArrayIndexed(func_get_args());
        $mean = self::AVERAGE($aArgs);
        $stdDev = self::STDEV($aArgs);
        $count = $summer = 0;
        foreach ($aArgs as $k => $arg) {
            if (!is_bool($arg) || PHPExcel_Calculation_Functions::isMatrixValue($k)) {
                if (is_numeric($arg) && !is_string($arg)) {
                    $summer += pow(($arg - $mean) / $stdDev, 3);
                    $count++;
                }
            }
        }
        if (2 < $count) {
            return $summer * $count / (($count - 1) * ($count - 2));
        }
        return PHPExcel_Calculation_Functions::DIV0();
    }
    /**
     * SLOPE
     *
     * Returns the slope of the linear regression line through data points in known_y's and known_x's.
     *
     * @param    array of mixed        Data Series Y
     * @param    array of mixed        Data Series X
     * @return    float
     */
    public static function SLOPE($yValues, $xValues)
    {
        if (!self::checkTrendArrays($yValues, $xValues)) {
            return PHPExcel_Calculation_Functions::VALUE();
        }
        $yValueCount = count($yValues);
        $xValueCount = count($xValues);
        if ($yValueCount == 0 || $yValueCount != $xValueCount) {
            return PHPExcel_Calculation_Functions::NA();
        }
        if ($yValueCount == 1) {
            return PHPExcel_Calculation_Functions::DIV0();
        }
        $bestFitLinear = trendClass::calculate(trendClass::TREND_LINEAR, $yValues, $xValues);
        return $bestFitLinear->getSlope();
    }
    /**
     * SMALL
     *
     * Returns the nth smallest value in a data set. You can use this function to
     *        select a value based on its relative standing.
     *
     * Excel Function:
     *        SMALL(value1[,value2[, ...]],entry)
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @param    int            $entry            Position (ordered from the smallest) in the array or range of data to return
     * @return    float
     */
    public static function SMALL()
    {
        $aArgs = PHPExcel_Calculation_Functions::flattenArray(func_get_args());
        $entry = array_pop($aArgs);
        if (is_numeric($entry) && !is_string($entry)) {
            $mArgs = array();
            foreach ($aArgs as $arg) {
                if (is_numeric($arg) && !is_string($arg)) {
                    $mArgs[] = $arg;
                }
            }
            $count = self::COUNT($mArgs);
            $entry = floor(--$entry);
            if ($entry < 0 || $count <= $entry || $count == 0) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            sort($mArgs);
            return $mArgs[$entry];
        } else {
            return PHPExcel_Calculation_Functions::VALUE();
        }
    }
    /**
     * STANDARDIZE
     *
     * Returns a normalized value from a distribution characterized by mean and standard_dev.
     *
     * @param    float    $value        Value to normalize
     * @param    float    $mean        Mean Value
     * @param    float    $stdDev        Standard Deviation
     * @return    float    Standardized value
     */
    public static function STANDARDIZE($value, $mean, $stdDev)
    {
        $value = PHPExcel_Calculation_Functions::flattenSingleValue($value);
        $mean = PHPExcel_Calculation_Functions::flattenSingleValue($mean);
        $stdDev = PHPExcel_Calculation_Functions::flattenSingleValue($stdDev);
        if (is_numeric($value) && is_numeric($mean) && is_numeric($stdDev)) {
            if ($stdDev <= 0) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            return ($value - $mean) / $stdDev;
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * STDEV
     *
     * Estimates standard deviation based on a sample. The standard deviation is a measure of how
     *        widely values are dispersed from the average value (the mean).
     *
     * Excel Function:
     *        STDEV(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    float
     */
    public static function STDEV()
    {
        $aArgs = PHPExcel_Calculation_Functions::flattenArrayIndexed(func_get_args());
        $returnValue = NULL;
        $aMean = self::AVERAGE($aArgs);
        if (!is_null($aMean)) {
            $aCount = -1;
            foreach ($aArgs as $k => $arg) {
                if (is_bool($arg) && (!PHPExcel_Calculation_Functions::isCellValue($k) || PHPExcel_Calculation_Functions::getCompatibilityMode() == PHPExcel_Calculation_Functions::COMPATIBILITY_OPENOFFICE)) {
                    $arg = (int) $arg;
                }
                if (is_numeric($arg) && !is_string($arg)) {
                    if (is_null($returnValue)) {
                        $returnValue = pow($arg - $aMean, 2);
                    } else {
                        $returnValue += pow($arg - $aMean, 2);
                    }
                    $aCount++;
                }
            }
            if (0 < $aCount && 0 <= $returnValue) {
                return sqrt($returnValue / $aCount);
            }
        }
        return PHPExcel_Calculation_Functions::DIV0();
    }
    /**
     * STDEVA
     *
     * Estimates standard deviation based on a sample, including numbers, text, and logical values
     *
     * Excel Function:
     *        STDEVA(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    float
     */
    public static function STDEVA()
    {
        $aArgs = PHPExcel_Calculation_Functions::flattenArrayIndexed(func_get_args());
        $returnValue = NULL;
        $aMean = self::AVERAGEA($aArgs);
        if (!is_null($aMean)) {
            $aCount = -1;
            foreach ($aArgs as $k => $arg) {
                if (!is_bool($arg) || PHPExcel_Calculation_Functions::isMatrixValue($k)) {
                    if (is_numeric($arg) || is_bool($arg) || is_string($arg) & $arg != "") {
                        if (is_bool($arg)) {
                            $arg = (int) $arg;
                        } else {
                            if (is_string($arg)) {
                                $arg = 0;
                            }
                        }
                        if (is_null($returnValue)) {
                            $returnValue = pow($arg - $aMean, 2);
                        } else {
                            $returnValue += pow($arg - $aMean, 2);
                        }
                        $aCount++;
                    }
                }
            }
            if (0 < $aCount && 0 <= $returnValue) {
                return sqrt($returnValue / $aCount);
            }
        }
        return PHPExcel_Calculation_Functions::DIV0();
    }
    /**
     * STDEVP
     *
     * Calculates standard deviation based on the entire population
     *
     * Excel Function:
     *        STDEVP(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    float
     */
    public static function STDEVP()
    {
        $aArgs = PHPExcel_Calculation_Functions::flattenArrayIndexed(func_get_args());
        $returnValue = NULL;
        $aMean = self::AVERAGE($aArgs);
        if (!is_null($aMean)) {
            $aCount = 0;
            foreach ($aArgs as $k => $arg) {
                if (is_bool($arg) && (!PHPExcel_Calculation_Functions::isCellValue($k) || PHPExcel_Calculation_Functions::getCompatibilityMode() == PHPExcel_Calculation_Functions::COMPATIBILITY_OPENOFFICE)) {
                    $arg = (int) $arg;
                }
                if (is_numeric($arg) && !is_string($arg)) {
                    if (is_null($returnValue)) {
                        $returnValue = pow($arg - $aMean, 2);
                    } else {
                        $returnValue += pow($arg - $aMean, 2);
                    }
                    $aCount++;
                }
            }
            if (0 < $aCount && 0 <= $returnValue) {
                return sqrt($returnValue / $aCount);
            }
        }
        return PHPExcel_Calculation_Functions::DIV0();
    }
    /**
     * STDEVPA
     *
     * Calculates standard deviation based on the entire population, including numbers, text, and logical values
     *
     * Excel Function:
     *        STDEVPA(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    float
     */
    public static function STDEVPA()
    {
        $aArgs = PHPExcel_Calculation_Functions::flattenArrayIndexed(func_get_args());
        $returnValue = NULL;
        $aMean = self::AVERAGEA($aArgs);
        if (!is_null($aMean)) {
            $aCount = 0;
            foreach ($aArgs as $k => $arg) {
                if (!is_bool($arg) || PHPExcel_Calculation_Functions::isMatrixValue($k)) {
                    if (is_numeric($arg) || is_bool($arg) || is_string($arg) & $arg != "") {
                        if (is_bool($arg)) {
                            $arg = (int) $arg;
                        } else {
                            if (is_string($arg)) {
                                $arg = 0;
                            }
                        }
                        if (is_null($returnValue)) {
                            $returnValue = pow($arg - $aMean, 2);
                        } else {
                            $returnValue += pow($arg - $aMean, 2);
                        }
                        $aCount++;
                    }
                }
            }
            if (0 < $aCount && 0 <= $returnValue) {
                return sqrt($returnValue / $aCount);
            }
        }
        return PHPExcel_Calculation_Functions::DIV0();
    }
    /**
     * STEYX
     *
     * Returns the standard error of the predicted y-value for each x in the regression.
     *
     * @param    array of mixed        Data Series Y
     * @param    array of mixed        Data Series X
     * @return    float
     */
    public static function STEYX($yValues, $xValues)
    {
        if (!self::checkTrendArrays($yValues, $xValues)) {
            return PHPExcel_Calculation_Functions::VALUE();
        }
        $yValueCount = count($yValues);
        $xValueCount = count($xValues);
        if ($yValueCount == 0 || $yValueCount != $xValueCount) {
            return PHPExcel_Calculation_Functions::NA();
        }
        if ($yValueCount == 1) {
            return PHPExcel_Calculation_Functions::DIV0();
        }
        $bestFitLinear = trendClass::calculate(trendClass::TREND_LINEAR, $yValues, $xValues);
        return $bestFitLinear->getStdevOfResiduals();
    }
    /**
     * TDIST
     *
     * Returns the probability of Student's T distribution.
     *
     * @param    float        $value            Value for the function
     * @param    float        $degrees        degrees of freedom
     * @param    float        $tails            number of tails (1 or 2)
     * @return    float
     */
    public static function TDIST($value, $degrees, $tails)
    {
        $value = PHPExcel_Calculation_Functions::flattenSingleValue($value);
        $degrees = floor(PHPExcel_Calculation_Functions::flattenSingleValue($degrees));
        $tails = floor(PHPExcel_Calculation_Functions::flattenSingleValue($tails));
        if (is_numeric($value) && is_numeric($degrees) && is_numeric($tails)) {
            if ($value < 0 || $degrees < 1 || $tails < 1 || 2 < $tails) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            $tterm = $degrees;
            $ttheta = atan2($value, sqrt($tterm));
            $tc = cos($ttheta);
            $ts = sin($ttheta);
            $tsum = 0;
            if ($degrees % 2 == 1) {
                $ti = 3;
                $tterm = $tc;
            } else {
                $ti = 2;
                $tterm = 1;
            }
            $tsum = $tterm;
            while ($ti < $degrees) {
                $tterm *= $tc * $tc * ($ti - 1) / $ti;
                $tsum += $tterm;
                $ti += 2;
            }
            $tsum *= $ts;
            if ($degrees % 2 == 1) {
                $tsum = M_2DIVPI * ($tsum + $ttheta);
            }
            $tValue = 0.5 * (1 + $tsum);
            if ($tails == 1) {
                return 1 - abs($tValue);
            }
            return 1 - abs(1 - $tValue - $tValue);
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * TINV
     *
     * Returns the one-tailed probability of the chi-squared distribution.
     *
     * @param    float        $probability    Probability for the function
     * @param    float        $degrees        degrees of freedom
     * @return    float
     */
    public static function TINV($probability, $degrees)
    {
        $probability = PHPExcel_Calculation_Functions::flattenSingleValue($probability);
        $degrees = floor(PHPExcel_Calculation_Functions::flattenSingleValue($degrees));
        if (is_numeric($probability) && is_numeric($degrees)) {
            $xLo = 100;
            $xHi = 0;
            $x = $xNew = 1;
            $dx = 1;
            $i = 0;
            while (PRECISION < abs($dx) && $i++ < MAX_ITERATIONS) {
                $result = self::TDIST($x, $degrees, 2);
                $error = $result - $probability;
                if ($error == 0) {
                    $dx = 0;
                } else {
                    if ($error < 0) {
                        $xLo = $x;
                    } else {
                        $xHi = $x;
                    }
                }
                if ($result != 0) {
                    $dx = $error / $result;
                    $xNew = $x - $dx;
                }
                if ($xNew < $xLo || $xHi < $xNew || $result == 0) {
                    $xNew = ($xLo + $xHi) / 2;
                    $dx = $xNew - $x;
                }
                $x = $xNew;
            }
            if ($i == MAX_ITERATIONS) {
                return PHPExcel_Calculation_Functions::NA();
            }
            return round($x, 12);
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * TREND
     *
     * Returns values along a linear trend
     *
     * @param    array of mixed        Data Series Y
     * @param    array of mixed        Data Series X
     * @param    array of mixed        Values of X for which we want to find Y
     * @param    boolean                A logical value specifying whether to force the intersect to equal 0.
     * @return    array of float
     */
    public static function TREND($yValues, $xValues = array(), $newValues = array(), $const = true)
    {
        $yValues = PHPExcel_Calculation_Functions::flattenArray($yValues);
        $xValues = PHPExcel_Calculation_Functions::flattenArray($xValues);
        $newValues = PHPExcel_Calculation_Functions::flattenArray($newValues);
        $const = is_null($const) ? true : (bool) PHPExcel_Calculation_Functions::flattenSingleValue($const);
        $bestFitLinear = trendClass::calculate(trendClass::TREND_LINEAR, $yValues, $xValues, $const);
        if (empty($newValues)) {
            $newValues = $bestFitLinear->getXValues();
        }
        $returnArray = array();
        foreach ($newValues as $xValue) {
            $returnArray[0][] = $bestFitLinear->getValueOfYForX($xValue);
        }
        return $returnArray;
    }
    /**
     * TRIMMEAN
     *
     * Returns the mean of the interior of a data set. TRIMMEAN calculates the mean
     *        taken by excluding a percentage of data points from the top and bottom tails
     *        of a data set.
     *
     * Excel Function:
     *        TRIMEAN(value1[,value2[, ...]], $discard)
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @param    float        $discard        Percentage to discard
     * @return    float
     */
    public static function TRIMMEAN()
    {
        $aArgs = PHPExcel_Calculation_Functions::flattenArray(func_get_args());
        $percent = array_pop($aArgs);
        if (is_numeric($percent) && !is_string($percent)) {
            if ($percent < 0 || 1 < $percent) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            $mArgs = array();
            foreach ($aArgs as $arg) {
                if (is_numeric($arg) && !is_string($arg)) {
                    $mArgs[] = $arg;
                }
            }
            $discard = floor(self::COUNT($mArgs) * $percent / 2);
            sort($mArgs);
            for ($i = 0; $i < $discard; $i++) {
                array_pop($mArgs);
                array_shift($mArgs);
            }
            return self::AVERAGE($mArgs);
        } else {
            return PHPExcel_Calculation_Functions::VALUE();
        }
    }
    /**
     * VARFunc
     *
     * Estimates variance based on a sample.
     *
     * Excel Function:
     *        VAR(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    float
     */
    public static function VARFunc()
    {
        $returnValue = PHPExcel_Calculation_Functions::DIV0();
        $summerA = $summerB = 0;
        $aArgs = PHPExcel_Calculation_Functions::flattenArray(func_get_args());
        $aCount = 0;
        foreach ($aArgs as $arg) {
            if (is_bool($arg)) {
                $arg = (int) $arg;
            }
            if (is_numeric($arg) && !is_string($arg)) {
                $summerA += $arg * $arg;
                $summerB += $arg;
                $aCount++;
            }
        }
        if (1 < $aCount) {
            $summerA *= $aCount;
            $summerB *= $summerB;
            $returnValue = ($summerA - $summerB) / ($aCount * ($aCount - 1));
        }
        return $returnValue;
    }
    /**
     * VARA
     *
     * Estimates variance based on a sample, including numbers, text, and logical values
     *
     * Excel Function:
     *        VARA(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    float
     */
    public static function VARA()
    {
        $returnValue = PHPExcel_Calculation_Functions::DIV0();
        $summerA = $summerB = 0;
        $aArgs = PHPExcel_Calculation_Functions::flattenArrayIndexed(func_get_args());
        $aCount = 0;
        foreach ($aArgs as $k => $arg) {
            if (is_string($arg) && PHPExcel_Calculation_Functions::isValue($k)) {
                return PHPExcel_Calculation_Functions::VALUE();
            }
            if (!is_string($arg) || PHPExcel_Calculation_Functions::isMatrixValue($k)) {
                if (is_numeric($arg) || is_bool($arg) || is_string($arg) & $arg != "") {
                    if (is_bool($arg)) {
                        $arg = (int) $arg;
                    } else {
                        if (is_string($arg)) {
                            $arg = 0;
                        }
                    }
                    $summerA += $arg * $arg;
                    $summerB += $arg;
                    $aCount++;
                }
            }
        }
        if (1 < $aCount) {
            $summerA *= $aCount;
            $summerB *= $summerB;
            $returnValue = ($summerA - $summerB) / ($aCount * ($aCount - 1));
        }
        return $returnValue;
    }
    /**
     * VARP
     *
     * Calculates variance based on the entire population
     *
     * Excel Function:
     *        VARP(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    float
     */
    public static function VARP()
    {
        $returnValue = PHPExcel_Calculation_Functions::DIV0();
        $summerA = $summerB = 0;
        $aArgs = PHPExcel_Calculation_Functions::flattenArray(func_get_args());
        $aCount = 0;
        foreach ($aArgs as $arg) {
            if (is_bool($arg)) {
                $arg = (int) $arg;
            }
            if (is_numeric($arg) && !is_string($arg)) {
                $summerA += $arg * $arg;
                $summerB += $arg;
                $aCount++;
            }
        }
        if (0 < $aCount) {
            $summerA *= $aCount;
            $summerB *= $summerB;
            $returnValue = ($summerA - $summerB) / ($aCount * $aCount);
        }
        return $returnValue;
    }
    /**
     * VARPA
     *
     * Calculates variance based on the entire population, including numbers, text, and logical values
     *
     * Excel Function:
     *        VARPA(value1[,value2[, ...]])
     *
     * @access    public
     * @category Statistical Functions
     * @param    mixed        $arg,...        Data values
     * @return    float
     */
    public static function VARPA()
    {
        $returnValue = PHPExcel_Calculation_Functions::DIV0();
        $summerA = $summerB = 0;
        $aArgs = PHPExcel_Calculation_Functions::flattenArrayIndexed(func_get_args());
        $aCount = 0;
        foreach ($aArgs as $k => $arg) {
            if (is_string($arg) && PHPExcel_Calculation_Functions::isValue($k)) {
                return PHPExcel_Calculation_Functions::VALUE();
            }
            if (!is_string($arg) || PHPExcel_Calculation_Functions::isMatrixValue($k)) {
                if (is_numeric($arg) || is_bool($arg) || is_string($arg) & $arg != "") {
                    if (is_bool($arg)) {
                        $arg = (int) $arg;
                    } else {
                        if (is_string($arg)) {
                            $arg = 0;
                        }
                    }
                    $summerA += $arg * $arg;
                    $summerB += $arg;
                    $aCount++;
                }
            }
        }
        if (0 < $aCount) {
            $summerA *= $aCount;
            $summerB *= $summerB;
            $returnValue = ($summerA - $summerB) / ($aCount * $aCount);
        }
        return $returnValue;
    }
    /**
     * WEIBULL
     *
     * Returns the Weibull distribution. Use this distribution in reliability
     * analysis, such as calculating a device's mean time to failure.
     *
     * @param    float        $value
     * @param    float        $alpha        Alpha Parameter
     * @param    float        $beta        Beta Parameter
     * @param    boolean        $cumulative
     * @return    float
     *
     */
    public static function WEIBULL($value, $alpha, $beta, $cumulative)
    {
        $value = PHPExcel_Calculation_Functions::flattenSingleValue($value);
        $alpha = PHPExcel_Calculation_Functions::flattenSingleValue($alpha);
        $beta = PHPExcel_Calculation_Functions::flattenSingleValue($beta);
        if (is_numeric($value) && is_numeric($alpha) && is_numeric($beta)) {
            if ($value < 0 || $alpha <= 0 || $beta <= 0) {
                return PHPExcel_Calculation_Functions::NaN();
            }
            if (is_numeric($cumulative) || is_bool($cumulative)) {
                if ($cumulative) {
                    return 1 - exp(0 - pow($value / $beta, $alpha));
                }
                return $alpha / pow($beta, $alpha) * pow($value, $alpha - 1) * exp(0 - pow($value / $beta, $alpha));
            }
        }
        return PHPExcel_Calculation_Functions::VALUE();
    }
    /**
     * ZTEST
     *
     * Returns the Weibull distribution. Use this distribution in reliability
     * analysis, such as calculating a device's mean time to failure.
     *
     * @param    float        $dataSet
     * @param    float        $m0        Alpha Parameter
     * @param    float        $sigma    Beta Parameter
     * @param    boolean        $cumulative
     * @return    float
     *
     */
    public static function ZTEST($dataSet, $m0, $sigma = NULL)
    {
        $dataSet = PHPExcel_Calculation_Functions::flattenArrayIndexed($dataSet);
        $m0 = PHPExcel_Calculation_Functions::flattenSingleValue($m0);
        $sigma = PHPExcel_Calculation_Functions::flattenSingleValue($sigma);
        if (is_null($sigma)) {
            $sigma = self::STDEV($dataSet);
        }
        $n = count($dataSet);
        return 1 - self::NORMSDIST((self::AVERAGE($dataSet) - $m0) / ($sigma / SQRT($n)));
    }
}

?>