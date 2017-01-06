<?php

namespace SalesTest;

use Application\Utility\Strings;
use SalesTest\Bootstrap;
use Zend\Loader\AutoloaderFactory;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;

error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

/**
 * Test bootstrap, for setting up autoloading
 */
class Bootstrap {

    protected static $serviceManager;

    public static function init() {
        $zf2ModulePaths = array(dirname(dirname(__DIR__)));
        if (($path = static::findParentPath('vendor'))) {
            $zf2ModulePaths[] = $path;
        }
        if (($path = static::findParentPath('module')) !== $zf2ModulePaths[0]) {
            $zf2ModulePaths[] = $path;
        }

        static::initAutoloader();

        // use ModuleManager to load this module and it's dependencies
        $config = array(
            'module_listener_options' => array(
                'module_paths' => $zf2ModulePaths,
            ),
            'modules' => array(
                'Zend\Navigation',
                'Zend\ServiceManager\Di',
                'Zend\Mvc\Plugin\FilePrg',
                'Zend\Mvc\Plugin\FlashMessenger',
                'Zend\Mvc\Plugin\Identity',
                'Zend\Session',
                'Zend\Mvc\Console',
                'Zend\InputFilter',
                'Zend\Filter',
                'Zend\Hydrator',
                'Zend\I18n',
                'Zend\Log',
                'Zend\Form',
                'Zend\Db',
                'Zend\Router',
                'Zend\Validator',
                'Zend\Mvc\Plugin\FlashMessenger',
                'DoctrineModule',
                'DoctrineORMModule',
                'DataAccess',
                'Application',
                'Album',
                'Login',
                'Sales',
                'MyAcl',
                'Ajax',
                'Command'
            )
        );
        $servicemanagerconfig = new ServiceManagerConfig();
        $servicemanagerconfigarr = $servicemanagerconfig->toArray();
        $serviceManager = new ServiceManager($servicemanagerconfigarr);
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();
        static::$serviceManager = $serviceManager;
    }

    public static function chroot() {
        $rootPath = dirname(static::findParentPath('module'));
        chdir($rootPath);
    }

    public static function getServiceManager() {
        return static::$serviceManager;
    }

    protected static function initAutoloader() {
        $vendorPath = static::findParentPath('vendor');

        if (file_exists($vendorPath . '/autoload.php')) {
            include $vendorPath . '/autoload.php';
        }

        if (!class_exists('Zend\Loader\AutoloaderFactory')) {
            throw new RuntimeException(
            'Unable to load ZF2. Run `php composer.phar install`'
            );
        }

        AutoloaderFactory::factory(array(
            Strings::STANDARD_AUTO_LOADER => array(
                'autoregister_zf' => true,
                Strings::NAMESPACES => array(
                    __NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
                ),
            ),
        ));
    }

    protected static function findParentPath($path) {
        $dir = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) {
                return false;
            }
            $previousDir = $dir;
        }
        return $dir . '/' . $path;
    }

}

try {
    Bootstrap::init();
    Bootstrap::chroot();
} catch (Exception $exc) {
    echo $exc->getTraceAsString();
}

