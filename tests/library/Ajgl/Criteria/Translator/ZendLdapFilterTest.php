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
 * @subpackage Translator\Tests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
namespace Ajgl\Criteria\Translator;

use Ajgl\Criteria\Criterion;

/**
 * @category   Ajgl
 * @package    Ajgl\Criteria
 * @subpackage Translator\Tests
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class ZendLdapFilterTest
    extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ZendLdapFilter
     */
    protected $translator;

    protected function setUp() {
        parent::setUp();
        $this->translator = new ZendLdapFilter();
    }

    public function testTranslateAnyCriterion()
    {
        $criterion = new Criterion\Any('foo');
        $filter = $this->translator->translateCriterion($criterion);
        $this->assertEquals('(foo=*)', $filter->__toString());
    }

    public function testTranslateBeginsWithCriterion()
    {
        $criterion = new Criterion\BeginsWith('foo', 'bar');
        $filter = $this->translator->translateCriterion($criterion);
        $this->assertEquals('(foo=bar*)', $filter->__toString());
    }

    public function testTranslateEndsWithCriterion()
    {
        $criterion = new Criterion\EndsWith('foo', 'bar');
        $filter = $this->translator->translateCriterion($criterion);
        $this->assertEquals('(foo=*bar)', $filter->__toString());
    }

    public function testTranslateEqualsCriterion()
    {
        $criterion = new Criterion\Equals('foo', 'bar');
        $filter = $this->translator->translateCriterion($criterion);
        $this->assertEquals('(foo=bar)', $filter->__toString());
    }

    public function testTranslateGreaterOrEqualsCriterion()
    {
        $criterion = new Criterion\GreaterOrEquals('foo', 'bar');
        $filter = $this->translator->translateCriterion($criterion);
        $this->assertEquals('(foo>=bar)', $filter->__toString());
    }

    public function testTranslateGreaterCriterion()
    {
        $criterion = new Criterion\Greater('foo', 'bar');
        $filter = $this->translator->translateCriterion($criterion);
        $this->assertEquals('(foo>bar)', $filter->__toString());
    }

    public function testTranslateInCriterion()
    {
        $criterion = new Criterion\In('foo', array('bar','lala'));
        $filter = $this->translator->translateCriterion($criterion);
        $this->assertEquals('(|(foo=bar)(foo=lala))', $filter->__toString());
    }

    public function testTranslateLesserOrEqualsCriterion()
    {
        $criterion = new Criterion\LesserOrEquals('foo', 'bar');
        $filter = $this->translator->translateCriterion($criterion);
        $this->assertEquals('(foo<=bar)', $filter->__toString());
    }

    public function testTranslateLesserCriterion()
    {
        $criterion = new Criterion\Lesser('foo', 'bar');
        $filter = $this->translator->translateCriterion($criterion);
        $this->assertEquals('(foo<bar)', $filter->__toString());
    }

    public function testTranslateLogicalCriterion()
    {
        $criterion = new Criterion\Logical(
            array(
                new Criterion\Equals('foo', 'bar'),
                new Criterion\Equals('bar', 'foo')
            ),
            Criterion\Logical::BOOL_AND
        );
        $filter = $this->translator->translateCriterion($criterion);
        $this->assertEquals('(&(foo=bar)(bar=foo))', $filter->__toString());

        $criterion = new Criterion\Logical(
            array(
                new Criterion\Equals('foo', 'bar'),
                new Criterion\Equals('bar', 'foo')
            ),
            Criterion\Logical::BOOL_OR
        );
        $filter = $this->translator->translateCriterion($criterion);
        $this->assertEquals('(|(foo=bar)(bar=foo))', $filter->__toString());
    }

    public function testTranslateNotCriterion()
    {
        $criterion = new Criterion\Not(
            new Criterion\Equals('foo', 'bar')
        );
        $filter = $this->translator->translateCriterion($criterion);
        $this->assertEquals('(!(foo=bar))', $filter->__toString());
    }

    public function testTranslateWildcardCriterion()
    {
        $criterion = new Criterion\Wildcard('foo', '*bar*');
        $filter = $this->translator->translateCriterion($criterion);
        $this->assertEquals('(foo=*bar*)', $filter->__toString());

        $criterion = new Criterion\Wildcard('foo', 'bar*');
        $filter = $this->translator->translateCriterion($criterion);
        $this->assertEquals('(foo=bar*)', $filter->__toString());

        $criterion = new Criterion\Wildcard('foo', '*bar');
        $filter = $this->translator->translateCriterion($criterion);
        $this->assertEquals('(foo=*bar)', $filter->__toString());
    }

    /**
     * @expectedException Ajgl\Criteria\Exception\InvalidArgumentException
     * @expectedExceptionMessage Cannot translate 'b*ar' expression
     */
    public function testTranslateWildcarCriterionFailsOnBadValue()
    {
        $criterion = new Criterion\Wildcard('foo', 'b*ar');
        $this->translator->translateCriterion($criterion);
    }
}
