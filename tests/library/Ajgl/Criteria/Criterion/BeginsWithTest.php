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
class Ajgl_Criteria_Criterion_BeginsWithTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ajgl_Criteria_Criterion_BeginsWith
     */
    protected $_criterion;
    
    public function setUp()
    {
        $this->_criterion = new Ajgl_Criteria_Criterion_BeginsWith('foo', 'bar');
    }
    
    public function testConstructor()
    {
        $this->assertEquals('foo', $this->_criterion->getField());
        $this->assertEquals('bar*', $this->_criterion->getValue());
        $this->assertEquals(
            Ajgl_Criteria_Criterion_FieldAbstract::OPERATOR_WILDCARD,
            $this->_criterion->getOperator()
        );
    }
}

