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
     * @param   string[]    $methods
     * @return MockObject | AuthenticationTrait
     */
    protected function buildMock($methods = null)
    {
        $mock = $this->getMockBuilder(AuthenticationTrait::class)
            ->setMethods($methods)
            ->getMockForTrait();

        return $mock;
    }


    /**
     * @param   string[]    $methods
     * @param   Curl        $curl
     * @param   mixed       $times
     * @return MockObject | AuthenticationTrait
     */
    protected function buildMockWithCurl($methods, Curl $curl, $times)
    {
        $mock = $this->buildMock($methods);

        $mock->expects($times)
            ->method('getCurl')
            ->willReturn($curl);

        return $mock;
    }


    public function testIsLoggedIn()
    {
        /** @var MockObject|Curl $curl */
        $curl = $this->getMock(Curl::class, ['get', 'getLastCode']);
        $curl->expects($this->any())
            ->method('get')
            ->willReturnOnConsecutiveCalls(
                null,
                json_encode(['code' => -1]),
                json_encode(['code' => 0, 'data' => ['name' => '']]),
                json_encode(['code' => 0, 'data' => ['name' => 'dummy']])
            );
        $curl->expects($this->any())
            ->method('getLastCode')
            ->willReturnOnConsecutiveCalls(400, 200, 200, 200);

        $authTrait =
            $this->buildMockWithCurl(['getCurl'], $curl, $this->exactly(4));

        $this->assertFalse($authTrait->isLoggedIn());

        $this->reflectionSet($authTrait, 'loggedIn', null);
        $this->assertFalse($authTrait->isLoggedIn());

        $this->reflectionSet($authTrait, 'loggedIn', null);
        $this->assertFalse($authTrait->isLoggedIn());

        $this->reflectionSet($authTrait, 'loggedIn', null);
        $this->assertTrue($authTrait->isLoggedIn());
    }


    public function testLogin()
    {
        /** @var MockObject|Curl $curl */
        $curl = $this->getMock(Curl::class, ['post']);
        $curl->expects($this->any())
            ->method('post')
            ->willReturn(
                json_encode(['code' => 0, 'data' => ['name' => 'dummy']])
            );

        $authTrait =
            $this->buildMockWithCurl(['getCurl'], $curl, $this->once());

        $authTrait->login();
    }


    /**
     * @expectedException   \Fwolf\Client\Coding\Exception\LoginFailException
     */
    public function testLoginFail()
    {
        /** @var MockObject|Curl $curl */
        $curl = $this->getMock(Curl::class, ['post']);
        $curl->expects($this->any())
            ->method('post')
            ->willReturn(
                json_encode(['code' => 01])
            );

        $authTrait =
            $this->buildMockWithCurl(['getCurl'], $curl, $this->once());

        $authTrait->login();
    }


    public function testSetAuthentication()
    {
        $authTrait = $this->buildMock();

        $authTrait->setAuthentication('user', 'pass');
        $this->assertTrue(true);
    }


    public function testSetCookieFile()
    {
        /** @var MockObject|Curl $curl */
        $curl = $this->getMock(Curl::class, ['setCookieFile']);
        $curl->expects($this->once())
            ->method('setCookieFile');

        $authTrait =
            $this->buildMockWithCurl(['getCurl'], $curl, $this->once());

        $authTrait->setCookieFile('dummy/file/path');
    }
}
