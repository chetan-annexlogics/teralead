<?php
/*
 //headerNopDec - //nopH9
 * //nopH2
 * //nopH3
 * //nopH6 02/06/2020
 *
 * //nopH7
 */

if (!defined("PHPEXCEL_ROOT")) {
    define("PHPEXCEL_ROOT", dirname(__FILE__) . "/../../../");
    require PHPEXCEL_ROOT . "PHPExcel/Autoloader.php";
}
class PHPExcel_Shared_JAMA_Matrix
{
    /**
     *    Matrix storage
     *
     *    @var array
     *    @access public
     */
    public $A = array();
    /**
     *    Matrix row dimension
     *
     *    @var int
     *    @access private
     */
    private $m = NULL;
    /**
     *    Matrix column dimension
     *
     *    @var int
     *    @access private
     */
    private $n = NULL;
    const POLYMORPHIC_ARGUMENT_EXCEPTION = "Invalid argument pattern for polymorphic function.";
    const ARGUMENT_TYPE_EXCEPTION = "Invalid argument type.";
    const ARGUMENT_BOUNDS_EXCEPTION = "Invalid argument range.";
    const MATRIX_DIMENSION_EXCEPTION = "Matrix dimensions are not equal.";
    const ARRAY_LENGTH_EXCEPTION = "Array length must be a multiple of m.";
    /**
     *    Polymorphic constructor
     *
     *    As PHP has no support for polymorphic constructors, we hack our own sort of polymorphism using func_num_args, func_get_arg, and gettype. In essence, we're just implementing a simple RTTI filter and calling the appropriate constructor.
     */
    public function __construct()
    {
        if (0 < func_num_args()) {
            $args = func_get_args();
            $match = implode(",", array_map("gettype", $args));
            switch ($match) {
                case "array":
                    $this->m = count($args[0]);
                    $this->n = count($args[0][0]);
                    $this->A = $args[0];
                    break;
                case "integer":
                    $this->m = $args[0];
                    $this->n = $args[0];
                    $this->A = array_fill(0, $this->m, array_fill(0, $this->n, 0));
                    break;
                case "integer,integer":
                    list($this->m, $this->n) = $args;
                    $this->A = array_fill(0, $this->m, array_fill(0, $this->n, 0));
                    break;
                case "array,integer":
                    $this->m = $args[1];
                    if ($this->m != 0) {
                        $this->n = count($args[0]) / $this->m;
                    } else {
                        $this->n = 0;
                    }
                    if ($this->m * $this->n == count($args[0])) {
                        for ($i = 0; $i < $this->m; $i++) {
                            for ($j = 0; $j < $this->n; $j++) {
                                $this->A[$i][$j] = $args[0][$i + $j * $this->m];
                            }
                        }
                        break;
                    }
                    throw new PHPExcel_Calculation_Exception(self::ARRAY_LENGTH_EXCEPTION);
                default:
                    throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
            }
        } else {
            throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
        }
    }
    /**
     *    getArray
     *
     *    @return array Matrix array
     */
    public function getArray()
    {
        return $this->A;
    }
    /**
     *    getRowDimension
     *
     *    @return int Row dimension
     */
    public function getRowDimension()
    {
        return $this->m;
    }
    /**
     *    getColumnDimension
     *
     *    @return int Column dimension
     */
    public function getColumnDimension()
    {
        return $this->n;
    }
    /**
     *    get
     *
     *    Get the i,j-th element of the matrix.
     *    @param int $i Row position
     *    @param int $j Column position
     *    @return mixed Element (int/float/double)
     */
    public function get($i = NULL, $j = NULL)
    {
        return $this->A[$i][$j];
    }
    /**
     *    getMatrix
     *
     *    Get a submatrix
     *    @param int $i0 Initial row index
     *    @param int $iF Final row index
     *    @param int $j0 Initial column index
     *    @param int $jF Final column index
     *    @return Matrix Submatrix
     */
    public function getMatrix()
    {
        if (0 < func_num_args()) {
            $args = func_get_args();
            $match = implode(",", array_map("gettype", $args));
            switch ($match) {
                case "integer,integer":
                    list($i0, $j0) = $args;
                    if (0 <= $i0) {
                        $m = $this->m - $i0;
                        if (0 <= $j0) {
                            $n = $this->n - $j0;
                            $R = new PHPExcel_Shared_JAMA_Matrix($m, $n);
                            for ($i = $i0; $i < $this->m; $i++) {
                                for ($j = $j0; $j < $this->n; $j++) {
                                    $R->set($i, $j, $this->A[$i][$j]);
                                }
                            }
                            return $R;
                        }
                        throw new PHPExcel_Calculation_Exception(self::ARGUMENT_BOUNDS_EXCEPTION);
                    }
                    throw new PHPExcel_Calculation_Exception(self::ARGUMENT_BOUNDS_EXCEPTION);
                case "integer,integer,integer,integer":
                    list($i0, $iF, $j0, $jF) = $args;
                    if ($i0 < $iF && $iF <= $this->m && 0 <= $i0) {
                        $m = $iF - $i0;
                        if ($j0 < $jF && $jF <= $this->n && 0 <= $j0) {
                            $n = $jF - $j0;
                            $R = new PHPExcel_Shared_JAMA_Matrix($m + 1, $n + 1);
                            for ($i = $i0; $i <= $iF; $i++) {
                                for ($j = $j0; $j <= $jF; $j++) {
                                    $R->set($i - $i0, $j - $j0, $this->A[$i][$j]);
                                }
                            }
                            return $R;
                        }
                        throw new PHPExcel_Calculation_Exception(self::ARGUMENT_BOUNDS_EXCEPTION);
                    }
                    throw new PHPExcel_Calculation_Exception(self::ARGUMENT_BOUNDS_EXCEPTION);
                case "array,array":
                    list($RL, $CL) = $args;
                    if (0 < count($RL)) {
                        $m = count($RL);
                        if (0 < count($CL)) {
                            $n = count($CL);
                            $R = new PHPExcel_Shared_JAMA_Matrix($m, $n);
                            for ($i = 0; $i < $m; $i++) {
                                for ($j = 0; $j < $n; $j++) {
                                    $R->set($i - $i0, $j - $j0, $this->A[$RL[$i]][$CL[$j]]);
                                }
                            }
                            return $R;
                        }
                        throw new PHPExcel_Calculation_Exception(self::ARGUMENT_BOUNDS_EXCEPTION);
                    }
                    throw new PHPExcel_Calculation_Exception(self::ARGUMENT_BOUNDS_EXCEPTION);
                case "array,array":
                    list($RL, $CL) = $args;
                    if (0 < count($RL)) {
                        $m = count($RL);
                        if (0 < count($CL)) {
                            $n = count($CL);
                            $R = new PHPExcel_Shared_JAMA_Matrix($m, $n);
                            for ($i = 0; $i < $m; $i++) {
                                for ($j = 0; $j < $n; $j++) {
                                    $R->set($i, $j, $this->A[$RL[$i]][$CL[$j]]);
                                }
                            }
                            return $R;
                        }
                        throw new PHPExcel_Calculation_Exception(self::ARGUMENT_BOUNDS_EXCEPTION);
                    }
                    throw new PHPExcel_Calculation_Exception(self::ARGUMENT_BOUNDS_EXCEPTION);
                case "integer,integer,array":
                    list($i0, $iF, $CL) = $args;
                    if ($i0 < $iF && $iF <= $this->m && 0 <= $i0) {
                        $m = $iF - $i0;
                        if (0 < count($CL)) {
                            $n = count($CL);
                            $R = new PHPExcel_Shared_JAMA_Matrix($m, $n);
                            for ($i = $i0; $i < $iF; $i++) {
                                for ($j = 0; $j < $n; $j++) {
                                    $R->set($i - $i0, $j, $this->A[$RL[$i]][$j]);
                                }
                            }
                            return $R;
                        }
                        throw new PHPExcel_Calculation_Exception(self::ARGUMENT_BOUNDS_EXCEPTION);
                    }
                    throw new PHPExcel_Calculation_Exception(self::ARGUMENT_BOUNDS_EXCEPTION);
                case "array,integer,integer":
                    list($RL, $j0, $jF) = $args;
                    if (0 < count($RL)) {
                        $m = count($RL);
                        if ($j0 <= $jF && $jF <= $this->n && 0 <= $j0) {
                            $n = $jF - $j0;
                            $R = new PHPExcel_Shared_JAMA_Matrix($m, $n + 1);
                            for ($i = 0; $i < $m; $i++) {
                                for ($j = $j0; $j <= $jF; $j++) {
                                    $R->set($i, $j - $j0, $this->A[$RL[$i]][$j]);
                                }
                            }
                            return $R;
                        }
                        throw new PHPExcel_Calculation_Exception(self::ARGUMENT_BOUNDS_EXCEPTION);
                    }
                    throw new PHPExcel_Calculation_Exception(self::ARGUMENT_BOUNDS_EXCEPTION);
                default:
                    throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
            }
        } else {
            throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
        }
    }
    /**
     *    checkMatrixDimensions
     *
     *    Is matrix B the same size?
     *    @param Matrix $B Matrix B
     *    @return boolean
     */
    public function checkMatrixDimensions($B = NULL)
    {
        if ($B instanceof PHPExcel_Shared_JAMA_Matrix) {
            if ($this->m == $B->getRowDimension() && $this->n == $B->getColumnDimension()) {
                return true;
            }
            throw new PHPExcel_Calculation_Exception(self::MATRIX_DIMENSION_EXCEPTION);
        }
        throw new PHPExcel_Calculation_Exception(self::ARGUMENT_TYPE_EXCEPTION);
    }
    /**
     *    set
     *
     *    Set the i,j-th element of the matrix.
     *    @param int $i Row position
     *    @param int $j Column position
     *    @param mixed $c Int/float/double value
     *    @return mixed Element (int/float/double)
     */
    public function set($i = NULL, $j = NULL, $c = NULL)
    {
        $this->A[$i][$j] = $c;
    }
    /**
     *    identity
     *
     *    Generate an identity matrix.
     *    @param int $m Row dimension
     *    @param int $n Column dimension
     *    @return Matrix Identity matrix
     */
    public function identity($m = NULL, $n = NULL)
    {
        return $this->diagonal($m, $n, 1);
    }
    /**
     *    diagonal
     *
     *    Generate a diagonal matrix
     *    @param int $m Row dimension
     *    @param int $n Column dimension
     *    @param mixed $c Diagonal value
     *    @return Matrix Diagonal matrix
     */
    public function diagonal($m = NULL, $n = NULL, $c = 1)
    {
        $R = new PHPExcel_Shared_JAMA_Matrix($m, $n);
        for ($i = 0; $i < $m; $i++) {
            $R->set($i, $i, $c);
        }
        return $R;
    }
    /**
     *    getMatrixByRow
     *
     *    Get a submatrix by row index/range
     *    @param int $i0 Initial row index
     *    @param int $iF Final row index
     *    @return Matrix Submatrix
     */
    public function getMatrixByRow($i0 = NULL, $iF = NULL)
    {
        if (is_int($i0)) {
            if (is_int($iF)) {
                return $this->getMatrix($i0, 0, $iF + 1, $this->n);
            }
            return $this->getMatrix($i0, 0, $i0 + 1, $this->n);
        }
        throw new PHPExcel_Calculation_Exception(self::ARGUMENT_TYPE_EXCEPTION);
    }
    /**
     *    getMatrixByCol
     *
     *    Get a submatrix by column index/range
     *    @param int $i0 Initial column index
     *    @param int $iF Final column index
     *    @return Matrix Submatrix
     */
    public function getMatrixByCol($j0 = NULL, $jF = NULL)
    {
        if (is_int($j0)) {
            if (is_int($jF)) {
                return $this->getMatrix(0, $j0, $this->m, $jF + 1);
            }
            return $this->getMatrix(0, $j0, $this->m, $j0 + 1);
        }
        throw new PHPExcel_Calculation_Exception(self::ARGUMENT_TYPE_EXCEPTION);
    }
    /**
     *    transpose
     *
     *    Tranpose matrix
     *    @return Matrix Transposed matrix
     */
    public function transpose()
    {
        $R = new PHPExcel_Shared_JAMA_Matrix($this->n, $this->m);
        for ($i = 0; $i < $this->m; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                $R->set($j, $i, $this->A[$i][$j]);
            }
        }
        return $R;
    }
    /**
     *    trace
     *
     *    Sum of diagonal elements
     *    @return float Sum of diagonal elements
     */
    public function trace()
    {
        $s = 0;
        $n = min($this->m, $this->n);
        for ($i = 0; $i < $n; $i++) {
            $s += $this->A[$i][$i];
        }
        return $s;
    }
    /**
     *    uminus
     *
     *    Unary minus matrix -A
     *    @return Matrix Unary minus matrix
     */
    public function uminus()
    {
    }
    /**
     *    plus
     *
     *    A + B
     *    @param mixed $B Matrix/Array
     *    @return Matrix Sum
     */
    public function plus()
    {
        if (0 < func_num_args()) {
            $args = func_get_args();
            $match = implode(",", array_map("gettype", $args));
            switch ($match) {
                case "object":
                    if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
                        $M = $args[0];
                        break;
                    }
                    throw new PHPExcel_Calculation_Exception(self::ARGUMENT_TYPE_EXCEPTION);
                case "array":
                    $M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
                    break;
                default:
                    throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
            }
            $this->checkMatrixDimensions($M);
            for ($i = 0; $i < $this->m; $i++) {
                for ($j = 0; $j < $this->n; $j++) {
                    $M->set($i, $j, $M->get($i, $j) + $this->A[$i][$j]);
                }
            }
            return $M;
        }
        throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
    }
    /**
     *    plusEquals
     *
     *    A = A + B
     *    @param mixed $B Matrix/Array
     *    @return Matrix Sum
     */
    public function plusEquals()
    {
        if (0 < func_num_args()) {
            $args = func_get_args();
            $match = implode(",", array_map("gettype", $args));
            switch ($match) {
                case "object":
                    if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
                        $M = $args[0];
                        break;
                    }
                    throw new PHPExcel_Calculation_Exception(self::ARGUMENT_TYPE_EXCEPTION);
                case "array":
                    $M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
                    break;
                default:
                    throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
            }
            $this->checkMatrixDimensions($M);
            for ($i = 0; $i < $this->m; $i++) {
                for ($j = 0; $j < $this->n; $j++) {
                    $validValues = true;
                    $value = $M->get($i, $j);
                    if (is_string($this->A[$i][$j]) && 0 < strlen($this->A[$i][$j]) && !is_numeric($this->A[$i][$j])) {
                        $this->A[$i][$j] = trim($this->A[$i][$j], "\"");
                        $validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($this->A[$i][$j]);
                    }
                    if (is_string($value) && 0 < strlen($value) && !is_numeric($value)) {
                        $value = trim($value, "\"");
                        $validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($value);
                    }
                    if ($validValues) {
                        $this->A[$i][$j] += $value;
                    } else {
                        $this->A[$i][$j] = PHPExcel_Calculation_Functions::NaN();
                    }
                }
            }
            return $this;
        }
        throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
    }
    /**
     *    minus
     *
     *    A - B
     *    @param mixed $B Matrix/Array
     *    @return Matrix Sum
     */
    public function minus()
    {
        if (0 < func_num_args()) {
            $args = func_get_args();
            $match = implode(",", array_map("gettype", $args));
            switch ($match) {
                case "object":
                    if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
                        $M = $args[0];
                        break;
                    }
                    throw new PHPExcel_Calculation_Exception(self::ARGUMENT_TYPE_EXCEPTION);
                case "array":
                    $M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
                    break;
                default:
                    throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
            }
            $this->checkMatrixDimensions($M);
            for ($i = 0; $i < $this->m; $i++) {
                for ($j = 0; $j < $this->n; $j++) {
                    $M->set($i, $j, $M->get($i, $j) - $this->A[$i][$j]);
                }
            }
            return $M;
        }
        throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
    }
    /**
     *    minusEquals
     *
     *    A = A - B
     *    @param mixed $B Matrix/Array
     *    @return Matrix Sum
     */
    public function minusEquals()
    {
        if (0 < func_num_args()) {
            $args = func_get_args();
            $match = implode(",", array_map("gettype", $args));
            switch ($match) {
                case "object":
                    if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
                        $M = $args[0];
                        break;
                    }
                    throw new PHPExcel_Calculation_Exception(self::ARGUMENT_TYPE_EXCEPTION);
                case "array":
                    $M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
                    break;
                default:
                    throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
            }
            $this->checkMatrixDimensions($M);
            for ($i = 0; $i < $this->m; $i++) {
                for ($j = 0; $j < $this->n; $j++) {
                    $validValues = true;
                    $value = $M->get($i, $j);
                    if (is_string($this->A[$i][$j]) && 0 < strlen($this->A[$i][$j]) && !is_numeric($this->A[$i][$j])) {
                        $this->A[$i][$j] = trim($this->A[$i][$j], "\"");
                        $validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($this->A[$i][$j]);
                    }
                    if (is_string($value) && 0 < strlen($value) && !is_numeric($value)) {
                        $value = trim($value, "\"");
                        $validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($value);
                    }
                    if ($validValues) {
                        $this->A[$i][$j] -= $value;
                    } else {
                        $this->A[$i][$j] = PHPExcel_Calculation_Functions::NaN();
                    }
                }
            }
            return $this;
        }
        throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
    }
    /**
     *    arrayTimes
     *
     *    Element-by-element multiplication
     *    Cij = Aij * Bij
     *    @param mixed $B Matrix/Array
     *    @return Matrix Matrix Cij
     */
    public function arrayTimes()
    {
        if (0 < func_num_args()) {
            $args = func_get_args();
            $match = implode(",", array_map("gettype", $args));
            switch ($match) {
                case "object":
                    if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
                        $M = $args[0];
                        break;
                    }
                    throw new PHPExcel_Calculation_Exception(self::ARGUMENT_TYPE_EXCEPTION);
                case "array":
                    $M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
                    break;
                default:
                    throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
            }
            $this->checkMatrixDimensions($M);
            for ($i = 0; $i < $this->m; $i++) {
                for ($j = 0; $j < $this->n; $j++) {
                    $M->set($i, $j, $M->get($i, $j) * $this->A[$i][$j]);
                }
            }
            return $M;
        }
        throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
    }
    /**
     *    arrayTimesEquals
     *
     *    Element-by-element multiplication
     *    Aij = Aij * Bij
     *    @param mixed $B Matrix/Array
     *    @return Matrix Matrix Aij
     */
    public function arrayTimesEquals()
    {
        if (0 < func_num_args()) {
            $args = func_get_args();
            $match = implode(",", array_map("gettype", $args));
            switch ($match) {
                case "object":
                    if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
                        $M = $args[0];
                        break;
                    }
                    throw new PHPExcel_Calculation_Exception(self::ARGUMENT_TYPE_EXCEPTION);
                case "array":
                    $M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
                    break;
                default:
                    throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
            }
            $this->checkMatrixDimensions($M);
            for ($i = 0; $i < $this->m; $i++) {
                for ($j = 0; $j < $this->n; $j++) {
                    $validValues = true;
                    $value = $M->get($i, $j);
                    if (is_string($this->A[$i][$j]) && 0 < strlen($this->A[$i][$j]) && !is_numeric($this->A[$i][$j])) {
                        $this->A[$i][$j] = trim($this->A[$i][$j], "\"");
                        $validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($this->A[$i][$j]);
                    }
                    if (is_string($value) && 0 < strlen($value) && !is_numeric($value)) {
                        $value = trim($value, "\"");
                        $validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($value);
                    }
                    if ($validValues) {
                        $this->A[$i][$j] *= $value;
                    } else {
                        $this->A[$i][$j] = PHPExcel_Calculation_Functions::NaN();
                    }
                }
            }
            return $this;
        }
        throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
    }
    /**
     *    arrayRightDivide
     *
     *    Element-by-element right division
     *    A / B
     *    @param Matrix $B Matrix B
     *    @return Matrix Division result
     */
    public function arrayRightDivide()
    {
        if (0 < func_num_args()) {
            $args = func_get_args();
            $match = implode(",", array_map("gettype", $args));
            switch ($match) {
                case "object":
                    if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
                        $M = $args[0];
                        break;
                    }
                    throw new PHPExcel_Calculation_Exception(self::ARGUMENT_TYPE_EXCEPTION);
                case "array":
                    $M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
                    break;
                default:
                    throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
            }
            $this->checkMatrixDimensions($M);
            for ($i = 0; $i < $this->m; $i++) {
                for ($j = 0; $j < $this->n; $j++) {
                    $validValues = true;
                    $value = $M->get($i, $j);
                    if (is_string($this->A[$i][$j]) && 0 < strlen($this->A[$i][$j]) && !is_numeric($this->A[$i][$j])) {
                        $this->A[$i][$j] = trim($this->A[$i][$j], "\"");
                        $validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($this->A[$i][$j]);
                    }
                    if (is_string($value) && 0 < strlen($value) && !is_numeric($value)) {
                        $value = trim($value, "\"");
                        $validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($value);
                    }
                    if ($validValues) {
                        if ($value == 0) {
                            $M->set($i, $j, "#DIV/0!");
                        } else {
                            $M->set($i, $j, $this->A[$i][$j] / $value);
                        }
                    } else {
                        $M->set($i, $j, PHPExcel_Calculation_Functions::NaN());
                    }
                }
            }
            return $M;
        }
        throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
    }
    /**
     *    arrayRightDivideEquals
     *
     *    Element-by-element right division
     *    Aij = Aij / Bij
     *    @param mixed $B Matrix/Array
     *    @return Matrix Matrix Aij
     */
    public function arrayRightDivideEquals()
    {
        if (0 < func_num_args()) {
            $args = func_get_args();
            $match = implode(",", array_map("gettype", $args));
            switch ($match) {
                case "object":
                    if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
                        $M = $args[0];
                        break;
                    }
                    throw new PHPExcel_Calculation_Exception(self::ARGUMENT_TYPE_EXCEPTION);
                case "array":
                    $M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
                    break;
                default:
                    throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
            }
            $this->checkMatrixDimensions($M);
            for ($i = 0; $i < $this->m; $i++) {
                for ($j = 0; $j < $this->n; $j++) {
                    $this->A[$i][$j] = $this->A[$i][$j] / $M->get($i, $j);
                }
            }
            return $M;
        }
        throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
    }
    /**
     *    arrayLeftDivide
     *
     *    Element-by-element Left division
     *    A / B
     *    @param Matrix $B Matrix B
     *    @return Matrix Division result
     */
    public function arrayLeftDivide()
    {
        if (0 < func_num_args()) {
            $args = func_get_args();
            $match = implode(",", array_map("gettype", $args));
            switch ($match) {
                case "object":
                    if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
                        $M = $args[0];
                        break;
                    }
                    throw new PHPExcel_Calculation_Exception(self::ARGUMENT_TYPE_EXCEPTION);
                case "array":
                    $M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
                    break;
                default:
                    throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
            }
            $this->checkMatrixDimensions($M);
            for ($i = 0; $i < $this->m; $i++) {
                for ($j = 0; $j < $this->n; $j++) {
                    $M->set($i, $j, $M->get($i, $j) / $this->A[$i][$j]);
                }
            }
            return $M;
        }
        throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
    }
    /**
     *    arrayLeftDivideEquals
     *
     *    Element-by-element Left division
     *    Aij = Aij / Bij
     *    @param mixed $B Matrix/Array
     *    @return Matrix Matrix Aij
     */
    public function arrayLeftDivideEquals()
    {
        if (0 < func_num_args()) {
            $args = func_get_args();
            $match = implode(",", array_map("gettype", $args));
            switch ($match) {
                case "object":
                    if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
                        $M = $args[0];
                        break;
                    }
                    throw new PHPExcel_Calculation_Exception(self::ARGUMENT_TYPE_EXCEPTION);
                case "array":
                    $M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
                    break;
                default:
                    throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
            }
            $this->checkMatrixDimensions($M);
            for ($i = 0; $i < $this->m; $i++) {
                for ($j = 0; $j < $this->n; $j++) {
                    $this->A[$i][$j] = $M->get($i, $j) / $this->A[$i][$j];
                }
            }
            return $M;
        }
        throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
    }
    /**
     *    times
     *
     *    Matrix multiplication
     *    @param mixed $n Matrix/Array/Scalar
     *    @return Matrix Product
     */
    public function times()
    {
        if (0 < func_num_args()) {
            $args = func_get_args();
            $match = implode(",", array_map("gettype", $args));
            switch ($match) {
                case "object":
                    if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
                        $B = $args[0];
                        if ($this->n == $B->m) {
                            $C = new PHPExcel_Shared_JAMA_Matrix($this->m, $B->n);
                            for ($j = 0; $j < $B->n; $j++) {
                                for ($k = 0; $k < $this->n; $k++) {
                                    $Bcolj[$k] = $B->A[$k][$j];
                                }
                                for ($i = 0; $i < $this->m; $i++) {
                                    $Arowi = $this->A[$i];
                                    $s = 0;
                                    for ($k = 0; $k < $this->n; $k++) {
                                        $s += $Arowi[$k] * $Bcolj[$k];
                                    }
                                    $C->A[$i][$j] = $s;
                                }
                            }
                            return $C;
                        }
                        throw new PHPExcel_Calculation_Exception(JAMAError(MatrixDimensionMismatch));
                    }
                    throw new PHPExcel_Calculation_Exception(self::ARGUMENT_TYPE_EXCEPTION);
                case "array":
                    $B = new PHPExcel_Shared_JAMA_Matrix($args[0]);
                    if ($this->n == $B->m) {
                        $C = new PHPExcel_Shared_JAMA_Matrix($this->m, $B->n);
                        for ($i = 0; $i < $C->m; $i++) {
                            for ($j = 0; $j < $C->n; $j++) {
                                $s = "0";
                                for ($k = 0; $k < $C->n; $k++) {
                                    $s += $this->A[$i][$k] * $B->A[$k][$j];
                                }
                                $C->A[$i][$j] = $s;
                            }
                        }
                        return $C;
                    }
                    throw new PHPExcel_Calculation_Exception(JAMAError(MatrixDimensionMismatch));
                case "integer":
                    $C = new PHPExcel_Shared_JAMA_Matrix($this->A);
                    for ($i = 0; $i < $C->m; $i++) {
                        for ($j = 0; $j < $C->n; $j++) {
                            $C->A[$i][$j] *= $args[0];
                        }
                    }
                    return $C;
                case "double":
                    $C = new PHPExcel_Shared_JAMA_Matrix($this->m, $this->n);
                    for ($i = 0; $i < $C->m; $i++) {
                        for ($j = 0; $j < $C->n; $j++) {
                            $C->A[$i][$j] = $args[0] * $this->A[$i][$j];
                        }
                    }
                    return $C;
                case "float":
                    $C = new PHPExcel_Shared_JAMA_Matrix($this->A);
                    for ($i = 0; $i < $C->m; $i++) {
                        for ($j = 0; $j < $C->n; $j++) {
                            $C->A[$i][$j] *= $args[0];
                        }
                    }
                    return $C;
                default:
                    throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
            }
        } else {
            throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
        }
    }
    /**
     *    power
     *
     *    A = A ^ B
     *    @param mixed $B Matrix/Array
     *    @return Matrix Sum
     */
    public function power()
    {
        if (0 < func_num_args()) {
            $args = func_get_args();
            $match = implode(",", array_map("gettype", $args));
            switch ($match) {
                case "object":
                    if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
                        $M = $args[0];
                        break;
                    }
                    throw new PHPExcel_Calculation_Exception(self::ARGUMENT_TYPE_EXCEPTION);
                case "array":
                    $M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
                    break;
                default:
                    throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
            }
            $this->checkMatrixDimensions($M);
            for ($i = 0; $i < $this->m; $i++) {
                for ($j = 0; $j < $this->n; $j++) {
                    $validValues = true;
                    $value = $M->get($i, $j);
                    if (is_string($this->A[$i][$j]) && 0 < strlen($this->A[$i][$j]) && !is_numeric($this->A[$i][$j])) {
                        $this->A[$i][$j] = trim($this->A[$i][$j], "\"");
                        $validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($this->A[$i][$j]);
                    }
                    if (is_string($value) && 0 < strlen($value) && !is_numeric($value)) {
                        $value = trim($value, "\"");
                        $validValues &= PHPExcel_Shared_String::convertToNumberIfFraction($value);
                    }
                    if ($validValues) {
                        $this->A[$i][$j] = pow($this->A[$i][$j], $value);
                    } else {
                        $this->A[$i][$j] = PHPExcel_Calculation_Functions::NaN();
                    }
                }
            }
            return $this;
        }
        throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
    }
    /**
     *    concat
     *
     *    A = A & B
     *    @param mixed $B Matrix/Array
     *    @return Matrix Sum
     */
    public function concat()
    {
        if (0 < func_num_args()) {
            $args = func_get_args();
            $match = implode(",", array_map("gettype", $args));
            switch ($match) {
                case "object":
                    if ($args[0] instanceof PHPExcel_Shared_JAMA_Matrix) {
                        $M = $args[0];
                    } else {
                        throw new PHPExcel_Calculation_Exception(self::ARGUMENT_TYPE_EXCEPTION);
                    }
                case "array":
                    $M = new PHPExcel_Shared_JAMA_Matrix($args[0]);
                    break;
                default:
                    throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
            }
            $this->checkMatrixDimensions($M);
            for ($i = 0; $i < $this->m; $i++) {
                for ($j = 0; $j < $this->n; $j++) {
                    $this->A[$i][$j] = trim($this->A[$i][$j], "\"") . trim($M->get($i, $j), "\"");
                }
            }
            return $this;
        }
        throw new PHPExcel_Calculation_Exception(self::POLYMORPHIC_ARGUMENT_EXCEPTION);
    }
    /**
     *    Solve A*X = B.
     *
     *    @param Matrix $B Right hand side
     *    @return Matrix ... Solution if A is square, least squares solution otherwise
     */
    public function solve($B)
    {
        if ($this->m == $this->n) {
            $LU = new PHPExcel_Shared_JAMA_LUDecomposition($this);
            return $LU->solve($B);
        }
        $QR = new PHPExcel_Shared_JAMA_QRDecomposition($this);
        return $QR->solve($B);
    }
    /**
     *    Matrix inverse or pseudoinverse.
     *
     *    @return Matrix ... Inverse(A) if A is square, pseudoinverse otherwise.
     */
    public function inverse()
    {
        return $this->solve($this->identity($this->m, $this->m));
    }
    /**
     *    det
     *
     *    Calculate determinant
     *    @return float Determinant
     */
    public function det()
    {
        $L = new PHPExcel_Shared_JAMA_LUDecomposition($this);
        return $L->det();
    }
}

?>