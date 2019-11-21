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
    const MODULE_NAME = 'module-name';      // Manual or compiled (would be directory name)
    const COMPILED_MODULE_PATH = 'module-path';      // Compiled

    const CLASS_DIRECTORY_NAME = 'class-directory'; // Manual
    const CLASS_PREFIX = 'class-prefix';            // Manual

    /** @var string The deciders for a module, can be listed as class names or arrays, where the array's first element must be the class name and any further element is passed as argument. Please note that no services and parameters are available yet! */
    const MODULE_DECIDER_CLASSES = 'decider-classess';


    const COMPILED_SERVICE_CONFIG_PATH = 'sc-path';
    const COMPILED_LOCATION_CONFIG_PATH = 'lc-path';
    const COMPILED_PARAMETERS_CONFIG_PATH = 'ptr-path';

    // Set this key to true and the module loader will only load the module configurations except the main configuration.
    const COMPILED_IGNORE_DEFAULT = 'ignore-def';
}