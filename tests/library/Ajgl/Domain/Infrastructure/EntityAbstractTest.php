<?php
/**
 * AJ Global Libraries
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
 * Plugin to render the messages registered with the flashMessenger action helper
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
        $this->assertEquals('Set foo', $this->_entity->setPropertyE('foo'));
        $this->assertEquals('Get foo', $this->_entity->getPropertyE());
    }

    public function testMagicCallCallingGetterForUndefinedProperty()
    {
        $this->assertEquals('Get undefined', $this->_entity->getPropertyF());
    }

    public function testMagicCallCallingSetterForUndefinedProperty()
    {
        $this->assertEquals('Set undefined bar', $this->_entity->setPropertyF('bar'));
        $this->assertEquals('Get undefined', $this->_entity->getPropertyF());
    }
    
    public function testToArray()
    {
        $data = array('propertyB' => 2, 'propertyG' => null);
        $this->assertEquals($data, $this->_entity->toArray());
        $this->assertEquals(array_keys($data), array_keys($this->_entity->toArray()));
    }
    
    public function testFromArray()
    {
        $data = array('propertyB' => null, 'propertyG' => 43);
        $this->_entity->fromArray($data);
        $this->assertEquals($data, $this->_entity->toArray());
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

    public function setPropertyE($value)
    {
        $this->propertyE = $value;
        return 'Set ' . $this->propertyE;
    }

    public function getPropertyE()
    {
        return 'Get ' . $this->propertyE;
    }

    public function setPropertyF($value)
    {
        return 'Set undefined ' . $value;
    }

    public function getPropertyF()
    {
        return 'Get undefined';
    }
}