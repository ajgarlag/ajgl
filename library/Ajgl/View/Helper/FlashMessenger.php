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
 * @package    Ajgl_View
 * @subpackage Helper
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */

/**
 * Plugin to render the messages registered with the flashMessenger action helper
 * @category   Ajgl
 * @package    Ajgl_View
 * @subpackage Helper
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class Ajgl_View_Helper_FlashMessenger extends Zend_View_Helper_Abstract
{
    /**
     * @var Zend_Controller_Action_Helper_FlashMessenger 
     */
    protected $_flashMessenger;
    
    /**
     * @param string $namespace
     * @return CudbApp_View_Helper_FlashMessenger
     */
    public function flashMessenger($namespace = null)
    {
        // Set namespace to retrieve
        if ($namespace !== null) {
            $this->getFlashMessenger()->setNamespace($namespace);
        }

        return $this;
    }
    
    /**
     * @return Zend_Controller_Action_Helper_FlashMessenger 
     */
    public function getFlashMessenger()
    {
        if (null == $this->_flashMessenger) {
            $this->_flashMessenger 
                = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        }
        
        return $this->_flashMessenger;
    }

    /**
     * @return boolean
     */
    public function hasAnyMessage()
    {
        return $this->getFlashMessenger()->hasCurrentMessages()
            || $this->getFlashMessenger()->hasMessages();
    }
    
    /**
     * @return array
     */
    public function getAllMessages()
    {
        $messages = array();
        if ($this->getFlashMessenger()->hasMessages()) {
            $messages = array_merge($messages, $this->getFlashMessenger()->getMessages());
        }
        if ($this->getFlashMessenger()->hasCurrentMessages()) {
            $messages = array_merge($messages, $this->getFlashMessenger()->getCurrentMessages());
        }
        return $messages;
    }
    
    /**
     * @return void
     */
    public function clearAllMessages()
    {
        if ($this->getFlashMessenger()->hasMessages()) {
            $this->getFlashMessenger()->clearMessages();
        }
        if ($this->getFlashMessenger()->hasCurrentMessages()) {
            $this->getFlashMessenger()->clearCurrentMessages();
        }
    }
    
    /**
     * @return array
     */
    public function getAndClearAllMessages()
    {
        $messages = $this->getAllMessages();
        $this->clearAllMessages();
        return $messages;
    }
    
    /**
     * @param string $name
     * @param mixed $arguments
     * @return mixed 
     */
    public function __call($name, $arguments) {
        return call_user_func_array(array($this->getFlashMessenger(), $name), $arguments);
    }
}