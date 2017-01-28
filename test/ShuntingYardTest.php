<?php namespace ryo511;

class ShuntingYardTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider provideTestData
     */
    public function infixToPrefix($data, $expected)
    {
        $this->assertSame($expected, (new ShuntingYard())->infixToPrefix($data));
    }

    public function provideTestData()
    {
        return [
            ['1 + 2', '1 2 +'],
            ['0 - 0', '0 0 -'],
            ['3 + 4 * 2', '3 4 2 * +'],
            ['3 * 4 + 2', '3 4 * 2 +'],
            ['3 + 4 * 2 + 1', '3 4 2 * + 1 +'],
            ['(3 + 4) * 2', '3 4 + 2 *'],
            ['(((((1+1)))))', '1 1 +'],
        ];
    }
}
