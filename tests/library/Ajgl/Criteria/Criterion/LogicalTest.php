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

use Ajgl\Criteria\Exception;

/**
 * @category   Ajgl
 * @package    Ajgl\Criteria
 * @subpackage Criterion\Tests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class LogicalTest
    extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CriterionAbstract
     */
    protected $mockCriterion1;

    /**
     * @var CriterionAbstract
     */
    protected $mockCriterion2;

    /**
     * @var Logical
     */
    protected $criterion;

    protected function setUp()
    {
        $this->mockCriterion1 = $this->getMock(__NAMESPACE__ . '\CriterionAbstract');
        $this->mockCriterion2 = $this->getMock(__NAMESPACE__ . '\CriterionAbstract');
        $this->criterion = new Logical(
            array($this->mockCriterion1, $this->mockCriterion2),
            CriterionAbstract::BOOL_AND
        );
    }

    public function testConstructor()
    {
        $criterions = $this->criterion->getCriterions();
        reset($criterions);
        $this->assertEquals(2, count($criterions));
        $this->assertSame($this->mockCriterion1, current($criterions));
        $this->assertSame($this->mockCriterion2, next($criterions));
        $this->assertEquals(
            CriterionAbstract::BOOL_AND,
            $this->criterion->getSymbol()
        );

    }

    /**
     * @expectedException Ajgl\Criteria\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid symbol
     */
    public function testConstructorFailsOnInvalidSymbol()
    {
        $criterion = new Logical(
            array($this->mockCriterion1, $this->mockCriterion2),
            'foo'
        );
    }

    /**
     * @expectedException Ajgl\Criteria\Exception\InvalidArgumentException
     * @expectedExceptionMessage Only 'Ajgl\Criteria\Criterion\CriterionAbstract' allowed
     */
    public function testConstructorFailsOnInvalidCriterion()
    {
        $criterion = new Logical(
            array($this->mockCriterion1, $this->mockCriterion2, 'foobar'),
            CriterionAbstract::BOOL_OR
        );
    }
}

