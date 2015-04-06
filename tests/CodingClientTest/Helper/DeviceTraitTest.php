<?php
namespace FwolfTest\Client\Coding\Helper;

use Fwolf\Client\Coding\Helper\DeviceTrait;
use Fwolf\Wrapper\PHPUnit\PHPUnitTestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * @copyright   Copyright 2015 Fwolf
 * @license     http://opensource.org/licenses/MIT MIT
 */
class DeviceTraitTest extends PHPUnitTestCase
{
    /**
     * @return MockObject | DeviceTrait
     */
    protected function buildMock()
    {
        $mock = $this->getMockBuilder(DeviceTrait::class)
            ->setMethods(null)
            ->getMockForTrait();

        return $mock;
    }


    public function test()
    {
        $deviceTrait = $this->buildMock();

        $deviceName = 'Some mobile';
        $deviceTrait->setDevice($deviceName);
        $this->assertEquals($deviceName, $deviceTrait->getDevice());
    }
}
