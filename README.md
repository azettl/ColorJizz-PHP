#Getting started:

ColorJizz-PHP uses the PSR-0 standards for namespaces, so there should be no trouble using with frameworks like Symfony 2.

###Autoloading

An autoloader class is provided for when loading ColorJizz yourself.

First, include the autoloader and call the static register() function.


```php
<?php
require_once 'path/to/colorjizz/lib/MischiefCollective/ColorJizz/Autoloader.php';
MischiefCollective\ColorJizz\Autoloader::register();
?>
```

Now all ColorJizz classes will be automatically loaded in.

###Converting between formats

ColorJizz can convert to and from any of the supported color formats:

```php
<?php
use MischiefCollective\ColorJizz\Formats\Hex;

$red_hex = Hex::create(0xFF0000);
$red_cmyk = $hex->toCMYK();

echo get_class($red_cmyk); // MischiefCollective\ColorJizz\Formats\CMYK
echo $red_cmyk; // 0,1,1,0
?>
```

Any color manipulation or conversion will return a new instance of a color class, therefore your original color objects remains intact.

Color manipulation can be chained together:

```php
<?php
use MischiefCollective\ColorJizz\Formats\Hex;

echo Hex::fromString('red')->hue(-20)->greyscale(); // 555555
?>
```

Any color manipulation will always return the color in the same format unless you're specifically converting the format. For example:

```php
<?php
use MischiefCollective\ColorJizz\Formats\RGB;

$red = RGB::create(255, 0, 0);
echo get_class($red->hue(-20)->saturation(2)); // MischiefCollective\ColorJizz\Formats\RGB
?>
```

###Supported formats:

```php
<?php
RGB::create(r, g, b);
CMY::create(c, m, y);
CMYK::create(c, m, y, k);
Hex::create(0x000000);
HSV::create(h, s, v);
HSL::create(h, s, l);
CIELab::create(l, a, b);
CIELCh::create(l, c, h);
XYZ::create(x, y, z);
Yxy::create(Y, x, y);
```

```php
<?php
RGB::createFromString('119, 189, 57'); // or "RGB(119, 189, 57)"
CMY::createFromString('0.5333, 0.2588, 0.7765'); // or "CMY(0.5333, 0.2588, 0.7765)"
CMYK::createFromString('0.37, 0.00, 0.70, 0.26'); // or ""
Hex::createFromString('77BD39'); // or "#77BD39"
HSV::createFromString('92, 70, 74'); // or "92°, 70%, 74%" or "HSV(92°, 70%, 74%)"
HSL::createFromString('92, 54, 48'); // or "92°, 54%, 48%" or "HSL(92°, 54%, 48%)"
CIELab::createFromString('70, -43.638, 56.985'); // or "CIELAB(70, -43.638, 56.985)"
CIELCh::createFromString('70, 71.774, 127.444'); // or "CIELCH(70, 71.774, 127.444)"
XYZ::createFromString('26.5998, 40.7494, 10.3551'); // or "XYZ(26.5998, 40.7494, 10.3551)"
Yxy::createFromString('40.7494, 0.3423, 0.5244'); // or "YXY(40.7494, 0.3423, 0.5244)"
```

###Conversion functions:

```php
<?php
->toRGB();
->toCMY();
->toCMYK();
->toHex();
->toHSV();
->toHSL();
->toCIELab();
->toCIELCh();
->toXYZ();
->toYxy();
```
