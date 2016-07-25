<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection;

use Symfony\Component\DependencyInjection\Environment;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

/**
 * Define some ExpressionLanguage functions.
 *
 * To get a service, use service('request').
 * To get a parameter, use parameter('kernel.debug').
 * To get an environment variable, use env('UID').
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions()
    {
        return array(
            new ExpressionFunction('service', function ($arg) {
                return sprintf('$this->get(%s)', $arg);
            }, function (array $variables, $value) {
                return $variables['container']->get($value);
            }),

            new ExpressionFunction('parameter', function ($arg) {
                return sprintf('$this->getParameter(%s)', $arg);
            }, function (array $variables, $value) {
                return $variables['container']->getParameter($value);
            }),

            new ExpressionFunction('env', function ($arg, $default = null) {
                if (2 > func_num_args()) {
                    return sprintf('Symfony\\Component\\DependencyInjection\\Environment::getVariable(%s)', $arg);
                }

                return sprintf('Symfony\\Component\\DependencyInjection\\Environment::getVariable(%s, %s)', $arg, $default);
            }, function (array $variables, $value, $default = null) {
                if (3 > func_num_args()) {
                    return Environment::getVariable($value);
                }

                return Environment::getVariable($value, $default);
            }),
        );
    }
}
