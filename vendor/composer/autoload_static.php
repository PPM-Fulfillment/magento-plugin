<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0c177e5a4741091144605f594f8f0238
{
    public static $files = array (
        '416fe89948bdd9096f3fbd75beae063b' => __DIR__ . '/../..' . '/registration.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Ppm\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Ppm\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0c177e5a4741091144605f594f8f0238::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0c177e5a4741091144605f594f8f0238::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
