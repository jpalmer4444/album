<?php

namespace MyAcl\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Session\Container as SessionContainer;

class MyAclPlugin extends AbstractPlugin {

    protected $sesscontainer;
    
    protected $logger;
    
    protected $session;
    
    public function __construct($logger, $session) {
        $this->logger = $logger;
        $this->session = $session;
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
        $acl->addResource('album'); // album route
        $acl->addResource('login'); // login route
        $acl->addResource('sales'); // sales route
        $acl->addResource('items'); // items route
        $acl->addResource('users'); // users route
        //
        # end RESOURCES ########################################
        # 
        ################ PERMISSIONS #######################
        // $acl->allow('role', 'resource', 'controller:action');
        // Application -------------------------->
        $acl->allow('anonymous', 'login');//allow login page to render
        // Album -------------------------->
        $acl->allow('anonymous', 'album');
        // $acl->allow('role', 'resource', 'controller:action');
        // Sales -------------------------->
        $acl->allow('sales', 'items');
        $acl->allow('sales', 'users');
        
        // $acl->allow('role', 'resource', 'controller:action');
        // Sales -------------------------->
        $acl->allow('admin', 'sales');//admin should inherit
        
        // ################ end PERMISSIONS #####################

        $controller = $e->getTarget();
        $controllerClass = get_class($controller);
        $moduleName = strtolower(substr($controllerClass, 0, strpos($controllerClass, '\\')));
        $role = (isset($this->session['role'])) ? $this->session['role'] : 'anonymous';
        
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
        if (!$acl->isAllowed($role, $moduleName, $controllerName . ':' . $actionName)) {
            $router = $e->getRouter();
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
