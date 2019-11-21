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

namespace Skyline\Module\Plugin;


use Skyline\Application\Plugin\Router\ApplicationRouterPlugin;
use Skyline\Module\Config\ModuleConfig;
use Skyline\Module\Loader\ModuleLoader;
use Skyline\Router\AbstractRouterPlugin;
use Skyline\Router\RouterInterface;

class ApplicationRouterPluginWithModules extends ApplicationRouterPlugin
{
    protected function getRouterForSection(string $section, array $contents, int &$priority): ?RouterInterface
    {
        static $moduleName = NULL;
        NULL === $moduleName && $moduleName = ModuleLoader::getModuleInformation()[ ModuleConfig::MODULE_NAME ] ?? false;

        if($moduleName) {
            $newContents = [];
            foreach($contents as $key => $value) {
                if(isset( $value[ AbstractRouterPlugin::ROUTED_CONTROLLER_KEY ] )) {
                    // Any route to a controller must specify the current module

                    if($moduleName == ($value[ AbstractRouterPlugin::ROUTED_MODULE_KEY ] ?? '########'))
                        $newContents[$key] = $value;
                } else {
                    $newContents[$key] = $value;
                }
            }

            $contents = $newContents;
        }

        return parent::getRouterForSection($section, $contents, $priority);
    }
}