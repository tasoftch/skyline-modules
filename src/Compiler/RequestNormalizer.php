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

namespace Skyline\Module\Compiler;


use Symfony\Component\HttpFoundation\Request;

/**
 * Normalizing the request means to bring a request into a default structure.
 * This is useful to apply routings:
 *
 * Example: a decider for admin:
 *  accepts Admin module if the subdomain is admin
 *  But the action controllers did register their routings for /admin/...
 *  So with the normalizer you are able to add the /admin prefix to uri.
 *
 * @package Skyline\Module\Compiler
 */
abstract class RequestNormalizer
{
    /**
     * Normalize a URI prefix.
     *  Means: if $prefix is /admin, the normalized request's URI will always begin with /admin....
     *
     * @param Request $request
     * @param string $prefix
     * @return bool
     */
    public static function normalizeRequestToHaveURIPrefix(Request $request, string $prefix): bool {
        if($prefix) {
            if($prefix[0] == '/')
                $prefix = substr($prefix, 1);

            $pfx = $prefix;
            if(substr($pfx, -1) == '/')
                $pfx = substr($pfx, 0, strlen($pfx)-1);

            if(!preg_match( "/^\/?".preg_quote($pfx, '/')."/i", $uri = $request->getRequestUri() )) {
                if($uri[0] != "/")
                    $uri = sprintf("/%s/%s", $prefix, $uri);
                else
                    $uri = sprintf("/%s%s", $prefix, $uri);

                (
                (function() use ($request, $uri) {
                    /** @noinspection Annotator */
                    $request->requestUri = $uri;
                })->bindTo($request, Request::class)
                )();
            }
            return true;
        }
        return false;
    }

    /**
     * Basically the same like normalizeRequestToHaveURIPrefix but will remove the prefix if given.
     * This can be useful if you route action controllers generally. Example edit user => /users/edit/user-id
     *  If the client is requesting https://admin.domain.org/users/edit/user-id you can specify the module and the routing is valid.
     *  But requesting https://www.domain.org/users/edit-id will fail because without the module admin, the routing is not available
     *  Or even if you want to specify another module that is able to edit users, you can use the same routing, but different modules.
     *
     * @param Request $request
     * @param string $prefix
     * @return bool
     */
    public static function normalizeRequestToNotHaveURIPrefix(Request $request, string $prefix): bool {
        if($prefix) {
            if($prefix[0] == '/')
                $prefix = substr($prefix, 1);

            $pfx = $prefix;
            if(substr($pfx, -1) == '/')
                $pfx = substr($pfx, 0, strlen($pfx)-1);

            if(preg_match( "/^\/?".preg_quote($pfx, '/')."/i", $uri = $request->getRequestUri(), $ms )) {
                $uri = substr($uri, strlen($ms[0]));

                if($uri == "")
                    $uri = "/";

                (
                (function() use ($request, $uri) {
                    /** @noinspection Annotator */
                    $request->requestUri = $uri;
                })->bindTo($request, Request::class)
                )();
            }
            return true;
        }
        return false;
    }

    /**
     * Replaces a request uri
     *
     * @param Request $request
     * @param $preg_pattern
     * @param $preg_substitute
     * @return bool
     */
    public static function normalizeRequestByReplacingURI(Request $request, $preg_pattern, $preg_substitute): bool {
        $uri = $request->getRequestUri();

        error_clear_last();

        $u = @preg_replace($preg_pattern, $preg_substitute, $uri);
        if(error_get_last()) {
            error_clear_last();
            return false;
        }

        if($uri != $u) {
            (
                (function() use ($request, $u) {
                    /** @noinspection Annotator */
                    $request->requestUri = $u;
                })->bindTo($request, Request::class)
            )();
        }

        return true;
    }
}