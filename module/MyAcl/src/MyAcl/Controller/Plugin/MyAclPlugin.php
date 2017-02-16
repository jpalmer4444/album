<?php

namespace MyAcl\Controller\Plugin;

use Application\Utility\Logger;
use DataAccess\FFM\Entity\UserRoleXref;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;

class MyAclPlugin extends AbstractPlugin {

    protected $sesscontainer;
    protected $logger;
    protected $sessionService;

    public function __construct($logger, $sessionService) {
        $this->logger = $logger;
        $this->sessionService = $sessionService;
    }

    public function doAuthorization($e) {

        // set ACL
        $acl = new Acl();

        $acl->deny(); // on by default
        //$acl->allow(); // this will allow every route by default so then you have to explicitly deny all routes that you want to protect.            
        # ROLES ############################################
        $acl->addRole(new Role('anonymous'));
        $acl->addRole(new Role('user'), 'anonymous');
        $acl->addRole(new Role('sales'), 'user');
        $acl->addRole(new Role('admin'), 'sales');
        # end ROLES ########################################
        # 
        # RESOURCES ########################################
        //$acl->addResource('application'); // Application module
        $acl->addResource('album:Album\Controller\AlbumController:index'); // album module, album controller, index action
        $acl->addResource('album:Album\Controller\AlbumController:add');
        $acl->addResource('album:Album\Controller\AlbumController:edit');
        $acl->addResource('album:Album\Controller\AlbumController:delete');

        $acl->addResource('application:Application\Controller\IndexController:index');
        $acl->addResource('application:Application\Controller\IndexController:resetpassword');

        $acl->addResource('login:Login\Controller\LoginController:authenticate');
        $acl->addResource('login:Login\Controller\LoginController:login');
        $acl->addResource('login:Login\Controller\LoginController:login/authenticate');
        $acl->addResource('login:Login\Controller\LoginController:logout');

        //success route
        $acl->addResource('login:Login\Controller\SuccessController:index');

        //sales route
        $acl->addResource('sales:Sales\Controller\SalesController:index');
        $acl->addResource('sales:Sales\Controller\ItemsController:index');
        $acl->addResource('sales:Sales\Controller\ItemsController:report');
        $acl->addResource('sales:Sales\Controller\UsersController:index');

        //command route
        $acl->addResource('command:Command\Controller\Reporting\PriceOverrideController:priceoverridereport');

        //add doctrinemodule for Console calls or MyAclPlugin will intercept
        $acl->addResource('doctrinemodule');
        //doctrinemodule:DoctrineModule\Controller\Cli:cli
        $acl->addResource('doctrinemodule:DoctrineModule\Controller\Cli:cli');
        //
        # end RESOURCES ########################################
        # 
        ################ PERMISSIONS #######################
        // $acl->allow('role', 'resource', 'controller:action');
        // Application -------------------------->
        $acl->allow('anonymous', 'album:Album\Controller\AlbumController:index');
        $acl->allow('anonymous', 'album:Album\Controller\AlbumController:add');
        $acl->allow('anonymous', 'album:Album\Controller\AlbumController:edit');
        $acl->allow('anonymous', 'album:Album\Controller\AlbumController:delete');
        $acl->allow('anonymous', 'application:Application\Controller\IndexController:index');
        $acl->allow('anonymous', 'application:Application\Controller\IndexController:resetpassword');
        $acl->allow('anonymous', 'login:Login\Controller\LoginController:authenticate');
        $acl->allow('anonymous', 'login:Login\Controller\LoginController:login');
        $acl->allow('anonymous', 'doctrinemodule:DoctrineModule\Controller\Cli:cli');
        $acl->allow('anonymous', 'command:Command\Controller\Reporting\PriceOverrideController:priceoverridereport');
        /// module/MyAcl/src/MyAcl/Controller/Plugin/MyAclPlugin.php
        // $acl->allow('role', 'resource', 'controller:action');
        // Sales -------------------------->
        $acl->allow('sales', 'login:Login\Controller\LoginController:logout');
        $acl->allow('sales', 'login:Login\Controller\SuccessController:index');
        $acl->allow('sales', 'sales:Sales\Controller\ItemsController:index');
        $acl->allow('sales', 'sales:Sales\Controller\ItemsController:report');
        $acl->allow('sales', 'sales:Sales\Controller\UsersController:index');

        // $acl->allow('role', 'resource', 'controller:action');
        // Sales -------------------------->
        $acl->allow('admin', 'sales:Sales\Controller\SalesController:index'); //admin should inherit
        // ################ end PERMISSIONS #####################

        $controller = $e->getTarget();
        $controllerClass = get_class($controller);
        $moduleName = strtolower(substr($controllerClass, 0, strpos($controllerClass, '\\')));
        $roles = [];
        if (!empty($this->sessionService->getRoles())) {
            $roles = $this->sessionService->getRoles();
        } else {
            $anonymous = new UserRoleXref();
            $anonymous->setUsername("anonymous");
            $anonymous->setRole("anonymous");
            $roles[0] = $anonymous;
        }

        //$role = (!$this->getSessContainer()->__get('role')) ? 'anonymous' : $this->getSessContainer()->$this->getSessContainer()->__get('role');

        $routeMatch = $e->getRouteMatch();

        $actionName = strtolower($routeMatch->getParam('action', 'not-found')); // get the action name 
        $controllerName = $routeMatch->getParam('controller', 'not-found');     // get the controller name     
        //$controllerName = strtolower(array_pop(explode('\\', $controllerName)));

        /*
          print '<br>$moduleName: '.$moduleName.'<br>';
          print '<br>$controllerClass: '.$controllerClass.'<br>';
          print '$controllerName: '.$controllerName.'<br>';
          print '$action: '.$actionName.'<br>'; */


        #################### Check Access ########################
        $allowed = FALSE;
        foreach ($roles as $role) {
            if ($acl->isAllowed($role->getRole(), $moduleName . ':' . $controllerName . ':' . $actionName)) {
                $allowed = TRUE;
            }
        }

        Logger::info("MyAclPlugin", __LINE__, "Allowed: " . $allowed);

        if (!$allowed) {
            $router = $e->getRouter();
            $request = $e->getRequest();
            $routeMatch = $router->match($request);
            if (!is_null($routeMatch)) {
                $this->sessionService->addRequestedRoute($routeMatch->getMatchedRouteName());
            }
            // $url    = $router->assemble(array(), array('name' => 'Login/auth')); // assemble a login route
            $url = $router->assemble(array(), array('name' => 'login'));
            $response = $e->getResponse();
            $response->setStatusCode(302);
            // redirect to login page or other page.
            $response->getHeaders()->addHeaderLine('Location', $url);
            $e->stopPropagation();
        }
    }

}
