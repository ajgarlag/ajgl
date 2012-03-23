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
 * @package    Ajgl\Form
 * @subpackage Element\Tests
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
namespace Ajgl\Form\Element;

/**
 * Test class for SelectDate.
 * @category   Ajgl
 * @package    Ajgl\Form
 * @subpackage Element\Tests
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class SelectDateTest
    extends \PHPUnit_Framework_TestCase
{

    /**
     * @var SelectDate
     */
    protected $object;

    protected function setUp() {
        $this->object = new SelectDate('selectDate');
    }

    public function testInit() {
        $this->assertNull($this->object->init());
        $validators = $this->object->getValidators();
        $this->assertTrue($validators['Zend_Validate_Date'] instanceof \Zend_Validate_Date);
    }

    public function testGetDay() {
        $this->assertNull($this->object->getDay());
    }

    public function testSetDay() {
        $this->assertSame($this->object, $this->object->setDay(10));
        $this->assertEquals(10, $this->object->getDay());
    }

    public function testGetMonth() {
        $this->assertNull($this->object->getMonth());
    }

    public function testSetMonth() {
        $this->assertSame($this->object, $this->object->setMonth(10));
        $this->assertEquals(10, $this->object->getMonth());
    }

    public function testGetYear() {
        $this->assertNull($this->object->getYear());
    }

    public function testSetYear() {
        $this->assertSame($this->object, $this->object->setYear(2010));
        $this->assertEquals(2010, $this->object->getYear());
    }

    public function testGetFormat() {
        $this->assertEquals(\Zend_Date::DATE_SHORT, $this->object->getFormat());
    }

    public function testSetFormat() {
        $this->assertSame($this->object, $this->object->setFormat(\Zend_Date::DATE_FULL));
        $this->assertEquals(\Zend_Date::DATE_FULL, $this->object->getFormat());
    }

    public function testGetLocale() {
        $this->assertNull($this->object->getLocale());
    }

    public function testSetLocale() {
        $locale = $this->getMock('\Zend_Locale');
        $this->assertSame($this->object, $this->object->setLocale($locale));
        $this->assertEquals($locale, $this->object->getLocale());
    }

    public function testGetValue() {
        $this->assertNull($this->object->getValue());
    }

    public function testSetValueWithArray() {
        $expected = array('day' => 1, 'month' => 2, 'year' => 2010);
        $this->assertSame($this->object, $this->object->setValue($expected));
        $this->assertEquals($expected, $this->object->getValue());
    }

    public function testSetValueWithString() {
        $expected = array('day' => 1, 'month' => 2, 'year' => 2010);
        $this->assertSame($this->object, $this->object->setValue('02-01-2010'));
        $this->assertEquals($expected, $this->object->getValue());
    }

    public function testSetValueWithStringAndLocale() {
        $expected = array('day' => 1, 'month' => 2, 'year' => 2010);
        $this->assertSame($this->object, $this->object->setLocale('es'));
        $this->assertSame($this->object, $this->object->setValue('01-02-2010'));
        $this->assertEquals($expected, $this->object->getValue());
    }

    public function testSetValueWithZendDate() {
        $expected = array('day' => date('j'), 'month' => date('n'), 'year' => date('Y'));
        $this->assertSame($this->object, $this->object->setValue(new \Zend_Date));
        $this->assertEquals($expected, $this->object->getValue());
    }

    public function testSetValueWithDateTime() {
        $expected = array('day' => date('j'), 'month' => date('n'), 'year' => date('Y'));
        $this->assertSame($this->object, $this->object->setValue(new \DateTime));
        $this->assertEquals($expected, $this->object->getValue());
    }

    public function testSetValueWithNull() {
        $expected = array('day' => date('j'), 'month' => date('n'), 'year' => date('Y'));
        $this->assertSame($this->object, $this->object->setValue(new \DateTime));
        $this->assertEquals($expected, $this->object->getValue());
        $this->assertSame($this->object, $this->object->setValue(null));
        $this->assertNull($this->object->getValue());
    }

    /**
     * @expectedException Ajgl\Form\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid date value provided
     */
    public function testSetValueFailsWithInvalidArgument() {
        $this->object->setValue(1);
    }

    public function testGetValueAsZendDate() {
        $date = new \DateTime();
        $date->format(\DateTime::ATOM);
        $zendDate = new \Zend_Date($date->format(\DateTime::ATOM));
        $this->assertSame($this->object, $this->object->setValue($date));
        $this->assertEquals($zendDate->toString(\Zend_Date::DATE_FULL), $this->object->getValueAsZendDate()->toString(\Zend_Date::DATE_FULL));
        $this->assertSame($this->object, $this->object->setValue(null));
        $this->assertNull($this->object->getValueAsZendDate());
    }

    public function testRender() {
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}