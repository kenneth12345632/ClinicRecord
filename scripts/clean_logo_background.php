<?php

$src = __DIR__ . '/../public/images/login-clinic-logo.png';
$dst = __DIR__ . '/../public/images/login-clinic-logo-clean.png';

$im = imagecreatefrompng($src);
if (!$im) {
    fwrite(STDERR, "Failed to load source image.\n");
    exit(1);
}

imagesavealpha($im, true);
imagealphablending($im, false);

$w = imagesx($im);
$h = imagesy($im);

for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $idx = imagecolorat($im, $x, $y);
        $c = imagecolorsforindex($im, $idx);
        $r = $c['red'];
        $g = $c['green'];
        $b = $c['blue'];

        $isGrayRange = $r >= 185 && $r <= 235 && $g >= 185 && $g <= 235 && $b >= 185 && $b <= 235;
        $isNeutralGray = max(abs($r - $g), abs($g - $b), abs($r - $b)) < 8;

        if ($isGrayRange && $isNeutralGray) {
            $transparent = imagecolorallocatealpha($im, $r, $g, $b, 127);
            imagesetpixel($im, $x, $y, $transparent);
        }
    }
}

imagepng($im, $dst);
imagedestroy($im);

echo "Created: {$dst}\n";
