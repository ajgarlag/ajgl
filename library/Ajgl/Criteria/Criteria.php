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
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
namespace Ajgl\Criteria;

use Ajgl\Criteria\Criterion;

/**
 * Criteria class
 * @category   Ajgl
 * @package    Ajgl\Criteria
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class Criteria
{
    /**
     * @var Criterion\CriterionAbstract
     */
    protected $criterion;

    /**
     * @param Criterion\CriterionAbstract $criterion
     */
    public function __construct(Criterion\CriterionAbstract $criterion) {
        $this->criterion = $criterion;
    }

    /**
     * @param Criterion\CriterionAbstract $criterion
     * @return Criteria
     */
    public function setCriterion(Criterion\CriterionAbstract $criterion)
    {
        $this->criterion = $criterion;
        return $this;
    }

    /**
     * @return Criterion\CriterionAbstract
     */
    public function getCriterion()
    {
        return $this->criterion;
    }

    /**
     * @param Criterion\CriterionAbstract $criterion
     * @return Criteria
     */
    public function addAndCriterion(Criterion\CriterionAbstract $criterion)
    {
        $this->setCriterion($this->getCriterion()->addAnd($criterion));
        return $this;
    }

    /**
     * @param Criterion\CriterionAbstract $criterion
     * @return Criteria
     */
    public function addOrCriterion(Criterion\CriterionAbstract $criterion)
    {
        $this->setCriterion($this->getCriterion()->addOr($criterion));
        return $this;
    }

    /**
     * @return Criteria
     */
    public function negate()
    {
        $this->setCriterion($this->getCriterion()->negate());
        return $this;
    }
}
