<?php
/**
 * Copyright (c) 2019 TASoft Applications, Th. Abplanalp <info@tasoft.ch>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Skyline\Module\Config;

/**
 * The module.cfg.php file can contain this constant keys to specify the module.
 *
 *
 * @package Skyline\Module\Config
 */
class ModuleConfig
{
    const MODULE_NAME = 'module-name';  // Required! Without this key the module is not registered.

    const CONFIG_LOCATIONS = 'locations';
    const CONFIG_PARAMETERS = 'parameters';
    const CONFIG_SERVICES = 'services';

    const CONFIG_CLASS_DIRECTORY_NAME = 'class-directory';
    const CONFIG_CLASS_PREFIX = 'class-prefix';
    const CONFIG_COMPONENTS_DIRECTORY_NAME = 'components-directory';
    const CONFIG_TEMPLATES_DIRECTORY_NAME = 'templates-directory';

    protected static $defaults = [
        self::CONFIG_SERVICES => [],
        self::CONFIG_LOCATIONS => [],
        self::CONFIG_PARAMETERS => [],

        self::CONFIG_CLASS_DIRECTORY_NAME => 'Classes',
        self::CONFIG_COMPONENTS_DIRECTORY_NAME => 'Components',
        self::CONFIG_TEMPLATES_DIRECTORY_NAME => 'Templates',
    ];


    /**
     * Helper method to define default values
     *
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public static function getDefault(string $key, $default = NULL) {
        return static::$defaults[ $key ] ?? $default;
    }
}