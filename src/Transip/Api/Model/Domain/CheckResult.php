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
namespace Transip\Api\Model\Domain;

class CheckResult
{

    const STATUS_INYOURACCOUNT  = 'inyouraccount';
    const STATUS_UNAVAILABLE    = 'unavailable';
    const STATUS_NOTFREE        = 'notfree';
    const STATUS_FREE           = 'free';
    const STATUS_INTERNALPULL   = 'internalpull';
    const STATUS_INTERNALPUSH   = 'internalpush';
    const ACTION_REGISTER       = 'register';
    const ACTION_TRANSFER       = 'transfer';
    const ACTION_INTERNALPULL   = 'internalpull';
    const ACTION_INTERNALPUSH   = 'internalpush';

    /**
     * The name of the Domain for which we have a status in this object
     *
     * @var string  $domainName
     */
    public $domainName;

    /**
     * The status for this domain, one of the Transip\Api\Model\DomainService::AVAILABILITY_* constants.
     *
     * @var string  $status
     */
    public $status;

    /**
     * List of available actions to perform on this domain
     *
     * @var array $actions
     */
    public $actions;
}