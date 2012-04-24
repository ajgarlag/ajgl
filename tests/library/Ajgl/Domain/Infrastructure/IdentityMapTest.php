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
 * @package    Ajgl\Domain
 * @subpackage Infrastructure\Tests
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
namespace Ajgl\Domain\Infrastructure;
/**
 * Abstract identity map tests
 *
 * @category   Ajgl
 * @package    Ajgl\Domain
 * @subpackage Infrastructure\Tests
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class IdentityMapTest
    extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Ajgl_Domain_Infrastructure_IdentityMap
     */
    protected $identityMap;

    protected function setUp()
    {
        $this->identityMap = new IdentityMap();
    }

    /**
     * @expectedException Ajgl\Domain\Infrastructure\Exception\InvalidArgumentException
     * @expectedExceptionMessage The entity must have identity
     */
    public function testAddFailsIfNoIdentity()
    {
        $e = new Foo();
        $this->identityMap->add($e);
    }

    public function testAdd()
    {
        $e = new Foo();
        $e->setId(uniqid());
        $this->assertSame($this->identityMap, $this->identityMap->add($e));
        $this->assertTrue($this->identityMap->exists($e));
    }

    /**
     * @expectedException Ajgl\Domain\Infrastructure\Exception\InvalidArgumentException
     * @expectedExceptionMessage Another entity with the same identity exists in the identity map
     */
    public function testAddFailsOnTwoDifferentInstancesWithTheSameIdentity()
    {
        $e1 = new Foo();
        $e1->setId(uniqid());
        $e2 = new Bar();
        $e2->setId($e1->getIdentity());
        $this->identityMap->add($e1);
        $this->identityMap->add($e2);
    }

    /**
     * @expectedException Ajgl\Domain\Infrastructure\Exception\InvalidArgumentException
     * @expectedExceptionMessage The entity must have identity
     */
    public function testRemoveFailsIfNoIdentity()
    {
        $e = new Foo();
        $this->identityMap->remove($e);
    }

    public function testRemove()
    {
        $e = new Foo();
        $e->setId(uniqid());
        $this->assertSame($this->identityMap, $this->identityMap->add($e));
        $this->assertTrue($this->identityMap->exists($e));
        $this->assertSame($this->identityMap, $this->identityMap->remove($e));
        $this->assertFalse($this->identityMap->exists($e));
    }

    /**
     * @expectedException Ajgl\Domain\Infrastructure\Exception\InvalidArgumentException
     * @expectedExceptionMessage Another entity with the same identity exists in the identity map
     */
    public function testRemoveFailsOnTwoDifferentInstancesWithTheSameIdentity()
    {
        $e1 = new Foo();
        $e1->setId(uniqid());
        $e2 = new Bar();
        $e2->setId($e1->getIdentity());
        $this->identityMap->add($e1);
        $this->identityMap->remove($e2);
    }

    public function testExists()
    {
        $e = new Foo();
        $this->assertFalse($this->identityMap->exists($e));
        $e->setId(uniqid());
        $this->assertFalse($this->identityMap->exists($e));
        $this->identityMap->add($e);
        $this->assertTrue($this->identityMap->exists($e));
        $this->identityMap->remove($e);
        $this->assertFalse($this->identityMap->exists($e));
    }

    public function testHasEntity()
    {
        $e = new Bar();
        $id = uniqid();
        $e->setId($id);
        $this->assertFalse($this->identityMap->hasEntity($e->getRootClass(), $id));
        $this->identityMap->add($e);
        $this->assertTrue($this->identityMap->hasEntity($e->getRootClass(), $id));
    }

    /**
     * @expectedException Ajgl\Domain\Infrastructure\Exception\InvalidArgumentException
     * @expectedExceptionMessage The required entity does not exists
     */
    public function testGetEntityFailsIfNoEntity()
    {
        $e = new Bar();
        $id = uniqid();
        $this->identityMap->getEntity($e->getRootClass(), $id);
    }

    public function testGet()
    {
        $e1 = new Foo();
        $id1 = uniqid();
        $e1->setId($id1);
        $this->identityMap->add($e1);

        $e2 = new Bar();
        $id2 = uniqid();
        $e2->setId($id2);
        $this->identityMap->add($e2);

        $this->assertTrue($this->identityMap->exists($e1));
        $this->assertTrue($this->identityMap->exists($e2));

        $this->assertSame($e1, $this->identityMap->getEntity($e1->getRootClass(), $e1->getId()));
        $this->assertSame($e2, $this->identityMap->getEntity($e2->getRootClass(), $e2->getId()));
        $this->assertNotEquals(get_class($e1), get_class($e2));
    }
}

class Foo
    extends EntityAbstract
{
    protected $properties = array(
        'id'
    );

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

class Bar
    extends Foo
{

}