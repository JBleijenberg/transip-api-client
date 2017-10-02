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
    public function __construct($hostname, $ipv4='', $ipv6='')
    {
        $this->hostname = $hostname;
        $this->ipv4		= $ipv4;
        $this->ipv6		= $ipv6;
    }
}