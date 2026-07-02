<?php

namespace App\Helpers;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;

class QrCodeHelper
{
    /**
     * Génère un QR Code simple
     */
    public static function generate(string $data, int $size = 300): string
    {
        $result = (new Builder(
            writer: new PngWriter,
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: $size,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        ))->build();

        return $result->getDataUri();
    }

    /**
     * Génère un QR Code en SVG
     */
    public static function generateSvg(string $data, int $size = 300): string
    {
        $result = (new Builder(
            writer: new SvgWriter,
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: $size,
            margin: 10,
        ))->build();

        return $result->getString();
    }

    /**
     * Génère un QR Code avec logo
     */
    public static function generateWithLogo(string $data, string $logoPath, int $size = 400): string
    {
        $result = (new Builder(
            writer: new PngWriter,
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: $size,
            margin: 10,
            logoPath: $logoPath,
            logoResizeToWidth: 60,
            logoPunchoutBackground: true,
        ))->build();

        return $result->getDataUri();
    }

    /**
     * Génère un QR Code avec des couleurs personnalisées
     */
    public static function generateColored(string $data, string $foregroundColor = '#000000', string $backgroundColor = '#ffffff', int $size = 300): string
    {
        $result = (new Builder(
            writer: new PngWriter,
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: $size,
            margin: 10,
            foregroundColor: self::hexToColor($foregroundColor),
            backgroundColor: self::hexToColor($backgroundColor),
        ))->build();

        return $result->getDataUri();
    }

    /**
     * Sauvegarde un QR Code sur le disque
     */
    public static function saveToDisk(string $data, string $path, int $size = 300): bool
    {
        $result = (new Builder(
            writer: new PngWriter,
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: $size,
            margin: 10,
        ))->build();

        return file_put_contents($path, $result->getString()) !== false;
    }

    /**
     * Convertit une couleur hexadécimale (#rrggbb) en objet Color
     */
    private static function hexToColor(string $hex): Color
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        return new Color(
            (int) hexdec(substr($hex, 0, 2)),
            (int) hexdec(substr($hex, 2, 2)),
            (int) hexdec(substr($hex, 4, 2)),
        );
    }
}