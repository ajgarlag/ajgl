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
 * @subpackage Translator
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
namespace Ajgl\Criteria\Translator;

use Ajgl\Criteria\Criteria,
    Ajgl\Criteria\Criterion,
    Ajgl\Criteria\Exception;

/**
 * @category   Ajgl
 * @package    Ajgl\Criteria
 * @subpackage Translator
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class ZendLdapFilter
{
    /**
     * @param Criteria $criteria
     * @return \Zend_Ldap_Filter
     */
    public function translate(Criteria $criteria)
    {
        return $this->translateCriterion($criteria->getCriterion());
    }

    /**
     * @param Criterion_CriterionAbstract $criterion
     * @return \Zend_Ldap_Filter
     */
    public function translateCriterion(Criterion\CriterionAbstract $criterion)
    {
        if ($criterion instanceof Criterion\Logical) {
            return $this->_translateLogicalCriterion($criterion);
        } elseif ($criterion instanceof Criterion\Not) {
            return $this->_translateNotCriterion($criterion);
        } elseif ($criterion instanceof Criterion\FieldAbstract) {
            return $this->_translateFieldCriterion($criterion);
        } else {
            $class = get_class($criterion);
            throw new Exception\InvalidArgumentException("Cannot translate '$class' criterion class");
        }
    }

    /**
     * @param Criterion\Logical $criterion
     * @return \Zend_Ldap_Filter_Logical
     */
    protected function _translateLogicalCriterion(Criterion\Logical $criterion)
    {
        $aFilters = array();
        foreach ($criterion->getCriterions() as $subCriterion) {
            $aFilters[] = $this->translateCriterion($subCriterion);
        }

        switch($criterion->getSymbol()) {
            case Criterion\CriterionAbstract::BOOL_AND:
                return new \Zend_Ldap_Filter_And($aFilters);
                break;
            case Criterion\CriterionAbstract::BOOL_OR:
                return new \Zend_Ldap_Filter_Or($aFilters);
                break;
        }
    }

    /**
     * @param Criterion\FieldAbstract $criterion
     * @return \Zend_Ldap_Filter
     */
    protected function _translateFieldCriterion(Criterion\FieldAbstract $criterion)
    {
        switch ($criterion->getOperator()) {
            case Criterion\FieldAbstract::OPERATOR_WILDCARD:
                return $this->_translateWildcardCriterion($criterion);
                break;
            case Criterion\FieldAbstract::OPERATOR_IN:
                return $this->_translateInCriterion($criterion);
                break;
            default:
                return new \Zend_Ldap_Filter($criterion->getField(), $criterion->getValue(), $criterion->getOperator());
                break;
        }
    }

    /**
     * @param Criterion\Not $criterion
     * @return \Zend_Ldap_Filter_Not
     */
    protected function _translateNotCriterion(Criterion\Not $criterion)
    {
        return new \Zend_Ldap_Filter_Not(
            $this->translateCriterion(
                $criterion->getInnerCriterion()
            )
        );
    }

    /**
     * @param Criterion\In $criterion
     * @return \Zend_Ldap_Filter_Logical
     */
    protected function _translateInCriterion(Criterion\In $criterion)
    {
        $criterions = array();
        foreach ($criterion->getValue() as $value) {
            $criterions[] = new Criterion\Equals(
                $criterion->getField(),
                $value
            );
        }
        $criterion = new Criterion\Logical(
            $criterions,
            Criterion\CriterionAbstract::BOOL_OR
        );
        return $this->_translateLogicalCriterion($criterion);
    }

    /**
     * @param Criterion\Wildcard $criterion
     * @return \Zend_Ldap_Filter
     */
    protected function _translateWildcardCriterion(Criterion\Wildcard $criterion)
    {
        if (strlen($criterion->getValue()) > 1
            && substr($criterion->getValue(), 0, 1) == '*'
            && substr($criterion->getValue(), -1) == '*'
            ) {
            return \Zend_Ldap_Filter::contains($criterion->getField(), substr($criterion->getValue(), 1, -1));
        } elseif ($criterion instanceof Criterion\BeginsWith || substr($criterion->getValue(), -1) == '*') {
            return \Zend_Ldap_Filter::begins($criterion->getField(), substr($criterion->getValue(), 0, -1));
        } elseif ($criterion instanceof Criterion\EndsWith || substr($criterion->getValue(), 0, 1) == '*') {
            return \Zend_Ldap_Filter::ends($criterion->getField(), substr($criterion->getValue(), 1));
        } elseif ($criterion instanceof Criterion\Any) {
            return \Zend_Ldap_Filter::any($criterion->getField());
        } else {
            throw new Exception\InvalidArgumentException("Cannot translate '{$criterion->getValue()}' expression");
        }
    }
}
