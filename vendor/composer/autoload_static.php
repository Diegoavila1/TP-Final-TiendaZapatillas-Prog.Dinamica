<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita8306d0e234b11e532fdf2d2bed51aa7
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Whoops\\' => 7,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'Mpdf\\QrCode\\' => 12,
        ),
        'J' => 
        array (
            'Juan\\TpFinalTiendaZapatillas\\' => 29,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Whoops\\' => 
        array (
            0 => __DIR__ . '/..' . '/filp/whoops/src/Whoops',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/src',
        ),
        'Mpdf\\QrCode\\' => 
        array (
            0 => __DIR__ . '/..' . '/mpdf/qrcode/src',
        ),
        'Juan\\TpFinalTiendaZapatillas\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita8306d0e234b11e532fdf2d2bed51aa7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita8306d0e234b11e532fdf2d2bed51aa7::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita8306d0e234b11e532fdf2d2bed51aa7::$classMap;

        }, null, ClassLoader::class);
    }
}
