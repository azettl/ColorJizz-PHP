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
 * Yxy represents the Yxy color format
 *
 * @author Mikee Franklin <mikeefranklin@gmail.com>
 */
class Yxy extends ColorJizz
{

    /**
     * The Y
     * @var float
     */
    private $_Y;

    /**
     * The x
     * @var float
     */
    private $_x;

    /**
     * The y
     * @var float
     */
    private $_y;

    /**
     * Create a new Yxy color
     *
     * @param float $Y The Y
     * @param float $x The x
     * @param float $y The y
     */
    private function __construct($Y, $x, $y)
    {
        $this->toSelf = "toYxy";
        $this->_Y = $Y;
        $this->_x = $x;
        $this->_y = $y;
    }

    /**
     * Create a new Yxy color
     *
     * @param float $Y The Y
     * @param float $x The x
     * @param float $y The y
     *
     * @return MischiefCollective\ColorJizz\Formats\Yxy the color in Yxy format
     */
    public static function create($Y, $x, $y)
    {
        return new Yxy($Y, $x, $y);
    }

    /**
     * Convert the color to Hex format
     *
     * @return MischiefCollective\ColorJizz\Formats\Hex the color in Hex format
     */
    public function toHex()
    {
        return $this->toXYZ()->toRGB()->toHex();
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
        $X = ($this->_Y == 0) ? 0 : $this->_x * ($this->_Y / $this->_y);
        $Y = $this->_Y;
        $Z = ($this->_Y == 0) ? 0 : (1 - $this->_x - $this->_y) * ($this->_Y / $this->_y);
        return XYZ::create($X, $Y, $Z);
    }

    /**
     * Convert the color to Yxy format
     *
     * @return MischiefCollective\ColorJizz\Formats\Yxy the color in Yxy format
     */
    public function toYxy()
    {
        return $this;
    }

    /**
     * Convert the color to HSV format
     *
     * @return MischiefCollective\ColorJizz\Formats\HSV the color in HSV format
     */
    public function toHSV()
    {
        return $this->toXYZ()->toHSV();
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
     * Convert the color to CMY format
     *
     * @return MischiefCollective\ColorJizz\Formats\CMY the color in CMY format
     */
    public function toCMY()
    {
        return $this->toXYZ()->toCMY();
    }

    /**
     * Convert the color to CMYK format
     *
     * @return MischiefCollective\ColorJizz\Formats\CMYK the color in CMYK format
     */
    public function toCMYK()
    {
        return $this->toXYZ()->toCMYK();
    }

    /**
     * Convert the color to CIELab format
     *
     * @return MischiefCollective\ColorJizz\Formats\CIELab the color in CIELab format
     */
    public function toCIELab()
    {
        return $this->toXYZ()->toCIELab();
    }

    /**
     * Convert the color to CIELCh format
     *
     * @return MischiefCollective\ColorJizz\Formats\CIELCh the color in CIELCh format
     */
    public function toCIELCh()
    {
        return $this->toXYZ()->toCIELCh();
    }

    /**
     * A string representation of this color in the current format
     *
     * @return string The color in format: $Y,$x,$y
     */
    public function __toString()
    {
        return sprintf('%01.4f, %01.4f, %01.4f', $this->_Y, $this->_x, $this->_y);
    }

    /**
     * A url string representation of this color in the current format
     *
     * @return string The color in format: $Y_$x_$y
     */
    public function toUrlString()
    {
        return sprintf('%01.4f_%01.4f_%01.4f', $this->_Y, $this->_x, $this->_y);
    }

    /**
     * A css string representation of this color in the current format
     *
     * @return string The color in format: rgb(R,G,B)
     */
    public function toCssString()
    {
        return $this->toRGB()->toCssString();
    }

    /**
     * Create a new yxy from a string.
     *
     * @param string $str Can be a color name or string Yxy value (i.e. "y,x,y" or "yxy(y, x, y)")
     *
     * @return MischiefCollective\ColorJizz\Formats\Yxy the color in Yxy format
     */
    public static function fromString($str)
    {
        $str = str_replace(
          array('yxy', '(', ')', ';', '°', '%'),
          '',
          $str
        );
        $str = str_replace(
          array('yxy', '(', ')', ';', '°', '%'),
          '',
          strtolower($str)
        );

        $oYxy = explode(',', $str);

        if (count($oYxy) == 3) {
            if(is_numeric(trim($oYxy[0])) && is_numeric(trim($oYxy[1])) && is_numeric(trim($oYxy[2]))) {

              return Yxy::create(trim($oYxy[0]), trim($oYxy[1]), trim($oYxy[2]));
            }
        }

        throw new InvalidArgumentException(sprintf('Parameter str is an invalid yxy string (%s)', $str));
    }
}
