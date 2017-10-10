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
use Transip\Api\Model\Domain;
use Transip\Api\Soap\Service\DomainService;

class EditCommand extends CommandAbstract
{

    protected function configure()
    {
        $this
            ->setName('dns:edit')
            ->setDescription('Edit an existing DNS record from the given domain')
            ->addOption('domain', null, InputOption::VALUE_REQUIRED, 'The domains of the record that will be edited')
            ->addOption('type', null, InputOption::VALUE_REQUIRED, 'The curent type of the record. Must be of type A, AAAA, CNAME, MX, NS, SRV or TXT')
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'The name of the record')
            ->addOption('content', null, InputOption::VALUE_OPTIONAL, 'The content of the record.')
            ->addArgument('new-content', InputArgument::REQUIRED, 'The new content for this record');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper  = $this->getDomainHelper();
        $domain  = $helper->getDomain($input->getOption('domain'));
        $type    = $helper->validateType($input->getOption('type'));
        $name    = $input->getOption('name');
        $content = $helper->validateContent($input->getArgument('new-content'));

        try {
            if ($domain instanceof Domain && $domain->dnsEntryExists($name, $type)) {
                $domain->getDnsEntryByName($name, $type)
                    ->setContent($content);

                $service = new DomainService();
                $service->setDnsEntries($domain);

                $output->writeln('');
                $output->writeln('<info>SUCCESS: </info>DNS record successfully edited');
                $output->writeln('');
            } else {
                throw new \Exception('DNS entry not found.');
            }
        } catch(\Exception $e) {
            $output->writeln('');
            $output->writeln("<warning>ERROR: </warning>{$e->getMessage()}");
            $output->writeln('');
        }
    }
}