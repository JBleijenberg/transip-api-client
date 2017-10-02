<?php
/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 3.0)
 * that is bundled with this package in the file LICENSE
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/GPL-3.0
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @author      Jeroen Bleijenberg
 *
 * @copyright   Copyright (c) 2017
 * @license     http://opensource.org/licenses/GPL-3.0 General Public License (GPL 3.0)
 */
namespace Transip;

use Composer\Autoload\ClassLoader;

Class Api
{

    /**
     * Bootstrap Transpi Api
     *
     * @param ClassLoader|null $loader
     * @return Api\Application
     */
    public static function createApplication(ClassLoader $loader = null)
    {
        if ($loader === null) {
            $loader = self::getLoader();
        }

        $application = new Api\Application($loader);

        return $application;
    }

    /**
     * Include autoloader
     */
    public static function getLoader()
    {
        $autoloader = dirname(dirname(__DIR__)) . '/vendor/autoload.php';

        if (!file_exists($autoloader)) {
            throw new \ErrorException('Unable to find autoloader' . PHP_EOL);
        }

        return include $autoloader;
    }
}