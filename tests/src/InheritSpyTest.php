<?php

namespace AZnC\ExposeIt;


class InheritSpyTest extends \PHPUnit_Framework_TestCase
{
    protected $spy;

    public function setUp()
    {
        $obj = new SampleClass();
        $this->spy = ExposeIt::CreateInheritSpy($obj);
    }

    /** @test */
    public function willReturnInstanceOfTheClass()
    {
        $obj = new SampleClass();
        $spy = ExposeIt::CreateInheritSpy($obj);

        $this->assertInstanceOf(SampleClass::class, $spy);
    }

    /** @test */
    public function canAccessProtectedField()
    {
        // this is a protected field of SampleClass
        // should not trigger error
        $this->spy->lastEcho;
    }

    /** @test */
    public function theValueOfProtectedFieldIsCorrect()
    {
        $obj = new SampleClass();
        $spy = ExposeIt::CreateInheritSpy($obj);

        $expected = "echo this string";
        $obj->publicEcho($expected);
        $this->assertSame($expected, $spy->lastEcho);

        // change value and test again
        $expected = "echo this string version 2";
        $obj->publicEcho($expected);
        $this->assertSame($expected, $spy->lastEcho);
    }
}
