<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8ca2b2ad811bfcd13479afb81dd677db
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

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8ca2b2ad811bfcd13479afb81dd677db::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8ca2b2ad811bfcd13479afb81dd677db::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit8ca2b2ad811bfcd13479afb81dd677db::$classMap;

        }, null, ClassLoader::class);
    }
}