<?php

namespace Application\Utility;

use Application\Service\ReportService;
use Application\Service\RestService;
use Doctrine\ORM\EntityManagerInterface;
use Zend\Session\SessionManager;

/**
 * Description of Strings
 *
 * @author jasonpalmer
 */
class Strings {

    /**
     * @see EntityManagerInterface
     * EntityService
     * Service that provides an entry point into Doctrine EntityManager.
     */
    const ENTITY_SERVICE = 'EntityService';

    /**
     * @see SessionManager
     * SessionManager
     * Service that provides an entry point into Zend\Session\SessionManager.
     */
    const SESSION_MANAGER = 'SessionManager';

    /**
     * @see ReportService
     * ReportService
     * Service for reports.
     */
    const REPORT_SERVICE = 'ReportService';

    /**
     * @see RestService
     * RestService
     * Service that makes REST calls to svc backend.
     */
    const REST_SERVICE = 'RestService';

    /**
     * config
     */
    const CONFIG = 'config';

    /**
     * pricing_config
     */
    const PRICING_CONFIG = 'pricing_config';

    /**
     * controllers
     */
    const CONTROLLERS = 'controllers';

    /**
     * factories
     */
    const FACTORIES = 'factories';

    /**
     * dispatch
     */
    const DISPATCH = 'dispatch';

    /**
     * invokables
     */
    const INVOKABLES = 'invokables';

    /**
     * request
     */
    const REQUEST = 'request';

    /**
     * response
     */
    const RESPONSE = 'response';

    /**
     * router
     */
    const ROUTER = 'router';

    /**
     * controller
     */
    const CONTROLLER = 'controller';

    /**
     * action
     */
    const ACTION = 'action';

    /**
     * LoginController
     */
    const LOGIN_CONTROLLER = 'Login\Controller\LoginController';

    /**
     * SuccessController
     */
    const SUCCESS_CONTROLLER = 'Login\Controller\SuccessController';

    /**
     * MyAuthStorage
     */
    const MY_AUTH_STORAGE = 'Login\Model\MyAuthStorage';

    /**
     * AuthService
     */
    const AUTH_SERVICE = 'AuthService';

    /**
     * Zend\Db\Adapter\Adapter
     */
    const ZEND_DB_ADAPTER = 'Zend\Db\Adapter\Adapter';

    /**
     * Zend\Mvc\Controller\AbstractActionController
     */
    const ABSTRACT_ACTION_CONTROLLER = 'Zend\Mvc\Controller\AbstractActionController';

    /**
     * Zend\Loader\ClassMapAutoloader
     */
    const CLASS_MAP_AUTO_LOADER = 'Zend\Loader\ClassMapAutoloader';

    /**
     * Zend\Loader\StandardAutoloader
     */
    const STANDARD_AUTO_LOADER = 'Zend\Loader\StandardAutoloader';

    /**
     * Sales\Factory\ItemsControllerFactory
     */
    const SALES_ITEMS_CONTROLLER_FACTORY = "Sales\Factory\ItemsControllerFactory";

    /**
     * Sales\Factory\SalesControllerFactory
     */
    const SALES_SALES_CONTROLLER_FACTORY = "Sales\Factory\SalesControllerFactory";

    /**
     * Sales\Factory\UsersControllerFactory
     */
    const SALES_USERS_CONTROLLER_FACTORY = "Sales\Factory\UsersControllerFactory";

    /**
     * Sales\Controller\ItemsControllerFactory
     */
    const SALES_ITEMS_CONTROLLER = "Sales\Controller\ItemsController";

    /**
     * Sales\Controller\SalesControllerFactory
     */
    const SALES_SALES_CONTROLLER = "Sales\Controller\SalesController";

    /**
     * Sales\Controller\UsersControllerFactory
     */
    const SALES_USERS_CONTROLLER = "Sales\Controller\UsersController";

    /**
     * Ajax\Controller\ItemsControllerFactory
     */
    const AJAX_ITEMS_CONTROLLER = "Ajax\Controller\Sales\ItemsController";

    /**
     * Ajax\Controller\SalesControllerFactory
     */
    const AJAX_SALES_CONTROLLER = "Ajax\Controller\Sales\SalesController";

    /**
     * Ajax\Controller\UsersControllerFactory
     */
    const AJAX_USERS_CONTROLLER = "Ajax\Controller\Sales\UsersController";

    /**
     * ItemsFilterTableArrayService
     */
    const ITEMS_FILTER_TABLE_ARRAY_SERVICE = "ItemsFilterTableArrayService";

    /**
     * Application\ViewHelper\RequiredMarkInFormLabel
     */
    const REQUIRED_MARK_IN_FORM_LABEL = 'Application\ViewHelper\RequiredMarkInFormLabel';

    /**
     * formLabel
     */
    const FORM_LABEL = 'formLabel';

    /**
     * namespaces
     */
    const NAMESPACES = 'namespaces';
    
    const COOKIE_DOMAIN = 'cookieDomain';
    const COOKIE_HTTP_ONLY = 'cookieHttpOnly';
    const COOKIE_LIFETIME = 'cookieLifetime';
    const COOKIE_PATH = 'cookiePath';
    const COOKIE_SECURE = 'cookieSecure';
    const NAME = 'name';
    const OPTIONS = 'options';
    const REMEMBER_ME_SECONDS = 'rememberMeSeconds';
    const SAVE_PATH = 'savePath';
    const USE_COOKIES = 'useCookies';
    const SESSION = 'session';

    /**
     * 
     * @staticvar array $eols
     * @param type $str
     * @param type $default
     * @return string EOL character terminating the passsed in string
     */
    public static function detectEol($str, $default = PHP_EOL) {
        static $eols = array(
            "\0x000D000A", // [UNICODE] CR+LF: CR (U+000D) followed by LF (U+000A)
            "\0x000A", // [UNICODE] LF: Line Feed, U+000A
            "\0x000B", // [UNICODE] VT: Vertical Tab, U+000B
            "\0x000C", // [UNICODE] FF: Form Feed, U+000C
            "\0x000D", // [UNICODE] CR: Carriage Return, U+000D
            "\0x0085", // [UNICODE] NEL: Next Line, U+0085
            "\0x2028", // [UNICODE] LS: Line Separator, U+2028
            "\0x2029", // [UNICODE] PS: Paragraph Separator, U+2029
            "\0x0D0A", // [ASCII] CR+LF: Windows, TOPS-10, RT-11, CP/M, MP/M, DOS, Atari TOS, OS/2, Symbian OS, Palm OS
            "\0x0A0D", // [ASCII] LF+CR: BBC Acorn, RISC OS spooled text output.
            "\0x0A", // [ASCII] LF: Multics, Unix, Unix-like, BeOS, Amiga, RISC OS
            "\0x0D", // [ASCII] CR: Commodore 8-bit, BBC Acorn, TRS-80, Apple II, Mac OS <=v9, OS-9
            "\0x1E", // [ASCII] RS: QNX (pre-POSIX)
            //"\0x76",       // [?????] NEWLINE: ZX80, ZX81 [DEPRECATED]
            "\0x15", // [EBCDEIC] NEL: OS/390, OS/400
        );
        $cur_cnt = 0;
        $cur_eol = $default;
        foreach ($eols as $eol) {
            if (($count = substr_count($str, $eol)) > $cur_cnt) {
                $cur_cnt = $count;
                $cur_eol = $eol;
            }
        }
        return $cur_eol;
    }

}
