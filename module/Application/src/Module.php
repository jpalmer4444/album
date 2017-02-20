<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Controller\IndexController;
use Application\Factory\CookieServiceFactory;
use Application\Factory\DateServiceFactory;
use Application\Factory\EntityServiceFactory;
use Application\Factory\LoggingServiceFactory;
use Application\Factory\ReportServiceFactory;
use Application\Factory\RestServiceFactory;
use Application\Factory\SessionServiceFactory;
use Application\Factory\SynchronizationServiceFactory;
use Application\Factory\TableServiceFactory;
use Application\Utility\Strings;
use Zend\Http\Request;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Session\SessionManager;
use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\Validator\HttpUserAgent;
use Zend\Session\Validator\RemoteAddr;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ModelInterface;

class Module implements ConfigProviderInterface, ServiceProviderInterface, AutoloaderProviderInterface {

    const VERSION = '3.0.2dev';

    public function getControllerConfig() {
        return [
            Strings::CONTROLLERS => [
                Strings::FACTORIES => [
                    IndexController::class => InvokableFactory::class,
                ],
        ]];
    }

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            Strings::STANDARD_AUTO_LOADER => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() {
        return array(
            Strings::FACTORIES => array(
                'LoggingService' => LoggingServiceFactory::class,
                Strings::REST_SERVICE => RestServiceFactory::class,
                Strings::REPORT_SERVICE => ReportServiceFactory::class,
                'EntityService' => EntityServiceFactory::class,
                'SessionService' => SessionServiceFactory::class,
                'DateService' => DateServiceFactory::class,
                'SynchronizationService' => SynchronizationServiceFactory::class,
                'CookieService' => CookieServiceFactory::class,
                'TableService' => TableServiceFactory::class,
            ),
        );
    }

    /**
     * Adds global method available in layout
     */
    public function onBootstrap($e) {
        $sm = $e->getApplication()->getServiceManager();
        $router = $sm->get(Strings::ROUTER);
        $request = $sm->get(Strings::REQUEST);
        $matchedRoute = $router->match($request);
        if ($matchedRoute) {
            $params = $matchedRoute->getParams();
            $controller = $params[Strings::CONTROLLER];
            //only needed when this is not an Ajax request and not security related.
            if (isset($params[Strings::ACTION])) {
                $action = $params[Strings::ACTION];
                $module_array = explode('\\', $controller);
                $module = array_pop($module_array);
                $route = $matchedRoute->getMatchedRouteName();
                    $sessionService = $sm->get('SessionService');
                    $e->getViewModel()->setVariables(
                            array(
                                'CURRENT_MODULE_NAME' => $module,
                                'CURRENT_CONTROLLER_NAME' => $controller,
                                'CURRENT_ACTION_NAME' => $action,
                                'CURRENT_ROUTE_NAME' => $route,
                                'FFM_SESSION' => [
                                    'user' => $sessionService->getUser(),
                                    'roles' => $sessionService->getRoles(),
                                    'admin' => $sessionService->admin(),
                                    'salespersoninplay' => $sessionService->getSalespersonInPlay()
                                ]
                            )
                    );
            }
        }
    }

    public function bootstrapSession($e) {
        $session = $e->getApplication()
                ->getServiceManager()
                ->get(SessionManager::class);
        $sessionId = $session->start();
        $serviceManager = $e->getApplication()->getServiceManager();
        $session->setStorage(new SessionArrayStorage());
        if(empty($sessionId)){
            $session->regenerateId(FALSE);
        }
        $config = $serviceManager->get('Config');
        if (!isset($config['session'])) {
            return;
        }
        $sessionConfig = $config['session'];
        if (!isset($sessionConfig['validators'])) {
            return;
        }
        $chain = $session->getValidatorChain();
        foreach ($sessionConfig['validators'] as $validator) {
            switch ($validator) {
                case HttpUserAgent::class:
                    $validator = new $validator($container->httpUserAgent);
                    break;
                case RemoteAddr::class:
                    $validator = new $validator($container->remoteAddr);
                    break;
                default:
                    $validator = new $validator();
            }
            $chain->attach('session.validate', array($validator, 'isValid'));
        }
    }
    
    public function onRenderError($e)
    {
        // must be an error
        if (!$e->isError()) {
            return;
        }
        // Check the accept headers for application/json
        $request = $e->getRequest();
        if (!$request instanceof Request) {
            return;
        }
        $headers = $request->getHeaders();
        if (!$headers->has('Accept')) {
            return;
        }
        $accept = $headers->get('Accept');
        $match  = $accept->match('application/json');
        if (!$match || $match->getTypeString() == '*/*') {
            // not application/json
            return;
        }
        // make debugging easier if we're using xdebug!
        ini_set('html_errors', 0); 
        // if we have a JsonModel in the result, then do nothing
        $currentModel = $e->getResult();
        if ($currentModel instanceof JsonModel) {
            return;
        }
        // create a new JsonModel - use application/api-problem+json fields.
        $response = $e->getResponse();
        $model = new JsonModel(array(
            "httpStatus" => $response->getStatusCode(),
            "title" => $response->getReasonPhrase(),
        ));
        // Find out what the error is
        $exception  = $currentModel->getVariable('exception');
        if ($currentModel instanceof ModelInterface && $currentModel->reason) {
            switch ($currentModel->reason) {
                case 'error-controller-cannot-dispatch':
                    $model->detail = 'The requested controller was unable to dispatch the request.';
                    break;
                case 'error-controller-not-found':
                    $model->detail = 'The requested controller could not be mapped to an existing controller class.';
                    break;
                case 'error-controller-invalid':
                    $model->detail = 'The requested controller was not dispatchable.';
                    break;
                case 'error-router-no-match':
                    $model->detail = 'The requested URL could not be matched by routing.';
                    break;
                default:
                    $model->detail = $currentModel->message;
                    break;
            }
        }
        if ($exception) {
            if ($exception->getCode()) {
                $e->getResponse()->setStatusCode($exception->getCode());
            }
            $model->detail = $exception->getMessage();
            // find the previous exceptions
            $messages = array();
            while ($exception = $exception->getPrevious()) {
                $messages[] = "* " . $exception->getMessage();
            };
            if (count($messages)) {
                $exceptionString = implode("n", $messages);
                $model->messages = $exceptionString;
            }
        }
        // set our new view model
        $model->setTerminal(true);
        $e->setResult($model);
        $e->setViewModel($model);
    }

    public function getViewHelperConfig() {
        return array(
            Strings::INVOKABLES => array(
                Strings::FORM_LABEL => Strings::REQUIRED_MARK_IN_FORM_LABEL,
            ),
        );
    }

}
