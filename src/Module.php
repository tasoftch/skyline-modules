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


class Module implements ModuleInterface
{
    private $locations;
    private $services;
    private $parameters;
    private $classesDirectory;
    /** @var string */
    private $classPrefix;
    /** @var string|null */
    private $componentsDirectory;
    /** @var string|null */
    private $templatesDirectory;

    private $routingTable;

    /**
     * @return string|null
     */
    public function getComponentsDirectory(): ?string
    {
        return $this->componentsDirectory;
    }

    /**
     * @return string|null
     */
    public function getTemplatesDirectory(): ?string
    {
        return $this->templatesDirectory;
    }


    /**
     * @return mixed
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * @return mixed
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return mixed
     */
    public function getClassesDirectory(): ?string
    {
        return $this->classesDirectory;
    }

    /**
     * @return string
     */
    public function getClassPrefix(): string
    {
        return $this->classPrefix;
    }

    /**
     * @return mixed
     */
    public function getRoutingTable()
    {
        return $this->routingTable;
    }

    public function unserialize($serialized) {
        list(
            $this->locations,
            $this->services,
            $this->parameters,
            $this->classesDirectory,
            $this->classPrefix,
            $this->componentsDirectory,
            $this->templatesDirectory,
            $this->routingTable
            ) = unserialize($serialized);
    }
}