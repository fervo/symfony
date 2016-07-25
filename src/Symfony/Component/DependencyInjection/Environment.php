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

use Symfony\Component\DependencyInjection\Exception\EnvironmentVariableNotFoundException;

/**
 * @author Magnus Nordlander <magnus@fervo.se>
 * @internal
 */
class Environment
{
    public static function getVariable($name, $default = null)
    {
        if (isset($_ENV[$name])) {
            return $_ENV[$name];
        }

        if (false !== $value = getenv($name)) {
            return $value;
        }

        if (2 > func_num_args()) {
            throw new EnvironmentVariableNotFoundException($name);
        }

        return $default;
    }
}
