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

namespace Skyline\Module\Loader;


use Skyline\Kernel\Loader\LoaderInterface;
use Skyline\Kernel\Loader\RequestLoader;
use Skyline\Module\Compiler\Decider\DeciderInterface;
use Symfony\Component\HttpFoundation\Request;
use TASoft\Config\Config;

class ModuleLoader implements LoaderInterface
{
    private static $moduleName;

    public function __construct()
    {
    }

    public function bootstrap(Config $configuration)
    {
        if($md = SkyGetPath( "$(C)/modules.config.php")) {
            $moduleApplyers = require $md;
            /** @var Request $request */
            $request = RequestLoader::$request;

            foreach($moduleApplyers as $moduleName => $applyers) {
                foreach($applyers as $applyer) {
                    if($applyer = $this->createApplyer($applyer)) {
                        if($applyer->acceptFromRequest($request, $moduleName)) {
                            self::$moduleName = $moduleName;
                            break;
                        }
                    }
                }
            }
        }
    }

    private function createApplyer($applyer): ?DeciderInterface {
        if(is_string($applyer)) {
            return new $applyer();
        }

        if(is_object($applyer) && is_iterable($applyer))
            $applyer = iterator_to_array( $applyer );

        if(is_array($applyer)) {
            $class = array_shift($applyer);
            return new $class( ...array_values($applyer) );
        }
        return NULL;
    }

    /**
     * @return mixed
     */
    public static function getModuleName()
    {
        return self::$moduleName;
    }

    /**
     * Directly used in config files to apply additional configuration for modules.
     *
     * @param callable $originalConfiguration
     * @param $additionalConfiguration
     * @return array
     * @internal
     */
    public static function dynamicallyCompile(callable $originalConfiguration, $additionalConfiguration) {
        if($mn = static::getModuleName()) {
            $config = $additionalConfiguration[ $mn ] ?? NULL;

            if(is_string($config) && is_file($config))
                $config = new Config( require $config );
            elseif(is_iterable($config))
                $config = new Config( $config );
        }

        if(isset($config)) {
            $originalConfiguration = new Config( $originalConfiguration() );
            $originalConfiguration->merge( $config );
            return $originalConfiguration->toArray(true);
        }

        return $originalConfiguration();
    }
}