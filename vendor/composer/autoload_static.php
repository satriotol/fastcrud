<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7745bbfe3abec25a46e250eac01a98d9
{
    public static $files = array (
        '46dcc1775268e838c8764d871bccc16c' => __DIR__ . '/../..' . '/app/Helpers/Helpers.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Satriotol\\Fastcrud\\' => 19,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Satriotol\\Fastcrud\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit7745bbfe3abec25a46e250eac01a98d9::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7745bbfe3abec25a46e250eac01a98d9::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7745bbfe3abec25a46e250eac01a98d9::$classMap;

        }, null, ClassLoader::class);
    }
}
