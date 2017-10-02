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

class Tld
{

    const CAPABILITY_REQUIRESAUTHCODE               = 'requiresAuthCode';
    const CAPABILITY_CANREGISTER                    = 'canRegister';
    const CAPABILITY_CANTRANSFERWITHOWNERCHANGE     = 'canTransferWithOwnerChange';
    const CAPABILITY_CANTRANSFERWITHOUTOWNERCHANGE  = 'canTransferWithoutOwnerChange';
    const CAPABILITY_CANSETLOCK                     = 'canSetLock';
    const CAPABILITY_CANSETOWNER                    = 'canSetOwner';
    const CAPABILITY_CANSETCONTACTS                 = 'canSetContacts';
    const CAPABILITY_CANSETNAMESERVERS              = 'canSetNameservers';

    /**
     * The name of this TLD, including the starting dot. E.g. .nl or .com.
     *
     * @var string  $name
     */
    public $name;

    /**
     * Price of the TLD in Euros
     *
     * @var float   $price
     */
    public $price;

    /**
     * Price for renewing the TLD in Euros
     *
     * @var float   $renewalPrice
     */
    public $renewalPrice;

    /**
     * A list of the capabilities that this Tld has (the things that can be
     * done with a domain under this tld).
     * All capabilities are one of CAPABILITY_* constants.
     *
     * @var array  $capabilities
     */
    public $capabilities;

    /**
     * Length in months of each registration or renewal period.
     *
     * @var int $registrationPeriodLength
     */
    public $registrationPeriodLength;

    /**
     * Number of days a domain needs to be canceled before the renewal date.
     * E.g., If the renewal date is 10-Dec-2011 and the cancelTimeFrame is 4 days,
     * the domain has to be canceled before 6-Dec-2011, otherwise it will be
     * renewed already.
     *
     * @var int $cancelTimeFrame
     */
    public $cancelTimeFrame;
}