<?php

Trait StaticInstance
{
    protected static $instances = [];
    protected $setting = [];

    public static function instance($index = null)
    {
        $index = is_null($index) ? md5(get_called_class()) : $index;
        if( ! isset(static::$instances[$index])){
            static::$instances[$index] = new static();
        }

        return static::$instances[$index];
    }
}