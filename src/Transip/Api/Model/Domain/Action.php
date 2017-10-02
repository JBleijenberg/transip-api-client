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

class Action
{

    /**
     * The name of this DomainAction.
     *
     * @var string  $name
     */
    public $name;

    /**
     * If this action has failed, this field will be true.
     *
     * @var boolean $hasFailed
     */
    public $hasFailed;

    /**
     * If this action has failed, this field will contain an descriptive message.
     *
     * @var string  $message
     */
    public $message;
}