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
class Ajgl_Criteria_Translator_ZendLdapFilterTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ajgl_Criteria_Translator_ZendLdapFilter
     */
    protected $_translator;
    
    public function setUp() {
        parent::setUp();
        $this->_translator = new Ajgl_Criteria_Translator_ZendLdapFilter();
    }
    
    public function testTranslateAnyCriterion()
    {
        $criterion = new Ajgl_Criteria_Criterion_Any('foo');
        $filter = $this->_translator->translateCriterion($criterion);
        $this->assertEquals('(foo=*)', $filter->__toString());
    }
    
    public function testTranslateBeginsWithCriterion()
    {
        $criterion = new Ajgl_Criteria_Criterion_BeginsWith('foo', 'bar');
        $filter = $this->_translator->translateCriterion($criterion);
        $this->assertEquals('(foo=bar*)', $filter->__toString());
    }
    
    public function testTranslateEndsWithCriterion()
    {
        $criterion = new Ajgl_Criteria_Criterion_EndsWith('foo', 'bar');
        $filter = $this->_translator->translateCriterion($criterion);
        $this->assertEquals('(foo=*bar)', $filter->__toString());
    }
    
    public function testTranslateEqualsCriterion()
    {
        $criterion = new Ajgl_Criteria_Criterion_Equals('foo', 'bar');
        $filter = $this->_translator->translateCriterion($criterion);
        $this->assertEquals('(foo=bar)', $filter->__toString());
    }
    
    public function testTranslateGreaterOrEqualsCriterion()
    {
        $criterion = new Ajgl_Criteria_Criterion_GreaterOrEquals('foo', 'bar');
        $filter = $this->_translator->translateCriterion($criterion);
        $this->assertEquals('(foo>=bar)', $filter->__toString());
    }
    
    public function testTranslateGreaterCriterion()
    {
        $criterion = new Ajgl_Criteria_Criterion_Greater('foo', 'bar');
        $filter = $this->_translator->translateCriterion($criterion);
        $this->assertEquals('(foo>bar)', $filter->__toString());
    }
    
    public function testTranslateInCriterion()
    {
        $criterion = new Ajgl_Criteria_Criterion_In('foo', array('bar','lala'));
        $filter = $this->_translator->translateCriterion($criterion);
        $this->assertEquals('(|(foo=bar)(foo=lala))', $filter->__toString());
    }
    
    public function testTranslateLesserOrEqualsCriterion()
    {
        $criterion = new Ajgl_Criteria_Criterion_LesserOrEquals('foo', 'bar');
        $filter = $this->_translator->translateCriterion($criterion);
        $this->assertEquals('(foo<=bar)', $filter->__toString());
    }
    
    public function testTranslateLesserCriterion()
    {
        $criterion = new Ajgl_Criteria_Criterion_Lesser('foo', 'bar');
        $filter = $this->_translator->translateCriterion($criterion);
        $this->assertEquals('(foo<bar)', $filter->__toString());
    }
    
    public function testTranslateLogicalCriterion()
    {
        $criterion = new Ajgl_Criteria_Criterion_Logical(
            array(
                new Ajgl_Criteria_Criterion_Equals('foo', 'bar'),
                new Ajgl_Criteria_Criterion_Equals('bar', 'foo')
            ),
            Ajgl_Criteria_Criterion_Logical::BOOL_AND
        );
        $filter = $this->_translator->translateCriterion($criterion);
        $this->assertEquals('(&(foo=bar)(bar=foo))', $filter->__toString());
        
        $criterion = new Ajgl_Criteria_Criterion_Logical(
            array(
                new Ajgl_Criteria_Criterion_Equals('foo', 'bar'),
                new Ajgl_Criteria_Criterion_Equals('bar', 'foo')
            ),
            Ajgl_Criteria_Criterion_Logical::BOOL_OR
        );
        $filter = $this->_translator->translateCriterion($criterion);
        $this->assertEquals('(|(foo=bar)(bar=foo))', $filter->__toString());
    }
    
    public function testTranslateNotCriterion()
    {
        $criterion = new Ajgl_Criteria_Criterion_Not(
            new Ajgl_Criteria_Criterion_Equals('foo', 'bar')
        );
        $filter = $this->_translator->translateCriterion($criterion);
        $this->assertEquals('(!(foo=bar))', $filter->__toString());
    }
    
    public function testTranslateWildcardCriterion()
    {
        $criterion = new Ajgl_Criteria_Criterion_Wildcard('foo', '*bar*');
        $filter = $this->_translator->translateCriterion($criterion);
        $this->assertEquals('(foo=*bar*)', $filter->__toString());
        
        $criterion = new Ajgl_Criteria_Criterion_Wildcard('foo', 'bar*');
        $filter = $this->_translator->translateCriterion($criterion);
        $this->assertEquals('(foo=bar*)', $filter->__toString());
        
        $criterion = new Ajgl_Criteria_Criterion_Wildcard('foo', '*bar');
        $filter = $this->_translator->translateCriterion($criterion);
        $this->assertEquals('(foo=*bar)', $filter->__toString());
    }
    
    /**
     * @expectedException Exception
     * @expectedExceptionMessage Cannot translate 'b*ar' expression
     */
    public function testTranslateWildcarCriterionFailsOnBadValue()
    {
        $criterion = new Ajgl_Criteria_Criterion_Wildcard('foo', 'b*ar');
        $this->_translator->translateCriterion($criterion);
    }
}
