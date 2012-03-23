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
 * @package    Ajgl\Controller
 * @subpackage Action\Helper\Tests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 */
namespace Ajgl\Controller\Action\Helper;

/**
 * @category   Ajgl
 * @package    Ajgl\Controller
 * @subpackage Action\Helper\Tests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 */
class AuthTest
    extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Zend_Registry
     */
    protected $previousRegistry;

    /**
     * @var Auth
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->previousRegistry = \Zend_Registry::getInstance();
        \Zend_Registry::_unsetInstance();
        $this->object = new Auth();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        \Zend_Registry::_unsetInstance();
        if (isset($this->previousRegistry)) {
            \Zend_Registry::setInstance($this->previousRegistry);
        }
    }

    /**
     * @todo Implement testPreDispatch().
     */
    public function testPreDispatch() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    public function testGetAclGetsFromZendRegistry()
    {
        $acl = new \Zend_Acl;
        \Zend_Registry::set('acl', $acl);
        $this->assertSame($acl, $this->object->getAcl());
    }

    /**
     * @expectedException Ajgl\Controller\Action\Helper\Exception\RuntimeException
     * @expectedExceptionMessage An instance of \Zend_Acl should be registered at \Zend_Registry 'acl' index
     */
    public function testGetAclFailsIfNotAclRegistered()
    {
        $this->object->getAcl();
    }

    /**
     * @expectedException Ajgl\Controller\Action\Helper\Exception\RuntimeException
     * @expectedExceptionMessage The 'acl' key registered at \Zend_Registry must be an instance of '\Zend_Acl'
     */
    public function testGetAclFailsIfRegisteredAclNotValid()
    {
        \Zend_Registry::set('acl', new  \stdClass);
        //$this->setExpectedException('Exception', "The 'acl' key registered at Zend_Registry must be an instance of 'Zend_Acl'");
        $this->object->getAcl();
    }

    public function testSetAcl()
    {
        $acl = new \Zend_Acl;
        $this->object->setAcl($acl);
        $this->assertSame($acl, $this->object->getAcl());
    }

    public function testGetAuth()
    {
        $this->assertSame(\Zend_Auth::getInstance(), $this->object->getAuth());
    }

    public function testSetAuth()
    {
        $auth = \Zend_Auth::getInstance();
        $this->object->setAuth($auth);
        $this->assertSame($auth, $this->object->getAuth());
    }

    /**
     * @expectedException Ajgl\Controller\Action\Helper\Exception\InvalidArgumentException
     * @expectedExceptionMessage Argument must be a callable callback
     */
    public function testSetGetRoleCallbackFailsIfNotCallable()
    {
        $this->object->setGetRoleCallback(true);
    }

    public function testGetRoleReturnsNullIfNoCallbackSet()
    {
        $this->assertNull($this->object->getRole());
    }

    public function testSetGetRoleCallback()
    {
        $f = function() {return new \stdClass();};
        $this->object->setGetRoleCallback($f);
        $this->assertTrue($this->object->getRole() instanceof \stdClass);

        $f = function($v) {return $v;};
        $this->object->setGetRoleCallback($f, array('roleName'));
        $this->assertEquals('roleName', $this->object->getRole());
    }

}