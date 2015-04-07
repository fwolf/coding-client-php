<?php
namespace Fwolf\Client\Coding\Helper;

use Fwolf\Client\Coding\Exception\SendTweetFailException;
use Fwolf\Client\Coding\Exception\UploadTweetImageFailException;

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
     * @param   array   $images     {description: filePath}
     * @return  array   New added tweet data
     * @throws  SendTweetFailException
     */
    public function sendTweet($content, $images = [])
    {
        $this->login();

        foreach ($images as $key => $image) {
            $imageUrl = $this->uploadImage($image);

            $key = is_int($key) ? "图片{$key}" : $key;

            $content .= "\n![$key]($imageUrl)";
        }

        $curl = $this->getCurl();
        $content = urlencode($content);
        $device = urlencode($this->getDevice());
        $result = $curl->post(
            "tweet?content={$content}&device={$device}",
            []
        );
        $resultArray = json_decode($result, true);

        if (empty($result) || 0 != $resultArray['code']) {
            throw new SendTweetFailException($result);
        }

        return $resultArray['data'];
    }


    /**
     * Upload image, got stored url
     *
     * @param   string  $image  File path of image
     * @return  string
     */
    public function uploadImage($image)
    {
        $curl = $this->getCurl();
        $result = $curl->post(
            'tweet/insert_image',
            [
                'tweetImg' => new \CURLFile($image),
            ]
        );
        $resultArray = json_decode($result, true);

        if (empty($result) || 0 != $resultArray['code']) {
            throw new UploadTweetImageFailException($result);
        }

        return $resultArray['data'];
    }
}
