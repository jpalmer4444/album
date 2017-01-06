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
     * FFMEntityManagerService
     * Service that provides an entry point into Doctrine EntityManager.
     */
    const FFM_ENTITY_MANAGER = <<<'EOT'
   FFMEntityManager
EOT;

    /**
     * @see SessionManager
     * SessionManager
     * Service that provides an entry point into Zend\Session\SessionManager.
     */
    const SESSION_MANAGER = <<<'EOT'
   SessionManager
EOT;

    /**
     * @see ReportService
     * ReportService
     * Service for reports.
     */
    const REPORT_SERVICE = <<<'EOT'
   ReportService
EOT;

    /**
     * @see RestService
     * RestService
     * Service that makes REST calls to svc backend.
     */
    const REST_SERVICE = <<<'EOT'
   RestService
EOT;

    /**
     * config
     */
    const CONFIG = <<<'EOT'
   config
EOT;

    /**
     * pricing_config
     */
    const PRICING_CONFIG =  <<<'EOT'
   pricing_config
EOT;

    /**
     * controllers
     */
    const CONTROLLERS = <<<'EOT'
   controllers
EOT;

    /**
     * factories
     */
    const FACTORIES = <<<'EOT'
   factories
EOT;

    /**
     * dispatch
     */
    const DISPATCH = <<<'EOT'
   dispatch
EOT;

    /**
     * invokables
     */
    const INVOKABLES = <<<'EOT'
   invokables
EOT;

    /**
     * request
     */
    const REQUEST = <<<'EOT'
   request
EOT;

    /**
     * response
     */
    const RESPONSE = <<<'EOT'
   response
EOT;

    /**
     * router
     */
    const ROUTER = <<<'EOT'
   router
EOT;

    /**
     * controller
     */
    const CONTROLLER = <<<'EOT'
   controller
EOT;

    /**
     * action
     */
    const ACTION = <<<'EOT'
   action
EOT;

    /**
     * LoginController
     */
    const LOGIN_CONTROLLER =  <<<'EOT'
   Login\Controller\LoginController
EOT;

    /**
     * SuccessController
     */
    const SUCCESS_CONTROLLER = <<<'EOT'
   Login\Controller\SuccessController
EOT;

    /**
     * MyAuthStorage
     */
    const MY_AUTH_STORAGE = <<<'EOT'
   Login\Model\MyAuthStorage
EOT;

    /**
     * AuthService
     */
    const AUTH_SERVICE = <<<'EOT'
   AuthService
EOT;

    /**
     * Zend\Db\Adapter\Adapter
     */
    const ZEND_DB_ADAPTER = <<<'EOT'
   Zend\Db\Adapter\Adapter
EOT;

    /**
     * Zend\Mvc\Controller\AbstractActionController
     */
    const ABSTRACT_ACTION_CONTROLLER = <<<'EOT'
   Zend\Mvc\Controller\AbstractActionController
EOT;

    /**
     * Zend\Loader\ClassMapAutoloader
     */
    const CLASS_MAP_AUTO_LOADER = <<<'EOT'
   Zend\Loader\ClassMapAutoloader
EOT;

    /**
     * Zend\Loader\StandardAutoloader
     */
    const STANDARD_AUTO_LOADER = <<<'EOT'
   Zend\Loader\StandardAutoloader
EOT;

    /**
     * Sales\Factory\ItemsControllerFactory
     */
    const SALES_ITEMS_CONTROLLER_FACTORY = <<<'EOT'
   Sales\Factory\ItemsControllerFactory
EOT;

    /**
     * Sales\Factory\SalesControllerFactory
     */
    const SALES_SALES_CONTROLLER_FACTORY = <<<'EOT'
   Sales\Factory\SalesControllerFactory
EOT;

    /**
     * Sales\Factory\UsersControllerFactory
     */
    const SALES_USERS_CONTROLLER_FACTORY = <<<'EOT'
   Sales\Factory\UsersControllerFactory
EOT;

    /**
     * Sales\Controller\ItemsControllerFactory
     */
    const SALES_ITEMS_CONTROLLER = <<<'EOT'
   Sales\Controller\ItemsController
EOT;

    /**
     * Sales\Controller\SalesControllerFactory
     */
    const SALES_SALES_CONTROLLER = <<<'EOT'
   Sales\Controller\SalesController
EOT;

    /**
     * Sales\Controller\UsersControllerFactory
     */
    const SALES_USERS_CONTROLLER = <<<'EOT'
   Sales\Controller\UsersController
EOT;

    /**
     * Ajax\Controller\ItemsControllerFactory
     */
    const AJAX_ITEMS_CONTROLLER = <<<'EOT'
   Ajax\Controller\Sales\ItemsController
EOT;

    /**
     * Ajax\Controller\SalesControllerFactory
     */
    const AJAX_SALES_CONTROLLER = <<<'EOT'
   Ajax\Controller\Sales\SalesController
EOT;

    /**
     * Ajax\Controller\UsersControllerFactory
     */
    const AJAX_USERS_CONTROLLER = <<<'EOT'
   Ajax\Controller\Sales\UsersController
EOT;

    /**
     * ItemsFilterTableArrayService
     */
    const ITEMS_FILTER_TABLE_ARRAY_SERVICE = <<<'EOT'
   ItemsFilterTableArrayService
EOT;

    /**
     * Application\ViewHelper\RequiredMarkInFormLabel
     */
    const REQUIRED_MARK_IN_FORM_LABEL = <<<'EOT'
   Application\ViewHelper\RequiredMarkInFormLabel
EOT;

    /**
     * formLabel
     */
    const FORM_LABEL = <<<'EOT'
   formLabel
EOT;

    /**
     * namespaces
     */
    const NAMESPACES = <<<'EOT'
   namespaces
EOT;

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
