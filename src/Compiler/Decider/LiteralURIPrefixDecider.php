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

namespace Skyline\Module\Compiler\Decider;


use Symfony\Component\HttpFoundation\Request;

class LiteralURIPrefixDecider implements DeciderInterface
{
    private $URIPrefix;

    /**
     * LiteralURIPrefixDecider constructor.
     * @param $URIPrefix
     */
    public function __construct($URIPrefix)
    {
        $this->URIPrefix = $URIPrefix;
    }

    /**
     * @return mixed
     */
    public function getURIPrefix()
    {
        return $this->URIPrefix;
    }

    /**
     * @inheritDoc
     */
    public function acceptFromRequest(Request $request, string $moduleName): bool
    {
        $uri = $request->getRequestUri();
        return stripos($uri, $this->getURIPrefix()) === 0 ? true : false;
    }
}