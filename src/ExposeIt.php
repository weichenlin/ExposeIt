<?php

namespace AZnC\ExposeIt;


class ExposeIt
{
    protected static $nakedClassMap = [];

    protected static $inheritSpyClassMap = [];

    public static function CreateNakedInstance($fullClassName, $arguments = [])
    {
        if (!array_key_exists($fullClassName, self::$nakedClassMap)) {
            $newClass = "Naked" . substr($fullClassName, strrpos($fullClassName, '\\') + 1) . uniqid();

            $classDefine = "class $newClass extends $fullClassName\n";
            $bodyDefine =
<<<'END_DEFINE'
{
    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            $method = new ReflectionMethod(__CLASS__, $name);
            $method->setAccessible(true);
            return $method->invokeArgs($this, $arguments);
        } elseif (method_exists(get_parent_class($this), "__call")) {
            return call_user_func_array(array($this, 'parent::__call'), [$name, $arguments]);
        }

        return trigger_error("Call to undefined method " . __CLASS__ . "::" . $name . "()", E_USER_ERROR);
    }
}
END_DEFINE;

            eval($classDefine . $bodyDefine);

            self::$nakedClassMap[$fullClassName] = $newClass;
        }

        $reflect  = new \ReflectionClass(self::$nakedClassMap[$fullClassName]);
        return $reflect->newInstanceArgs($arguments);
    }

    public static function CreateInheritSpy($obj)
    {
        $fullClassName = get_class($obj);
        $newClass = "Spied" . substr($fullClassName, strrpos($fullClassName, '\\') + 1) . uniqid();

        $classDefine = "class $newClass extends $fullClassName\n";
        $bodyDefine =
<<<'END_DEFINE'
{
    protected $base;

    public function __construct($obj)
    {
        $this->base = $obj;
    }
    
    public function __get($name)
    {
        return $this->base->$name;
    }
}
END_DEFINE;

        eval($classDefine . $bodyDefine);

        return new $newClass($obj);
    }
}