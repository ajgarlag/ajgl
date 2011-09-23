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
 * @package    Ajgl_Application
 * @subpackage UnitTests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 */

/**
 * @category   Ajgl
 * @package    Ajgl_Application
 * @subpackage UnitTests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 */
class Ajgl_Application_Resource_AclContainerTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ajgl_Application_Resource_AclContainer
     */
    protected $_aclContainer;
 
    /**
     * @var array
     */
    protected $_options = array();
    
    public function setUp()
    {
        $this->_options = array(
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
        );
        $this->_aclContainer = new Ajgl_Application_Resource_AclContainer();
    }

    public function testInit()
    {
        $this->assertSame($this->_aclContainer, $this->_aclContainer->init());
    }
    
    public function testGetAcl()
    {
        $this->_aclContainer->setOptions($this->_options);
        $acl = $this->_aclContainer->getAcl();
        $this->assertTrue($acl instanceof Ajgl_Acl);
        $this->assertSame($acl, $this->_aclContainer->getAcl());
        $this->assertTrue($acl->has('account#index'));
    }
}
