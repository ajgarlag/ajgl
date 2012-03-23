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
 * @package    Ajgl\Criteria
 * @subpackage Tests
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
namespace Ajgl\Criteria;

use Ajgl\Criteria\Criterion;

/**
 * @category   Ajgl
 * @package    Ajgl\Criteria
 * @subpackage Tests
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class CriteriaTest
    extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Criterion\CriterionAbstract
     */
    protected $criterion;

    /**
     * @var Ajgl_Criteria
     */
    protected $criteria;

    protected function setUp()
    {
        $this->criterion = $this->getMock('Ajgl\Criteria\Criterion\CriterionAbstract');
        $this->criteria = new Criteria($this->criterion);
    }

    public function testGetCriterion()
    {
        $this->assertSame($this->criterion, $this->criteria->getCriterion());
    }

    public function testSetCriterion()
    {
        $mockCriterion = $this->getMock('Ajgl\Criteria\Criterion\CriterionAbstract');
        $this->assertNotSame($mockCriterion, $this->criteria->getCriterion());
        $this->assertSame($this->criteria, $this->criteria->setCriterion($mockCriterion));
        $this->assertSame($mockCriterion, $this->criteria->getCriterion());
    }

    public function testAddAndCriterion()
    {
        $resCriterion = $this->getMock('Ajgl\Criteria\Criterion\Logical', array(), array(), '', false);
        $this->criterion->expects($this->once())->method('addAnd')
            ->will($this->returnValue($resCriterion));
        $this->assertSame(
            $this->criteria,
            $this->criteria->addAndCriterion($this->getMock('Ajgl\Criteria\Criterion\CriterionAbstract'))
        );
        $this->assertSame($resCriterion, $this->criteria->getCriterion());
    }

    public function testAddOrCriterion()
    {
        $resCriterion = $this->getMock('Ajgl\Criteria\Criterion\Logical', array(), array(), '', false);
        $this->criterion->expects($this->once())->method('addOr')
            ->will($this->returnValue($resCriterion));
        $this->assertSame(
            $this->criteria,
            $this->criteria->addOrCriterion($this->getMock('Ajgl\Criteria\Criterion\CriterionAbstract'))
        );
        $this->assertSame($resCriterion, $this->criteria->getCriterion());
    }

    public function testNegate()
    {
        $resCriterion = $this->getMock('Ajgl\Criteria\Criterion\Not', array(), array(), '', false);
        $this->criterion->expects($this->once())->method('negate')
            ->will($this->returnValue($resCriterion));
        $this->assertSame(
            $this->criteria,
            $this->criteria->negate()
        );
        $this->assertSame($resCriterion, $this->criteria->getCriterion());
    }
}
