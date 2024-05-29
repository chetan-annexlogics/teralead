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
 *    For an m-by-n matrix A with m >= n, the QR decomposition is an m-by-n
 *    orthogonal matrix Q and an n-by-n upper triangular matrix R so that
 *    A = Q*R.
 *
 *    The QR decompostion always exists, even if the matrix does not have
 *    full rank, so the constructor will never fail.  The primary use of the
 *    QR decomposition is in the least squares solution of nonsquare systems
 *    of simultaneous linear equations.  This will fail if isFullRank()
 *    returns false.
 *
 *    @author  Paul Meagher
 *    @license PHP v3.0
 *    @version 1.1
 */
class PHPExcel_Shared_JAMA_QRDecomposition
{
    /**
     *    Array for internal storage of decomposition.
     *    @var array
     */
    private $QR = array();
    /**
     *    Row dimension.
     *    @var integer
     */
    private $m = NULL;
    /**
     *    Column dimension.
     *    @var integer
     */
    private $n = NULL;
    /**
     *    Array for internal storage of diagonal of R.
     *    @var  array
     */
    private $Rdiag = array();
    const MATRIX_RANK_EXCEPTION = "Can only perform operation on full-rank matrix.";
    /**
     *    QR Decomposition computed by Householder reflections.
     *
     *    @param matrix $A Rectangular matrix
     *    @return Structure to access R and the Householder vectors and compute Q.
     */
    public function __construct($A)
    {
        if ($A instanceof PHPExcel_Shared_JAMA_Matrix) {
            $this->QR = $A->getArrayCopy();
            $this->m = $A->getRowDimension();
            $this->n = $A->getColumnDimension();
            for ($k = 0; $k < $this->n; $k++) {
                $nrm = 0;
                for ($i = $k; $i < $this->m; $i++) {
                    $nrm = hypo($nrm, $this->QR[$i][$k]);
                }
                if ($nrm != 0) {
                    if ($this->QR[$k][$k] < 0) {
                        $nrm = 0 - $nrm;
                    }
                    for ($i = $k; $i < $this->m; $i++) {
                        $this->QR[$i][$k] /= $nrm;
                    }
                    $this->QR[$k][$k] += 1;
                    for ($j = $k + 1; $j < $this->n; $j++) {
                        $s = 0;
                        for ($i = $k; $i < $this->m; $i++) {
                            $s += $this->QR[$i][$k] * $this->QR[$i][$j];
                        }
                        $s = (0 - $s) / $this->QR[$k][$k];
                        for ($i = $k; $i < $this->m; $i++) {
                            $this->QR[$i][$j] += $s * $this->QR[$i][$k];
                        }
                    }
                }
                $this->Rdiag[$k] = 0 - $nrm;
            }
        } else {
            throw new PHPExcel_Calculation_Exception(PHPExcel_Shared_JAMA_Matrix::ARGUMENT_TYPE_EXCEPTION);
        }
    }
    /**
     *    Is the matrix full rank?
     *
     *    @return boolean true if R, and hence A, has full rank, else false.
     */
    public function isFullRank()
    {
        for ($j = 0; $j < $this->n; $j++) {
            if ($this->Rdiag[$j] == 0) {
                return false;
            }
        }
        return true;
    }
    /**
     *    Return the Householder vectors
     *
     *    @return Matrix Lower trapezoidal matrix whose columns define the reflections
     */
    public function getH()
    {
        for ($i = 0; $i < $this->m; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                if ($j <= $i) {
                    $H[$i][$j] = $this->QR[$i][$j];
                } else {
                    $H[$i][$j] = 0;
                }
            }
        }
        return new PHPExcel_Shared_JAMA_Matrix($H);
    }
    /**
     *    Return the upper triangular factor
     *
     *    @return Matrix upper triangular factor
     */
    public function getR()
    {
        for ($i = 0; $i < $this->n; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                if ($i < $j) {
                    $R[$i][$j] = $this->QR[$i][$j];
                } else {
                    if ($i == $j) {
                        $R[$i][$j] = $this->Rdiag[$i];
                    } else {
                        $R[$i][$j] = 0;
                    }
                }
            }
        }
        return new PHPExcel_Shared_JAMA_Matrix($R);
    }
    /**
     *    Generate and return the (economy-sized) orthogonal factor
     *
     *    @return Matrix orthogonal factor
     */
    public function getQ()
    {
        for ($k = $this->n - 1; 0 <= $k; $k--) {
            for ($i = 0; $i < $this->m; $i++) {
                $Q[$i][$k] = 0;
            }
            $Q[$k][$k] = 1;
            for ($j = $k; $j < $this->n; $j++) {
                if ($this->QR[$k][$k] != 0) {
                    $s = 0;
                    for ($i = $k; $i < $this->m; $i++) {
                        $s += $this->QR[$i][$k] * $Q[$i][$j];
                    }
                    $s = (0 - $s) / $this->QR[$k][$k];
                    for ($i = $k; $i < $this->m; $i++) {
                        $Q[$i][$j] += $s * $this->QR[$i][$k];
                    }
                }
            }
        }
        return new PHPExcel_Shared_JAMA_Matrix($Q);
    }
    /**
     *    Least squares solution of A*X = B
     *
     *    @param Matrix $B A Matrix with as many rows as A and any number of columns.
     *    @return Matrix Matrix that minimizes the two norm of Q*R*X-B.
     */
    public function solve($B)
    {
        if ($B->getRowDimension() == $this->m) {
            if ($this->isFullRank()) {
                $nx = $B->getColumnDimension();
                $X = $B->getArrayCopy();
                for ($k = 0; $k < $this->n; $k++) {
                    for ($j = 0; $j < $nx; $j++) {
                        $s = 0;
                        for ($i = $k; $i < $this->m; $i++) {
                            $s += $this->QR[$i][$k] * $X[$i][$j];
                        }
                        $s = (0 - $s) / $this->QR[$k][$k];
                        for ($i = $k; $i < $this->m; $i++) {
                            $X[$i][$j] += $s * $this->QR[$i][$k];
                        }
                    }
                }
                for ($k = $this->n - 1; 0 <= $k; $k--) {
                    for ($j = 0; $j < $nx; $j++) {
                        $X[$k][$j] /= $this->Rdiag[$k];
                    }
                    for ($i = 0; $i < $k; $i++) {
                        for ($j = 0; $j < $nx; $j++) {
                            $X[$i][$j] -= $X[$k][$j] * $this->QR[$i][$k];
                        }
                    }
                }
                $X = new PHPExcel_Shared_JAMA_Matrix($X);
                return $X->getMatrix(0, $this->n - 1, 0, $nx);
            }
            throw new PHPExcel_Calculation_Exception(self::MATRIX_RANK_EXCEPTION);
        }
        throw new PHPExcel_Calculation_Exception(PHPExcel_Shared_JAMA_Matrix::MATRIX_DIMENSION_EXCEPTION);
    }
}

?>