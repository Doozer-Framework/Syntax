<?php

namespace Doozer\Syntax\Tests\Fixtures;

/**
 * Doozer - Syntax - Tests - Fixtures - TraitWrapper.
 *
 * TraitWrapper.php - Wrapper implementing trait for Unit-Tests.
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
use Doozer\Syntax\SyntaxAwareTrait;

/**
 * TraitWrapper
 * @author Benjamin Carl <opensource@clickalicious.de>
 */
class TraitWrapper
{
    // Define trait we are going to test here ...
    use SyntaxAwareTrait;

    /**
     * Constructor.
     *
     * @param string $basePath  Base path used for relative includes and requires.
     * @param array  $variables Variables being used for replacements.
     * @param array  $constants Constants being used for replacements.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    public function __construct($basePath, array $variables = [], array $constants = [])
    {
        $this
            ->basePath($basePath)
            ->variables($variables)
            ->constants($constants);
    }

    /**
     * Accessor with public visibility for result check in unit tests.
     *
     * @param string $sourceCode The source code to compile.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return string Result of compiler run
     */
    public function getCompiledResult($sourceCode)
    {
        return $this->compile($sourceCode);
    }
}
