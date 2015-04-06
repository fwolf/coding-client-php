<?php
namespace FwolfTest\Client\Coding\Helper;

use Fwlib\Net\Curl;
use Fwolf\Client\Coding\Helper\AuthenticationTrait;
use Fwolf\Wrapper\PHPUnit\PHPUnitTestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * @copyright   Copyright 2015 Fwolf
 * @license     http://opensource.org/licenses/MIT MIT
 */
class AuthenticationTraitTest extends PHPUnitTestCase
{
    /**
     * @return MockObject | AuthenticationTrait
     */
    protected function buildMock()
    {
        $mock = $this->getMockBuilder(AuthenticationTrait::class)
            ->setMethods(null)
            ->getMockForTrait();

        return $mock;
    }


    public function testSetAuthentication()
    {
        $authTrait = $this->buildMock();

        $authTrait->setAuthentication('user', 'pass');
        $this->assertTrue(true);
    }


    public function testSetCookieFile()
    {
        /** @var MockObject|AuthenticationTrait $authTrait */
        $authTrait = $this->getMockBuilder(AuthenticationTrait::class)
            ->setMethods(['getCurl'])
            ->getMockForTrait();

        $curl = $this->getMock(Curl::class, ['setCookieFile']);
        $curl->expects($this->once())
            ->method('setCookieFile');

        $authTrait->expects($this->once())
            ->method('getCurl')
            ->willReturn($curl);

        $authTrait->setCookieFile('dummy/file/path');
    }
}
