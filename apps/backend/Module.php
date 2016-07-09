<?php

namespace Corephalcon\Backend;

use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Config;


class Module implements ModuleDefinitionInterface
{
    /**
     * Registers an autoloader related to the module
     *
     * @param DiInterface $di
     */
    public function registerAutoloaders(DiInterface $di = null)
    {
        $loader = new Loader();

        $loader->registerNamespaces(array(
            'Corephalcon\Backend\Controllers' => '../apps/backend/controllers/',
            'Corephalcon\Modeldb\Models' => '../apps/modeldb/models/',
            'Corephalcon\Commons' => '../apps/commons/'
        ));

        $loader->register();
    }

    /**
     * Registers services related to the module
     *
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {
        /**
         * Read common configuration
         */
        $config = $di->has('config') ? $di->getShared('config') : null;

        /**
         * Try to load local configuration
         */
//        if (file_exists(__DIR__ . '/config/config.php')) {
//            $override = new Config(include __DIR__ . 'apps/backend/config/config.php');;
//
//            if ($config instanceof Config) {
//                $config->merge($override);
//            } else {
//                $config = $override;
//            }
//        }
        //$config = include APP_PATH . "/config/config.php";
        $config = include APP_PATH . "/apps/backend/config/config.php";
        /**
         * Setting up the view component
         */
        $di['view'] = function () use ($config) {
            $view = new View();
            $view->setViewsDir($config->get('application')->viewsDir);

            return $view;
        };

        /**
         * Database connection is created based in the parameters defined in the configuration file
         */
        $di['db'] = function () use ($config) {
            $config = $config->database->toArray();

            $dbAdapter = '\Phalcon\Db\Adapter\Pdo\\' . $config['adapter'];
            unset($config['adapter']);

            return new $dbAdapter($config);
        };
    }
}
