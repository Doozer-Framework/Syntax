<?php

namespace Doozer\Syntax\Tests;

/**
 * Doozer - Syntax - Tests - SyntaxAwareTraitTest.
 *
 * SyntaxAwareTraitTest.php - Tests the functionality of the SyntaxAwareTrait.
 *
 * PHP versions 5.6
 *
 * LICENSE:
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2005 - 2016, Benjamin Carl - All rights reserved.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 * and associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute,
 * sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT
 * NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * Please feel free to contact us via e-mail: opensource@clickalicious.de
 *
 * @category  Doozer-Framework
 *
 * @author    Benjamin Carl <opensource@clickalicious.de>
 * @copyright 2005 - 2016 Benjamin Carl
 * @license   https://opensource.org/licenses/MIT The MIT License
 *
 * @version   Git: $Id$
 *
 * @link      https://github.com/Doozer-Framework/Syntax
 */
use Doozer\Syntax\Tests\Fixtures\Foo;

/**
 * SyntaxAwareTraitTest
 *
 * @author Benjamin Carl <opensource@clickalicious.de>
 */
class SyntaxAwareTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test subject implementing Syntax trait.
     *
     * @var \Doozer\Syntax\Tests\Fixtures\Foo
     */
    protected static $fixtureInstance;

    /**
     * Variables for testing compiler.
     *
     * @var array
     */
    protected static $variableSet;

    /**
     * Constants for testing compiler.
     *
     * @var array
     */
    protected static $constantSet;

    /**
     * Buffer/string for testing compiler.
     *
     * @var string
     */
    protected static $buffer;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        static::$variableSet = [
            'foo' => 'bar',
            'bar' => 'baz',
            'baz' => 123,
        ];

        static::$constantSet = [
            'FOO' => 'BAR',
            'BAR' => 'BAZ',
            'BAZ' => 456
        ];

        $basePath    = __DIR__.DIRECTORY_SEPARATOR.'Fixtures'.DIRECTORY_SEPARATOR.'Success'.DIRECTORY_SEPARATOR;
        $includeFile = 'foo.json';

        //{{foo}}{{FOO}}{{bar}}{{BAR}}{{baz}}{{BAZ}}
        static::$buffer = sprintf('{{include(%s)}}', $includeFile);

        $defaultContent = '{}';

        static::$fixtureInstance = new Foo($basePath, $defaultContent, static::$constantSet, static::$variableSet);
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        static::$fixtureInstance = null;
    }

    public function testInstance()
    {
        static::assertInstanceOf('\Doozer\Syntax\Tests\Fixtures\Foo', static::$fixtureInstance);
    }

    public function testCompilingBuffer()
    {
        static::assertSame(static::$fixtureInstance->getCompileResult(static::$buffer), 'barBARbazBAZ123456');
    }

    /*
    public function testSettingVariables()
    {
        static::$fixtureInstance->setVariables(
            static::$variableSet
        );

        self::assertSame(static::$fixtureInstance->getVariables(), static::$variableSet);
    }
    */

    /*
    public function testSettingConstants()
    {
        static::$fixtureInstance->setConstants(
            static::$constantSet
        );

        self::assertSame(static::$fixtureInstance->getConstants(), static::$constantSet);
    }
    */
}
