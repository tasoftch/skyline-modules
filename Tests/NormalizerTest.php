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

/**
 * NormalizerTest.php
 * skyline-modules
 *
 * Created on 2019-10-30 11:53 by thomas
 */

use PHPUnit\Framework\TestCase;
use Skyline\Module\Compiler\RequestNormalizer;
use Symfony\Component\HttpFoundation\Request;

class NormalizerTest extends TestCase
{
    public function testReplacement() {
        $r = Request::create("/my/file.txt");

        $this->assertTrue( RequestNormalizer::normalizeRequestByReplacingURI($r, '%^/?my/?(.*?)\.%i', '/your-$1.') );
        $this->assertEquals("/your-file.txt", $r->getRequestUri());

        // Bad formatted regex
        $this->assertFalse( RequestNormalizer::normalizeRequestByReplacingURI($r, '%^/?test', '') );
        $this->assertEquals("/your-file.txt", $r->getRequestUri());
    }

    public function testURIPrefix() {
        $r = Request::create("/my/uri/to/file.txt");
        $this->assertTrue( RequestNormalizer::normalizeRequestToHaveURIPrefix($r, '/admin'));

        $this->assertEquals("/admin/my/uri/to/file.txt", $r->getRequestUri());
        $this->assertTrue( RequestNormalizer::normalizeRequestToHaveURIPrefix($r, '/admin'));

        $this->assertEquals("/admin/my/uri/to/file.txt", $r->getRequestUri());

        $r = Request::create("/admin");
        $this->assertTrue( RequestNormalizer::normalizeRequestToHaveURIPrefix($r, '/admin'));
        $this->assertEquals("/admin", $r->getRequestUri());

        $this->assertTrue( RequestNormalizer::normalizeRequestToHaveURIPrefix($r, 'admin'));
        $this->assertEquals("/admin", $r->getRequestUri());


        $r = Request::create("/my/file.txt");
        $this->assertFalse( RequestNormalizer::normalizeRequestToHaveURIPrefix($r, '') );
        $this->assertEquals("/my/file.txt", $r->getRequestUri());
    }

    public function testNotURIPrefix() {
        $r = Request::create("/my/uri/to/file.txt");
        $this->assertTrue( RequestNormalizer::normalizeRequestToNotHaveURIPrefix($r, '/admin'));
        $this->assertEquals("/my/uri/to/file.txt", $r->getRequestUri());

        $r = Request::create("/admin");
        $this->assertTrue( RequestNormalizer::normalizeRequestToNotHaveURIPrefix($r, '/admin'));
        $this->assertEquals("/", $r->getRequestUri());

        $r = Request::create("/admin/test.txt");
        $this->assertTrue( RequestNormalizer::normalizeRequestToNotHaveURIPrefix($r, 'admin'));
        $this->assertEquals("/test.txt", $r->getRequestUri());


        $r = Request::create("/my/file.txt");
        $this->assertFalse( RequestNormalizer::normalizeRequestToNotHaveURIPrefix($r, '') );
        $this->assertEquals("/my/file.txt", $r->getRequestUri());
    }
}
