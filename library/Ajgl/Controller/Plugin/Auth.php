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
 * @subpackage Plugin
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */

/**
 * Plugin to check the dispatch request authorization.
 * @category   Ajgl
 * @package    Ajgl_Controller
 * @subpackage Plugin
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class Ajgl_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{

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
     * Checks if the current user is allowed to perform the requested action
     * 
     * @param Zend_Controller_Request_Abstract
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $controller = $request->getControllerName();
        $action     = $request->getActionName();
        $module     = $request->getModuleName();
        $resource   = $module . ':' . $controller;

        $auth = $this->getAuth();
        $acl = $this->getAcl();

        if (!$acl->has($resource)) {
            $resource = null;
        }

        $role = ($auth->hasIdentity())?$this->getRole():null;
        
        if (!$acl->isAllowed($role, $resource, $action)) {
            if (!$auth->hasIdentity()) {
                $module     = 'account';
                $controller = 'login';
                $action     = 'index';
            } else {
                $module     = 'account';
                $controller = 'login';
                $action     = 'unauth';
            }

            $returnUrl = urlencode(serialize($request->getParams()));
            $request->setParam('return', $returnUrl);
        }

        $request->setModuleName($module);
        $request->setControllerName($controller);
        $request->setActionName($action);
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
                throw new Exception("The 'acl' key registered at Zend_Registry must be an instance of 'Zend_Acl'");
            }
            $this->_acl = $acl;
        }
        return $this->_acl;
    }

    /**
     * Sets the ACL
     * @param Zend_Acl $acl
     * @return Ajgl_Controller_Plugin_Auth Fluent interface
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
     * @return Ajgl_Controller_Plugin_Auth Fluent interface
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
     * @return Ajgl_Controller_Plugin_Auth Fluent interface
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
            throw new UnexpectedValueException('The callback function to get role must be set');
        }
        return call_user_func_array($this->_getRoleCallback, $this->_getRoleCallbackArguments);
    }
    

}