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
 * @package    Ajgl\Acl
 * @subpackage Tests
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
namespace Ajgl\Acl;

/**
 * @category   Ajgl
 * @package    Ajgl\Acl
 * @subpackage Tests
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class AclTest
    extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Zend_Config
     */
    protected $config;

    /**
     * @var Acl
     */
    protected $acl;

    protected function setUp()
    {
        $this->config = new \Zend_Config(
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
        $this->acl = new Acl();
    }

    public function testLoadConfig()
    {
        $this->assertSame($this->acl, $this->acl->loadConfig($this->config));

        foreach ($this->config->roles as $role => $parentRoles) {
            $this->assertTrue($this->acl->hasRole($role));
            if (!empty($parentRoles)) {
                if (is_scalar($parentRoles)) {
                    $this->assertTrue($this->acl->inheritsRole($role, $parentRoles));
                } else {
                    foreach ($parentRoles as $parentRole) {
                        $this->assertTrue($this->acl->inheritsRole($role, $parentRole));
                    }
                }
            }
        }

        foreach ($this->config->resources as $resource => $parentResource) {
            $this->assertTrue($this->acl->has($resource));
            if (!empty($parentResource)) {
                $this->assertTrue($this->acl->inherits($resource, $parentResource));
            }
        }

        foreach ($this->config->permissions as $permission => $resources) {
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
        $this->assertTrue($this->acl->isAllowed($role, $resource, $privilege));
    }

    protected function _assertdeny($role = null, $resource = null, $privilege = null)
    {
        $this->assertFalse($this->acl->isAllowed($role, $resource, $privilege));
    }

}
