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

abstract class AbstractNormalizingDecider implements DeciderInterface
{
    const NORMALIZE_TO_HAVE_URI = 1;
    const NORMALIZE_TO_NOT_HAVE_URI = 2;

    private $normalize = 0;
    private $URIPrefix = "";

    /**
     * AbstractSubdomainDecider constructor.
     * @param int $normalize
     * @param string $URIPrefix
     */
    public function __construct(int $normalize = 0, string $URIPrefix = "")
    {
        $this->normalize = $normalize;
        $this->URIPrefix = $URIPrefix;
    }

    /**
     * @return int
     */
    public function getNormalize(): int
    {
        return $this->normalize;
    }

    /**
     * @return string
     */
    public function getURIPrefix(): string
    {
        return $this->URIPrefix;
    }


    public function acceptFromRequest(Request $request, string $moduleName): bool
    {
        $cv = $this->getComparisonValue($request, $moduleName);

        if($this->matchComparisonValue($cv, $moduleName)) {
            if($prefix = $this->getURIPrefix()) {
                switch ($this->getNormalize()) {
                    case static::NORMALIZE_TO_HAVE_URI:
                        return RequestNormalizer::normalizeRequestToHaveURIPrefix($request, $prefix);
                        break;
                    case static::NORMALIZE_TO_NOT_HAVE_URI:
                        return RequestNormalizer::normalizeRequestToNotHaveURIPrefix($request, $prefix);
                        break;
                    default:
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Creates a comparison value
     *
     * @param Request $request
     * @param string $moduleName
     * @return mixed
     */
    abstract protected function getComparisonValue(Request $request, string $moduleName);

    /**
     * Called to determine if the subdomains match.
     * The subdomains are passed as array like:
     *  - www.tasoft.ch => [www],
     *  - tasoft.ch => [],
     *  - www.api.tasoft.ch => [www, api]
     *
     * @param array $subdomains
     * @param string $moduleName
     * @return bool
     */
    abstract protected function matchComparisonValue($subdomains, $moduleName): bool;
}