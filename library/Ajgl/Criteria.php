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
 * Criteria class
 * @category   Ajgl
 * @package    Ajgl_Criteria
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class Ajgl_Criteria
{
    /**
     * @var Ajgl_Criteria_Criterion_CriterionAbstract 
     */
    protected $_criterion;
    
    /**
     * @param Ajgl_Criteria_Criterion_CriterionAbstract $criterion 
     */
    public function __construct(Ajgl_Criteria_Criterion_CriterionAbstract $criterion) {
        $this->_criterion = $criterion;
    }

    /**
     * @param Ajgl_Criteria_Criterion_CriterionAbstract $criterion
     * @return Ajgl_Criteria 
     */
    public function setCriterion(Ajgl_Criteria_Criterion_CriterionAbstract $criterion)
    {
        $this->_criterion = $criterion;
        return $this;
    }

    /**
     * @return Ajgl_Criteria_Criterion_CriterionAbstract 
     */
    public function getCriterion()
    {
        return $this->_criterion;
    }
    
    /**
     * @param Ajgl_Criteria_Criterion_CriterionAbstract $criterion
     * @return Ajgl_Criteria 
     */
    public function addAndCriterion(Ajgl_Criteria_Criterion_CriterionAbstract $criterion)
    {
        $this->setCriterion($this->getCriterion()->addAnd($criterion));
        return $this;
    }

    /**
     * @param Ajgl_Criteria_Criterion_CriterionAbstract $criterion
     * @return Ajgl_Criteria 
     */
    public function addOrCriterion(Ajgl_Criteria_Criterion_CriterionAbstract $criterion)
    {
        $this->setCriterion($this->getCriterion()->addOr($criterion));
        return $this;
    }

    /**
     * @return Ajgl_Criteria
     */
    public function negate()
    {
        $this->setCriterion($this->getCriterion()->negate());
        return $this;
    }
}
