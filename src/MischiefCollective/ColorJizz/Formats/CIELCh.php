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
 * CIELCh represents the CIELCh color format
 *
 * @author Mikee Franklin <mikeefranklin@gmail.com>
 */
class CIELCh extends ColorJizz
{

    /**
     * The lightness
     * @var float
     */
    private $_lightness;

    /**
     * The chroma
     * @var float
     */
    private $_chroma;

    /**
     * The hue
     * @var float
     */
    private $_hue;

    /**
     * Returns the lightness
     *
     * @return float
     */
    public function getLightness()
    {
      return $this->_lightness;
    }

    /**
     * Returns the chroma
     *
     * @return float
     */
    public function getChroma()
    {
      return $this->_chroma;
    }

    /**
     * Returns the hue
     *
     * @return float
     */
    public function getHue()
    {
      return $this->_hue;
    }

    /**
     * sets the hue
     *
     * @param float $hue
     */
    public function setHue($hue)
    {
      $this->_hue = $hue;
    }

    /**
     * Create a new CIELCh color
     *
     * @param float $lightness The lightness
     * @param float $chroma The chroma
     * @param float $hue The hue
     */
    private function __construct($lightness, $chroma, $hue)
    {
        $this->toSelf = "toCIELCh";
        $this->_lightness = $lightness;
        $this->_chroma = $chroma;
        $this->_hue = fmod($hue, 360);
        if ($this->_hue < 0) {
            $this->_hue += 360;
        }
    }

    /**
     * Create a new CIELCh color
     *
     * @param float $lightness The lightness
     * @param float $chroma The chroma
     * @param float $hue The hue
     *
     * @return MischiefCollective\ColorJizz\Formats\CIELCh the color in CIELCh format
     */
    public static function create($lightness, $chroma, $hue)
    {
        return new CIELCh($lightness, $chroma, $hue);
    }

    /**
     * Convert the color to Hex format
     *
     * @return MischiefCollective\ColorJizz\Formats\Hex the color in Hex format
     */
    public function toHex()
    {
        return $this->toCIELab()->toHex();
    }

    /**
     * Convert the color to RGB format
     *
     * @return MischiefCollective\ColorJizz\Formats\RGB the color in RGB format
     */
    public function toRGB()
    {
        return $this->toCIELab()->toRGB();
    }

    /**
     * Convert the color to XYZ format
     *
     * @return MischiefCollective\ColorJizz\Formats\XYZ the color in XYZ format
     */
    public function toXYZ()
    {
        return $this->toCIELab()->toXYZ();
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
        return $this->toCIELab()->toHSV();
    }

    /**
     * Convert the color to CMY format
     *
     * @return MischiefCollective\ColorJizz\Formats\CMY the color in CMY format
     */
    public function toCMY()
    {
        return $this->toCIELab()->toCMY();
    }

    /**
     * Convert the color to CMYK format
     *
     * @return MischiefCollective\ColorJizz\Formats\CMYK the color in CMYK format
     */
    public function toCMYK()
    {
        return $this->toCIELab()->toCMYK();
    }

    /**
     * Convert the color to CIELab format
     *
     * @return MischiefCollective\ColorJizz\Formats\CIELab the color in CIELab format
     */
    public function toCIELab()
    {
        $hradi = $this->_hue * (pi() / 180);
        $a_dimension = cos($hradi) * $this->_chroma;
        $b_dimension = sin($hradi) * $this->_chroma;
        return CIELab::create($this->_lightness, $a_dimension, $b_dimension);
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
        return $this;
    }

    /**
     * A string representation of this color in the current format
     *
     * @return string The color in format: $lightness,$chroma,$hue
     */
    public function __toString()
    {
        return sprintf('%01.0f, %01.3f, %01.3f', $this->_lightness, $this->_chroma, $this->_hue);
    }

    /**
     * A url string representation of this color in the current format
     *
     * @return string The color in format: $lightness_$chroma_$hue
     */
    public function toUrlString()
    {
        return sprintf('%01.0f_%01.3f_%01.3f', $this->_lightness, $this->_chroma, $this->_hue);
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
     * Create a new CIELCh from a string.
     *
     * @param string $str Can be a color name or string CIELCh value (i.e. "l,c,h" or "cielch(l, c, h)")
     *
     * @return MischiefCollective\ColorJizz\Formats\CIELCh the color in CIELCh format
     */
    public static function createFromString($str)
    {
        $str = str_replace(
          array('cieLch', '(', ')', ';'),
          '',
          strtolower($str)
        );

        $oCIELCh = explode(',', $str);

        if (count($oCIELCh) == 3) {
            if(is_numeric(trim($oCIELCh[0]))
              && trim($oCIELCh[0]) >= 0 && trim($oCIELCh[0]) <= 100
              && is_numeric(trim($oCIELCh[1])) && is_numeric(trim($oCIELCh[2]))) {

              return CIELCh::create(trim($oCIELCh[0]), trim($oCIELCh[1]), trim($oCIELCh[2]));
            }
        }

        throw new InvalidArgumentException(sprintf('Parameter str is an invalid CIELCh string (%s)', $str));
    }
}
