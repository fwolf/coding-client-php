<?php
namespace FwolfTest\Client\Coding\Helper;

use Fwolf\Client\Coding\Helper\Curl;
use Fwolf\Client\Coding\Helper\CurlAwareTrait;
use Fwolf\Wrapper\PHPUnit\PHPUnitTestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * @copyright   Copyright 2015 Fwolf
 * @license     http://opensource.org/licenses/MIT MIT
 */
class CurlAwareTraitTest extends PHPUnitTestCase
{
    /**
     * @return MockObject | CurlAwareTrait
     */
    protected function buildMock()
    {
        $mock = $this->getMockBuilder(CurlAwareTrait::class)
            ->setMethods(null)
            ->getMockForTrait();

        return $mock;
    }


    public function test()
    {
        $curlAware = $this->buildMock();

        $curl = $curlAware->getCurl();
        $this->assertInstanceOf(Curl::class, $curl);

        $curlAware->setCurl($curl);
        $this->assertInstanceOf(Curl::class, $curlAware->getCurl());
    }


    public function testRenewCurlHandle()
    {
        $curlAware = $this->buildMock();
        $oldHandle = $curlAware->getCurl()->getHandle();

        $curlAware->renewCurlHandle();
        $newHandle = $curlAware->getCurl()->getHandle();

        $this->assertNotEquals($oldHandle, $newHandle);
    }
}
