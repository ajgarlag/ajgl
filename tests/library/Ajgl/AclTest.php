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
 * @subpackage UnitTests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */

/**
 * @category   Ajgl
 * @package    Ajgl_Acl
 * @subpackage UnitTests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class Ajgl_AclTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * @var Zend_Config
     */
    protected $_config;
    
    /**
     * @var Ajgl_Acl
     */
    protected $_acl;
    
    public function setUp()
    {
        $this->_config = new Zend_Config(
            array(
                'roles' => array(
                    'guest' => null,
                    'member' => 'guest',
                    'admin' => 'member',
                    'api' => null,
                    'partner' => array('member', 'api')
                ),
                'resources' => array(
                    'default#index' => null,
                    'default#error'=> null,
                    'default#login' => null,
                    'default#lala' => 'default#login',
                    'account#index' => null,
                    'account#password' => null
                ),
                'permissions' => array(
                    'allow' => array(
                        'default#index' => null,
                        'default#error' => null,
                        'default#login' => array(
                            'login' => 'guest',
                            'logout' => 'member'
                        ),
                        'account#index' => 'member',
                        'account#password' => array(
                            'index' => 'member',
                            'recover' => 'guest',
                            'reset' => 'guest',
                            'rest' => array('member', 'api'),
                            'info' => null
                        )
                    ),
                    'deny' => array(
                        'default#lala' => null
                    )
                )
            )
        );
        $this->_acl = new Ajgl_Acl();
    }
    
    public function testLoadConfig()
    {
        $this->assertSame($this->_acl, $this->_acl->loadConfig($this->_config));
        
        foreach ($this->_config->roles as $role => $parentRoles) {
            $this->assertTrue($this->_acl->hasRole($role));
            if (!empty($parentRoles)) {
                if (is_scalar($parentRoles)) {
                    $this->assertTrue($this->_acl->inheritsRole($role, $parentRoles));
                } else {
                    foreach ($parentRoles as $parentRole) {
                        $this->assertTrue($this->_acl->inheritsRole($role, $parentRole));
                    }
                }
            }
        }
        
        foreach ($this->_config->resources as $resource => $parentResource) {
            $this->assertTrue($this->_acl->has($resource));
            if (!empty($parentResource)) {
                $this->assertTrue($this->_acl->inherits($resource, $parentResource));
            }
        }
        
        foreach ($this->_config->permissions as $permission => $resources) {
            $method = '_assert' . $permission;
            foreach ($resources as $resource => $privileges) {
                if (empty($privileges)) {
                    $this->$method(null, $resource);
                } elseif (is_scalar($privileges)) {
                    $this->$method($privileges, $resource);
                } else {
                    foreach ($privileges as $privilege => $roles) {
                        if (empty($roles)) {
                            $this->$method(null, $resource, $privilege);
                        } elseif (is_scalar($roles)) {
                            $this->$method($roles, $resource, $privilege);
                        } else {
                            foreach ($roles as $role) {
                                $this->$method($role, $resource, $privilege);
                            }
                        }
                    }
                }
            }
        }
    }
    
    protected function _assertallow($role = null, $resource = null, $privilege = null)
    {
        $this->assertTrue($this->_acl->isAllowed($role, $resource, $privilege));
    }
    
    protected function _assertdeny($role = null, $resource = null, $privilege = null)
    {
        $this->assertFalse($this->_acl->isAllowed($role, $resource, $privilege));
    }
    
}
