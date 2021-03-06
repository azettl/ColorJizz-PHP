<?php
/**
 * This file is part of the ColorJizz package.
 *
 * (c) Mikee Franklin <mikeefranklin@gmail.com>
 */

namespace MischiefCollective\ColorJizz;

use MischiefCollective\ColorJizz\Formats\HSV;
use MischiefCollective\ColorJizz\Formats\CIELCh;
use MischiefCollective\ColorJizz\Formats\RGB;
use MischiefCollective\ColorJizz\Formats\Hex;

/**
 * ColorJizz is the base class that all color objects extend
 *
 * @author Mikee Franklin <mikee@mischiefcollective.com>
 */
abstract class ColorJizz
{

    public $toSelf;

    /**
     * Convert the color to Hex format
     *
     * @return MischiefCollective\ColorJizz\Formats\Hex the color in Hex format
     */
    abstract public function toHex();

    /**
     * Convert the color to RGB format
     *
     * @return MischiefCollective\ColorJizz\Formats\RGB the color in RGB format
     */
    abstract public function toRGB();

    /**
     * Convert the color to XYZ format
     *
     * @return MischiefCollective\ColorJizz\Formats\XYZ the color in XYZ format
     */
    abstract public function toXYZ();

    /**
     * Convert the color to Yxy format
     *
     * @return MischiefCollective\ColorJizz\Formats\Yxy the color in Yxy format
     */
    abstract public function toYxy();

    /**
     * Convert the color to CIELab format
     *
     * @return MischiefCollective\ColorJizz\Formats\CIELab the color in CIELab format
     */
    abstract public function toCIELab();

    /**
     * Convert the color to CIELCh format
     *
     * @return MischiefCollective\ColorJizz\Formats\CIELCh the color in CIELCh format
     */
    abstract public function toCIELCh();

    /**
     * Convert the color to CMY format
     *
     * @return MischiefCollective\ColorJizz\Formats\CMY the color in CMY format
     */
    abstract public function toCMY();

    /**
     * Convert the color to CMYK format
     *
     * @return MischiefCollective\ColorJizz\Formats\CMYK the color in CMYK format
     */
    abstract public function toCMYK();

    /**
     * Convert the color to HSV format
     *
     * @return MischiefCollective\ColorJizz\Formats\HSV the color in HSV format
     */
    abstract public function toHSV();

    /**
     * Convert the color to HSL format
     *
     * @return MischiefCollective\ColorJizz\Formats\HSL the color in HSL format
     */
    abstract public function toHSL();


    /**
     * create a color object from a passed string
     *
     * @return MischiefCollective\ColorJizz\Formats\* the color in classes format
     */
    abstract public static function createFromString($string);


    /**
     * A css string representation of this color in the current format
     *
     * @return string
     */
    abstract public function toCssString();


    /**
     * A url string representation of this color in the current format
     *
     * @return string
     */
    abstract public function toUrlString();

    /**
     * Find the distance to the destination color
     *
     * @param MischiefCollective\ColorJizz\ColorJizz $destinationColor The destination color
     *
     * @return int distance to destination color
     */
    public function distance(ColorJizz $destinationColor)
    {
        $a = $this->toCIELab();
        $b = $destinationColor->toCIELab();

        $lightness_pow = pow(($a->getLightness() - $b->getLightness()), 2);
        $a_dimension_pow = pow(($a->getADimension() - $b->getADimension()), 2);
        $b_dimension_pow = pow(($a->getBDimension() - $b->getBDimension()), 2);

        return sqrt($lightness_pow + $a_dimension_pow + $b_dimension_pow);
    }

    /**
     * Find the closest websafe color
     *
     * @return MischiefCollective\ColorJizz\ColorJizz The closest color
     */
    public function websafe()
    {
        $palette = array();
        for ($red = 0; $red <= 255; $red += 51) {
            for ($green = 0; $green <= 255; $green += 51) {
                for ($blue = 0; $blue <= 255; $blue += 51) {
                    $palette[] = RGB::create($red, $green, $blue);
                }
            }
        }
        return $this->match($palette);
    }

    /**
     * Match the current color to the closest from the array $palette
     *
     * @param array $palette An array of ColorJizz objects to match against
     *
     * @return MischiefCollective\ColorJizz\ColorJizz The closest color
     */
    public function match(array $palette)
    {
        $distance = 100000000000;
        $closest = null;
        for ($i = 0; $i < count($palette); $i++) {
            $cdistance = $this->distance($palette[$i]);
            if ($distance == 100000000000 || $cdistance < $distance) {
                $distance = $cdistance;
                $closest = $palette[$i];
            }
        }
        return call_user_func(array($closest, $this->toSelf));
    }

    public function equal($parts, $includeSelf = false)
    {
        $parts = max($parts, 2);
        $current = $this->toCIELCh();
        $distance = 360 / $parts;
        $palette = array();
        if ($includeSelf) {
            $palette[] = $this;
        }
        for ($i = 1; $i < $parts; $i++) {
            $t = CIELCh::create($current->getLightness(), $current->getChroma(), $current->getHue() + ($distance * $i));
            $palette[] = call_user_func(array($t, $this->toSelf));
        }
        return $palette;
    }

    public function split($includeSelf = false)
    {
        $rtn = array();
        $t = $this->hue(-150);
        $rtn[] = call_user_func(array($t, $this->toSelf));
        if ($includeSelf) {
            $rtn[] = $this;
        }
        $t = $this->hue(150);
        $rtn[] = call_user_func(array($t, $this->toSelf));
        return $rtn;
    }

    /**
     * Return the opposite, complimentary color
     *
     * @return MischiefCollective\ColorJizz\ColorJizz The greyscale color
     */
    public function complement()
    {
        return $this->hue(180);
    }

    /**
     * Returns whether a color can be considered dark or not
     *
     * @return bool   color is dark or not
     */
    public function isDark()
    {
      $sHexColor = $this->toHex();
      $r         = (hexdec(substr($sHexColor, 0, 2)) / 255);
      $g         = (hexdec(substr($sHexColor, 2, 2)) / 255);
      $b         = (hexdec(substr($sHexColor, 4, 2)) / 255);
      $lightness = round((((max($r, $g, $b) + min($r, $g, $b)) / 2) * 100));

      if($lightness >= 50){

        return false;
      }else{

        return true;
      }
    }

    /**
     * Returns a matching text color for the current color in hex.
     *
     * @return MischiefCollective\ColorJizz\Formats\Hex the color in Hex format
     */
    public function getMatchingTextColor()
    {
      if($this->isDark() === false){

        return Hex::create(0x000000);
      }else{

        return Hex::create(0xFFFFFF);
      }
    }

    /**
     * Find complimentary colors
     *
     * @param int $includeSelf Include the current color in the return array
     *
     * @return MischiefCollective\ColorJizz\ColorJizz[] Array of complimentary colors
     */
    public function sweetspot($includeSelf = false)
    {
        $colors = array($this->toHSV());
        $colors[1] = HSV::create($colors[0]->getHue(), round($colors[0]->getSaturation() * 0.3), min(round($colors[0]->getValue() * 1.3), 100));
        $colors[3] = HSV::create(($colors[0]->getHue() + 300) % 360, $colors[0]->getSaturation(), $colors[0]->getValue());
        $colors[2] = HSV::create($colors[1]->getHue(), min(round($colors[1]->getSaturation() * 1.2), 100), min(round($colors[1]->getValue() * 0.5), 100));
        $colors[4] = HSV::create($colors[2]->getHue(), 0, ($colors[2]->getValue() + 50) % 100);
        $colors[5] = HSV::create($colors[4]->getHue(), $colors[4]->getSaturation(), ($colors[4]->getValue() + 50) % 100);
        if (!$includeSelf) {
            array_shift($colors);
        }
        for ($i = 0; $i < count($colors); $i++) {
            $colors[$i] = call_user_func(array($colors[$i], $this->toSelf));
        }
        return $colors;
    }

    public function analogous($includeSelf = false)
    {
        $rtn = array();
        $t = $this->hue(-30);
        $rtn[] = call_user_func(array($t, $this->toSelf));

        if ($includeSelf) {
            $rtn[] = $this;
        }

        $t = $this->hue(30);
        $rtn[] = call_user_func(array($t, $this->toSelf));
        return $rtn;
    }

    public function rectangle($sideLength, $includeSelf = false)
    {
        $side1 = $sideLength;
        $side2 = (360 - ($sideLength * 2)) / 2;
        $current = $this->toCIELCh();
        $rtn = array();

        $t = CIELCh::create($current->getLightness(), $current->getChroma(), $current->getHue() + $side1);
        $rtn[] = call_user_func(array($t, $this->toSelf));

        $t = CIELCh::create($current->getLightness(), $current->getChroma(), $current->getHue() + $side1 + $side2);
        $rtn[] = call_user_func(array($t, $this->toSelf));

        $t = CIELCh::create($current->getLightness(), $current->getChroma(), $current->getHue() + $side1 + $side2 + $side1);
        $rtn[] = call_user_func(array($t, $this->toSelf));

        if ($includeSelf) {
            array_unshift($rtn, $this);
        }

        return $rtn;
    }

    public function range($destinationColor, $steps, $includeSelf = false)
    {
        $a = $this->toRGB();
        $b = $destinationColor->toRGB();
        $colors = array();
        $steps--;
        for ($n = 1; $n < $steps; $n++) {
            $nr = floor($a->getRed() + ($n * ($b->getRed() - $a->getRed()) / $steps));
            $ng = floor($a->getGreen() + ($n * ($b->getGreen() - $a->getGreen()) / $steps));
            $nb = floor($a->getBlue() + ($n * ($b->getBlue() - $a->getBlue()) / $steps));
            $t = RGB::create($nr, $ng, $nb);
            $colors[] = call_user_func(array($t, $this->toSelf));
        }
        if ($includeSelf) {
            array_unshift($colors, $this);
            $colors[] = call_user_func(array($destinationColor, $this->toSelf));
        }
        return $colors;
    }

    /**
     * Return a greyscale version of the current color
     *
     * @return MischiefCollective\ColorJizz\ColorJizz The greyscale color
     */
    public function greyscale()
    {
        $a = $this->toRGB();
        $ds = $a->getRed() * 0.3 + $a->getGreen() * 0.59 + $a->getBlue() * 0.11;
        $t = RGB::create($ds, $ds, $ds);
        return call_user_func(array($t, $this->toSelf));
    }

    /**
     * Modify the hue by $degreeModifier degrees
     *
     * @param int $degreeModifier Degrees to modify by
     * @param bool $absolute If TRUE set absolute value
     *
     * @return MischiefCollective\ColorJizz\ColorJizz The modified color
     */
    public function hue($degreeModifier, $absolute = FALSE)
    {
        $a = $this->toCIELCh();
        $a->setHue($absolute ? $degreeModifier : $a->getHue() + $degreeModifier);
        $a->setHue(fmod($a->getHue(), 360));

        return call_user_func(array($a, $this->toSelf));
    }

    /**
     * Modify the saturation by $satModifier
     *
     * @param int $satModifier Value to modify by
     * @param bool $absolute If TRUE set absolute value
     *
     * @return MischiefCollective\ColorJizz\ColorJizz The modified color
     */
    public function saturation($satModifier, $absolute = FALSE)
    {
        $a = $this->toHSV();
        $a->setSaturation($absolute ? $satModifier : $a->getSaturation() + $satModifier);

        return call_user_func(array($a, $this->toSelf));
    }

    /**
     * Modify the brightness by $brightnessModifier
     *
     * @param int $brightnessModifier Value to modify by
     * @param bool $absolute If TRUE set absolute value
     *
     * @return MischiefCollective\ColorJizz\ColorJizz The modified color
     */
    public function brightness($brightnessModifier, $absolute = FALSE)
    {
        $a = $this->toCIELab();
        $a->setLightness($absolute ? $brightnessModifier : $a->getLightness() + $brightnessModifier);
        return call_user_func(array($a, $this->toSelf));
    }
}
