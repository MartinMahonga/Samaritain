<?php

namespace App\Helpers;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;

class QrCodeHelper
{
    /**
     * Génère un QR Code simple
     */
    public static function generate(string $data, int $size = 300): string
    {
        $result = Builder::create()
            ->writer(new PngWriter)
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh)
            ->size($size)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin)
            ->build();

        return $result->getDataUri();
    }

    /**
     * Génère un QR Code en SVG
     */
    public static function generateSvg(string $data, int $size = 300): string
    {
        $result = Builder::create()
            ->writer(new SvgWriter)
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh)
            ->size($size)
            ->margin(10)
            ->build();

        return $result->getString();
    }

    /**
     * Génère un QR Code avec logo
     */
    public static function generateWithLogo(string $data, string $logoPath, int $size = 400): string
    {
        $result = Builder::create()
            ->writer(new PngWriter)
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh)
            ->size($size)
            ->margin(10)
            ->logoPath($logoPath)
            ->logoResizeToWidth(60)
            ->logoPunchoutLogo(true)
            ->build();

        return $result->getDataUri();
    }

    /**
     * Génère un QR Code avec des couleurs personnalisées
     */
    public static function generateColored(string $data, string $foregroundColor = '#000000', string $backgroundColor = '#ffffff', int $size = 300): string
    {
        $result = Builder::create()
            ->writer(new PngWriter)
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh)
            ->size($size)
            ->margin(10)
            ->foregroundColor($foregroundColor)
            ->backgroundColor($backgroundColor)
            ->build();

        return $result->getDataUri();
    }

    /**
     * Sauvegarde un QR Code sur le disque
     */
    public static function saveToDisk(string $data, string $path, int $size = 300): bool
    {
        $result = Builder::create()
            ->writer(new PngWriter)
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh)
            ->size($size)
            ->margin(10)
            ->build();

        return file_put_contents($path, $result->getString()) !== false;
    }
}
