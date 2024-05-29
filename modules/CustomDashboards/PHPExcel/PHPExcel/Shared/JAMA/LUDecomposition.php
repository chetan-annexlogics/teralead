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
 *    For an m-by-n matrix A with m >= n, the LU decomposition is an m-by-n
 *    unit lower triangular matrix L, an n-by-n upper triangular matrix U,
 *    and a permutation vector piv of length m so that A(piv,:) = L*U.
 *    If m < n, then L is m-by-m and U is m-by-n.
 *
 *    The LU decompostion with pivoting always exists, even if the matrix is
 *    singular, so the constructor will never fail. The primary use of the
 *    LU decomposition is in the solution of square systems of simultaneous
 *    linear equations. This will fail if isNonsingular() returns false.
 *
 *    @author Paul Meagher
 *    @author Bartosz Matosiuk
 *    @author Michael Bommarito
 *    @version 1.1
 *    @license PHP v3.0
 */
class PHPExcel_Shared_JAMA_LUDecomposition
{
    /**
     *    Decomposition storage
     *    @var array
     */
    private $LU = array();
    /**
     *    Row dimension.
     *    @var int
     */
    private $m = NULL;
    /**
     *    Column dimension.
     *    @var int
     */
    private $n = NULL;
    /**
     *    Pivot sign.
     *    @var int
     */
    private $pivsign = NULL;
    /**
     *    Internal storage of pivot vector.
     *    @var array
     */
    private $piv = array();
    const MATRIX_SINGULAR_EXCEPTION = "Can only perform operation on singular matrix.";
    const MATRIX_SQUARE_EXCEPTION = "Mismatched Row dimension";
    /**
     *    LU Decomposition constructor.
     *
     *    @param $A Rectangular matrix
     *    @return Structure to access L, U and piv.
     */
    public function __construct($A)
    {
        if ($A instanceof PHPExcel_Shared_JAMA_Matrix) {
            $this->LU = $A->getArray();
            $this->m = $A->getRowDimension();
            $this->n = $A->getColumnDimension();
            for ($i = 0; $i < $this->m; $i++) {
                $this->piv[$i] = $i;
            }
            $this->pivsign = 1;
            $LUrowi = $LUcolj = array();
            for ($j = 0; $j < $this->n; $j++) {
                for ($i = 0; $i < $this->m; $i++) {
                    $LUcolj[$i] =& $this->LU[$i][$j];
                }
                for ($i = 0; $i < $this->m; $i++) {
                    $LUrowi = $this->LU[$i];
                    $kmax = min($i, $j);
                    $s = 0;
                    for ($k = 0; $k < $kmax; $k++) {
                        $s += $LUrowi[$k] * $LUcolj[$k];
                    }
                    $LUrowi[$j] = $LUcolj[$i] -= $s;
                }
                $p = $j;
                for ($i = $j + 1; $i < $this->m; $i++) {
                    if (abs($LUcolj[$p]) < abs($LUcolj[$i])) {
                        $p = $i;
                    }
                }
                if ($p != $j) {
                    for ($k = 0; $k < $this->n; $k++) {
                        $t = $this->LU[$p][$k];
                        $this->LU[$p][$k] = $this->LU[$j][$k];
                        $this->LU[$j][$k] = $t;
                    }
                    $k = $this->piv[$p];
                    $this->piv[$p] = $this->piv[$j];
                    $this->piv[$j] = $k;
                    $this->pivsign = $this->pivsign * -1;
                }
                if ($j < $this->m && $this->LU[$j][$j] != 0) {
                    for ($i = $j + 1; $i < $this->m; $i++) {
                        $this->LU[$i][$j] /= $this->LU[$j][$j];
                    }
                }
            }
        } else {
            throw new PHPExcel_Calculation_Exception(PHPExcel_Shared_JAMA_Matrix::ARGUMENT_TYPE_EXCEPTION);
        }
    }
    /**
     *    Get lower triangular factor.
     *
     *    @return array Lower triangular factor
     */
    public function getL()
    {
        for ($i = 0; $i < $this->m; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                if ($j < $i) {
                    $L[$i][$j] = $this->LU[$i][$j];
                } else {
                    if ($i == $j) {
                        $L[$i][$j] = 1;
                    } else {
                        $L[$i][$j] = 0;
                    }
                }
            }
        }
        return new PHPExcel_Shared_JAMA_Matrix($L);
    }
    /**
     *    Get upper triangular factor.
     *
     *    @return array Upper triangular factor
     */
    public function getU()
    {
        for ($i = 0; $i < $this->n; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                if ($i <= $j) {
                    $U[$i][$j] = $this->LU[$i][$j];
                } else {
                    $U[$i][$j] = 0;
                }
            }
        }
        return new PHPExcel_Shared_JAMA_Matrix($U);
    }
    /**
     *    Return pivot permutation vector.
     *
     *    @return array Pivot vector
     */
    public function getPivot()
    {
        return $this->piv;
    }
    /**
     *    Alias for getPivot
     *
     *    @see getPivot
     */
    public function getDoublePivot()
    {
        return $this->getPivot();
    }
    /**
     *    Is the matrix nonsingular?
     *
     *    @return true if U, and hence A, is nonsingular.
     */
    public function isNonsingular()
    {
        for ($j = 0; $j < $this->n; $j++) {
            if ($this->LU[$j][$j] == 0) {
                return false;
            }
        }
        return true;
    }
    /**
     *    Count determinants
     *
     *    @return array d matrix deterninat
     */
    public function det()
    {
        if ($this->m == $this->n) {
            $d = $this->pivsign;
            for ($j = 0; $j < $this->n; $j++) {
                $d *= $this->LU[$j][$j];
            }
            return $d;
        }
        throw new PHPExcel_Calculation_Exception(PHPExcel_Shared_JAMA_Matrix::MATRIX_DIMENSION_EXCEPTION);
    }
    /**
     *    Solve A*X = B
     *
     *    @param  $B  A Matrix with as many rows as A and any number of columns.
     *    @return  X so that L*U*X = B(piv,:)
     *    @PHPExcel_Calculation_Exception  IllegalArgumentException Matrix row dimensions must agree.
     *    @PHPExcel_Calculation_Exception  RuntimeException  Matrix is singular.
     */
    public function solve($B)
    {
        if ($B->getRowDimension() == $this->m) {
            if ($this->isNonsingular()) {
                $nx = $B->getColumnDimension();
                $X = $B->getMatrix($this->piv, 0, $nx - 1);
                for ($k = 0; $k < $this->n; $k++) {
                    for ($i = $k + 1; $i < $this->n; $i++) {
                        for ($j = 0; $j < $nx; $j++) {
                            $X->A[$i][$j] -= $X->A[$k][$j] * $this->LU[$i][$k];
                        }
                    }
                }
                for ($k = $this->n - 1; 0 <= $k; $k--) {
                    for ($j = 0; $j < $nx; $j++) {
                        $X->A[$k][$j] /= $this->LU[$k][$k];
                    }
                    for ($i = 0; $i < $k; $i++) {
                        for ($j = 0; $j < $nx; $j++) {
                            $X->A[$i][$j] -= $X->A[$k][$j] * $this->LU[$i][$k];
                        }
                    }
                }
                return $X;
            }
            throw new PHPExcel_Calculation_Exception(self::MATRIX_SINGULAR_EXCEPTION);
        }
        throw new PHPExcel_Calculation_Exception(self::MATRIX_SQUARE_EXCEPTION);
    }
}

?>