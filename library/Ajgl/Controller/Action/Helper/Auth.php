<?php
/**
 * AJ General Libraries
 * Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
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
 * @package    Ajgl\Controller
 * @subpackage Action\Helper
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
namespace Ajgl\Controller\Action\Helper;

use Ajgl\Controller\Action\Helper\Exception;

/**
 * Helper to check the dispatch request authorization.
 * @category   Ajgl
 * @package    Ajgl\Controller
 * @subpackage Action\Helper
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class Auth
    extends \Zend_Controller_Action_Helper_Abstract
{

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var \Zend_Acl
     */
    protected $acl;

    /**
     * @var \Zend_Auth
     */
    protected $auth;

    /**
     * @var callback
     */
    protected $getRoleCallback;

    /**
     * @var array
     */
    protected $getRoleCallbackArguments = array();

    /**
     * @var array
     */
    protected $loginRoute = array(
        'module' => 'default',
        'controller' => 'login',
        'action' => 'index'
    );

    /**
     * @var array
     */
    protected $unauthRoute = array(
        'module' => 'default',
        'controller' => 'index',
        'action' => 'index'
    );

    /**
     * @var string
     */
    protected $returnParamName = 'return';

    /**
     * @param mixed array|\Zend_Config
     */
    public function __construct($options = null)
    {
        if (null !== $options) {
            $this->setOptions($options);
        }
    }

    /**
     * @param mixed array|\Zend_Config $options
     */
    public function setOptions($options)
    {
        if ($options instanceof \Zend_Config) {
            $options = $options->toArray();
        }

        if (!is_array($options)) {
            throw new Exception\InvalidArgumentException('Invalid options; must be array or \Zend_Config object');
        }

        $this->options = $options;

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
            $baseUrl = $this->getFrontController()->getBaseUrl();
            if (strpos($uri, $baseUrl) === 0) {
                $uri = substr($uri, strlen($baseUrl));
            }
            /**
             * Base64 encoded to avoid an apache error with default config
             * @see http://httpd.apache.org/docs/2.2/mod/core.html#allowencodedslashes
             */
            $returnUrl = base64_encode($uri);
            $routeOptions[$this->getReturnParamName()] = $returnUrl;

            $redirector = $this->_actionController->getHelper('Redirector');
            $redirector->gotoRoute($routeOptions, null, true);
        }

    }

    /**
     * Returns the ACL
     * @return \Zend_Acl
     */
    public function getAcl()
    {
        if (null === $this->acl) {
            if (!\Zend_Registry::isRegistered('acl')) {
                throw new Exception\RuntimeException(
                    "An instance of \Zend_Acl should be registered at \Zend_Registry 'acl' index"
                );
            }
            $acl = \Zend_Registry::get('acl');
            if (!($acl instanceof \Zend_Acl)) {
                throw new Exception\RuntimeException(
                    "The 'acl' key registered at \Zend_Registry must be an instance of '\Zend_Acl'"
                );
            }
            $this->acl = $acl;
        }

        return $this->acl;
    }

    /**
     * Sets the ACL
     * @param  \Zend_Acl $acl
     * @return Auth      Fluent interface
     */
    public function setAcl(\Zend_Acl $acl)
    {
        $this->acl = $acl;

        return $this;
    }

    /**
     * Returns the \Zend_Auth instance
     * @return \Zend_Auth
     */
    public function getAuth()
    {
        if (null == $this->auth) {
            $this->auth = \Zend_Auth::getInstance();
        }

        return $this->auth;
    }

    /**
     * Sets the \Zend_Auth instance
     * @param  \Zend_Auth $auth
     * @return Auth       Fluent interface
     */
    public function setAuth(\Zend_Auth $auth)
    {
        $this->auth = $auth;

        return $this;
    }

    /**
     * Sets the callback to be called when looking for the role
     * @param  callback $callback
     * @param  array    $arguments
     * @return Auth     Fluent interface
     */
    public function setGetRoleCallback($callback, array $arguments = array())
    {
        if (!is_callable($callback)) {
            throw new Exception\InvalidArgumentException("Argument must be a callable callback");
        }

        $this->getRoleCallback = $callback;
        $this->getRoleCallbackArguments = $arguments;

        return $this;
    }

    /**
     * Returns the role calling the role callback
     * @return mixed
     */
    public function getRole()
    {
        if (null === $this->getRoleCallback) {
            return null;
        }

        return call_user_func_array($this->getRoleCallback, $this->getRoleCallbackArguments);
    }

    /**
     * @param  array $loginRoute
     * @return Auth
     */
    public function setLoginRouteOptions(array $loginRoute)
    {
        $this->loginRoute = $loginRoute;

        return $this;
    }

    /**
     * @return array
     */
    public function getLoginRouteOptions()
    {
        return $this->loginRoute;
    }

    /**
     * @param  array $unauthRoute
     * @return Auth
     */
    public function setUnauthRouteOptions(array $unauthRoute)
    {
        $this->unauthRoute = $unauthRoute;

        return $this;
    }

    /**
     * @return array
     */
    public function getUnauthRouteOptions()
    {
        return $this->unauthRoute;
    }

    /**
     * @param  type $returnParamName
     * @return Auth
     */
    public function setReturnParamName($returnParamName)
    {
        $this->returnParamName = (string)$returnParamName;

        return $this;
    }

    /**
     * @return string
     */
    public function getReturnParamName()
    {
        return $this->returnParamName;
    }
}
