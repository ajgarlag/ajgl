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
 * @package    Ajgl_Acl
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */

/**
 * Acl class
 * @category   Ajgl
 * @package    Ajgl_Acl
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class Ajgl_Acl
    extends Zend_Acl
{
    /**
     * Load roles, resources an permissions definitions from a Zend_Config object
     * @param Zend_Config $config 
     * @return Ajgl_Acl
     */
    public function loadConfig(Zend_Config $config)
    {
        $this->_loadRoles($config->roles);
        $this->_loadResources($config->resources);
        $this->_loadPermissions($config->permissions);
        return $this;
    }
    
    /**
     * @param Zend_Config $roles 
     */
    protected function _loadRoles(Zend_Config $roles)
    {
        foreach ($roles as $role => $parentRoles) {
            if (empty($parentRoles)) {
                $parentRoles = null;
            } elseif (!is_scalar($parentRoles)) {
                $parentRoles = $parentRoles->toArray();
            }
            $this->addRole($role, $parentRoles);
        }
    }

    /**
     * @param Zend_Config $resources 
     */
    protected function _loadResources(Zend_Config $resources)
    {
        foreach ($resources as $resource => $parentResource) {
            if (empty($parentResource)) {
                $parentResource = null;
            }
            $this->addResource($resource, $parentResource);
        }
    }

    /**
     * @param Zend_Config $permissions 
     */
    protected function _loadPermissions(Zend_Config $permissions)
    {
        foreach ($permissions as $permission => $resources) {
            foreach ($resources as $resource => $privileges) {
                if (empty($privileges)) {
                    $this->$permission(null, $resource);
                } elseif (is_scalar($privileges)) {
                    $this->$permission($privileges, $resource);
                } else {
                    foreach ($privileges as $privilege => $roles) {
                        if (empty($roles)) {
                            $this->$permission(null, $resource, $privilege);
                        } elseif (is_scalar($roles)) {
                            $this->$permission($roles, $resource, $privilege);
                        } else {
                            $this->$permission($roles->toArray(), $resource, $privilege);
                        }
                    }
                }
            }
        }
    }
}