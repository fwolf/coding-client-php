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
            ->willReturn(json_encode(['code' => 0, 'data' => []]));

        $methods = ['getCurl', 'getDevice', 'login'];
        $tweetTrait = $this->buildMockWithCurl($methods, $curl, $this->once());

        $tweetTrait->sendTweet('dummy tweet content');
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
}
