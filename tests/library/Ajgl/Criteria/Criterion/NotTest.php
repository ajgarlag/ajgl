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
class NotTest
    extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CriterionAbstract
     */
    protected $innerCriterion;

    /**
     * @var Not
     */
    protected $criterion;

    protected function setUp()
    {
        $this->innerCriterion = $this->getMock(__NAMESPACE__ . '\CriterionAbstract');
        $this->criterion = new Not($this->innerCriterion);
    }

    public function testConstructor()
    {
        $this->assertSame($this->innerCriterion, $this->criterion->getInnerCriterion());
    }
}

