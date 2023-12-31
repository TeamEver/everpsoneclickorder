<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita461969e0038de7b70202c9c86067291
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'Ever\\Oneclickorder\\' => 19,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Ever\\Oneclickorder\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInita461969e0038de7b70202c9c86067291::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita461969e0038de7b70202c9c86067291::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita461969e0038de7b70202c9c86067291::$classMap;

        }, null, ClassLoader::class);
    }
}
