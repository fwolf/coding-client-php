<?php
namespace Fwolf\Client\Coding\Helper;

use Fwlib\Net\Curl as FwlibCurl;
use Fwolf\Client\Coding\Coding;

/**
 * Curl
 *
 * Extend for auto prepend api url to {@see get()} and {@see post()}.
 *
 * @codeCoverageIgnore
 *
 * @copyright   Copyright 2015 Fwolf
 * @license     http://opensource.org/licenses/MIT MIT
 */
class Curl extends FwlibCurl
{
    /**
     * {@inheritdoc}
     */
    public function get($url, $param = null)
    {
        return parent::get(Coding::API_URL . $url, $param);
    }


    /**
     * {@inheritdoc}
     */
    public function post($url, $params = '')
    {
        return parent::post(Coding::API_URL . $url, $params);
    }
}
