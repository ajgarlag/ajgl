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
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */

/**
 * Abstract entity class
 * @category   Ajgl
 * @package    Ajgl_Criteria
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class Ajgl_Criteria_Criterion_Logical
    extends Ajgl_Criteria_Criterion_CriterionAbstract
{
    /**
     * @var string
     */
    protected $_symbol;

    /**
     * @var array
     */
    protected $_validSymbols = array(self::BOOL_AND, self::BOOL_OR);

    /**
     * @var array
     */
    protected $_criterions = array();

    /**
     * @param array $criterions
     * @param string $symbol 
     */
    public function __construct(array $criterions, $symbol) {
        
        if (!in_array($symbol, $this->_validSymbols)) {
            throw new Exception("Invalid symbol");
        }
        
        foreach ($criterions as $key => $s) {
            if (!($s instanceof Ajgl_Criteria_Criterion_CriterionAbstract)) {
                throw new Exception("Only 'Ajgl_Criteria_Criterion_CriterionAbstract' allowed");
            }
        }
        
        $this->_criterions = $criterions;
        $this->_symbol = $symbol;
    }
    
    /**
     * @return string
     */
    public function getSymbol()
    {
        return $this->_symbol;
    }

    /**
     * @return array
     */
    public function getCriterions()
    {
        return $this->_criterions;
    }
}