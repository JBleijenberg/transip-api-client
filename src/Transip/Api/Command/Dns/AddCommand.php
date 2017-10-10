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

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Transip\Api\Command\CommandAbstract;
use Transip\Api\Model\DnsEntry;
use Transip\Api\Model\Domain;
use Transip\Api\Soap\Service\DomainService;

class AddCommand extends CommandAbstract
{

    protected function configure()
    {
        $this
            ->setName('dns:add')
            ->setDescription('Add a new DNS record to the given domain')
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'The name of the new record')
            ->addOption('domain', null, InputOption::VALUE_REQUIRED, 'The domains the new record should be added to')
            ->addOption('type', null, InputOption::VALUE_REQUIRED, 'The type of the new record. Must be of type A, AAAA, CNAME, MX, NS, SRV or TXT')
            ->addOption('ttl', null, InputOption::VALUE_REQUIRED, 'The TTL of the new record. Values can be 50, 300, 3600 or 86400.')
            ->addArgument('content', InputArgument::REQUIRED, 'The content of the new DNS record');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getDomainHelper();

        try {
            $domain  = $helper->getDomain($input->getOption('domain'));
            $type    = $helper->validateType($input->getOption('type'));
            $content = $helper->validateContent($input->getArgument('content'));
            $name    = $helper->validateName($domain, $input->getOption('name'), $type, $content);
            $ttl     = $helper->validateTtl($input->getOption('ttl'));

            if ($domain instanceof Domain && $type && $name && $ttl && $content) {
                $newDnsEntry   = new DnsEntry($name);
                $domainService = new DomainService();

                $newDnsEntry
                    ->setType($type)
                    ->setTtl($ttl)
                    ->setContent($content);

                $domain->addDnsEntry($newDnsEntry);

                $domainService->setDnsEntries($domain);
            }

            $output->writeln('');
            $output->writeln('<info>SUCCESS: </info>DNS record successfully added');
            $output->writeln('');

            exit(0);
        } catch (\Exception $e) {
            $output->writeln('');
            $output->writeln("<warning>ERROR: </warning>{$e->getMessage()}");
            $output->writeln('');

            exit(1);
        }
    }
}