<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit79839803a55e18a9ac3e249d95b046d9
{
    public static $prefixLengthsPsr4 = array (
        'r' => 
        array (
            'raelgc\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'raelgc\\' => 
        array (
            0 => __DIR__ . '/..' . '/raelgc/template/raelgc',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit79839803a55e18a9ac3e249d95b046d9::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit79839803a55e18a9ac3e249d95b046d9::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
