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
namespace Transip\Api\Command\Dns;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Transip\Api\Helper\DomainHelper;
use Transip\Api\Soap\Service\DomainService;

class AddCommand extends Command
{

    /**
     * @var DomainHelper
     */
    private $domainHelper;

    protected function configure()
    {
        $this
            ->setName('dns:add')
            ->setDescription('Add a new DNS record to the given domain')
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'The name of the new record')
            ->addOption('domain', null, InputOption::VALUE_REQUIRED, 'The domains the new record should be added to')
            ->addOption('type', null, InputOption::VALUE_REQUIRED, 'The type of the new record. Must be of type A, AAAA, CNAME, MX, NS, SRV or TXT')
            ->addOption('ttl', null, InputOption::VALUE_REQUIRED, 'The TTL of the new record');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->domainService = new DomainService($output);
        $helper              = $this->getDomainHelper($output);

        $domain = $helper->validateDomain($input->getOption('domain'));
        $type   = $helper->validateType($input->getOption('type'));
        $name   = $helper->validateName($domain, $input->getOption('name'), $type);

        var_dump([$domain, $type, $name]);
    }

    /**
     * @return DomainHelper
     */
    public function getDomainHelper(OutputInterface $output)
    {
        if (!$this->domainHelper) {
            $this->domainHelper = new DomainHelper($output);
        }

        return $this->domainHelper;
    }
}