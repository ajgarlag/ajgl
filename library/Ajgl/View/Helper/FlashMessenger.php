<?php
/**
 * AJ Global Libraries
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
     * Retrieve the FlashMessenger messages.
     *
     * @param string|null $namespace
     * @return string 
     */
    public function flashMessenger($namespace = null)
    {
        // Retrieve instance
        $this->_flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');

        // Set namespace to retrieve
        if ($namespace !== null) {
            $this->_flashMessenger->setNamespace($namespace);
        }

        $messages = array_merge(
            $this->_flashMessenger->getMessages(),
            $this->_flashMessenger->getCurrentMessages()
        );
        
        $this->_flashMessenger->clearCurrentMessages();
        
        return $this->view->htmlList($messages);
    }
}