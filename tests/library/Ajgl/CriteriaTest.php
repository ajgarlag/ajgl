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
class Ajgl_CriteriaTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ajgl_Criteria_Criterion_CriterionAbstract
     */
    protected $_criterion;
    
    /**
     * @var Ajgl_Criteria
     */
    protected $_criteria;
    
    public function setUp()
    {
        $this->_criterion = $this->getMock('Ajgl_Criteria_Criterion_CriterionAbstract');
        $this->_criteria = new Ajgl_Criteria($this->_criterion);
    }
    
    public function testGetCriterion()
    {
        $this->assertSame($this->_criterion, $this->_criteria->getCriterion());
    }
    
    public function testSetCriterion()
    {
        $mockCriterion = $this->getMock('Ajgl_Criteria_Criterion_CriterionAbstract');
        $this->assertNotSame($mockCriterion, $this->_criteria->getCriterion());
        $this->assertSame($this->_criteria, $this->_criteria->setCriterion($mockCriterion));
        $this->assertSame($mockCriterion, $this->_criteria->getCriterion());
    }
    
    public function testAddAndCriterion()
    {
        $resCriterion = $this->getMock('Ajgl_Criteria_Criterion_Logical', array(), array(), '', false);
        $this->_criterion->expects($this->once())->method('addAnd')
            ->will($this->returnValue($resCriterion));
        $this->assertSame(
            $this->_criteria,
            $this->_criteria->addAndCriterion($this->getMock('Ajgl_Criteria_Criterion_CriterionAbstract'))
        );
        $this->assertSame($resCriterion, $this->_criteria->getCriterion());
    }
    
    public function testAddOrCriterion()
    {
        $resCriterion = $this->getMock('Ajgl_Criteria_Criterion_Logical', array(), array(), '', false);
        $this->_criterion->expects($this->once())->method('addOr')
            ->will($this->returnValue($resCriterion));
        $this->assertSame(
            $this->_criteria,
            $this->_criteria->addOrCriterion($this->getMock('Ajgl_Criteria_Criterion_CriterionAbstract'))
        );
        $this->assertSame($resCriterion, $this->_criteria->getCriterion());
    }
    
    public function testNegate()
    {
        $resCriterion = $this->getMock('Ajgl_Criteria_Criterion_Not', array(), array(), '', false);
        $this->_criterion->expects($this->once())->method('negate')
            ->will($this->returnValue($resCriterion));
        $this->assertSame(
            $this->_criteria,
            $this->_criteria->negate()
        );
        $this->assertSame($resCriterion, $this->_criteria->getCriterion());
    }
}
