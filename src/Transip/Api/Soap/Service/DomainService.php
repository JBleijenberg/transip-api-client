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
namespace Transip\Api\Soap\Service;

use Transip\Api\Model\Domain;
use Transip\Api\Soap\Client;

class DomainService extends Client
{
    protected $service = 'DomainService';

    protected $classmap = [
        'DomainCheckResult' => 'Transip\Api\Model\Domain\CheckResult',
        'Domain'            => 'Transip\Api\Model\Domain',
        'Nameserver'        => 'Transip\Api\Model\Nameserver',
        'WhoisContact'      => 'Transip\Api\Model\WhoisContact',
        'DnsEntry'          => 'Transip\Api\Model\DnsEntry',
        'DomainBranding'    => 'Transip\Api\Model\Domain\Branding',
        'Tld'               => 'Transip\Api\Model\Tld',
        'DomainAction'      => 'Transip\Api\Model\omainAction',
    ];

    public function getInfo($domain)
    {
        return $this
            ->setSignatureCookie('getInfo', $domain)
            ->getClient()
            ->getInfo($domain);
    }

    /**
     * Set (all) DNS entries for the given domain
     *
     * @param Domain $domain
     * @return null
     */
    public function setDnsEntries(Domain $domain)
    {
        return $this
            ->setSignatureCookie('setDnsEntries', $domain->getName(), $domain->getDnsEntries())
            ->getClient()
            ->setDnsEntries($domain->getName(), $domain->getDnsentries());
    }
}