<?php
$src = __DIR__ . '/../assets/icons/source.jpg';
$outDir = __DIR__ . '/../assets/icons';
if (!file_exists($src)) {
    echo "Source image not found: $src\n";
    exit(1);
}
$sizes = [192, 512];
$srcImg = imagecreatefromjpeg($src);
if (!$srcImg) { echo "Failed to create image from source\n"; exit(1); }
$w = imagesx($srcImg);
$h = imagesy($srcImg);
foreach ($sizes as $s) {
    $dst = imagecreatetruecolor($s, $s);
    // fill with white
    $white = imagecolorallocate($dst, 255, 255, 255);
    imagefill($dst, 0, 0, $white);
    // calculate resize keeping aspect
    $scale = min($s / $w, $s / $h);
    $nw = (int)($w * $scale);
    $nh = (int)($h * $scale);
    $dx = (int)(($s - $nw) / 2);
    $dy = (int)(($s - $nh) / 2);
    imagecopyresampled($dst, $srcImg, $dx, $dy, 0, 0, $nw, $nh, $w, $h);
    $out = "$outDir/admin-$s.png";
    imagepng($dst, $out, 8);
    imagedestroy($dst);
    echo "Created $out\n";
}
imagedestroy($srcImg);
