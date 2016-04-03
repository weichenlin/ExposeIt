<?php

namespace AZnC\ExposeIt;


class NakedInstanceTest extends \PHPUnit_Framework_TestCase
{
    protected $obj;

    public function setUp()
    {
        $this->obj = ExposeIt::CreateNakedInstance(SampleClass::class);
    }

    /** @test */
    public function willReturnInstanceOfTheClass()
    {
        $obj = ExposeIt::CreateNakedInstance(SampleClass::class);

        $this->assertInstanceOf(SampleClass::class, $obj);
    }

    /** @test */
    public function canInvokeProtectedMethod()
    {
        // should be able to call protected function
        $this->obj->protectedEcho("hello");
    }

    /** @test */
    public function canInvokePrivateMethod()
    {
        // should be able to call private function
        $this->obj->privateEcho("hello");
    }

    /** @test */
    public function shouldNotDefineSameClassTwice()
    {
        $obj1 = ExposeIt::CreateNakedInstance(SampleClass::class);
        $obj2 = ExposeIt::CreateNakedInstance(SampleClass::class);

        $this->assertSame(get_class($obj1), get_class($obj2));
    }

    public function testPassingArgumentForConstructor()
    {
        $obj1 = ExposeIt::CreateNakedInstance(SampleClass::class, ["foo"]);
        $obj2 = ExposeIt::CreateNakedInstance(SampleClass::class, ["bar"]);

        $this->assertSame("foo", $obj1->strForConstruct);
        $this->assertSame("bar", $obj2->strForConstruct);
    }

    /**
     * @test
     *
     * http://stackoverflow.com/questions/1225776/test-the-return-value-of-a-method-that-triggers-an-error-with-phpunit
     */
    public function shouldTriggerErrorWhenInvokingNonExistsMethod()
    {
        $this->expectException(\PHPUnit_Framework_Error::class);

        $this->obj->notExistsMethod("hello");
    }

    /** @test */
    public function shouldExposeParentMethod()
    {
        $obj = ExposeIt::CreateNakedInstance(ChildClass::class);

        $obj->parentPrivateMethod();
    }

    /** @test */
    public function shouldInvokeParentMagicMethod__call()
    {
        $obj = ExposeIt::CreateNakedInstance(ChildClass::class);

        // parentMagicMethod will return arguments as array
        $actual = $obj->parentMagicMethod("hi");
        $this->assertSame(["hi"], $actual);

        $actual = $obj->parentMagicMethod("hmm");
        $this->assertSame(["hmm"], $actual);
    }
}
