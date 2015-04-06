<?php
namespace Fwolf\Client\Coding;

use Fwolf\Client\Coding\Helper\AuthenticationTrait;
use Fwolf\Client\Coding\Helper\CurlAwareTrait;
use Fwolf\Client\Coding\Helper\DeviceTrait;
use Fwolf\Client\Coding\Helper\TweetTrait;

/**
 * Coding
 *
 * Main entrance.
 *
 * @copyright   Copyright 2015 Fwolf
 * @license     http://opensource.org/licenses/MIT MIT
 */
class Coding
{
    use AuthenticationTrait;
    use CurlAwareTrait;
    use DeviceTrait;
    use TweetTrait;


    /**
     * Url of api, with tailing '/'
     */
    const API_URL = 'https://coding.net/api/';
}
