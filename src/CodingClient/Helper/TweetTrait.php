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
     * Parameter is url string ?
     *
     * When use IFTTT send mail to mailgun, the mail carry a url point to
     * ift.tt with http header 'Location', this url point to original weibo
     * image url with http header 'Location' too, we can use this image url
     * directly in pp, need not upload it again.
     *
     * @param   string  $filename
     * @return  bool
     */
    protected function isUrl($filename)
    {
        $filename = strtolower($filename);

        return ('http://' == substr($filename, 0, 7)) ||
            ('https://' == substr($filename, 0, 8));
    }


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

        $content = urlencode($content);
        $device = urlencode($this->getDevice());

        $url = "tweet?content={$content}";
        if (!empty($device)) {
            $url .= "&device={$device}";
        }
        $curl = $this->getCurl();
        $result = $curl->post($url, []);
        $resultArray = json_decode($result, true);

        if (empty($result) || 0 != $resultArray['code']) {
            throw new SendTweetFailException($result);
        }

        return $resultArray['data'];
    }


    /**
     * Upload image, got stored url
     *
     * Coding does not allow upload file without ext, so we auto add ext.
     *
     * @param   string  $image  File path of image
     * @return  string
     * @throws  UploadTweetImageFailException
     */
    public function uploadImage($image)
    {
        if ($this->isUrl($image)) {
            return $image;
        }

        $this->login();

        $imageExt = pathinfo($image, PATHINFO_EXTENSION);
        $newImageExt = empty($imageExt) ? 'jpg' : $imageExt;
        $imageWithExt = empty($imageExt)
            ? $image . '.' . $newImageExt
            : $image;

        $curlFile = new \CURLFile($image);
        $curlFile->setPostFilename($imageWithExt);

        $curl = $this->getCurl();
        $result = $curl->post(
            'tweet/insert_image',
            ['tweetImg' => $curlFile]
        );
        $resultArray = json_decode($result, true);

        if (empty($result) || 0 != $resultArray['code']) {
            throw new UploadTweetImageFailException($result);
        }

        return $resultArray['data'];
    }
}
