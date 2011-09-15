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
class Ajgl_Criteria_Criterion_CriterionAbstractTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ajgl_Criteria_Criterion_CriterionAbstract
     */
    protected $_criterion;
    
    public function setUp()
    {
        $this->_criterion = new Ajgl_Criteria_Criterion_CriterionAbstractTest_Criterion();
    }
    
    public function testAddAnd()
    {
        $criterion = $this->getMock('Ajgl_Criteria_Criterion_CriterionAbstract');
        $resCriterion = $this->_criterion->addAnd($criterion);
        $this->assertTrue($resCriterion instanceof Ajgl_Criteria_Criterion_Logical);
        $this->assertEquals(Ajgl_Criteria_Criterion_CriterionAbstract::BOOL_AND, $resCriterion->getSymbol());
        $this->assertEquals(2, count($resCriterion->getCriterions()));
        $this->assertSame($this->_criterion, current($resCriterion->getCriterions()));
    }
    
    public function testAddOr()
    {
        $criterion = $this->getMock('Ajgl_Criteria_Criterion_CriterionAbstract');
        $resCriterion = $this->_criterion->addOr($criterion);
        $this->assertTrue($resCriterion instanceof Ajgl_Criteria_Criterion_Logical);
        $this->assertEquals(Ajgl_Criteria_Criterion_CriterionAbstract::BOOL_OR, $resCriterion->getSymbol());
        $this->assertEquals(2, count($resCriterion->getCriterions()));
        $this->assertSame($this->_criterion, current($resCriterion->getCriterions()));
    }
    
    public function testNegate()
    {
        $resCriterion = $this->_criterion->negate();
        $this->assertTrue($resCriterion instanceof Ajgl_Criteria_Criterion_Not);
        $this->assertSame($this->_criterion, $resCriterion->getInnerCriterion());
    }
}

class Ajgl_Criteria_Criterion_CriterionAbstractTest_Criterion
    extends Ajgl_Criteria_Criterion_CriterionAbstract
{
    
}
