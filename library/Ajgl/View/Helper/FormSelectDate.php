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
 * @package    Ajgl\View
 * @subpackage Helper
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
namespace Ajgl\View\Helper;

/**
 * Plugin to render the form select date
 * @category   Ajgl
 * @package    Ajgl\View
 * @subpackage Helper
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class FormSelectDate
    extends \Zend_View_Helper_FormText
{
    /**
     * @param string|array $name If a string, the element name.  If an
     *  array, all other parameters are ignored, and the array elements
     *  are used in place of added parameters.
     * @param mixed $value The element value.
     * @param array $attribs Attributes for the element tag.
     * @return string The element XHTML.
     */
    public function formSelectDate($name, array $value = null, $attribs = array())
    {
        if (isset($attribs['multiple'])) {
            unset($attribs['multiple']);
        }

        $dayAttribs = $monthAttribs = array_merge($attribs, array('size' => 2));
        $yearAttribs = array_merge($attribs, array('size' => 4));

        $dayOptions = array(0 => '') + array_combine(range(1, 31), range(1, 31));
        $monthOptions = array(0 => '') + array_combine(range(1, 12), range(1, 12));

        $html = '';
        if (!isset($attribs['readonly']) || $attribs['readonly'] == false) {
            $html .= $this->view->formSelect($name.'[day]', $value['day'], $attribs, $dayOptions);
            $html .= $this->view->formSelect($name.'[month]', $value['month'], $attribs, $monthOptions);
        } else {
            $html .= $this->view->formText($name.'[day]', $value['day'], $dayAttribs);
            $html .= $this->view->formText($name.'[month]', $value['month'], $monthAttribs);
        }
        $html .= $this->view->formText($name.'[year]', $value['year'], $yearAttribs);

        return $html;
    }
}
