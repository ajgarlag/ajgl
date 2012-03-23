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
 * @subpackage Criterio
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
namespace Ajgl\Criteria\Criterion;

use Ajgl\Criteria\Exception;

/**
 * Abstract entity class
 * @category   Ajgl
 * @package    Ajgl\Criteria
 * @subpackage Criterion
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class Logical
    extends CriterionAbstract
{
    /**
     * @var string
     */
    protected $symbol;

    /**
     * @var array
     */
    protected $validSymbols = array(self::BOOL_AND, self::BOOL_OR);

    /**
     * @var array
     */
    protected $criterions = array();

    /**
     * @param array $criterions
     * @param string $symbol
     */
    public function __construct(array $criterions, $symbol)
    {

        if (!in_array($symbol, $this->validSymbols)) {
            throw new Exception\InvalidArgumentException("Invalid symbol");
        }

        foreach ($criterions as $key => $s) {
            if (!($s instanceof CriterionAbstract)) {
                throw new Exception\InvalidArgumentException("Only '".__NAMESPACE__."\CriterionAbstract' allowed");
            }
        }

        $this->criterions = $criterions;
        $this->symbol = $symbol;
    }

    /**
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @return array
     */
    public function getCriterions()
    {
        return $this->criterions;
    }
}