<?php
namespace FwolfTest\Client\Coding\Helper;

use Fwolf\Client\Coding\Helper\Curl;
use Fwolf\Client\Coding\Helper\TweetTrait;
use Fwolf\Wrapper\PHPUnit\PHPUnitTestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * @copyright   Copyright 2015 Fwolf
 * @license     http://opensource.org/licenses/MIT MIT
 */
class TweetTraitTest extends PHPUnitTestCase
{
    /**
     * @param   array   $methods
     * @return MockObject | TweetTrait
     */
    protected function buildMock($methods = null)
    {
        $mock = $this->getMockBuilder(TweetTrait::class)
            ->setMethods($methods)
            ->getMockForTrait();

        return $mock;
    }


    /**
     * @param   string[]    $methods
     * @param   Curl        $curl
     * @param   mixed       $times
     * @return MockObject | TweetTrait
     */
    protected function buildMockWithCurl($methods, Curl $curl, $times)
    {
        $mock = $this->buildMock($methods);

        $mock->expects($times)
            ->method('getCurl')
            ->willReturn($curl);

        return $mock;
    }


    public function testSendTweet()
    {
        /** @var MockObject|Curl $curl */
        $curl = $this->getMock(Curl::class, ['post']);
        $curl->expects($this->any())
            ->method('post')
            ->willReturnCallback(function($url, $params) {
                return json_encode([
                    'code' => 0,
                    'data' => ['url' => $url, 'params' => $params]
                ]);

            });

        $methods = ['getCurl', 'getDevice', 'login', 'uploadImage'];
        $tweetTrait = $this->buildMockWithCurl($methods, $curl, $this->once());

        $tweetTrait->expects($this->once())
            ->method('getDevice')
            ->willReturn('Android');

        $tweetTrait->expects($this->exactly(2))
            ->method('uploadImage')
            ->willReturnArgument(0);

        $result = $tweetTrait->sendTweet('dummy tweet content', ['foo', 'bar']);
        $this->assertStringStartsWith(
            'tweet?content=dummy+tweet+content',
            $result['url']
        );
        // In the middle is markdown of uploaded images
        $this->assertStringEndsWith('&device=Android', $result['url']);
    }


    /**
     * @expectedException \Fwolf\Client\Coding\Exception\SendTweetFailException
     */
    public function testSendTweetFail()
    {
        /** @var MockObject|Curl $curl */
        $curl = $this->getMock(Curl::class, ['post']);
        $curl->expects($this->any())
            ->method('post')
            ->willReturn(json_encode(['code' => 1]));

        $methods = ['getCurl', 'getDevice', 'login'];
        $tweetTrait = $this->buildMockWithCurl($methods, $curl, $this->once());

        $tweetTrait->sendTweet('dummy tweet content');
    }


    public function testSendTweetWithoutDevice()
    {
        /** @var MockObject|Curl $curl */
        $curl = $this->getMock(Curl::class, ['post']);
        $curl->expects($this->any())
            ->method('post')
            ->willReturnCallback(function($url, $params) {
                return json_encode([
                    'code' => 0,
                    'data' => ['url' => $url, 'params' => $params]
                ]);

            });

        $methods = ['getCurl', 'getDevice', 'login', 'uploadImage'];
        $tweetTrait = $this->buildMockWithCurl($methods, $curl, $this->once());

        $result = $tweetTrait->sendTweet('dummy tweet content', []);
        $this->assertEquals(
            'tweet?content=dummy+tweet+content',
            $result['url']
        );
    }


    public function testUploadImage()
    {
        /** @var MockObject|Curl $curl */
        $curl = $this->getMock(Curl::class, ['post']);
        $curl->expects($this->any())
            ->method('post')
            ->willReturn(json_encode(['code' => 0, 'data' => []]));

        $methods = ['getCurl', 'getDevice', 'login'];
        $tweetTrait = $this->buildMockWithCurl($methods, $curl, $this->once());

        $tweetTrait->uploadImage('');
    }


    /**
     * @expectedException \Fwolf\Client\Coding\Exception\UploadTweetImageFailException
     */
    public function testUploadImageFail()
    {
        /** @var MockObject|Curl $curl */
        $curl = $this->getMock(Curl::class, ['post']);
        $curl->expects($this->any())
            ->method('post')
            ->willReturn(json_encode(['code' => 1]));

        $methods = ['getCurl', 'getDevice', 'login'];
        $tweetTrait = $this->buildMockWithCurl($methods, $curl, $this->once());

        $tweetTrait->uploadImage('');
    }
}
