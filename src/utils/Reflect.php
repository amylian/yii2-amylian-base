<?php

/*
 * Copyright 2018 Andreas Prucha, Abexto - Helicon Software Development.
 */

namespace abexto\amylian\yii\base\utils;

/**
 * Description of Reflect
 *
 * @author Andreas Prucha, Abexto - Helicon Software Development
 */
class Reflect
{

    private static $_reflections = [];

    /**
     * Returns ReflectionClass of a class or object
     * 
     * @param string|object $class
     * @return \ReflectionClass
     */
    public static function getRelfectionClass($class)
    {
        $className = is_object($class) ? get_class($class) : $class;
        if (isset(static::$_reflections[$className])) {
            return static::$_reflections[$className]; // Cached Refleciton found ===> RETURN and EXIT
        }
        $result                                                               = new \ReflectionClass($class);
        static::$_reflections[is_object($class) ? get_class($class) : $class] = $result;
        return $result;
    }

}
