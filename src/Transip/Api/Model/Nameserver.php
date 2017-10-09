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
namespace Transip\Api\Model;

class Nameserver
{

    /**
     * The hostname of this nameserver
     *
     * @var string $hostname
     */
    public $hostname;

    /**
     * Optional ipv4 glue record for this nameserver, leave
     * empty when no ipv4 glue record is needed for this nameserver.
     *
     * @var string $ipv4
     */
    public $ipv4;

    /**
     * Optional ipv6 glue record for this nameserver, leave
     * empty when no ipv6 glue record is needed for this nameserver.
     *
     * @var string  $ipv6
     */
    public $ipv6;

    /**
     * Constructs a new Nameserver.
     *
     * @param string $hostname      the hostname for this nameserver
     * @param string $ipv4 OPTIONAL ipv4 glue record for this nameserver
     * @param string $ipv6 OPTIONAL ipv6 glue record for this nameserver
     */
    public function __construct($hostname)
    {
        $this->setHostname($hostname);

        return $this;
    }

    /**
     * @return string
     */
    public function getHostname()
    {

        return $this->hostname;
    }

    /**
     * Set hostname
     * @param string $hostname
     * @return $this
     * @throws \Exception
     */
    public function setHostname($hostname)
    {
        if (is_string($hostname)) {
            $this->hostname = $hostname;
        } else {
            throw new \Exception('Invalid hostname given. Value must be a string');
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getIpv4()
    {
        return $this->ipv4;
    }

    /**
     * @param string $ipv4
     * @return $this
     * @throws \Exception
     */
    public function setIpv4($ipv4)
    {
        if (filter_var($ipv4, FILTER_VALIDATE_IP)) {
            $this->ipv4 = $ipv4;
        } else {
            throw new \Exception('Invalid IPv4 address given.');
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getIpv6()
    {
        return $this->ipv6;
    }

    /**
     * @param string $ipv6
     * @return $this
     * @throws \Exception
     */
    public function setIpv6($ipv6)
    {
        if (filter_var($ipv6, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $this->ipv6 = $ipv6;
        } else {
            throw new \Exception('Invalid IPv6 address given.');
        }

        return $this;
    }
}