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
 * @subpackage Translator
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */

/**
 * @category   Ajgl
 * @package    Ajgl_Criteria
 * @subpackage Translator
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class Ajgl_Criteria_Translator_ZendLdapFilter
{
    /**
     * @param Ajgl_Criteria $criteria
     * @return Zend_Ldap_Filter 
     */
    public function translate(Ajgl_Criteria $criteria)
    {
        return $this->translateCriterion($criteria->getCriterion());
    }
    
    /**
     * @param Ajgl_Criteria_Criterion_CriterionAbstract $criterion
     * @return Zend_Ldap_Filter 
     */
    public function translateCriterion(Ajgl_Criteria_Criterion_CriterionAbstract $criterion)
    {
        if ($criterion instanceof Ajgl_Criteria_Criterion_Logical) {
            return $this->_translateLogicalCriterion($criterion);
        } elseif ($criterion instanceof Ajgl_Criteria_Criterion_Not) {
            return $this->_translateNotCriterion($criterion);
        } elseif ($criterion instanceof Ajgl_Criteria_Criterion_FieldAbstract) {
            return $this->_translateFieldCriterion($criterion);
        } else {
            $class = get_class($criterion);
            throw new Exception("Cannot translate '$class' criterion class");
        }
    }
    
    /**
     * @param Ajgl_Criteria_Criterion_Logical $criterion
     * @return Zend_Ldap_Filter_Logical 
     */
    protected function _translateLogicalCriterion(Ajgl_Criteria_Criterion_Logical $criterion)
    {
        $aFilters = array();
        foreach ($criterion->getCriterions() as $subCriterion) {
            $aFilters[] = $this->translateCriterion($subCriterion);
        }
        
        switch($criterion->getSymbol()) {
            case Ajgl_Criteria_Criterion_CriterionAbstract::BOOL_AND:
                return new Zend_Ldap_Filter_And($aFilters);
                break;
            case Ajgl_Criteria_Criterion_CriterionAbstract::BOOL_OR:
                return new Zend_Ldap_Filter_Or($aFilters);
                break;
        }
    }
    
    /**
     * @param Ajgl_Criteria_Criterion_FieldAbstract $criterion
     * @return Zend_Ldap_Filter 
     */
    protected function _translateFieldCriterion(Ajgl_Criteria_Criterion_FieldAbstract $criterion)
    {
        switch ($criterion->getOperator()) {
            case Ajgl_Criteria_Criterion_FieldAbstract::OPERATOR_WILDCARD:
                return $this->_translateWildcardCriterion($criterion);
                break;
            case Ajgl_Criteria_Criterion_FieldAbstract::OPERATOR_IN:
                return $this->_translateInCriterion($criterion);
                break;
            default:
                return new Zend_Ldap_Filter($criterion->getField(), $criterion->getValue(), $criterion->getOperator());
                break;
        }
    }
    
    /**
     * @param Ajgl_Criteria_Criterion_Not $criterion
     * @return Zend_Ldap_Filter_Not 
     */
    protected function _translateNotCriterion(Ajgl_Criteria_Criterion_Not $criterion)
    {
        return new Zend_Ldap_Filter_Not(
            $this->translateCriterion(
                $criterion->getInnerCriterion()
            )
        );
    }
    
    /**
     * @param Ajgl_Criteria_Criterion_In $criterion
     * @return Zend_Ldap_Filter_Logical 
     */
    protected function _translateInCriterion(Ajgl_Criteria_Criterion_In $criterion)
    {
        $criterions = array();
        foreach ($criterion->getValue() as $value) {
            $criterions[] = new Ajgl_Criteria_Criterion_Equals(
                $criterion->getField(),
                $value
            );
        }
        $criterion = new Ajgl_Criteria_Criterion_Logical(
            $criterions,
            Ajgl_Criteria_Criterion_CriterionAbstract::BOOL_OR
        );
        return $this->_translateLogicalCriterion($criterion);
    }
    
    /**
     * @param Ajgl_Criteria_Criterion_Wildcard $criterion
     * @return Zend_Ldap_Filter 
     */
    protected function _translateWildcardCriterion(Ajgl_Criteria_Criterion_Wildcard $criterion)
    {
        if (strlen($criterion->getValue()) > 1 
            && substr($criterion->getValue(), 0, 1) == '*'
            && substr($criterion->getValue(), -1) == '*'
            ) {
            return Zend_Ldap_Filter::contains($criterion->getField(), substr($criterion->getValue(), 1, -1));
        } elseif ($criterion instanceof Ajgl_Criteria_Criterion_BeginsWith || substr($criterion->getValue(), -1) == '*') {
            return Zend_Ldap_Filter::begins($criterion->getField(), substr($criterion->getValue(), 0, -1));
        } elseif ($criterion instanceof Ajgl_Criteria_Criterion_EndsWith || substr($criterion->getValue(), 0, 1) == '*') {
            return Zend_Ldap_Filter::ends($criterion->getField(), substr($criterion->getValue(), 1));
        } elseif ($criterion instanceof Ajgl_Criteria_Criterion_Any) {
            return Zend_Ldap_Filter::any($criterion->getField());
        } else {
            throw new Exception("Cannot translate '{$criterion->getValue()}' expression");
        }
    }
}
