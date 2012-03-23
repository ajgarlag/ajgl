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
 * @package    Ajgl\Application
 * @subpackage Resource
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
namespace Ajgl\Application\Resource;

use Ajgl\Acl\Acl;

/**
 * Acl class
 * @category   Ajgl
 * @package    Ajgl\Application
 * @subpackage Resource
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class Aclcontainer
    extends \Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var Acl
     */
    protected $acl;

    /**
     * Retrieve initialized Ldap connection
     *
     * @return Acl
     */
    public function getAcl()
    {
        if (null === $this->acl) {
            $config = new \Zend_Config($this->getOptions());
            $this->acl = new Acl();
            $this->acl->loadConfig($config);
        }
        return $this->acl;
    }

    /**
     * Defined by \Zend_Application_Resource_Resource
     *
     * @return Aclcontainer
     */
    public function init()
    {
        return $this;
    }
}