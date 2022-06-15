<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit11d4e87ca9da732823c19275f9c5095d
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'DenisKisel\\NestedCategories\\' => 28,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'DenisKisel\\NestedCategories\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit11d4e87ca9da732823c19275f9c5095d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit11d4e87ca9da732823c19275f9c5095d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit11d4e87ca9da732823c19275f9c5095d::$classMap;

        }, null, ClassLoader::class);
    }
}