<?php
namespace Fwolf\Client\Coding\Helper;

use Fwolf\Client\Coding\Exception\SendTweetFailException;

/**
 * TweetTrait
 *
 * @method  Curl    getCurl()
 * @method  string  getDevice()
 * @method  static  login()
 *
 * @copyright   Copyright 2015 Fwolf
 * @license     http://opensource.org/licenses/MIT MIT
 */
trait TweetTrait
{
    /**
     * Send a tweet
     *
     * @param   string  $content
     * @return  array   New added tweet data
     * @throws  SendTweetFailException
     */
    public function sendTweet($content)
    {
        $this->login();

        $curl = $this->getCurl();
        $result = $curl->post(
            'tweet',
            [
                'content' => $content,
                'device'  => $this->getDevice(),
            ]
        );
        $resultArray = json_decode($result, true);

        if (empty($result) || 0 != $resultArray['code']) {
            throw new SendTweetFailException($result);
        }

        return $resultArray['data'];
    }
}
