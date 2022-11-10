<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInite1c01be4966e29d686e714c9a9d96c2a
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInite1c01be4966e29d686e714c9a9d96c2a', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInite1c01be4966e29d686e714c9a9d96c2a', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInite1c01be4966e29d686e714c9a9d96c2a::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}