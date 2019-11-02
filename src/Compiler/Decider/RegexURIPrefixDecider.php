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


use Skyline\Module\Compiler\RequestNormalizer;
use Symfony\Component\HttpFoundation\Request;

class RegexURIPrefixDecider extends LiteralURIPrefixDecider
{
    private $replacement;

    public function __construct(int $normalize = 0, string $URIPrefix = "", $replacement = false)
    {
        parent::__construct($normalize, $URIPrefix);
        $this->replacement = $replacement;
    }

    public function acceptFromRequest(Request $request, string $moduleName): bool
    {
        $uri = $request->getRequestUri();
        if(preg_match($this->getURIPrefix(), $uri)) {
            if(false !== ($repl = $this->getReplacement())) {
                return RequestNormalizer::normalizeRequestByReplacingURI($request, $this->getURIPrefix(), $repl);
            }
            return true;
        }
        return false;
    }

    /**
     * @return string|false
     */
    public function getReplacement()
    {
        return $this->replacement;
    }
}