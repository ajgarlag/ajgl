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
 * Abstract entity class tests
 * 
 * @category   Ajgl
 * @package    Ajgl_Domain
 * @subpackage UnitTests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class Ajgl_Domain_Infrastructure_EntityAbstractTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ajgl_Domain_Infrastructure_EntityAbstractTest_Concrete
     */
    protected $_entity;
    
    public function setUp()
    {
        $this->_entity = new Ajgl_Domain_Infrastructure_EntityAbstractTest_Concrete;
    }
    
    public function testMagicSetAndGet()
    {
        $this->assertEquals(2, $this->_entity->propertyB);
        $this->_entity->propertyB = 'foo';
        $this->assertEquals('foo', $this->_entity->propertyB);
    }

    /**
     * @expectedException Exception
     */
    public function testMagicGetFailsTryingToAccessToPrivateProperties()
    {
        $this->_entity->propertyA;
    }
    
    /**
     * @expectedException Exception
     */
    public function testMagicGetFailsTryingToAccessToUndefinedProperties()
    {
        $this->_entity->property;
    }

    /**
     * @expectedException Exception
     */
    public function testMagicGetFailsTryingToAccessToPrivatePropertiesStartingWithUnderscore()
    {
        $this->_entity->_propertyC;
    }

    /**
     * @expectedException Exception
     */
    public function testMagicGetFailsTryingToAccessToProtectedPropertiesStartingWithUnderscore()
    {
        $this->_entity->_propertyD;
    }

    /**
     * @expectedException Exception
     */
    public function testMagicSetFailsTryingToAccessToPrivateProperties()
    {
        $this->_entity->propertyA = 'lala';
    }
    
    /**
     * @expectedException Exception
     */
    public function testMagicSetFailsTryingToAccessToUndefindedProperties()
    {
        $this->_entity->property = 'lala';
    }

    /**
     * @expectedException Exception
     */
    public function testMagicSetFailsTryingToAccessToPrivatePropertiesStartingWithUnderscore()
    {
        $this->_entity->_propertyC = 'lala';
    }

    /**
     * @expectedException Exception
     */
    public function testMagicSetFailsTryingToAccessToProtectedPropertiesStartingWithUnderscore()
    {
        $this->_entity->_propertyD = 'lala';
    }

    public function testMagicCallCallingGetter()
    {
        $this->assertEquals('Get ', $this->_entity->getPropertyE());
    }

    public function testMagicCallCallingSetter()
    {
        $this->assertSame($this->_entity, $this->_entity->setPropertyE('foo'));
        $this->assertEquals('Get E:foo', $this->_entity->getPropertyE());
    }
    
    public function testDirectAccessNotByPassGetter()
    {
        $this->assertEquals('Get ', $this->_entity->propertyE);
    }

    public function testDirectAccessNotByPassSetter()
    {
        $this->_entity->propertyE = 'foo';
        $this->assertEquals('Get E:foo', $this->_entity->propertyE);
    }

    public function testMagicCallCallingGetterForUndefinedProperty()
    {
        $this->assertEquals('UNDEFINED', $this->_entity->getPropertyF());
    }

    public function testMagicCallCallingSetterForUndefinedProperty()
    {
        $this->assertSame($this->_entity, $this->_entity->setPropertyF('bar'));
        $this->assertEquals('UNDEFINED:bar', $this->_entity->getPropertyF());
    }
    
    /**
     * @expectedException Exception
     */
    public function testMagicCallCallingGetterWithParam()
    {
        $this->_entity->getPropertyA('foo');
    }
    
    /**
     * @expectedException Exception
     */
    public function testMagicCallCallingSetterWithoutParam()
    {
        $this->_entity->setPropertyA();
    }
    
    /**
     * @expectedException Exception
     */
    public function testMagicCallCallingSetterWithMoreThanOneParam()
    {
        $this->_entity->setPropertyA('foo', 'bar');
    }
    
    /**
     * @expectedException Exception
     */
    public function testMagicCallNotCallingGetterNorSetter()
    {
        $this->_entity->fooBar();
    }
        
    public function testIsSet()
    {
        $this->assertFalse(isset($this->_entity->propertyA));
        $this->assertTrue(isset($this->_entity->propertyB));
    }
    
    public function testUnset()
    {
        $this->assertFalse(isset($this->_entity->propertyA));
        $this->assertTrue(isset($this->_entity->propertyB));
        unset($this->_entity->propertyA);
        unset($this->_entity->propertyB);
        $this->assertFalse(isset($this->_entity->propertyA));
        $this->assertFalse(isset($this->_entity->propertyB));
    }
    
    public function testToArray()
    {
        $data = array('propertyB' => 2, 'propertyG' => null, 'propertyH' => null);
        $this->assertEquals($data, $this->_entity->toArray());
        $this->assertEquals(array_keys($data), array_keys($this->_entity->toArray()));
    }
    
    public function testFromArray()
    {
        $data = array('propertyB' => 2, 'propertyG' => 43);
        $expected = array_merge($data, array('propertyH' => null));
        $this->assertSame($this->_entity, $this->_entity->fromArray($data));
        $this->assertEquals($expected, $this->_entity->toArray());
    }
    
    public function testFromArrayCallsUnsetOnNullValues()
    {
        $data = array('propertyG' => 'foo', 'propertyH' => 'bar');
        try {
            $this->_entity->fromArray($data);
            $this->fail('propertyH must admit only stdClass objects as values');
        } catch (PHPUnit_Framework_Error $e) {}
        $h = new stdClass();
        $data = array('propertyG' => 'foo', 'propertyH' => $h);
        $this->_entity->fromArray($data);
        $this->assertSame($h, $this->_entity->getPropertyH());
        $data = array('propertyH' => null);
        $this->_entity->fromArray($data);
        $this->assertNull($this->_entity->getPropertyH());
    }

}

class Ajgl_Domain_Infrastructure_EntityAbstractTest_Concrete
    extends Ajgl_Domain_Infrastructure_EntityAbstract
{
    protected $propertyG;
    private $propertyA = 1;
    protected $propertyB = 2;
    private $_propertyC;
    protected $_propertyD;
    private $propertyE;
    
    /**
     * @var stdClass
     */
    protected $propertyH;
    
    private $_lazyPropertyF = 'UNDEFINED';
    
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
        $this->_lazyPropertyF = 'UNDEFINED:' . $value;
        return $this;
    }

    public function getPropertyF()
    {
        return $this->_lazyPropertyF;
    }
    
    public function setPropertyH(stdClass $object)
    {
        $this->propertyH = $object;
        return $this;
    }
}