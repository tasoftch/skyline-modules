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


use DirectoryIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Skyline\Compiler\AbstractCompiler;
use Skyline\Compiler\CompilerConfiguration;
use Skyline\Compiler\CompilerContext;
use Skyline\Compiler\Context\Code\SourceFile;
use Skyline\HTMLRender\Compiler\FindHTMLTemplatesCompiler;
use Skyline\Module\Config\ModuleConfig;
use Skyline\Render\Compiler\FindTemplatesCompiler;

class ModuleCompiler extends AbstractCompiler
{
    private $templates;

    public function compile(CompilerContext $context)
    {
        $modules = [];
        $applyers = [];

        $services = [];
        $locations = [];

        $dynamicConfigurations = [];


        $configFiles = $this->_findCompiledConfigFiles($context);

        $moduleTemplates = [];

        foreach($context->getSourceCodeManager()->yieldSourceFiles("/^module\.cfg\.php$/i") as $moduleFile) {
            $config = require $moduleFile;
            if(!isset($config[ ModuleConfig::MODULE_NAME ])) {
                $config[ ModuleConfig::MODULE_NAME ] = basename(dirname($moduleFile));
            }

            $config[ ModuleConfig::COMPILED_MODULE_PATH ] = $context->getRelativeProjectPath( $modPath = realpath( dirname($moduleFile) ) );
            $mn = $config[ModuleConfig::MODULE_NAME];

            if($cp = $config[ ModuleConfig::CLASS_DIRECTORY_NAME ] ?? NULL)
                $config[ ModuleConfig::CLASS_DIRECTORY_NAME ] = $context->getRelativeProjectPath( $cp );


            if($apply = $config[ ModuleConfig::MODULE_DECIDER_CLASSES ] ?? NULL) {
                foreach ($apply as $a)
                    $applyers[$mn][] = $a;
            } else {
                trigger_error(sprintf("** Module %s is without deciders, so it will never be applyed", $config[ModuleConfig::MODULE_NAME]), E_USER_WARNING);
                continue;
            }

            // Register custom configuration
            if(is_file($f = $modPath.DIRECTORY_SEPARATOR."services.config.php")) {
                $services[ $mn ] = $config[ ModuleConfig::COMPILED_SERVICE_CONFIG_PATH ] = $context->getRelativeProjectPath( realpath($f) );
            }
            if(is_file($f = $modPath.DIRECTORY_SEPARATOR."locations.config.php")) {
                $locations[ $mn ] = $config[ ModuleConfig::COMPILED_LOCATION_CONFIG_PATH ] = $context->getRelativeProjectPath( realpath($f) );
            }

            foreach($configFiles as $cfgFile) {
                if(is_file($f = $modPath.DIRECTORY_SEPARATOR.$cfgFile)) {
                    $dynamicConfigurations[$cfgFile][$mn] = $context->getRelativeProjectPath( realpath($f) );
                }
            }

            $this->templates = [];
            $this->compileIncludedTemplates($modPath, $mn, $context);

            if($this->templates) {
                $moduleTemplates[$mn] = $this->templates;
            }

            $modules[  $mn ] = $config;
        }


        $dir = $context->getSkylineAppDirectory( CompilerConfiguration::SKYLINE_DIR_COMPILED );


        if($moduleTemplates) {
            if(is_file($f = "$dir/templates.config.php")) {
                $this->_createDynamicConfiguredFile($dir, 'templates.config.php', $moduleTemplates);
            }
        }


        foreach($dynamicConfigurations as $fileName => $dynamicConfiguration) {
            $this->_createDynamicConfiguredFile($dir, $fileName, $dynamicConfiguration);
        }

        $data = var_export(['#' => $applyers, '@' => $modules], true);
        $data = '<?php
  return ' . $data . ";";

        file_put_contents($dir.DIRECTORY_SEPARATOR."modules.config.php", $data);
    }

    private function _findCompiledConfigFiles(CompilerContext $context) {
        $items = ['plugins.php'];

        $dir = $context->getSkylineAppDirectory( CompilerConfiguration::SKYLINE_DIR_COMPILED );
        foreach(new DirectoryIterator($dir) as $item) {
            if(preg_match("/^\w+\.config\.php$/i", $item)) {
                if(stripos($item, 'main.config.php') === 0)
                    continue;
                if(stripos($item, 'routing.config.php') === 0)
                    continue;
                $items[] = (string) $item;
            }
        }
        return $items;
    }


    private function _createDynamicConfiguredFile($compiledDir, $fileName, $dynamicConfig) {
        $code = "";
        foreach(token_get_all( file_get_contents( $compiledDir . DIRECTORY_SEPARATOR . $fileName ) ) as $token) {
            if(is_array($token)) {
                if($token[0] == T_OPEN_TAG || $token[0] == T_CLOSE_TAG)
                    continue;
                $token = $token[1];
            }

            $code .= $token;
        }

        if(!preg_match("/;\s*$/i", $code))
            $code .= ";";

        $data = var_export( $dynamicConfig, true );

        $content = "<?php
use Skyline\\Module\\Loader\\ModuleLoader;
return ModuleLoader::dynamicallyCompile(function() {
$code}, $data);";

        file_put_contents( $compiledDir . DIRECTORY_SEPARATOR . $fileName, $content );
    }

    private function compileIncludedTemplates($moduleDirectory, $moduleName, $context) {
        static $templateCompilers = [];
        if(!$templateCompilers) {
            $templateCompilers = [
                new FindTemplatesCompiler('module-templates'),
                new FindHTMLTemplatesCompiler('module-html-templates')
            ];
        }

        foreach(new RecursiveIteratorIterator( new RecursiveDirectoryIterator($moduleDirectory, RecursiveDirectoryIterator::FOLLOW_SYMLINKS| RecursiveDirectoryIterator::SKIP_DOTS) ) as $item) {
            /** @var FindTemplatesCompiler $compiler */
            foreach($templateCompilers as $compiler) {
                if(preg_match( $compiler->getTemplateFilenamePattern(), basename($item) )) {
                    $src = new SourceFile($item);
                    $compiler->compileTemplate($src, $this->templates, $context);
                }
            }
        }
    }
}