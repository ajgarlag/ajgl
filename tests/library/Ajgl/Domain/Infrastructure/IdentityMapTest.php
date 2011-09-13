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
 * @package    Ajgl_Domain
 * @subpackage UnitTests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */

/**
 * Abstract identity map tests
 * 
 * @category   Ajgl
 * @package    Ajgl_Domain
 * @subpackage UnitTests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class Ajgl_Domain_Infrastructure_IdentityMapTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ajgl_Domain_Infrastructure_IdentityMap
     */
    protected $_identityMap;
    
    public function setUp()
    {
        $this->_identityMap = new Ajgl_Domain_Infrastructure_IdentityMap();
    }
    
    /**
     * @expectedException Exception
     * @expectedExceptionMessage The entity must have identity
     */
    public function testAddFailsIfNoIdentity()
    {
        $e = new Ajgl_Domain_Infrastructure_IdentityMapTest_Foo();
        $this->_identityMap->add($e);
    }
    
    public function testAdd()
    {
        $e = new Ajgl_Domain_Infrastructure_IdentityMapTest_Foo();
        $e->setId(uniqid());
        $this->assertSame($this->_identityMap, $this->_identityMap->add($e));
        $this->assertTrue($this->_identityMap->exists($e));
    }
    
    /**
     * @expectedException Exception
     * @expectedExceptionMessage Another entity with the same identity exists in the identity map
     */
    public function testAddFailsOnTwoDifferentInstancesWithTheSameIdentity()
    {
        $e1 = new Ajgl_Domain_Infrastructure_IdentityMapTest_Foo();
        $e1->setId(uniqid());
        $e2 = new Ajgl_Domain_Infrastructure_IdentityMapTest_Bar();
        $e2->setId($e1->getIdentity());
        $this->_identityMap->add($e1);
        $this->_identityMap->add($e2);
    }
    
    /**
     * @expectedException Exception
     * @expectedExceptionMessage The entity must have identity
     */
    public function testRemoveFailsIfNoIdentity()
    {
        $e = new Ajgl_Domain_Infrastructure_IdentityMapTest_Foo();
        $this->_identityMap->remove($e);
    }
    
    public function testRemove()
    {
        $e = new Ajgl_Domain_Infrastructure_IdentityMapTest_Foo();
        $e->setId(uniqid());
        $this->assertSame($this->_identityMap, $this->_identityMap->add($e));
        $this->assertTrue($this->_identityMap->exists($e));
        $this->assertSame($this->_identityMap, $this->_identityMap->remove($e));
        $this->assertFalse($this->_identityMap->exists($e));
    }
    
    /**
     * @expectedException Exception
     * @expectedExceptionMessage Another entity with the same identity exists in the identity map
     */
    public function testRemoveFailsOnTwoDifferentInstancesWithTheSameIdentity()
    {
        $e1 = new Ajgl_Domain_Infrastructure_IdentityMapTest_Foo();
        $e1->setId(uniqid());
        $e2 = new Ajgl_Domain_Infrastructure_IdentityMapTest_Bar();
        $e2->setId($e1->getIdentity());
        $this->_identityMap->add($e1);
        $this->_identityMap->remove($e2);
    }
    
    public function testExists()
    {
        $e = new Ajgl_Domain_Infrastructure_IdentityMapTest_Foo();
        $this->assertFalse($this->_identityMap->exists($e));
        $e->setId(uniqid());
        $this->assertFalse($this->_identityMap->exists($e));
        $this->_identityMap->add($e);
        $this->assertTrue($this->_identityMap->exists($e));
        $this->_identityMap->remove($e);
        $this->assertFalse($this->_identityMap->exists($e));
    }

    public function testHasEntity()
    {
        $e = new Ajgl_Domain_Infrastructure_IdentityMapTest_Bar();
        $id = uniqid();
        $e->setId($id);
        $this->assertFalse($this->_identityMap->hasEntity($e->getRootClass(), $id));
        $this->_identityMap->add($e);
        $this->assertTrue($this->_identityMap->hasEntity($e->getRootClass(), $id));
    }
    
    /**
     * @expectedException Exception
     * @expectedExceptionMessage The required entity does not exists
     */
    public function testGetEntityFailsIfNoEntity()
    {
        $e = new Ajgl_Domain_Infrastructure_IdentityMapTest_Bar();
        $id = uniqid();
        $this->_identityMap->getEntity($e->getRootClass(), $id);
    }
    
    public function testGet()
    {
        $e1 = new Ajgl_Domain_Infrastructure_IdentityMapTest_Foo();
        $id1 = uniqid();
        $e1->setId($id1);
        $this->_identityMap->add($e1);

        $e2 = new Ajgl_Domain_Infrastructure_IdentityMapTest_Bar();
        $id2 = uniqid();
        $e2->setId($id2);
        $this->_identityMap->add($e2);

        $this->assertTrue($this->_identityMap->exists($e1));
        $this->assertTrue($this->_identityMap->exists($e2));

        $this->assertSame($e1, $this->_identityMap->getEntity($e1->getRootClass(), $e1->getId()));
        $this->assertSame($e2, $this->_identityMap->getEntity($e2->getRootClass(), $e2->getId()));
        $this->assertNotEquals(get_class($e1), get_class($e2));
    }
}

class Ajgl_Domain_Infrastructure_IdentityMapTest_Foo
    extends Ajgl_Domain_Infrastructure_EntityAbstract
{
    protected $id;
    
    public function  getRootClass()
    {
        return __CLASS__;
    }
    
    public function hasIdentity()
    {
        return $this->__isset('id');
    }
    
    public function getIdentity()
    {
        return $this->getId();
    }
}

class Ajgl_Domain_Infrastructure_IdentityMapTest_Bar
    extends Ajgl_Domain_Infrastructure_IdentityMapTest_Foo
{

}