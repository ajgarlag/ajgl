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
 * @package    Ajgl\View
 * @subpackage Helper\Tests
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
namespace Ajgl\View\Helper;

/**
 * Test class for Ajgl_View_Helper_FormSelectDate.
 * @category   Ajgl
 * @package    Ajgl\View
 * @subpackage Helper\Tests
 * @copyright  Copyright (C) 2010-2012 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class FormSelectDateTest
    extends \PHPUnit_Framework_TestCase
{

    /**
     * @var FormSelectDate
     */
    protected $object;

    protected function setUp() {
        $this->object = new FormSelectDate();
        $this->object->setView(new \Zend_View());
    }

    public function testFormSelectDate() {
        $expected = '<select name="lala[day]" id="lala-day">
    <option value="0" label=""></option>
    <option value="1" label="1">1</option>
    <option value="2" label="2">2</option>
    <option value="3" label="3">3</option>
    <option value="4" label="4">4</option>
    <option value="5" label="5">5</option>
    <option value="6" label="6">6</option>
    <option value="7" label="7">7</option>
    <option value="8" label="8">8</option>
    <option value="9" label="9">9</option>
    <option value="10" label="10">10</option>
    <option value="11" label="11">11</option>
    <option value="12" label="12">12</option>
    <option value="13" label="13">13</option>
    <option value="14" label="14">14</option>
    <option value="15" label="15">15</option>
    <option value="16" label="16">16</option>
    <option value="17" label="17">17</option>
    <option value="18" label="18">18</option>
    <option value="19" label="19">19</option>
    <option value="20" label="20">20</option>
    <option value="21" label="21">21</option>
    <option value="22" label="22">22</option>
    <option value="23" label="23">23</option>
    <option value="24" label="24">24</option>
    <option value="25" label="25">25</option>
    <option value="26" label="26">26</option>
    <option value="27" label="27">27</option>
    <option value="28" label="28">28</option>
    <option value="29" label="29">29</option>
    <option value="30" label="30">30</option>
    <option value="31" label="31">31</option>
</select><select name="lala[month]" id="lala-month">
    <option value="0" label=""></option>
    <option value="1" label="1">1</option>
    <option value="2" label="2">2</option>
    <option value="3" label="3">3</option>
    <option value="4" label="4">4</option>
    <option value="5" label="5">5</option>
    <option value="6" label="6">6</option>
    <option value="7" label="7">7</option>
    <option value="8" label="8">8</option>
    <option value="9" label="9">9</option>
    <option value="10" label="10">10</option>
    <option value="11" label="11">11</option>
    <option value="12" label="12">12</option>
</select><input type="text" name="lala[year]" id="lala-year" value="" size="4">';
        $this->assertEquals($expected, $this->object->formSelectDate('lala'));
    }

}
