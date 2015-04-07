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


    /**
     * Unset Curl instance
     *
     * Used to refresh cookie, do not forget re-assign options.
     *
     * @return  static
     */
    public function unsetCurl()
    {
        $this->curl->renewHandle();

        return $this;
    }
}
