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
namespace Transip\Api\Helper;

use Symfony\Component\Console\Output\OutputInterface;
use Transip\Api\Soap\Service\DomainService;
use Transip\Api\Model\Domain;
use Transip\Api\Model\DnsEntry;

class DomainHelper
{

    /**
     * @var DomainService   $domainService
     */
    private $domainService;

    /**
     * @var Domain  $domainInfo
     */
    private $domainInfo;

    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @param $domain       string  Fetch domaininfo from TransIP
     * @return null|Domain
     */
    public function getDomainInfo($domain)
    {
        if (!$this->domainInfo) {
            $this->domainInfo = $this->getDomainService()->getInfo($domain);
        }

        return $this->domainInfo;
    }

    public function getDomainService()
    {
        if (!$this->domainService) {
            $this->domainService = new DomainService($this->output);
        }

        return $this->domainService;
    }

    /**
     * Check if we can access the given domain
     *
     * @param $domain   string          The domain to validate
     * @return          null|string     Return the domainname on success, or null on error
     */
    public function validateDomain($domain)
    {
        if ($domain !== null && $this->getDomainInfo($domain) instanceof Domain) {
            return $domain;
        }

        return null;
    }

    /**
     * @param $type         $type   The domain type to validate
     * @return null|string  $type
     */
    public function validateType($type)
    {
        if (
            $type == DnsEntry::TYPE_A ||
            $type == DnsEntry::TYPE_AAAA ||
            $type == DnsEntry::TYPE_CNAME ||
            $type == DnsEntry::TYPE_MX ||
            $type == DnsEntry::TYPE_NS ||
            $type == DnsEntry::TYPE_SRV ||
            $type == DnsEntry::TYPE_TXT
        ) {
            return $type;
        }

        $this->output->writeln('<warning>ERROR: </warning>Invalid type given. See --help for more information about types');

        return null;
    }

    /**
     * @param string        $domain     The domain that is used
     * @param string        $name       The subdomain name to validate
     * @param $type         $type       The type to validate this subdomain with
     * @return null|string
     */
    public function validateName($domain, $name, $type)
    {
        if ($name !== null && $type !== null) {
            $domainInfo = $this->getDomainInfo($domain);

            if ($domainInfo instanceof Domain) {
                if (($arrayId = array_search($name, array_column($domainInfo->dnsEntries, 'name')))) {
                    $record = $domainInfo->dnsEntries[$arrayId];

                    if ($record->type === $type) {
                        $this->output->write("<warning>ERROR: </warning>{$name} already exists with type {$type}");

                        return null;
                    }
                }
            }
        }

        return $name;
    }
}