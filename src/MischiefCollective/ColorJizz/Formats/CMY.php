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
 * CMY represents the CMY color format
 *
 * @author Mikee Franklin <mikeefranklin@gmail.com>
 */
class CMY extends ColorJizz
{

    /**
     * The cyan
     * @var float
     */
    private $_cyan;

    /**
     * The magenta
     * @var float
     */
    private $_magenta;

    /**
     * The yellow
     * @var float
     */
    private $_yellow;

    /**
     * Create a new CIELab color
     *
     * @param float $cyan The cyan
     * @param float $magenta The magenta
     * @param float $yellow The yellow
     */
    private function __construct($cyan, $magenta, $yellow)
    {
        $this->toSelf = "toCMY";
        $this->_cyan = $cyan;
        $this->_magenta = $magenta;
        $this->_yellow = $yellow;
    }

    /**
     * Create a new CIELab color
     *
     * @param float $cyan The cyan
     * @param float $magenta The magenta
     * @param float $yellow The yellow
     *
     * @return MischiefCollective\ColorJizz\Formats\CMY the color in CMY format
     */
    public static function create($cyan, $magenta, $yellow)
    {
        return new CMY($cyan, $magenta, $yellow);
    }


    /**
     * Get the amount of Cyan
     *
     * @return int The amount of cyan
     */
    public function getCyan()
    {
        return $this->_cyan;
    }


    /**
     * Get the amount of Magenta
     *
     * @return int The amount of magenta
     */
    public function getMagenta()
    {
        return $this->_magenta;
    }


    /**
     * Get the amount of Yellow
     *
     * @return int The amount of yellow
     */
    public function getYellow()
    {
        return $this->_yellow;
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
        $red = (1 - $this->_cyan) * 255;
        $green = (1 - $this->_magenta) * 255;
        $blue = (1 - $this->_yellow) * 255;

        return RGB::create($red, $green, $blue);
    }

    /**
     * Convert the color to XYZ format
     *
     * @return MischiefCollective\ColorJizz\Formats\XYZ the color in XYZ format
     */
    public function toXYZ()
    {
        return $this->toRGB()->toXYZ();
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
        return $this;
    }

    /**
     * Convert the color to CMYK format
     *
     * @return MischiefCollective\ColorJizz\Formats\CMYK the color in CMYK format
     */
    public function toCMYK()
    {
        $var_K = 1;
        $cyan = $this->_cyan;
        $magenta = $this->_magenta;
        $yellow = $this->_yellow;
        if ($cyan < $var_K) {
            $var_K = $cyan;
        }
        if ($magenta < $var_K) {
            $var_K = $magenta;
        }
        if ($yellow < $var_K) {
            $var_K = $yellow;
        }
        if ($var_K == 1) {
            $cyan = 0;
            $magenta = 0;
            $yellow = 0;
        } else {
            $cyan = ($cyan - $var_K) / (1 - $var_K);
            $magenta = ($magenta - $var_K) / (1 - $var_K);
            $yellow = ($yellow - $var_K) / (1 - $var_K);
        }

        $key = $var_K;

        return CMYK::create($cyan, $magenta, $yellow, $key);
    }

    /**
     * Convert the color to CIELab format
     *
     * @return MischiefCollective\ColorJizz\Formats\CIELab the color in CIELab format
     */
    public function toCIELab()
    {
        return $this->toRGB()->toCIELab();
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
     * Convert the color to CIELCh format
     *
     * @return MischiefCollective\ColorJizz\Formats\CIELCh the color in CIELCh format
     */
    public function toCIELCh()
    {
        return $this->toCIELab()->toCIELCh();
    }

    /**
     * A string representation of this color in the current format
     *
     * @return string The color in format: $cyan,$magenta,$yellow
     */
    public function __toString()
    {
        return sprintf('%01.4f, %01.4f, %01.4f', $this->_cyan, $this->_magenta, $this->_yellow);
    }

    /**
     * A url string representation of this color in the current format
     *
     * @return string The color in format: $cyan_$magenta_$yellow
     */
    public function toUrlString()
    {
        return sprintf('%01.4f_%01.4f_%01.4f', $this->_cyan, $this->_magenta, $this->_yellow);
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
     * Create a new cmy from a string.
     *
     * @param string $str Can be a color name or string hex value (i.e. "c,m,y" or "cmy(c, m, y)")
     *
     * @return MischiefCollective\ColorJizz\Formats\CMY the color in cmy format
     */
    public static function createFromString($str)
    {
        $str = str_replace(
          array('cmy', '(', ')', ';'),
          '',
          strtolower($str)
        );

        $oCMY = explode(',', $str);

        if (count($oCMY) == 3) {
            if(is_numeric(trim($oCMY[0])) && is_numeric(trim($oCMY[1])) && is_numeric(trim($oCMY[2]))) {
              if(trim($oCMY[0]) >= 0 && trim($oCMY[1]) >= 0 && trim($oCMY[2]) >= 0){

                return CMY::create(trim($oCMY[0]), trim($oCMY[1]), trim($oCMY[2]));
              }
            }
        }

        throw new InvalidArgumentException(sprintf('Parameter str is an invalid cmy string (%s)', $str));
    }
}
