<?php

namespace Application\Service;

/**
 * Description of CookieService
 *
 * @author jasonpalmer
 */
class CookieService extends BaseService{
    
    protected $cookieLifetime;
    
    //new DateTime('today -1 day 1:00PM')
    public function __construct(array $config) {
        if (array_key_exists('session_config', $config) &&
                array_key_exists('cookie_lifetime', $config['session_config'])) {
            $session_config = $config['session_config'];
            $this->cookieLifetime = $session_config['cookie_lifetime'];
        }else{
            $this->cookieLifetime = 86400;
        }
    }
    
    public function getCookieLifetime(){
        return $this->cookieLifetime;
    }
    
}
