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
 * Helper to locate application services.
 * @category   Ajgl
 * @package    Ajgl_Controller
 * @subpackage Action_Helper
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class Ajgl_Controller_Action_Helper_ServiceLocator
    extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var Bisna\Service\ServiceLocator
     */
    protected $_serviceLocator;
    
    /**
     * Returns a service identified by its name
     * @param string $service
     * @return Bisna\Service\AbstractService
     */
    public function direct($service)
    {
        return $this->getServiceLocator()->getService($service);
    }
    
    /**
     * Returns the Service Locator
     * @return Bisna\Service\ServiceLocator
     */
    public function getServiceLocator()
    {
        if ($this->_serviceLocator === null) {
            $this->_serviceLocator = $this->getFrontController()->getParam('bootstrap')->getResource('servicelocator');
        }
        return $this->_serviceLocator;
    }
}
