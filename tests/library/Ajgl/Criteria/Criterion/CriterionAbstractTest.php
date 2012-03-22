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
 * @package    Ajgl\Criteria
 * @subpackage Criterion\Tests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
namespace Ajgl\Criteria\Criterion;

/**
 * @category   Ajgl
 * @package    Ajgl\Criteria
 * @subpackage Criterion\Tests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class CriterionAbstractTest
    extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CriterionAbstract
     */
    protected $criterion;

    public function setUp()
    {
        $this->criterion = new CriterionAbstractImplementation();
    }

    public function testAddAnd()
    {
        $criterion = $this->getMock(__NAMESPACE__ . '\CriterionAbstract');
        $resCriterion = $this->criterion->addAnd($criterion);
        $this->assertTrue($resCriterion instanceof Logical);
        $this->assertEquals(CriterionAbstract::BOOL_AND, $resCriterion->getSymbol());
        $this->assertEquals(2, count($resCriterion->getCriterions()));
        $this->assertSame($this->criterion, current($resCriterion->getCriterions()));
    }

    public function testAddOr()
    {
        $criterion = $this->getMock(__NAMESPACE__ . '\CriterionAbstract');
        $resCriterion = $this->criterion->addOr($criterion);
        $this->assertTrue($resCriterion instanceof Logical);
        $this->assertEquals(CriterionAbstract::BOOL_OR, $resCriterion->getSymbol());
        $this->assertEquals(2, count($resCriterion->getCriterions()));
        $this->assertSame($this->criterion, current($resCriterion->getCriterions()));
    }

    public function testNegate()
    {
        $resCriterion = $this->criterion->negate();
        $this->assertTrue($resCriterion instanceof Not);
        $this->assertSame($this->criterion, $resCriterion->getInnerCriterion());
    }
}

class CriterionAbstractImplementation
    extends CriterionAbstract
{

}
