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
     * @var Domain  $domain
     */
    private $domain;

    /**
     * @param $domain       string  Fetch domaininfo from TransIP
     * @param bool $force   bool    Force fetch domaininfo
     * @return null|Domain
     */
    public function getDomain($domain, $force = false)
    {
        if (!$this->domain || $force === true) {
            $this->domain = $this->getDomainService()->getInfo($domain);
        }

        return $this->domain;
    }

    public function getDomainService()
    {
        if (!$this->domainService) {
            $this->domainService = new DomainService();
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
        if ($domain !== null && $this->getDomain($domain) instanceof Domain) {
            return $domain;
        }

        return null;
    }

    /**
     * Validate DNS type
     *
     * @param String $type The domain type to validate
     * @return null|string $type
     * @throws \ErrorException
     * @throws \Exception
     */
    public function validateType($type)
    {
        if (is_string($type)) {
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
            } else {
                throw new \ErrorException('Invalid type given. See --help for more information about types');
            }
        } else {
            throw new \Exception('Invalid type given. Value must be string');
        }
    }

    /**
     * Validate DNS TTL
     *
     * @param $ttl
     * @return mixed
     * @throws \ErrorException
     * @throws \Exception
     */
    public function validateTtl($ttl)
    {
        if (is_numeric($ttl)) {
            if (
                $ttl == DnsEntry::TTL_1MIN ||
                $ttl == DnsEntry::TTL_5MIN ||
                $ttl == DnsEntry::TTL_1HR ||
                $ttl == DnsEntry::TTL_1DAY
            ) {
                return $ttl;
            } else {
                throw new \Exception('Invalid TTL given. See --help for more information about TTL');
            }
        } else {
            throw new \Exception('Invalid TTL given. Value must be numeric');
        }
    }

    /**
     * @param string $domain The domain that is used
     * @param string $name The subdomain name to validate
     * @param $type $type       The type to validate this subdomain with
     * @param $content
     * @return null|string
     * @throws \Exception
     */
    public function validateName($domain, $name, $type, $content)
    {
        if ($name !== null && $type !== null) {
            $domainInfo = $this->getDomain($domain);

            if ($domainInfo->dnsEntryExists($name, $type, $content)) {
                throw new \Exception("{$name} already exists with type {$type}");
            }
        }

        return $name;
    }

    /**
     * Simple validation to check if content is a string
     *
     * @param $content
     * @return mixed
     * @throws \Exception
     */
    public function validateContent($content)
    {
        if (is_string($content)) {
            return $content;
        } else {
            throw new \Exception('Invalid content given. Value must be a string');
        }
    }
}