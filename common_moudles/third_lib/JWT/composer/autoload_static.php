<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita46296f3767d27649467a1af557b1400
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita46296f3767d27649467a1af557b1400::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita46296f3767d27649467a1af557b1400::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
