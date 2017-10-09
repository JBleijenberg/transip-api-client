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
namespace Transip\Api\Command;

use Symfony\Component\Console\Command\Command;
use Transip\Api\Helper\DomainHelper;

abstract class CommandAbstract extends Command
{


    /**
     * @var DomainHelper
     */
    private $domainHelper;

    /**
     * @return DomainHelper
     */
    public function getDomainHelper()
    {
        if (!$this->domainHelper) {
            $this->domainHelper = new DomainHelper();
        }

        return $this->domainHelper;
    }
}