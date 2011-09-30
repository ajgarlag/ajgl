<?php
/**
 * AJ General Libraries
 * Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, version 3 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category   Ajgl
 * @package    Ajgl_Controller
 * @subpackage Action_Helper
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */

/**
 * Helper to check the dispatch request authorization.
 * @category   Ajgl
 * @package    Ajgl_Controller
 * @subpackage Action_Helper
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class Ajgl_Controller_Action_Helper_Auth
    extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * @var array
     */
    protected $_options = array();
    
    /**
     * @var Zend_Acl
     */
    protected $_acl;
    
    /**
     * @var Zend_Auth
     */
    protected $_auth;
    
    /**
     * @var callback
     */
    protected $_getRoleCallback;
    
    /**
     * @var array
     */
    protected $_getRoleCallbackArguments = array();
    
    /**
     * @var array
     */
    protected $_loginRoute = array(
        'module' => 'default',
        'controller' => 'login',
        'action' => 'index'
    );
    
    /**
     * @var array
     */
    protected $_unauthRoute = array(
        'module' => 'default',
        'controller' => 'index',
        'action' => 'index'
    );
    
    /**
     * @var string
     */
    protected $_returnParamName = 'return';
    
    /**
     * @param mixed array|Zend_Config
     */
    public function __construct($options = null)
    {
        if (null !== $options) {
            $this->setOptions($options);    
        }
    }
    
    /**
     * @param mixed array|Zend_Config $options 
     */
    public function setOptions($options)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }
        
        if (!is_array($options)) {
            throw new Exception('Invalid options; must be array or Zend_Config object');
        }
        
        $this->_options = $options;
        
        foreach ($options as $k => $v) {
            $setter = 'set' . ucfirst($k);
            if (method_exists($this, $setter)) {
                $this->$setter($v);
            }
        }
    }
    
    /**
     * Checks if the current user is allowed to perform the requested action
     * 
     * @param Zend_Controller_Request_Abstract
     * @return void
     */
    public function preDispatch()
    {
        $controller = $this->getRequest()->getControllerName();
        $action     = $this->getRequest()->getActionName();
        $module     = $this->getRequest()->getModuleName();
        $resource   = strtolower($module . '#' . $controller);

        if (!$this->getAcl()->has($resource)) {
            $resource = null;
        }

        $role = ($this->getAuth()->hasIdentity())?$this->getRole():null;
        
        if (!$this->getAcl()->isAllowed($role, $resource, $action)) {
            if (!$this->getAuth()->hasIdentity()) {
                $routeOptions = $this->getLoginRouteOptions();
            } else {
                $routeOptions = $this->getUnauthRouteOptions();
            }

            $uri = $this->getRequest()->getRequestUri();
            /**
             * Base64 encoded to avoid an apache error with default config
             * @see http://httpd.apache.org/docs/2.2/mod/core.html#allowencodedslashes
             */
            $returnUrl = urlencode(base64_encode($uri));
            $routeOptions[$this->getReturnParamName()] = $returnUrl;
            
            $redirector = $this->_actionController->getHelper('Redirector');
            $redirector->gotoRoute($routeOptions, null, true);
        }
  
    }
    
    /**
     * Returns the ACL
     * @return Zend_Acl
     */
    public function getAcl()
    {
        if (null === $this->_acl) {
            $acl = Zend_Registry::get('acl');
            if (!($acl instanceof Zend_Acl)) {
                throw new Exception("
                    The 'acl' key registered at Zend_Registry must be an instance of 'Zend_Acl'"
                );
            }
            $this->_acl = $acl;
        }
        return $this->_acl;
    }

    /**
     * Sets the ACL
     * @param Zend_Acl $acl
     * @return Ajgl_Controller_Action_Helper_Auth Fluent interface
     */
    public function setAcl(Zend_Acl $acl)
    {
        $this->_acl = $acl;
        return $this;
    }
    
    /**
     * Returns the Zend_Auth instance
     * @return Zend_Auth
     */
    public function getAuth()
    {
        if (null == $this->_auth) {
            $this->_auth = Zend_Auth::getInstance();
        }
        return $this->_auth;
    }
    
    /**
     * Sets the Zend_Auth instance
     * @param Zend_Auth $auth
     * @return Ajgl_Controller_Action_Helper_Auth Fluent interface
     */
    public function setAuth(Zend_Auth $auth)
    {
        $this->_auth = $auth;
        return $this;
    }
    
    /**
     * Sets the callback to be called when looking for the role
     * @param callback $callback
     * @param array $arguments
     * @return Ajgl_Controller_Action_Helper_Auth Fluent interface
     */
    public function setGetRoleCallback($callback, array $arguments = array())
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException("Argument must be a callable callback");
        }
        
        $this->_getRoleCallback = $callback;
        $this->_getRoleCallbackArguments = $arguments;
        return $this;
    }
    
    /**
     * Returns the role calling the role callback
     * @return mixed
     */
    public function getRole()
    {
        if (null === $this->_getRoleCallback) {
            return null;
        }
        return call_user_func_array($this->_getRoleCallback, $this->_getRoleCallbackArguments);
    }
    
    /**
     * @param array $loginRoute
     * @return Ajgl_Controller_Action_Helper_Auth 
     */
    public function setLoginRouteOptions(array $loginRoute)
    {
        $this->_loginRoute = $loginRoute;
        return $this;
    }
    
    /**
     * @return array
     */
    public function getLoginRouteOptions()
    {
        return $this->_loginRoute;
    }
    
    /**
     * @param array $unauthRoute
     * @return Ajgl_Controller_Action_Helper_Auth 
     */
    public function setUnauthRouteOptions(array $unauthRoute)
    {
        $this->_unauthRoute = $unauthRoute;
        return $this;
    }

    /**
     * @return array
     */
    public function getUnauthRouteOptions()
    {
        return $this->_unauthRoute;
    }

    /**
     * @param type $returnParamName
     * @return Ajgl_Controller_Action_Helper_Auth 
     */
    public function setReturnParamName($returnParamName)
    {
        $this->_returnParamName = (string)$returnParamName;
        return $this;
    }

    /**
     * @return string
     */
    public function getReturnParamName()
    {
        return $this->_returnParamName;
    }
}