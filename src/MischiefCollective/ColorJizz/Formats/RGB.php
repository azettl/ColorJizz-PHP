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
 * RGB represents the RGB color format
 *
 * @author Mikee Franklin <mikeefranklin@gmail.com>
 */
class RGB extends ColorJizz
{

    /**
     * The red value (0-255)
     * @var float
     */
    private $_red;

    /**
     * The green value (0-255)
     * @var float
     */
    private $_green;

    /**
     * The blue value (0-255)
     * @var float
     */
    private $_blue;

    /**
     * Create a new RGB color
     *
     * @param float $red The red (0-255)
     * @param float $green The green (0-255)
     * @param float $blue The blue (0-255)
     */
    private function __construct($red, $green, $blue)
    {
        $this->toSelf = "toRGB";

        if ($red < 0 || $red > 255) {
            throw new InvalidArgumentException(sprintf('Parameter red out of range (%s)', $red));
        }
        if ($green < 0 || $green > 255) {
            throw new InvalidArgumentException(sprintf('Parameter green out of range (%s)', $green));
        }
        if ($blue < 0 || $blue > 255) {
            throw new InvalidArgumentException(sprintf('Parameter blue out of range (%s)', $blue));
        }

        $this->_red = $red;
        $this->_green = $green;
        $this->_blue = $blue;
    }

    /**
     * Create a new RGB color
     *
     * @param float $red The red (0-255)
     * @param float $green The green (0-255)
     * @param float $blue The blue (0-255)
     *
     * @return MischiefCollective\ColorJizz\Formats\RGB the color in RGB format
     */
    public static function create($red, $green, $blue)
    {
        return new RGB($red, $green, $blue);
    }

    /**
     * Get the red value (rounded)
     *
     * @return int The red value
     */
    public function getRed()
    {
        return (0.5 + $this->_red) | 0;
    }

    /**
     * Get the green value (rounded)
     *
     * @return int The green value
     */
    public function getGreen()
    {
        return (0.5 + $this->_green) | 0;
    }

    /**
     * Get the blue value (rounded)
     *
     * @return int The blue value
     */
    public function getBlue()
    {
        return (0.5 + $this->_blue) | 0;
    }

    /**
     * Convert the color to Hex format
     *
     * @return MischiefCollective\ColorJizz\Formats\Hex the color in Hex format
     */
    public function toHex()
    {
        return Hex::create($this->getRed() << 16 | $this->getGreen() << 8 | $this->getBlue());
    }

    /**
     * Convert the color to RGB format
     *
     * @return MischiefCollective\ColorJizz\Formats\RGB the color in RGB format
     */
    public function toRGB()
    {
        return $this;
    }

    /**
     * Convert the color to XYZ format
     *
     * @return MischiefCollective\ColorJizz\Formats\XYZ the color in XYZ format
     */
    public function toXYZ()
    {
        $tmp_r = $this->_red / 255;
        $tmp_g = $this->_green / 255;
        $tmp_b = $this->_blue / 255;
        if ($tmp_r > 0.04045) {
            $tmp_r = pow((($tmp_r + 0.055) / 1.055), 2.4);
        } else {
            $tmp_r = $tmp_r / 12.92;
        }
        if ($tmp_g > 0.04045) {
            $tmp_g = pow((($tmp_g + 0.055) / 1.055), 2.4);
        } else {
            $tmp_g = $tmp_g / 12.92;
        }
        if ($tmp_b > 0.04045) {
            $tmp_b = pow((($tmp_b + 0.055) / 1.055), 2.4);
        } else {
            $tmp_b = $tmp_b / 12.92;
        }
        $tmp_r = $tmp_r * 100;
        $tmp_g = $tmp_g * 100;
        $tmp_b = $tmp_b * 100;
        $new_x = $tmp_r * 0.4124 + $tmp_g * 0.3576 + $tmp_b * 0.1805;
        $new_y = $tmp_r * 0.2126 + $tmp_g * 0.7152 + $tmp_b * 0.0722;
        $new_z = $tmp_r * 0.0193 + $tmp_g * 0.1192 + $tmp_b * 0.9505;
        return XYZ::create($new_x, $new_y, $new_z);
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
     * Convert the color to HSL format
     *
     * @return MischiefCollective\ColorJizz\Formats\HSL the color in HSL format
     */
    public function toHSL()
    {
      $red   = $this->_red / 255;
      $green = $this->_green / 255;
      $blue  = $this->_blue / 255;
      $max   = max($red, $green, $blue);
      $min   = min($red, $green, $blue);
      $delta = $max - $min;

      // Calculate lightness
      $l = ($max + $min) / 2;

      // If delta is 0, the color is grey
      // hue and saturation are set to 0
      if ($delta == 0){
        $hue = 0;
        $s   = 0;
      }else{
        if ($l < 0.5){
          $s = $delta / ($max + $min);
        }else{
          $s = $delta / (2 - $max - $min);
        }
        if ($red == $max) {
          $hue = (($delta) == 0) ? 0 : ($green - $blue) / $delta;
        } else {
          if ($green == $max) {
            $hue = 2 + ($blue - $red) / $delta;
          } else {
            $hue = 4 + ($red - $green) / $delta;
          }
        }

        $hue *= 60;
        if ($hue < 0) {
            $hue += 360;
        }
      }

      return HSL::create($hue, $s * 100, $l * 100);
    }

    /**
     * Convert the color to HSV format
     *
     * @return MischiefCollective\ColorJizz\Formats\HSV the color in HSV format
     */
    public function toHSV()
    {
        $red = $this->_red / 255;
        $green = $this->_green / 255;
        $blue = $this->_blue / 255;


        $min = min($red, $green, $blue);
        $max = max($red, $green, $blue);

        $value = $max;
        $delta = $max - $min;

        if ($delta == 0) {
            return HSV::create(0, 0, $value * 100);
        }

        $saturation = 0;

        if ($max != 0) {
            $saturation = $delta / $max;
        } else {
            $saturation = 0;
            $hue = -1;
            return HSV::create($hue, $saturation, $value);
        }
        if ($red == $max) {
            $hue = ($green - $blue) / $delta;
        } else {
            if ($green == $max) {
                $hue = 2 + ($blue - $red) / $delta;
            } else {
                $hue = 4 + ($red - $green) / $delta;
            }
        }
        $hue *= 60;
        if ($hue < 0) {
            $hue += 360;
        }

        return HSV::create($hue, $saturation * 100, $value * 100);
    }

    /**
     * Convert the color to CMY format
     *
     * @return MischiefCollective\ColorJizz\Formats\CMY the color in CMY format
     */
    public function toCMY()
    {
        $cyan = 1 - ($this->_red / 255);
        $magenta = 1 - ($this->_green / 255);
        $yellow = 1 - ($this->_blue / 255);
        return CMY::create($cyan, $magenta, $yellow);
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
        return $this->toXYZ()->toCIELab();
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
     * @return string The color in format: $red,$green,$blue (rounded)
     */
    public function __toString()
    {
        return $this->getRed() . ', ' . $this->getGreen() . ', ' . $this->getBlue();
    }

    /**
     * A url string representation of this color in the current format
     *
     * @return string The color in format: $red_$green_$blue (rounded)
     */
    public function toUrlString()
    {
        return $this->getRed() . '_' . $this->getGreen() . '_' . $this->getBlue();
    }

    /**
     * A css string representation of this color in the current format
     *
     * @return string The color in format: rgb(R, G, B)
     */
    public function toCssString()
    {
        return 'rgb(' . $this->getRed() . ', ' . $this->getGreen() . ', ' . $this->getBlue() . ')';
    }

    /**
     * Create a new rgb from a string.
     *
     * @param string $str Can be a color name or string hex value (i.e. "r,g,b" or "rgb(r, g, b)")
     *
     * @return MischiefCollective\ColorJizz\Formats\RGB the color in RGB format
     */
    public static function fromString($str)
    {
        $str = str_replace(
          array('rgb', '(', ')', ';'),
          '',
          strtolower($str)
        );

        $oRgb = explode(',', $str);

        if (count($oRgb) == 3) {
          if(self::is_digits(trim($oRgb[0])) && self::is_digits(trim($oRgb[1])) && self::is_digits(trim($oRgb[2]))) {
            if(trim($oRgb[0]) >= 0 && trim($oRgb[1]) >= 0 && trim($oRgb[2]) >= 0){

              return RGB::create(trim($oRgb[0]), trim($oRgb[1]), trim($oRgb[2]));
            }
          }
        }

        throw new InvalidArgumentException(sprintf('Parameter str is an invalid rgb string (%s)', $str));
    }

    /**
     * Checks if a string only contains digits
     *
     * @param string $str
     *
     * @return bool   true if its only digits
     */
    private static function is_digits($str) {
    	return !preg_match ("/[^0-9]/", $str);
    }
}
