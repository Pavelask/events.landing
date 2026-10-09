<?php

namespace App\Services;

use Imagick;
use ImagickPixel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    public function png(string $content, int $size = 400, int $margin = 2, string $errorCorrection = 'M'): string
    {
        $svg = QrCode::format('svg')
            ->size($size)
            ->margin($margin)
            ->errorCorrection($errorCorrection)
            ->generate($content);

        $image = new Imagick;
        $image->setBackgroundColor(new ImagickPixel('white'));
        $image->readImageBlob($svg);
        $image->setImageFormat('png');
        $image->resizeImage($size, $size, Imagick::FILTER_POINT, 1);

        return $image->getImageBlob();
    }
}
