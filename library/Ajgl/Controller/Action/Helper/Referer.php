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
 * Helper to track the browser referer
 * @category   Ajgl
 * @package    Ajgl_Controller
 * @subpackage Action_Helper
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class Ajgl_Controller_Action_Helper_Referer
    extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var Zend_Session_Namespace
     */
    protected $_sessionNamespace;

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
     * @return Ajgl_Action_Helper_History
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
        return $this;
    }

    /**
     * @return Zend_Session_Namespace
     */
    public function getSessionNamespace()
    {
        if (null === $this->_sessionNamespace) {
            $this->setSessionNamespace(new Zend_Session_Namespace(__CLASS__));
        }
        return $this->_sessionNamespace;
    }

    /**
     * @param Zend_Session_Namespace $sessionNamespace
     * @return Ajgl_Controller_Action_Helper_History
     */
    public function setSessionNamespace(Zend_Session_Namespace $sessionNamespace)
    {
        $this->_sessionNamespace = $sessionNamespace;
        return $this;
    }

    /**
     * Tracks the referer header
     */
    public function preDispatch()
    {
        if ($this->getRequest() instanceof Zend_Controller_Request_Http) {
            if ($this->getRequest()->isGet()) {
                if (!$this->getRequest()->isXmlHttpRequest()) {
                    if ($requestReferer = $this->getRequest()->getServer('HTTP_REFERER', null)) {
                        $baseUrl = $this->getFrontController()->getBaseUrl();
                        $serverHelper = new Zend_View_Helper_ServerUrl();
                        $serverUrl = $serverHelper->serverUrl($baseUrl);
                        if (strpos($requestReferer, $serverUrl) === 0) {
                            $requestReferer = substr($requestReferer, strlen($serverUrl));
                        } else {
                            $requestReferer = null;
                        }

                        $uri = $this->getRequest()->getRequestUri();
                        if (strlen($baseUrl) && strpos($uri, $baseUrl) === 0) {
                            $uri = substr($uri, strlen($baseUrl));
                        }

                        if ($uri != $requestReferer) {
                            $this->getSessionNamespace()->referer = $requestReferer;
                        }
                    }
                }
            }
        }
    }

    /**
     * @param integer $steps
     * @param string $whereToGoIfNoHistory Url to go if there is no history step
     */
    public function goToReferer($whereToGoIfNoReferer = '/') {
        if (!$url = $this->getSessionNamespace()->referer) {
            $url = $whereToGoIfNoReferer;
        }
        $this->_actionController->getHelper('Redirector')->gotoUrl($url);
    }
}