<?php

namespace Doozer\Syntax;

/**
 * Doozer - Syntax - SyntaxAwareTrait.
 *
 * SyntaxAwareTrait.php - Syntax trait enriching classes with parser, interpreter and compiler for expression support.
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

/**
 * Doozer - Syntax - Trait - SyntaxAwareTrait.
 *
 * Trait for syntax preprocessor and compiler support.
 */
trait SyntaxAwareTrait
{
    /**
     * The directives of Doozer syntax.
     * The bool value controls fail-on-error status (true|false).
     * Directives are executed before variables or constants are being resolved.
     *
     * If set to (bool)"true" then parser will fail with exception,
     * otherwise (bool)"false" will continue on error.
     *
     * @var array
     */
    protected $directives = [
        'include' => false,                                                                                             // Include Directive: {{include($filename)}}
        'require' => true,                                                                                              // Require Directive: {{require($filename)}}
    ];

    /**
     * The functions of Doozer syntax.
     * The bool value controls fail-on-error status (true|false).
     * Functions are executed after variables or constants are being resolved.
     * So variables and or constants can be used as arguments.
     *
     * If set to (bool)"true" then parser will fail with exception,
     * otherwise (bool)"false" will continue on error.
     *
     * @var array
     */
    protected $functions = [
        'php' => true,                                                                                                  // PHP Function:     {{php(function(){...})}}
    ];

    /**
     * Key => value collection of constants for compiling.
     *
     * @var array
     */
    protected $constants = [];

    /**
     * Key => value collection of variables for compiling.
     *
     * @var array
     */
    protected $variables = [];

    /**
     * Returns the constraint for a passed function.
     *
     * @param string $identifier Identifier of constraint to return.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return mixed
     */
    protected function getFunctionConstraint($identifier)
    {
        return $this->functions[$identifier];
    }

    /**
     * Setter for functions.
     *
     * @param array $functions Functions to set.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    protected function setFunctions(array $functions = [])
    {
        $this->functions = $functions;
    }

    /**
     * Fluent: Setter for functions.
     *
     * @param array $functions Functions to set.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return $this Instance for chaining
     */
    protected function functions(array $functions = [])
    {
        $this->setFunctions($functions);

        return $this;
    }

    /**
     * Getter for functions.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return array|null Collection of functions if set, otherwise NULL
     */
    protected function getFunctions()
    {
        return $this->functions;
    }

    /**
     * Validates a passed function against defined ones.
     *
     * @param string $function Function to validate
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return bool TRUE if function is valid, otherwise FALSE
     */
    protected function hasFunction($function)
    {
        return isset($this->getFunctions()[$function]);
    }

    /**
     * Returns the extracted functions of a passed string.
     *
     * @param string $buffer Buffer to extract functions from
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return array Of found functions or empty one if no matches
     */
    protected function extractFunctions($buffer)
    {
        $pattern = '/\{{2}(php)\({1}([A-Za-z0-9_\-\.\{\}\\\'\(\)\s\;\/$=<>]+)\){1}\}{2}/u';
        preg_match_all($pattern, $buffer, $result);

        return $result;
    }

    /**
     * Returns the constraint for a passed directive.
     *
     * @param string $identifier Identifier of constraint to return.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return mixed
     */
    protected function getDirectiveConstraint($identifier)
    {
        return $this->directives[$identifier];
    }

    /**
     * Setter for directives.
     *
     * @param array $directives Directives to set.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    protected function setDirectives(array $directives = [])
    {
        $this->directives = $directives;
    }

    /**
     * Fluent: Setter for directives.
     *
     * @param array $directives Directives to set.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return $this Instance for chaining
     */
    protected function directives(array $directives = [])
    {
        $this->setDirectives($directives);

        return $this;
    }

    /**
     * Getter for directives.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return array|null Collection of directives if set, otherwise NULL
     */
    protected function getDirectives()
    {
        return $this->directives;
    }

    /**
     * Validates a passed directive against defined ones.
     *
     * @param string $directive Directive to validate
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return bool TRUE if directive is valid, otherwise FALSE
     */
    protected function hasDirective($directive)
    {
        return isset($this->getDirectives()[$directive]);
    }

    /**
     * Returns the extracted directives of a passed string.
     *
     * @param string $buffer Buffer to extract directives from
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return array Of found directives or empty one if no matches
     */
    protected function extractDirectives($buffer)
    {
        $pattern = '/\{{2}([a-z]+)\({1}([A-Za-z0-9_\-\.]+)\){1}\}{2}/u';
        preg_match_all($pattern, $buffer, $result);

        return $result;
    }

    /**
     * Setter for constants.
     *
     * @param array $constants Variables to set.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    protected function setConstants(array $constants = [])
    {
        $this->constants = $constants;
    }

    /**
     * Fluent: Setter for constants.
     *
     * @param array $constants Variables to set.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return $this Instance for chaining
     */
    protected function constants(array $constants = [])
    {
        $this->setConstants($constants);

        return $this;
    }

    /**
     * Getter for constants.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return array|null Collection of constants if set, otherwise NULL
     */
    protected function getConstants()
    {
        return $this->constants;
    }

    /**
     * Returns the extracted constants of a passed string.
     *
     * @param string $buffer Buffer to parse
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return array Of found constants or empty one if no matches
     */
    protected function extractConstants($buffer)
    {
        $pattern = '/\{{2}([A-Z0-9_]{1,255})\}{2}/';
        preg_match_all($pattern, $buffer, $result);

        return array_unique($result[1]);
    }

    /**
     * Setter for variables.
     *
     * @param array $variables Variables to set.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    protected function setVariables(array $variables = [])
    {
        $this->variables = $variables;
    }

    /**
     * Fluent: Setter for variables.
     *
     * @param array $variables Variables to set.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return $this Instance for chaining
     */
    protected function variables(array $variables = [])
    {
        $this->setVariables($variables);

        return $this;
    }

    /**
     * Getter for variables.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return array|null Collection of variables if set, otherwise NULL
     */
    protected function getVariables()
    {
        return $this->variables;
    }

    /**
     * Returns the extracted variables of a passed string.
     *
     * @param string $buffer Buffer to parse
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return array Of found constants or empty one if no matches
     */
    protected function extractVariables($buffer)
    {
        $pattern = '/\{{2}([a-z0-9\._]{1,255})\}{2}/';
        preg_match_all($pattern, $buffer, $result);

        return array_unique($result[1]);
    }

    /**
     * Combines the whole stack of calls required to compile any kind of Doozer syntax.
     *
     * @param string $sourceCode Source code to compile
     *
     * @return string Compiled source code.
     *
     * throws PreprocessorException|CompilerException|ResolvePlaceholderException
     */
    protected function compile($sourceCode)
    {
        $sourceCode = $this->preProcess($sourceCode);
        $sourceCode = $this->resolve($sourceCode);
        $sourceCode = $this->postProcess($sourceCode);

        return $sourceCode;
    }

    /**
     * Pre-processes source and returns interpolated content for compiler.
     *
     * @link http://www.cprogramming.com/reference/preprocessor/
     *
     * @param string $sourceCode Source to process
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return string Processed result
     *
     * @throws SyntaxException|PreprocessorException
     */
    protected function preProcess($sourceCode)
    {
        $directives      = $this->extractDirectives($sourceCode);
        $countDirectives = count($directives[0]);

        for ($i = 0; $i < $countDirectives; ++$i) {
            $syntax    = $directives[0][$i];
            $directive = $directives[1][$i];
            $argument  = $directives[2][$i];

            if (true !== $this->hasDirective($directive)) {
                throw new SyntaxException(
                    sprintf('Syntax error. Directive "%s" is invalid and could not be processed.', $directive)
                );
            }

            try {
                $sourceCode = str_replace(
                    sprintf('"%s"', $directives[0][$i]),
                    $this->execute($directive, $argument),
                    $sourceCode
                );
            } catch (\RuntimeException $exception) {
                throw new PreprocessorException(
                    sprintf('Error processing directive "%s".', $syntax),
                    null,
                    $exception
                );
            }
        }

        return $sourceCode;
    }

    /**
     * Post processing of source code.
     * This part of compiler is responsible for executing function calls which can
     * rely on previously resolved variables or constants.
     *
     * @param string $sourceCode Source code being post processed.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return string Post processed source code.
     *
     * @throws SyntaxException|PreprocessorException
     */
    protected function postProcess($sourceCode)
    {
        $functions      = $this->extractFunctions($sourceCode);
        $countFunctions = count($functions[0]);

        for ($i = 0; $i < $countFunctions; ++$i) {
            $syntax   = $functions[0][$i];
            $function = $functions[1][$i];
            $argument = $functions[2][$i];

            if (true !== $this->hasFunction($function)) {
                throw new SyntaxException(
                    sprintf('Syntax error. Function "%s" is invalid and could not be processed.', $function)
                );
            }

            try {
                $result = $this->execute($function, $argument);

                if (true === is_string($result)) {
                    $marker = '%s';
                } else {
                    $marker = '"%s"';
                    $result = json_encode($result);
                }

                $sourceCode = str_replace(
                    sprintf($marker, $functions[0][$i]),
                    $result,
                    $sourceCode
                );
            } catch (\RuntimeException $exception) {
                throw new PreprocessorException(
                    sprintf('Error processing function "%s".', $syntax),
                    null,
                    $exception
                );
            }
        }

        return $sourceCode;
    }

    /**
     * Executes a function call.
     *
     * @param $directive
     * @param $argument
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     */
    protected function execute($directive, $argument)
    {
        return $this->{'__'.$directive}($argument);
    }

    /**
     * Compiles (replacing {{VARIABLES & CONSTANTS}}) a preprocessed buffer to final result.
     *
     * @param string $buffer Buffer to compile
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return string Compiled buffer
     *
     * @throws CompilerException|ResolvePlaceholderException
     */
    protected function resolve($buffer)
    {
        // Replace constants
        $buffer = $this->resolvePlaceholder(
            $buffer,
            $this->extractConstants($buffer),
            $this->getConstants()
        );

        // Replace variables
        $buffer = $this->resolvePlaceholder(
            $buffer,
            $this->extractVariables($buffer),
            $this->getVariables()
        );

        return $buffer;
    }

    /**
     * Generic placeholder replacer for variables & constants.
     *
     * @param string $buffer       Buffer to resolve placeholder in.
     * @param array  $placeholders Collection of placeholders to be replaced.
     * @param array  $replacements Collection of key => value pairs used for resolving.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return string Buffer with replaced content.
     *
     * @throws ResolvePlaceholderException
     */
    protected function resolvePlaceholder($buffer, array $placeholders, array $replacements)
    {
        foreach ($placeholders as $placeholder) {
            if (false === isset($replacements[$placeholder])) {
                throw new ResolvePlaceholderException(
                    sprintf('Compiler error for placeholder "%s". Value not found!', $placeholder)
                );
            }
            $buffer = str_replace('{{'.$placeholder.'}}', $replacements[$placeholder], $buffer);
        }

        return $buffer;
    }

    /**
     * Implementation of __php.
     *
     * @param string $code Code to be executed
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     *
     * @return string Result of operation
     *
     * @throws ExecutionFailedException|ExecutionResultException
     */
    protected function __php($code)
    {
        // Inject return to receive closure via eval()
        $code = 'return '.$code;
        $code = @eval($code);

        if (false === $code) {
            throw new ExecutionFailedException(
                sprintf('Executing PHP code "%s" failed.', $code)
            );
        }

        // Execute closure and receive result (must be string or \stdClass)
        $result = $code();

        if (false === is_string($result) && false === $result instanceof \stdClass) {
            throw new ExecutionResultException(
                sprintf(
                    'Executed PHP code must return a string or value \stdClass but returned "%s" instead.',
                    gettype($result)
                )
            );
        }

        return $result;
    }

    /*------------------------------------------------------------------------------------------------------------------
    | BLUEPRINT
    +-----------------------------------------------------------------------------------------------------------------*/

    /**
     * Implementation of __include.
     *
     * @param mixed $identifier Identifier for include.
     *
     * @return mixed Result
     */
    abstract protected function __include($identifier);

    /**
     * Implementation of __require.
     *
     * @param mixed $identifier Identifier for require.
     *
     * @return mixed Result
     */
    abstract protected function __require($identifier);
}
