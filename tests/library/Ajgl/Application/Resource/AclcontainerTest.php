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
 * @package    Ajgl\Application
 * @subpackage Tests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 */
namespace Ajgl\Application\Resource;

use Ajgl\Acl\Acl;

/**
 * @category   Ajgl
 * @package    Ajgl\Application
 * @subpackage Tests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 */
class AclcontainerTest
    extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Aclcontainer
     */
    protected $aclcontainer;

    /**
     * @var array
     */
    protected $options = array();

    public function setUp()
    {
        $this->options = array(
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
        $this->aclcontainer = new Aclcontainer();
    }

    public function testInit()
    {
        $this->assertSame($this->aclcontainer, $this->aclcontainer->init());
    }

    public function testGetAcl()
    {
        $this->aclcontainer->setOptions($this->options);
        $acl = $this->aclcontainer->getAcl();
        $this->assertTrue($acl instanceof Acl);
        $this->assertSame($acl, $this->aclcontainer->getAcl());
        $this->assertTrue($acl->has('account#index'));
    }
}
