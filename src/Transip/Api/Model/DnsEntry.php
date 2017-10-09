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

class DnsEntry implements ModelInterface
{

    const TYPE_A        = 'A';
    const TYPE_AAAA     = 'AAAA';
    const TYPE_CNAME    = 'CNAME';
    const TYPE_MX       = 'MX';
    const TYPE_NS       = 'NS';
    const TYPE_TXT      = 'TXT';
    const TYPE_SRV      = 'SRV';

    const TTL_1MIN      = 60;
    const TTL_5MIN      = 300;
    const TTL_1HR       = 3600;
    const TTL_1DAY      = 86400;

    /**
     * The name of the dns entry, for example '@' or 'www'
     *
     * @var string  $name
     */
    private $name;

    /**
     * The expiration period of the dns entry, in seconds. For example 86400 for a day
     * of expiration
     *
     * @var int $expire
     */
    private $expire;

    /**
     * The type of dns entry, for example A, MX or CNAME
     *
     * @var string  $type
     */
    private $type;

    /**
     * The content of of the dns entry, for example '10 mail', '127.0.0.1' or 'www'
     *
     * @var string  $content
     */
    private $content;

    /**
     * Constructs a new DnsEntry of the form
     * www    IN    86400    A        127.0.0.1
     * mail IN    86400    CNAME    @
     *
     * Note that the IN class is always mandatory for this Entry and this is implied.
     *
     * @param       string  $name       the name of this DnsEntry, e.g. www, mail or @
     * @internal    int     $expire     the expiration period of the dns entry, in seconds. For example 86400 for a day
     * @internal    string  $type       the type of this entry, one of the TYPE_ constants in this class
     * @internal    string  $content    content of of the dns entry, for example '10 mail', '127.0.0.1' or 'www'
     */
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * Return array private object data
     *
     * @return array
     */
    public function getData()
    {
        return [
            'name'      => $this->getName(),
            'expire'    =>$this->getTtl(),
            'type'      =>$this->getType(),
            'content'   => $this->getContent()
        ];
    }

    public function setName($name)
    {
        if (is_string($name)) {
            $this->name = $name;
        } else {
            throw new \Exception('Invalid or no name supplied. Only string are allowed. Value given: ' . $name);
        }

        return $this;
    }

    /**
     * Get the DNS name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set 'expire' parameter
     *
     * @param $ttl
     * @return $this
     * @throws \Exception
     */
    public function setTtl($ttl)
    {
        if (is_numeric($ttl)) {
            if (
                $ttl == self::TTL_1MIN ||
                $ttl == self::TTL_5MIN ||
                $ttl == self::TTL_1HR ||
                $ttl == self::TTL_1DAY
            ) {
                $this->expire = $ttl;
            } else {
                throw new \Exception('Invalid TTL given. Value can be 60, 300, 3600, 86400. Value given: ' . $ttl);
            }
        } else {
            throw new \Exception('Invalid TTL given. Value must be numeric. Value given: ' . $ttl);
        }

        return $this;
    }

    public function getTtl()
    {
        return $this->expire;
    }

    /**
     * Set DNS record type
     *
     * @param $type
     * @return $this
     * @throws \Exception
     */
    public function setType($type)
    {
        if (is_string($type)) {
            if (
                $type == self::TYPE_A ||
                $type == self::TYPE_AAAA ||
                $type == self::TYPE_CNAME ||
                $type == self::TYPE_NS ||
                $type == self::TYPE_TXT ||
                $type == self::TYPE_SRV ||
                $type == self::TYPE_MX
            ) {
                $this->type = $type;
            } else {
                throw new \Exception('Invalid type given. Value can be A, AAAA, CNAME, NS, TXT, SRV or MS. Value given: ' . $type);
            }
        } else {
            throw new \Exception('Invalid type given. Value must be a string. Value given: ' . $type);
        }

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * Set DNS record content
     *
     * @param $content
     * @throws \Exception
     */
    public function setContent($content)
    {
        if (is_string($content)) {
            $this->content = $content;
        } else {
            throw new \Exception('Invalid content given. Value must be a string. Value given: ' . $content);
        }
    }

    public function getContent()
    {
        return $this->content;
    }
}