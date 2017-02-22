<?php

namespace Application\Controller;

use InvalidArgumentException;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

abstract class BaseController extends AbstractActionController
{
    /**
     * Application wide DBAdapter
     * @var Adapter
     */
    protected $dbAdapter;
    
    /**
     * AuthenticationService
     * @var AuthenticationService
     */
    protected $authService;
    
    /**
     * Merged configuration array
     * @var array
     */
    protected $config;

    protected function getBasePath()
    {
        return BASE_PATH.'/';
    }

    protected function getBaseUrl()
    {;
        if (isset($this->config['baseUrl'])) {
            return $this->config['baseUrl'];
        }

        $uri = $this->getRequest()->getUri();

        return sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
    }

    /**
     * Check if user has permissions to access current route
     * @param MvcEvent $e
     * @return mixed
     */
    public function onDispatch(MvcEvent $e)
    {
        $routeMatch = $this->getEvent()->getRouteMatch()->getMatchedRouteName();

        if (is_null($this->getCurrentUser())) {
            
            
        } else {
            
        }

        parent::onDispatch($e);
    }

    /**
     * Get logged in username
     *
     * @return string
     */
    protected function getCurrentUser()
    {
        if ($this->authService->hasIdentity()) {
            return $this->authService->getIdentity();
        }

        return null;
    }

    /**
     * @return ViewModel
     */
    public function getView()
    {
        $view = new ViewModel();
        $view->loggedinusername = $this->getCurrentUser();

        return $view;
    }

    public function htmlResponse($html)
    {
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->setContent($html);
        return $response;
    }

    public function jsonResponse($data)
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException('$data param must be array');
        }

        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->setContent(json_encode($data));
        return $response;
    }

    /**
     *
     * @return Adapter
     */
    protected function getDbAdapter()
    {
        return $this->dbAdapter;
    }
    
    /**
     * @param Adapter $dbAdapter
     * @return $this
     */
    public function setDbAdapter(Adapter $dbAdapter) {
        $this->dbAdapter = $dbAdapter;
        return $this;
    }

    /**
     * @param AuthenticationService $authService
     * @return $this
     */
    public function setAuthService(AuthenticationService $authService) {
        $this->authService = $authService;
        return $this;
    }

    /**
     * 
     * @param type $config
     * @return $this
     */
    public function setConfig($config) {
        $this->config = $config;
        return $this;
    }
    
}
