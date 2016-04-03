<?php

namespace AZnC\ExposeIt;


class SampleClass
{
    public $strForConstruct;
    
    protected $lastEcho;
    
    public function __construct($str = "")
    {
        $this->strForConstruct = $str;
    }

    public function publicEcho($data)
    {
        $this->lastEcho = $data;
        return $data;
    }
    
    protected function protectedEcho($data)
    {
        $this->lastEcho = $data;
        return $data;
    }
    
    private function privateEcho($data)
    {
        $this->lastEcho = $data;
        return $data;
    }
}