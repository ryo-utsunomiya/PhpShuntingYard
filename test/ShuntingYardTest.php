<?php namespace ryo511;

class ShuntingYardTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider provideTestData
     */
    public function infixToPrefix($testData, $expected)
    {
        $this->assertSame($expected, (new ShuntingYard())->infixToPrefix($testData));
    }

    public function provideTestData()
    {
        return [
            ['1 + 2', '1 2 +'],
            ['4 * 3', '4 3 *'],
            ['3 + 4 * 2', '3 4 2 * +'],
            ['3 * 4 + 2', '3 4 * 2 +'],
            ['3 + 4 * 2 + 1', '3 4 2 * + 1 +'],
            ['(3 + 4) * 2', '3 4 + 2 *'],
        ];
    }
}
