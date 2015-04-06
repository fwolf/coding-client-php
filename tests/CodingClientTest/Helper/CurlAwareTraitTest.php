<?php
namespace FwolfTest\Client\Coding\Helper;

use Fwlib\Net\Curl;
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
}
