<?php

namespace AZnC\ExposeIt;


class BaseClass
{
    private function parentPrivateMethod()
    {
        
    }

    public function __call($name, $arguments)
    {
        if ("parentMagicMethod") {
            return $arguments;
        }
        
        return null;
    }
}