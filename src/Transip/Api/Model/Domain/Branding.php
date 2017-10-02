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

class Branding
{

    /**
     * The company name displayed in transfer-branded e-mails
     *
     * @var string  $companyName
     */
    public $companyName;

    /**
     * The support email used for transfer-branded e-mails
     *
     * @var string  $supportEmail
     */
    public $supportEmail;

    /**
     * The company url displayed in transfer-branded e-mails
     *
     * @var string  $companyUrl
     */
    public $companyUrl;

    /**
     * The terms of usage url as displayed in transfer-branded e-mails
     *
     * @var string  $termsOfUsageUrl
     */
    public $termsOfUsageUrl;

    /**
     * The first generic bannerLine displayed in whois-branded whois output.
     *
     * @var string  $bannerLine1
     */
    public $bannerLine1;

    /**
     * The second generic bannerLine displayed in whois-branded whois output.
     *
     * @var string  $bannerLine2
     */
    public $bannerLine2;

    /**
     * The third generic bannerLine displayed in whois-branded whois output.
     *
     * @var string  $bannerLine3
     */
    public $bannerLine3;
}