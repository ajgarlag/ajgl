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
 * @subpackage Criterion
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */

/**
 * @category   Ajgl
 * @package    Ajgl_Criteria
 * @subpackage Criterion
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
abstract class Ajgl_Criteria_Criterion_FieldAbstract
    extends Ajgl_Criteria_Criterion_CriterionAbstract
{
    const OPERATOR_EQUALS = '=';
    
    const OPERATOR_GREATER = '>';
    
    const OPERATOR_GREATEROREQUALS = '>=';
    
    const OPERATOR_LESSER = '<';
    
    const OPERATOR_LESSEROREQUALS = '<=';
    
    const OPERATOR_WILDCARD = '*';
    
    const OPERATOR_IN = '∈';
    
    /**
     * @var string
     */
    protected $_operator;
    
    /**
     * @var string
     */
    protected $_field;

    /**
     * @var string
     */
    protected $_value; 
    
    /**
     * @param string $field
     * @param string $value
     * @param string $operator 
     */
    public function __construct($field, $value, $operator)
    {
        $this->_operator = $operator;
        $this->_field = $field;
        $this->_value = $value;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->_operator;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->_field;
    }
    
    /**
     * @return string
     */
    public function getValue()
    {
        return $this->_value;
    }
}