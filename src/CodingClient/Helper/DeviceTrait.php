<?php
namespace Fwolf\Client\Coding\Helper;

/**
 * DeviceTrait
 *
 * @copyright   Copyright 2015 Fwolf
 * @license     http://opensource.org/licenses/MIT MIT
 */
trait DeviceTrait
{
    /**
     * @var string
     */
    protected $device = '';


    /**
     * Getter of $device
     *
     * @return  string
     */
    public function getDevice()
    {
        return $this->device;
    }


    /**
     * Setter of $device
     *
     * @param   string $device
     * @return  static
     */
    public function setDevice($device)
    {
        $this->device = $device;

        return $this;
    }
}
