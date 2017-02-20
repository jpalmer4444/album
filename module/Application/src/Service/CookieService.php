<?php

namespace Application\Service;

use Exception;
use Zend\Http\Header\SetCookie;

/**
 * Description of CookieService
 *
 * @author jasonpalmer
 */
class CookieService extends BaseService {

    /**
     * Lifetime of cookie in seconds.
     * @var type int
     */
    protected $cookieLifetime;

    //new DateTime('today -1 day 1:00PM')
    public function __construct(array $config) {
        if (array_key_exists('session_config', $config) &&
                array_key_exists('cookie_lifetime', $config['session_config'])) {
            $session_config = $config['session_config'];
            $this->cookieLifetime = $session_config['cookie_lifetime'];
        } else {
            $this->cookieLifetime = 86400;
        }
    }

    /**
     * Lifetime of cookie in seconds.
     * @return type int
     */
    public function getCookieLifetime() {
        return $this->cookieLifetime;
    }

    /**
     * Sets a browser cookie.
     * @param type $name name of the cookie
     * @param type $value value of the cookie
     * @param type $timeplus lifetime of the cookie in seconds
     * @param type $response Zend\Http\Response
     */
    public function setCookie($name, $value, $timeplus, $response) {
        if (empty($timeplus)) {
            $timeplus = $this->cookieLifetime;
        }
        try {
            // create a cookie
            $cookie = new SetCookie(
                    $name, $value, time() + $timeplus, // 1 year lifetime
                    '/'
            );

            $response->getHeaders()->addHeader($cookie);
            
        } catch (Exception $exc) {
            
            $this->log(this::class, __LINE__, "Exception: " . $exc->getTraceAsString());
            
        }
    }

    /**
     * Deletes a cookie by name.
     * @param type $name name of the cookie
     * @param type $response Zend\Http\Response
     */
    public function deleteCookie($name, $response) {
        $cookie = new SetCookie(
                $name, '', strtotime('-1 Year', time()), // -1 year lifetime (negative from now)
                '/'
        );
        $response->getHeaders()->addHeader($cookie);
    }

    /**
     * Gets a cookie by name.
     * @param type $name Name of the cookie
     * @param type $request Zend\Http\Request
     * @return type
     */
    public function getCookie($name, $request) {
        try {
            $cookie = $request->getCookie();
            if ($cookie->offsetExists($name)) {
                return $cookie->offsetGet($name);
            }
        } catch (Exception $exc) {
            $this->log(this::class, __LINE__, "Exception: " . $exc->getTraceAsString());
        }
    }

}
