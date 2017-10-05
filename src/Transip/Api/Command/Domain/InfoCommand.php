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
namespace Transip\Api\Command\Domain;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Transip\Api\Helper\DomainHelper;
use Transip\Api\Model\Domain;
use Transip\Api\Soap\Service\DomainService;

class InfoCommand extends Command
{
    const SERVICE_NAME = 'DomainService';

    /**
     * Configure domain:info command
     */
    protected function configure()
    {
        $this
            ->setName('domain:info')
            ->setDescription('Show DNS records of the given domain')
            ->addArgument('domain', InputArgument::REQUIRED, 'The domain you would like to see the records of. Using subdomains, will show the record info of that subdomain')
            ->addArgument('type', InputArgument::OPTIONAL, 'The recordtype you would like to see');
    }

    /**
     * Retrieve information about the given domain. Filter results if subdomains or domaintypes are given
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $domainArray    = explode('.', $input->getArgument('domain'));
        $domain         = array_slice($domainArray, -2, 2);
        $subdomain      = implode('.', array_slice($domainArray, 0, count($domainArray) - 2));
        $type           = $input->getArgument('type');
        $numFound       = 0;
        $helper         = new DomainHelper($output);


        try {
            $result = $helper->getDomainInfo(implode('.', $domain));

            if ($result instanceof Domain) {
                $output->writeln("<info>Domain: </info>{$result->getName()}");
                $output->writeln("<info>Nameservers: </info>");

                foreach ($result->getNameServers() as $nameserver) {
                    $output->writeln("  - {$nameserver->hostname}");
                }

                $output->writeln("<info>DNS Entries: </info>");

                $table = new Table($output);

                $table->setHeaders(['Name', 'Type', 'Content', 'TTL']);


                foreach ($result->getDnsEntries() as $dns) {
                    if (($type == null || ($type == $dns->type)) && ($subdomain == '' || $subdomain == $dns->name)) {
                        $table->addRow([$dns->name, $dns->type, $dns->content, $dns->expire]);

                        $numFound++;
                    }
                }

                $table->render();
            }
        } catch (\SoapFault $e) {
            $output->writeln('<warning>ERROR: </warning>' . $e->getMessage());
        }

        exit((int)!$numFound);
    }
}