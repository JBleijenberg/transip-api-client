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
namespace Transip\Api;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Transip\Api\Definition\ConfigConfiguration;
use Transip\Api\Loader\YamlLoader;

/**
 * Class Config
 * @package Transip\Api
 *
 * @method getEndpoint()
 * @method getUser()
 * @method getMode()
 * @method getApiVersion()
 */
class Config
{

    private static $instance;

    private $config;

    private function __construct()
    {
        $locator      = new FileLocator([getcwd()]);
        $loaderResolver = new LoaderResolver([new YamlLoader($locator)]);
        $delegatingLoader = new DelegatingLoader($loaderResolver);

        $yaml = $delegatingLoader->load('config.yaml');

        $processor = new Processor();
        $configuration = new ConfigConfiguration();

        try {
            $this->config = $processor->processConfiguration($configuration, $yaml);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Private key config can either be the key itself, or a path to the key
     *
     * @return String $private_key
     */
    public function getPrivateKey()
    {
        $private_key = $this->config['private_key'];

        if (is_file($private_key) && is_readable($private_key)) {
            return file_get_contents($private_key);
        }

        return $private_key;
    }

    /**
     * Get configuration value by magic getter
     *
     * @param $key
     * @param $value
     * @return null
     */
    public function __call($key, $value)
    {
        $type = substr($key, 0, 3);
        $key  = substr($key, 3);

        $this->convertStringToDataKey($key);

        if ($type == 'get' && array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }

        return null;
    }

    /**
     * Create data key for use with __call
     * @param $key
     */
    public function convertStringToDataKey(&$key)
    {
        $key = preg_replace('/(.)([A-Z])/', '$1_$2', $key);
        $key = strtolower($key);
    }
}