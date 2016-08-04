<?php
/**
 * This file is part of the ColorJizz package.
 *
 * (c) Mikee Franklin <mikeefranklin@gmail.com>
 */

namespace MischiefCollective\ColorJizz\Formats;

use MischiefCollective\ColorJizz\ColorJizz;
use MischiefCollective\ColorJizz\Exceptions\InvalidArgumentException;

/**
 * CIELab represents the CIELab color format
 *
 * @author Mikee Franklin <mikeefranklin@gmail.com>
 */
class CIELab extends ColorJizz
{

    /**
     * The lightness
     * @var float
     */
    private $_lightness;

    /**
     * The a dimension
     * @var float
     */
    private $_a_dimension;

    /**
     * The b dimenson
     * @var float
     */
    private $_b_dimension;

    /**
     * Create a new CIELab color
     *
     * @param float $lightness   The lightness
     * @param float $a_dimension The a dimenson
     * @param float $b_dimension The b dimenson
     */
    private function __construct($lightness, $a_dimension, $b_dimension)
    {
        $this->toSelf = "toCIELab";
        $this->_lightness = $lightness; //$this->roundDec($l, 3);
        $this->_a_dimension = $a_dimension; //$this->roundDec($a, 3);
        $this->_b_dimension = $b_dimension; //$this->roundDec($b, 3);
    }

    /**
     * Create a new CIELab color
     *
     * @param float $lightness   The lightness
     * @param float $a_dimension The a dimenson
     * @param float $b_dimension The b dimenson
     *
     * @return MischiefCollective\ColorJizz\Formats\CIELab the color in CIELab format
     */
    public static function create($lightness, $a_dimension, $b_dimension)
    {
        return new CIELab($lightness, $a_dimension, $b_dimension);
    }

    /**
     * Convert the color to Hex format
     *
     * @return MischiefCollective\ColorJizz\Formats\Hex the color in Hex format
     */
    public function toHex()
    {
        return $this->toRGB()->toHex();
    }

    /**
     * Convert the color to RGB format
     *
     * @return MischiefCollective\ColorJizz\Formats\RGB the color in RGB format
     */
    public function toRGB()
    {
        return $this->toXYZ()->toRGB();
    }

    /**
     * Convert the color to XYZ format
     *
     * @return MischiefCollective\ColorJizz\Formats\XYZ the color in XYZ format
     */
    public function toXYZ()
    {
        $ref_X = 95.047;
        $ref_Y = 100.000;
        $ref_Z = 108.883;

        $var_Y = ($this->_lightness + 16) / 116;
        $var_X = $this->_a_dimension / 500 + $var_Y;
        $var_Z = $var_Y - $this->_b_dimension / 200;

        if (pow($var_Y, 3) > 0.008856) {
            $var_Y = pow($var_Y, 3);
        } else {
            $var_Y = ($var_Y - 16 / 116) / 7.787;
        }
        if (pow($var_X, 3) > 0.008856) {
            $var_X = pow($var_X, 3);
        } else {
            $var_X = ($var_X - 16 / 116) / 7.787;
        }
        if (pow($var_Z, 3) > 0.008856) {
            $var_Z = pow($var_Z, 3);
        } else {
            $var_Z = ($var_Z - 16 / 116) / 7.787;
        }
        $position_x = $ref_X * $var_X;
        $position_y = $ref_Y * $var_Y;
        $position_z = $ref_Z * $var_Z;
        return XYZ::create($position_x, $position_y, $position_z);
    }

    /**
     * Convert the color to Yxy format
     *
     * @return MischiefCollective\ColorJizz\Formats\Yxy the color in Yxy format
     */
    public function toYxy()
    {
        return $this->toXYZ()->toYxy();
    }

    /**
     * Convert the color to HSV format
     *
     * @return MischiefCollective\ColorJizz\Formats\HSV the color in HSV format
     */
    public function toHSV()
    {
        return $this->toRGB()->toHSV();
    }

    /**
     * Convert the color to CMY format
     *
     * @return MischiefCollective\ColorJizz\Formats\CMY the color in CMY format
     */
    public function toCMY()
    {
        return $this->toRGB()->toCMY();
    }

    /**
     * Convert the color to CMYK format
     *
     * @return MischiefCollective\ColorJizz\Formats\CMYK the color in CMYK format
     */
    public function toCMYK()
    {
        return $this->toCMY()->toCMYK();
    }

    /**
     * Convert the color to CIELab format
     *
     * @return MischiefCollective\ColorJizz\Formats\CIELab the color in CIELab format
     */
    public function toCIELab()
    {
        return $this;
    }

    /**
     * Convert the color to CIELCh format
     *
     * @return MischiefCollective\ColorJizz\Formats\CIELCh the color in CIELCh format
     */
    public function toCIELCh()
    {
        $var_H = atan2($this->_b_dimension, $this->_a_dimension);

        if ($var_H > 0) {
            $var_H = ($var_H / pi()) * 180;
        } else {
            $var_H = 360 - (abs($var_H) / pi()) * 180;
        }

        $lightness = $this->_lightness;
        $chroma = sqrt(pow($this->_a_dimension, 2) + pow($this->_b_dimension, 2));
        $hue = $var_H;

        return CIELCh::create($lightness, $chroma, $hue);
    }

    /**
     * Convert the color to HSL format
     *
     * @return MischiefCollective\ColorJizz\Formats\HSL the color in HSL format
     */
    public function toHSL()
    {
        return $this->toRGB()->toHSL();
    }

    /**
     * A string representation of this color in the current format
     *
     * @return string The color in format: $lightness,$a_dimension,$b_dimension
     */
    public function __toString()
    {
        return sprintf('%01.0f, %01.3f, %01.3f', $this->_lightness, $this->_a_dimension, $this->_b_dimension);
    }

    /**
     * A css string representation of this color in the current format
     *
     * @return string The color in format: rgb(R, G, B)
     */
    public function toCssString()
    {
        return $this->toRGB()->toCssString();
    }

    /**
     * A url string representation of this color in the current format
     *
     * @return string The color in format: $lightness_$a_dimension_$b_dimension
     */
    public function toUrlString()
    {
      return sprintf('%01.0f_%01.3f_%01.3f', $this->_lightness, $this->_a_dimension, $this->_b_dimension);
    }

    /**
     * Create a new CIELab from a string.
     *
     * @param string $str Can be a color name or string CIELab value (i.e. "l,a,b" or "cielab(l, a, b)")
     *
     * @return MischiefCollective\ColorJizz\Formats\CIELab the color in CIELab format
     */
    public static function fromString($str)
    {
        $str = str_replace(
          array('cieLab', '(', ')', ';'),
          '',
          strtolower($str)
        );

        $oCIELab = explode(',', $str);

        if (count($oCIELab) == 3) {
            if(is_numeric(trim($oCIELab[0]))
              && trim($oCIELab[0]) >= 0 && trim($oCIELab[0]) <= 100
              && is_numeric(trim($oCIELab[1])) && is_numeric(trim($oCIELab[2]))) {

              return CIELab::create(trim($oCIELab[0]), trim($oCIELab[1]), trim($oCIELab[2]));
            }
        }

        throw new InvalidArgumentException(sprintf('Parameter str is an invalid CIELab string (%s)', $str));
    }
}
