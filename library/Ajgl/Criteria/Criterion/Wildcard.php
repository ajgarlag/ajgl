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
 * @subpackage Criterion
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
namespace Ajgl\Criteria\Criterion;

use Ajgl\Criteria\Exception;

/**
 * @category   Ajgl
 * @package    Ajgl\Criteria
 * @subpackage Criterion
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class Wildcard
    extends FieldAbstract
{
    /**
     * @param string $field
     * @param string $value
     */
    public function __construct($field, $value)
    {
        if (strpos($value, '*') === false) {
            throw new Exception\InvalidArgumentException("Value must have at least one wildcard");
        }
        parent::__construct($field, $value, self::OPERATOR_WILDCARD);
    }
}
