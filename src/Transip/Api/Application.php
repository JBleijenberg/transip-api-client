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

use Composer\Autoload\ClassLoader;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Application extends SymfonyApplication
{

    const APP_NAME = 'transip-api-client';

    const APP_VERSION = '0.0.1';

    /**
     * @var ClassLoader
     */
    protected $autoloader;

    /**
     * Application constructor.
     * @param ClassLoader $autoloader
     */
    public function __construct(ClassLoader $autoloader)
    {
        $this->autoloader = $autoloader;

        parent::__construct(self::APP_NAME, self::APP_VERSION);
    }

    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        if ($input === null) {
            $input = new ArgvInput();
        }

        if ($output === null) {
            $output = new ConsoleOutput();
        }

        try {
            $this->registerCommands();
        } catch (\ErrorException $e) {
            $output = new ConsoleOutput();

            $this->renderException($e, $output->getErrorOutput());
        }

        return parent::run($input, $output);
    }

    /**
     * Dynamically register commands
     */
    protected function registerCommands()
    {
        $commandsDir = __DIR__ . '/Command';
        $apiDir = str_replace('Transip/Api', '', __DIR__);

        $finder = Finder::create();

        $finder
            ->ignoreUnreadableDirs(true)
            ->ignoreDotFiles(true)
            ->files()
            ->followLinks()
            ->name('*Command.php')
            ->in($commandsDir);

        foreach ($finder as $command) {
            /** @var SplFileInfo $command */
            $className = str_replace('/', '\\', preg_replace('~'. $apiDir .'(.*)\.php~', '$1', $command->getPathname()));

            $this->add(new $className);
        }
    }
}