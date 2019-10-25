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

namespace Skyline\Module;

use TASoft\Config\Config;

/**
 * A module can extend an existing application setup by several specific information.
 *
 * The module is determined while application bootstrap and is then able to adjust several settings.
 *
 * @package Skyline\Module
 */
interface ModuleInterface
{
    /**
     * Gets additional locations
     *
     * @return array|Config
     */
    public function getLocations();

    /**
     * Get module specific parameters
     * @return array|Config
     */
    public function getParameters();

    /**
     * Gets additional services
     *
     * @return array|Config
     */
    public function getServices();

    /**
     * Gets additional routings
     *
     * @return array|Config
     */
    public function getRoutingTable();

    /**
     * Gets additional module classes or null, if there are no further classes available
     *
     * @return string|null
     */
    public function getClassesDirectory(): ?string;

    /**
     * If your module uses classes, you must declare the module prefix.
     * Ex: Application\MyModule
     *
     * @return string
     */
    public function getClassPrefix(): string;

    /**
     * Further components
     *
     * @return string|null
     */
    public function getComponentsDirectory(): ?string;

    /**
     * Further templates
     *
     * @return string|null
     */
    public function getTemplatesDirectory(): ?string;
}