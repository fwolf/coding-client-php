<?php
namespace Fwolf\Client\Coding\Helper;

use Fwlib\Net\Curl;
use Fwolf\Client\Coding\Exception\LoginFailException;

/**
 * AuthenticationTrait
 *
 * @method  Curl    getCurl()
 *
 * @copyright   Copyright 2015 Fwolf
 * @license     http://opensource.org/licenses/MIT MIT
 */
trait AuthenticationTrait
{
    /**
     * Captcha
     *
     * Needed when:
     *  - 异常操作 3 次
     *  - 同一个 ip 登录 3 个以上帐号，1个小时自动解除
     *  - 操作间隔 10 秒以内有可能触发，提示用户操作太频繁
     *
     * @var string
     */
    protected $captcha = '';

    /**
     * Is current logged in ?
     *
     * @var bool
     */
    protected $loggedIn = null;

    /**
     * Sha1 coded password
     *
     * @var string
     */
    protected $password = '';

    /**
     * Login name, 邮箱或个性后缀
     *
     * @var string
     */
    protected $username = '';


    /**
     * Check current login status
     *
     * @return  bool
     */
    public function isLoggedIn()
    {
        if (is_null($this->loggedIn)) {
            $curl = $this->getCurl();
            $result = json_decode($curl->get('user/current_user'), true);

            if (200 != $curl->getLastCode() ||
                0 != $result['code'] |
                empty($result['data']['name'])
            ) {
                $this->loggedIn = false;
            } else {
                $this->loggedIn = true;
            }
        }

        return $this->loggedIn;
    }


    /**
     * Do login
     *
     * @return  static
     * @throws  LoginFailException
     */
    public function login()
    {
        if (!$this->loggedIn) {
            $curl = $this->getCurl();
            $result = $curl->post(
                'login',
                [
                    'email'    => $this->username,
                    'password' => $this->password
                ]
            );

            if (empty($result) || 0 != json_decode($result, true)['code']) {
                throw new LoginFailException($result);
            }
        }

        return $this;
    }


    /**
     * @param   string  $username
     * @param   string  $password
     * @param   string  $captcha    Optional
     * @return  static
     */
    public function setAuthentication($username, $password, $captcha = '')
    {
        $this->username = $username;
        $this->password = $password;
        $this->captcha = $captcha;

        return $this;
    }


    /**
     * Set cookie file to store logged-in status
     *
     * @param   string  $file
     * @return  static
     */
    public function setCookieFile($file)
    {
        $curl = $this->getCurl();

        $curl->setCookieFile($file);

        return $this;
    }
}
