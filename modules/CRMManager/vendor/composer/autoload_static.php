<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit19832ddd3095b72130d5b959c11ebdea
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'Lazzard\\FtpClient\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Lazzard\\FtpClient\\' => 
        array (
            0 => __DIR__ . '/..' . '/lazzard/php-ftp-client/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit19832ddd3095b72130d5b959c11ebdea::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit19832ddd3095b72130d5b959c11ebdea::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit19832ddd3095b72130d5b959c11ebdea::$classMap;

        }, null, ClassLoader::class);
    }
}
