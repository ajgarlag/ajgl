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
 * @package    Ajgl_Form
 * @subpackage Element
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */

/**
 * @category   Ajgl
 * @package    Ajgl_Form
 * @subpackage Element
 * @copyright  Copyright (C) 2010-2011 Antonio J. García Lagar <aj@garcialagar.es>
 * @license    http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL3
 */
class Ajgl_Form_Element_TextDate
    extends Zend_Form_Element_Text
{
    /**
     * Default form view helper to use for rendering
     * @var string
     */
    public $helper = 'formTextDate';
    
    public function init()
    {
        $this->addValidator('Date');
        $this->addFilter('Null', array(Zend_Filter_Null::STRING));
    }

    public function setValue($value) {
        if (!empty($value) && !$value instanceof Zend_Date) {
            $value = new Zend_Date($value, Zend_Date::DATE_SHORT);
        }
        return parent::setValue($value);
    }
    
    /**
     * @param Zend_View_Interface $view
     * @return string
     */
    public function render(Zend_View_Interface $view = null) {
        
        if (null !== $view) {
            $this->setView($view);
            
        }

        if ($this->getView() instanceof Zend_View_Abstract) {
            $this->getView()->addHelperPath('Ajgl/View/Helper', 'Ajgl_View_Helper');
        }
        
        return parent::render($view);
    }
}
