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
 * @package    Ajgl_Criteria
 * @subpackage UnitTests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */

/**
 * @category   Ajgl
 * @package    Ajgl_Criteria
 * @subpackage UnitTests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class Ajgl_Criteria_Criterion_LogicalTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ajgl_Criteria_Criterion_CriterionAbstract
     */
    protected $_mockCriterion1;
    
    /**
     * @var Ajgl_Criteria_Criterion_CriterionAbstract
     */
    protected $_mockCriterion2;
    
    /**
     * @var Ajgl_Criteria_Criterion_Logical
     */
    protected $_criterion;
    
    public function setUp()
    {
        $this->_mockCriterion1 = $this->getMock('Ajgl_Criteria_Criterion_CriterionAbstract');
        $this->_mockCriterion2 = $this->getMock('Ajgl_Criteria_Criterion_CriterionAbstract');
        $this->_criterion = new Ajgl_Criteria_Criterion_Logical(
            array($this->_mockCriterion1, $this->_mockCriterion2),
            Ajgl_Criteria_Criterion_CriterionAbstract::BOOL_AND
        );
    }
    
    public function testConstructor()
    {
        $this->assertEquals(2, count($this->_criterion->getCriterions()));
        $this->assertSame($this->_mockCriterion1, current($this->_criterion->getCriterions()));
        $this->assertSame($this->_mockCriterion2, next($this->_criterion->getCriterions()));
        $this->assertEquals(
            Ajgl_Criteria_Criterion_CriterionAbstract::BOOL_AND,
            $this->_criterion->getSymbol()
        );
        
    }
    
    /**
     * @expectedException Exception
     * @expectedExceptionMessage Invalid symbol
     */
    public function testConstructorFailsOnInvalidSymbol()
    {
        $criterion = new Ajgl_Criteria_Criterion_Logical(
            array($this->_mockCriterion1, $this->_mockCriterion2),
            'foo'
        );
    }
    
    /**
     * @expectedException Exception
     * @expectedExceptionMessage Only 'Ajgl_Criteria_Criterion_CriterionAbstract' allowed
     */
    public function testConstructorFailsOnInvalidCriterion()
    {
        $criterion = new Ajgl_Criteria_Criterion_Logical(
            array($this->_mockCriterion1, $this->_mockCriterion2, 'foobar'),
            Ajgl_Criteria_Criterion_CriterionAbstract::BOOL_OR
        );
    }
}

