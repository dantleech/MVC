<?php

namespace DTL\MVC;

class Util
{
    public static function newClass($name)
    {
        if (!class_exists($name)) {
            throw new \Exception('Class "'.$name.'" does not exist.');
        }

        return new $name;
    }
}
