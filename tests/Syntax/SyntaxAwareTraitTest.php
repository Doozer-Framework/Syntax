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
use Doozer\Syntax\Tests\Fixtures\TraitWrapper;

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
     * @var \Doozer\Syntax\Tests\Fixtures\TraitWrapper
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
     * Result expected on test run.
     *
     * @var string
     */
    protected static $expectedResult;


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
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        static::$fixtureInstance = null;
        static::$expectedResult  = null;
        static::$buffer          = null;
    }

    /**
     * Tests: Creating an instance of fixture/wrapper.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testCreateInstanceOfWrapperContainingSyntaxTrait()
    {
        $basePath = __DIR__.DIRECTORY_SEPARATOR.'Fixtures'.DIRECTORY_SEPARATOR.'Success'.DIRECTORY_SEPARATOR.'Txt'.DIRECTORY_SEPARATOR;

        static::$fixtureInstance = new TraitWrapper($basePath, static::$variableSet, static::$constantSet);

        static::assertInstanceOf('\Doozer\Syntax\Tests\Fixtures\TraitWrapper', static::$fixtureInstance);
    }

    /**
     * Tests: Compiling a passed buffer with success.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function testCompileBufferWithSuccess()
    {
        $testMatrix = [
            [
                'mode'           => 'Txt',
                'borderMarker'   => '',
                'expectedResult' => "ALOHA\nBAR\n456\nbar\n123\nBAZ\nbaz",
                'includeFile'    => 'compile-successful-whole-stack',
            ],
            [
                'mode'           => 'Json',
                'borderMarker'   => '"',
                'expectedResult' => "{\n  \"foo\": {\n    \"bar\": {\n      \"ALOHA\": 123,\n      \"FOO\": \"BAR\",\n      \"BAZ\": \"456\",\n      \"foo\": \"bar\",\n      \"baz\": \"123\"\n    }\n  }\n}",
                'includeFile'    => 'compile-successful-whole-stack',
            ],
        ];

        foreach ($testMatrix as $testSetup) {
            $includeFileExtension = '.'.strtolower($testSetup['mode']);
            $basePath             = __DIR__.DIRECTORY_SEPARATOR.'Fixtures'.DIRECTORY_SEPARATOR.'Success'.DIRECTORY_SEPARATOR.$testSetup['mode'].DIRECTORY_SEPARATOR;
            $buffer               = sprintf('%s{{require(%s)}}%s', $testSetup['borderMarker'], $testSetup['includeFile'].$includeFileExtension, $testSetup['borderMarker']);
            $fixtureInstance      = new TraitWrapper($basePath, static::$variableSet, static::$constantSet, $testSetup['borderMarker']);

            static::assertSame(
                $fixtureInstance->getCompiledResult($buffer),
                $testSetup['expectedResult']
            );
        }
    }

    /**
     * Tests: Compiling a passed buffer with failure due to a missing required file.
     *
     * @expectedException \Doozer\Syntax\Exception\CompilerException
     * @expectedExceptionMessage Error processing directive "{{include(passing-missing-required-file.txt)}}".
     */
    public function testCompileBufferWithFailureDuePassingMissingRequiredFile()
    {
        $basePath             = __DIR__.DIRECTORY_SEPARATOR.'Fixtures'.DIRECTORY_SEPARATOR.'Failure'.DIRECTORY_SEPARATOR;
        $includeFile          = 'passing-missing-required-file';
        $includeFileExtension = '.txt';

        static::$buffer = sprintf('{{include(%s)}}', $includeFile.$includeFileExtension);
        static::$fixtureInstance = new TraitWrapper($basePath, static::$variableSet, static::$constantSet);
        static::$fixtureInstance->getCompiledResult(static::$buffer);
    }

    /**
     * Tests: Compiling a passed buffer with failure due to passing an invalid directive.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @expectedException \Doozer\Syntax\Exception\CompilerException
     * @expectedExceptionMessage Error processing directive "{{include(passing-invalid-directive.txt)}}".
     */
    public function testCompileFailurePassingInvalidDirective()
    {
        $basePath             = __DIR__.DIRECTORY_SEPARATOR.'Fixtures'.DIRECTORY_SEPARATOR.'Failure'.DIRECTORY_SEPARATOR;
        $includeFile          = 'passing-invalid-directive';
        $includeFileExtension = '.txt';

        static::$buffer = sprintf('{{include(%s)}}', $includeFile.$includeFileExtension);
        static::$fixtureInstance = new TraitWrapper($basePath, static::$variableSet, static::$constantSet);
        static::$fixtureInstance->getCompiledResult(static::$buffer);
    }

    /**
     * Tests: Compiling a passed buffer with failure due to an invalid piece of PHP code.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @expectedException \Doozer\Syntax\Exception\CompilerException
     * @expectedExceptionMessage Error processing directive "{{include(passing-invalid-php-code.txt)}}".
     */
    public function testCompileFailurePassingInvalidPhpCode()
    {
        $basePath             = __DIR__.DIRECTORY_SEPARATOR.'Fixtures'.DIRECTORY_SEPARATOR.'Failure'.DIRECTORY_SEPARATOR;
        $includeFile          = 'passing-invalid-php-code';
        $includeFileExtension = '.txt';

        static::$buffer = sprintf('{{include(%s)}}', $includeFile.$includeFileExtension);
        static::$fixtureInstance = new TraitWrapper($basePath, static::$variableSet, static::$constantSet);
        static::$fixtureInstance->getCompiledResult(static::$buffer);
    }

    /**
     * Tests: Compiling a passed buffer with failure due to a missing replacement variable.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @expectedException \Doozer\Syntax\Exception\CompilerException
     * @expectedExceptionMessage Error processing directive "{{include(passing-missing-replacement.txt)}}".
     */
    public function testCompileFailurePassingMissingReplacement()
    {
        $basePath             = __DIR__.DIRECTORY_SEPARATOR.'Fixtures'.DIRECTORY_SEPARATOR.'Failure'.DIRECTORY_SEPARATOR;
        $includeFile          = 'passing-missing-replacement';
        $includeFileExtension = '.txt';

        static::$buffer = sprintf('{{include(%s)}}', $includeFile.$includeFileExtension);
        static::$fixtureInstance = new TraitWrapper($basePath, static::$variableSet, static::$constantSet);
        static::$fixtureInstance->getCompiledResult(static::$buffer);
    }
}
