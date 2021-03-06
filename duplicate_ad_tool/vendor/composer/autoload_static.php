<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8587c0644152e35d780e7e0d98298410
{
    public static $files = array (
        'f38f5de8b305c666fc7efb30c07ad0d3' => __DIR__ . '/../..' . '/init/DuplicateAdInit.php',
    );

    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'DuplicateAd\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'DuplicateAd\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8587c0644152e35d780e7e0d98298410::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8587c0644152e35d780e7e0d98298410::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
