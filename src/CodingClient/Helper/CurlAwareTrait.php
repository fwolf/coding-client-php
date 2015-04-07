<?php
namespace Fwolf\Client\Coding\Helper;

/**
 * CurlAwareTrait
 *
 * @copyright   Copyright 2015 Fwolf
 * @license     http://opensource.org/licenses/MIT MIT
 */
trait CurlAwareTrait
{
    /**
     * @var Curl
     */
    protected $curl = null;


    /**
     * Getter of $curl
     *
     * @return  Curl
     */
    public function getCurl()
    {
        if (is_null($this->curl)) {
            $this->curl = new Curl();
        }

        return $this->curl;
    }


    /**
     * Renew curl handle
     *
     * Used to refresh cookie, do not forget to re-assign options.
     *
     * @return  static
     */
    public function renewCurlHandle()
    {
        $this->curl->renewHandle();

        return $this;
    }


    /**
     * Setter of $curl
     *
     * Can be used to inject initialized Curl instance.
     *
     * @param   Curl    $curl
     * @return  static
     */
    public function setCurl(Curl $curl)
    {
        $this->curl = $curl;

        return $this;
    }
}
