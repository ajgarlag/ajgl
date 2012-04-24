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

use Ajgl\Domain\Infrastructure\Exception;

/**
 * Abstract entity class tests
 *
 * @category   Ajgl
 * @package    Ajgl\Domain
 * @subpackage Infrastructure\Tests
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class EntityAbstractTest
    extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityAbstractImplementation
     */
    protected $entity;

    protected function setUp()
    {
        $this->entity = new EntityAbstractImplementation;
    }

    public function testMagicSetAndGet()
    {
        $this->assertEquals(2, $this->entity->propertyB);
        $this->entity->propertyB = 'foo';
        $this->assertEquals('foo', $this->entity->propertyB);
    }

    /**
     * @expectedException Ajgl\Domain\Infrastructure\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid property 'propertyA'
     */
    public function testMagicGetFailsTryingToAccessToPrivateProperties()
    {
        $this->entity->propertyA;
    }

    /**
     * @expectedException Ajgl\Domain\Infrastructure\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid property 'property'
     */
    public function testMagicGetFailsTryingToAccessToUndefinedProperties()
    {
        $this->entity->property;
    }

    /**
     * @expectedException Ajgl\Domain\Infrastructure\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid property '_propertyC'
     */
    public function testMagicGetFailsTryingToAccessToPrivatePropertiesStartingWithUnderscore()
    {
        $this->entity->_propertyC;
    }

    /**
     * @expectedException Ajgl\Domain\Infrastructure\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid property '_propertyD'
     */
    public function testMagicGetFailsTryingToAccessToProtectedPropertiesStartingWithUnderscore()
    {
        $this->entity->_propertyD;
    }

    /**
     * @expectedException Ajgl\Domain\Infrastructure\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid property 'propertyA'
     */
    public function testMagicSetFailsTryingToAccessToPrivateProperties()
    {
        $this->entity->propertyA = 'lala';
    }

    /**
     * @expectedException Ajgl\Domain\Infrastructure\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid property 'property'
     */
    public function testMagicSetFailsTryingToAccessToUndefindedProperties()
    {
        $this->entity->property = 'lala';
    }

    /**
     * @expectedException Ajgl\Domain\Infrastructure\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid property '_propertyC'
     */
    public function testMagicSetFailsTryingToAccessToPrivatePropertiesStartingWithUnderscore()
    {
        $this->entity->_propertyC = 'lala';
    }

    /**
     * @expectedException Ajgl\Domain\Infrastructure\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid property '_propertyD'
     */
    public function testMagicSetFailsTryingToAccessToProtectedPropertiesStartingWithUnderscore()
    {
        $this->entity->_propertyD = 'lala';
    }

    public function testMagicCallCallingGetter()
    {
        $this->assertEquals('Get ', $this->entity->getPropertyE());
    }

    public function testMagicCallCallingSetter()
    {
        $this->assertSame($this->entity, $this->entity->setPropertyE('foo'));
        $this->assertEquals('Get E:foo', $this->entity->getPropertyE());
    }

    public function testDirectAccessNotByPassGetter()
    {
        $this->assertEquals('Get ', $this->entity->propertyE);
    }

    public function testDirectAccessNotByPassSetter()
    {
        $this->entity->propertyE = 'foo';
        $this->assertEquals('Get E:foo', $this->entity->propertyE);
    }

    public function testMagicCallCallingGetterForUndefinedProperty()
    {
        $this->assertEquals('UNDEFINED', $this->entity->getPropertyF());
    }

    public function testMagicCallCallingSetterForUndefinedProperty()
    {
        $this->assertSame($this->entity, $this->entity->setPropertyF('bar'));
        $this->assertEquals('UNDEFINED:bar', $this->entity->getPropertyF());
    }

    /**
     * @expectedException Ajgl\Domain\Infrastructure\Exception\BadMethodCallException
     * @expectedExceptionMessage Calling a getter with 1 arguments. None allowed
     */
    public function testMagicCallCallingGetterWithParam()
    {
        $this->entity->getPropertyA('foo');
    }

    /**
     * @expectedException Ajgl\Domain\Infrastructure\Exception\BadMethodCallException
     * @expectedExceptionMessage Calling a setter with 0 arguments. Only one argument allowed
     */
    public function testMagicCallCallingSetterWithoutParam()
    {
        $this->entity->setPropertyA();
    }

    /**
     * @expectedException Ajgl\Domain\Infrastructure\Exception\BadMethodCallException
     * @expectedExceptionMessage Calling a setter with 2 arguments. Only one argument allowed
     */
    public function testMagicCallCallingSetterWithMoreThanOneParam()
    {
        $this->entity->setPropertyA('foo', 'bar');
    }

    /**
     * @expectedException Ajgl\Domain\Infrastructure\Exception\BadMethodCallException
     * @expectedExceptionMessage Calling a non get/set method that does not exist: fooBar
     */
    public function testMagicCallNotCallingGetterNorSetter()
    {
        $this->entity->fooBar();
    }

    public function testIsSet()
    {
        $this->assertFalse(isset($this->entity->propertyA));
        $this->assertTrue(isset($this->entity->propertyB));
    }

    public function testUnset()
    {
        $this->assertFalse(isset($this->entity->propertyA));
        $this->assertTrue(isset($this->entity->propertyB));
        unset($this->entity->propertyA);
        unset($this->entity->propertyB);
        $this->assertFalse(isset($this->entity->propertyA));
        $this->assertFalse(isset($this->entity->propertyB));
    }

    public function testToArray()
    {
        $data = array('propertyB' => 2, 'propertyG' => null, 'propertyH' => null);
        $this->assertEquals($data, $this->entity->toArray());
        $this->assertEquals(array_keys($data), array_keys($this->entity->toArray()));
    }

    public function testFromArray()
    {
        $data = array('propertyB' => 2, 'propertyG' => 43);
        $expected = array_merge($data, array('propertyH' => null));
        $this->assertSame($this->entity, $this->entity->fromArray($data));
        $this->assertEquals($expected, $this->entity->toArray());
    }

    public function testFromArrayCallsUnsetOnNullValues()
    {
        $data = array('propertyG' => 'foo', 'propertyH' => 'bar');
        try {
            $this->entity->fromArray($data);
            $this->fail('propertyH must admit only stdClass objects as values');
        } catch (\PHPUnit_Framework_Error $e) {}
        $h = new \stdClass();
        $data = array('propertyG' => 'foo', 'propertyH' => $h);
        $this->entity->fromArray($data);
        $this->assertSame($h, $this->entity->getPropertyH());
        $data = array('propertyH' => null);
        $this->entity->fromArray($data);
        $this->assertNull($this->entity->getPropertyH());
    }

}

class EntityAbstractImplementation
    extends EntityAbstract
{
    protected $properties = array(
        'propertyB',
        'propertyG',
        'propertyH'
    );

    protected $propertyG;
    private $propertyA = 1;
    protected $propertyB = 2;
    private $propertyC;
    protected $propertyD;
    private $propertyE;

    /**
     * @var stdClass
     */
    protected $propertyH;

    private $lazyPropertyF = 'UNDEFINED';

    public function hasIdentity()
    {
        return true;
    }

    public function getIdentity()
    {
        return 'a';
    }

    public function setPropertyE($value)
    {
        $this->propertyE = 'E:'.$value;
        return $this;
    }

    public function getPropertyE()
    {
        return 'Get ' . $this->propertyE;
    }

    public function setPropertyF($value)
    {
        $this->lazyPropertyF = 'UNDEFINED:' . $value;
        return $this;
    }

    public function getPropertyF()
    {
        return $this->lazyPropertyF;
    }

    public function setPropertyH(\stdClass $object)
    {
        $this->propertyH = $object;
        return $this;
    }
}