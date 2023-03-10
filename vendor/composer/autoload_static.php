<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb744a5b35e410088ec80789acd718fe2
{
    public static $files = array (
        '2859e28209fa490867cde7a8d28f50de' => __DIR__ . '/../..' . '/src/app/helpers.php',
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
            $loader->prefixLengthsPsr4 = ComposerStaticInitb744a5b35e410088ec80789acd718fe2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb744a5b35e410088ec80789acd718fe2::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb744a5b35e410088ec80789acd718fe2::$classMap;

        }, null, ClassLoader::class);
    }
}
