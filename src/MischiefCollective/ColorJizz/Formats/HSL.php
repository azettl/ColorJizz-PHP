<?php
/**
 * This file is part of the ColorJizz package.
 */

namespace MischiefCollective\ColorJizz\Formats;

use MischiefCollective\ColorJizz\ColorJizz;
use MischiefCollective\ColorJizz\Exceptions\InvalidArgumentException;

/**
 * HSL represents the HSL color format
 *
 * @author Andreas Zettl <info@azettl.net>
 */
class HSL extends ColorJizz
{

    /**
     * The hue
     * @var float
     */
    private $_hue;

    /**
     * The saturation
     * @var float
     */
    private $_saturation;

    /**
     * The lightness
     * @var float
     */
    private $_lightness;

    /**
     * Create a new HSL color
     *
     * @param float $hue The hue (0-1)
     * @param float $saturation The saturation (0-1)
     * @param float $lightness The lightness (0-1)
     */
    private function __construct($hue, $saturation, $lightness)
    {
        $this->toSelf     = "toHSL";
        $this->_hue        = $hue;
        $this->_saturation = $saturation;
        $this->_lightness  = $lightness;
    }

    /**
     * Create a new HSL color
     *
     * @param float $hue The hue (0-1)
     * @param float $saturation The saturation (0-1)
     * @param float $lightness The lightness (0-1)
     *
     * @return MischiefCollective\ColorJizz\Formats\HSL the color in HSL format
     */
    public static function create($hue, $saturation, $lightness)
    {
        return new HSL($hue, $saturation, $lightness);
    }

    /**
     * Convert the color to Hex format
     *
     * @return \MischiefCollective\ColorJizz\Formats\Hex the color in Hex format
     */
    public function toHex()
    {
        return $this->toRGB()->toHex();
    }

    /**
     * Convert the color to RGB format
     *
     * @return \MischiefCollective\ColorJizz\Formats\RGB the color in RGB format
     */
    public function toRGB()
    {
      $h = $this->_hue / 360;
      $s = $this->_saturation / 100;
      $l = $this->_lightness / 100;

      $r = $l;
      $g = $l;
      $b = $l;
      $v = ($l <= 0.5) ? ($l * (1.0 + $s)) : ($l + $s - $l * $s);
      if ($v > 0){
        $m;
        $sv;
        $sextant;
        $fract;
        $vsf;
        $mid1;
        $mid2;

        $m       = $l + $l - $v;
        $sv      = ($v - $m ) / $v;
        $h      *= 6.0;
        $sextant = floor($h);
        $fract   = $h - $sextant;
        $vsf     = $v * $sv * $fract;
        $mid1    = $m + $vsf;
        $mid2    = $v - $vsf;

        switch ($sextant)
        {
          case 0:
            $r = $v;
            $g = $mid1;
            $b = $m;
            break;
          case 1:
            $r = $mid2;
            $g = $v;
            $b = $m;
            break;
          case 2:
            $r = $m;
            $g = $v;
            $b = $mid1;
            break;
          case 3:
            $r = $m;
            $g = $mid2;
            $b = $v;
            break;
          case 4:
            $r = $mid1;
            $g = $m;
            $b = $v;
            break;
          case 5:
            $r = $v;
            $g = $m;
            $b = $mid2;
            break;
        }
      }

      return RGB::create($r * 255.0, $g * 255.0, $b * 255.0);
    }

    /**
     * Convert the color to XYZ format
     *
     * @return \MischiefCollective\ColorJizz\Formats\XYZ the color in XYZ format
     */
    public function toXYZ()
    {
        return $this->toRGB()->toXYZ();
    }

    /**
     * Convert the color to Yxy format
     *
     * @return \MischiefCollective\ColorJizz\Formats\Yxy the color in Yxy format
     */
    public function toYxy()
    {
        return $this->toXYZ()->toYxy();
    }

    /**
     * Convert the color to HSL format
     *
     * @return \MischiefCollective\ColorJizz\Formats\HSL the color in HSL format
     */
    public function toHSL()
    {
        return $this;
    }

    /**
     * Convert the color to HSV format
     *
     * @return \MischiefCollective\ColorJizz\Formats\HSV the color in HSV format
     */
    public function toHSV()
    {
        return $this->toRGB()->toHSV();
    }

    /**
     * Convert the color to CMY format
     *
     * @return \MischiefCollective\ColorJizz\Formats\CMY the color in CMY format
     */
    public function toCMY()
    {
        return $this->toRGB()->toCMY();
    }

    /**
     * Convert the color to CMYK format
     *
     * @return \MischiefCollective\ColorJizz\Formats\CMYK the color in CMYK format
     */
    public function toCMYK()
    {
        return $this->toCMY()->toCMYK();
    }

    /**
     * Convert the color to CIELab format
     *
     * @return \MischiefCollective\ColorJizz\Formats\CIELab the color in CIELab format
     */
    public function toCIELab()
    {
        return $this->toRGB()->toCIELab();
    }

    /**
     * Convert the color to CIELCh format
     *
     * @return \MischiefCollective\ColorJizz\Formats\CIELCh the color in CIELCh format
     */
    public function toCIELCh()
    {
        return $this->toCIELab()->toCIELCh();
    }

    /**
     * A string representation of this color in the current format
     *
     * @return string The color in format: $hue°, $saturation%, $lightness%
     */
    public function __toString()
    {
        return sprintf('%01.0f°, %01.0f%%, %01.0f%%', $this->_hue, $this->_saturation, $this->_lightness);
    }

    /**
     * A url string representation of this color in the current format
     *
     * @return string The color in format: $hue_$saturation_$lightness
     */
    public function toUrlString()
    {
      return sprintf('%01.0f_%01.0f_%01.0f', $this->_hue, $this->_saturation, $this->_lightness);
    }

    /**
     * A css string representation of this color in the current format
     *
     * @return string The color in format: hsl(h, s%, l%)
     */
    public function toCssString()
    {
        return sprintf('hsl(%01.0f, %01.0f%%, %01.0f%%)', $this->_hue, $this->_saturation, $this->_lightness);
    }

    /**
     * Create a new hsl from a string.
     *
     * @param string $str Can be a color name or string hsl value (i.e. "h,s,l" or "hsl(h, s, l)")
     *
     * @return \MischiefCollective\ColorJizz\Formats\HSL the color in hsl format
     */
    public static function fromString($str)
    {
        $str = str_replace(
          array('hsl', '(', ')', ';', '°', '%', 'â°'),
          '',
          ($str)
        );
        $str = str_replace(
          array('hsl', '(', ')', ';', '°', '%', 'â°'),
          '',
          strtolower($str)
        );

        $oHSL = explode(',', $str);
        if (count($oHSL) == 3) {
            if(self::is_digits(trim($oHSL[0])) && self::is_digits(trim($oHSL[1])) && self::is_digits(trim($oHSL[2]))) {

              return HSL::create(trim($oHSL[0]), trim($oHSL[1]), trim($oHSL[2]));
            }
        }

        throw new InvalidArgumentException(sprintf('Parameter str is an invalid hsl string (%s)', $str));
    }

    /**
     * Checks if a string only contains digits
     *
     * @param string $str
     *
     * @return bool   true if its only digits
     */
    private static function is_digits($element) {
    	return !preg_match ("/[^0-9]/", $element);
    }
}
